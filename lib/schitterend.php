<?php

// Check if BiG Article template is used
function is_big_article() {
    global $post;
    if (is_single($post->ID) && (get_field('groot_artikel', $post->ID) == '1')) {
        return true;
    }
    return false;
}

// numbered pagination
function wpbeginner_numeric_posts_nav() {

    if( is_singular() )
        return;

    global $wp_query;

    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;

    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );

    /**	Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;

    /**	Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }

    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }

    echo '<div class="navigation"><ul>' . "\n";

    /**	Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li>%s</li>' . "\n", get_previous_posts_link("&#12296;") );

    /**	Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';

        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

        if ( ! in_array( 2, $links ) )
            echo '<li>…</li>';
    }

    /**	Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }

    /**	Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li>…</li>' . "\n";

        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }

    /**	Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li>%s</li>' . "\n", get_next_posts_link("&#12297;") );

    echo '</ul></div>' . "\n";

}

//// GET PAGE NUMBER
function current_paged( $var = '' ) {
    if( empty( $var ) ) {
        global $wp_query;
        if( !isset( $wp_query->max_num_pages ) )
            return;
        $pages = $wp_query->max_num_pages;
    }
    else {
        global $$var;
        if( !is_a( $$var, 'WP_Query' ) )
            return;
        if( !isset( $$var->max_num_pages ) || !isset( $$var ) )
            return;
        $pages = absint( $$var->max_num_pages );
    }
    if( $pages < 1 )
        return;
    $page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    //echo 'Page ' . $page . ' of ' . $pages;
    return $page;
}


/// ADD CSS TO ADMIN
function admin_css_change() {
    echo '<style type="text/css">
           div#widgets-right .sidebars-column-1, div#widgets-right .sidebars-column-2 {
                max-width: inherit;
                width: 100%;
            }
         </style>';
}

add_action('admin_head', 'admin_css_change');


/**
 * ADD EXTRA IMAGE SIZES
 */
add_image_size('header-afbeelding-cover', 2000, 475, true);// Cover Image
add_image_size('header-afbeelding-cover-small', 1200, 270, true);// Cover Image

add_image_size('artikellijst', 700, 369, true);  // Article Thumb 495, 255
add_image_size('artikellijst-klein', 480, 253, true);  // Article Thumb 495, 255,
add_image_size('kop-afbeelding-artikel', 667, 999999, false); // Head Image
add_image_size('slider', 1000, 375, true); // Slider Image <- also sidebar
add_image_size('grote-afbeelding-in-tekst', 1000, 9999999, false);// max width

add_image_size('author', 400, 400, false);// author

add_image_size('ad', 1000, 300, true); // Ad Image
///add_image_size( 'auteur', 2000, 475, true); // Author USE THUMBNAIL

// Add new image sizes
function lc_insert_custom_image_sizes( $image_sizes ) {
    // get the custom image sizes
    global $_wp_additional_image_sizes;
    // if there are none, just return the built-in sizes
    if ( empty( $_wp_additional_image_sizes ) )
        return $image_sizes;

    // add all the custom sizes to the built-in sizes
    foreach ( $_wp_additional_image_sizes as $id => $data ) {
        // take the size ID (e.g., 'my-name'), replace hyphens with spaces,
        // and capitalise the first letter of each word
        if ( !isset($image_sizes[$id]) )
            $image_sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
    }

    return $image_sizes;
}

function lc_custom_image_setup () {
    add_filter( 'image_size_names_choose', 'lc_insert_custom_image_sizes' );
}
add_action( 'after_setup_theme', 'lc_custom_image_setup' );


///// IGNORE STICKY
// this is key!
add_action('pre_get_posts', 'wpse74620_ignore_sticky');
// the function that does the work
function wpse74620_ignore_sticky($query)
{
    // sure we're were we want to be.
    if (is_home() && $query->is_main_query())
        $query->set('ignore_sticky_posts', true);
}


/**
 * Prevent small image upload
 */
//add_filter('wp_handle_upload_prefilter','tc_handle_upload_prefilter');
function tc_handle_upload_prefilter($file) {
    $img=getimagesize($file['tmp_name']);
    $minimum = array('width' => '1000', 'height' => '375');
    $width= $img[0];
    $height =$img[1];

    if ($width < $minimum['width'] )
        return array("error"=>"Afbeelding te klein. Minimumbreedte is {$minimum['width']}px. Breedte van de afbeelding is $width px");

    elseif ($height <  $minimum['height'])
        return array("error"=>"Afbeelding te klein. Minimumhoogte is {$minimum['height']}px. Hoogte van de afbeelding is $height px");
    else
        return $file;
}

/**
 * CHANGE FEATURED IMAGE
 */

add_action('do_meta_boxes', 'change_image_box');
function change_image_box()
{
    remove_meta_box( 'postimagediv', 'post', 'side' );
    add_meta_box('postimagediv', __('Pagina-afbeelding <br />Minimaal (1000 x 375 px)'), 'post_thumbnail_meta_box', 'post', 'normal', 'high');
}
/*
add_action('admin_head-post-new.php',change_thumbnail_html);
add_action('admin_head-post.php',change_thumbnail_html);
function change_thumbnail_html( $content ) {
    if ('lit_bookinfo' == $GLOBALS['post_type'])
        add_filter('admin_post_thumbnail_html',do_thumb);
}
function do_thumb($content){
    return str_replace(__('Set featured image'), __('Pagina-afbeelding (minimaal 1000 x 375 px)'),$content);
}
*/


/***
 * Add settings field
 */
function demo_settings_page() {
        $this_title = get_bloginfo("name");
    $this_title .= " intsellingen";
    add_settings_section("section", $this_title, null, "general");
    add_settings_field("is-in-beta", "Is in beta", "is_in_beta_display", "general", "section");
    register_setting("section", "is-in-beta");
}

function is_in_beta_display() {
    settings_fields("section");
    ?>
    <input type="checkbox" name="is-in-beta" value="1" <?php checked(1, get_option('is-in-beta'), true); ?> />
    <?php
}

add_action("admin_init", "demo_settings_page");


/**
 * ADD SEARCH TO MENU
 */

// add_filter('wp_nav_menu_items','add_search_box_to_menu', 10, 2);
function add_search_box_to_menu( $items, $args ) {
    if( $args->theme_location == 'primary_navigation' )
        return $items.get_search_form();

    return $items;
}

/**
 *  Thumbnail upscale
*/
function alx_thumbnail_upscale( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){
    if ( !$crop ) return null; // let the wordpress default function handle this

    $aspect_ratio = $orig_w / $orig_h;
    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);

    $s_x = floor( ($orig_w - $crop_w) / 2 );
    $s_y = floor( ($orig_h - $crop_h) / 2 );

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}
add_filter( 'image_resize_dimensions', 'alx_thumbnail_upscale', 10, 6 );