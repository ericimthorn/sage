<?php

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
add_image_size('header-image', 700, 475, false);// Cover Image
add_image_size('link-block', 320, 190, true);//
add_image_size('link-block-large', 700, 416, true);

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
//add_action('pre_get_posts', 'wpse74620_ignore_sticky');
function wpse74620_ignore_sticky($query) {
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

//add_action('do_meta_boxes', 'change_image_box');
function change_image_box() {
    remove_meta_box( 'postimagediv', 'post', 'side' );
    add_meta_box('postimagediv', __('Pagina-afbeelding <br />Minimaal (1000 x 375 px)'), 'post_thumbnail_meta_box', 'post', 'normal', 'high');
}

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
//add_filter( 'image_resize_dimensions', 'alx_thumbnail_upscale', 10, 6 );
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


/**
 * ADD TEXT EDITOR STYLE
 */

function s_add_editor_styles() {
    $loc = 'kroon-magazine.dev/wp-content/themes/kroon/dist/styles/editor.css';
    // echo "<pre>";print_r($loc);die();
    add_editor_style($loc);
}
// add_action( 'admin_head', 's_add_editor_styles');



/**
 * Retrieves the attachment data such as Title, Caption, Alt Text, Description
 * @param int $post_id the ID of the Post, Page, or Custom Post Type
 * @param String $size The desired image size, e.g. thumbnail, medium, large, full, or a custom size
 * @return stdClass If there is only one result, this method returns a generic
 * stdClass object representing each of the image's properties, and an array if otherwise.
 */
function getImageAttachmentData( $post_id, $size = 'thumbnail', $count = 1 ) {
    $objMeta = array();
    $meta;// (stdClass)
    $args = array(
        'numberposts' => $count,
        'post_parent' => $post_id,
        'post_type' => 'attachment',
        'nopaging' => false,
        'post_mime_type' => 'image',
        'order' => 'ASC', // change this to reverse the order
        'orderby' => 'menu_order ID', // select which type of sorting
        'post_status' => 'any'
    );

    $attachments = & get_children($args);

    if( $attachments ) {
        foreach( $attachments as $attachment ) {
            $meta = new stdClass();
            $meta->ID = $attachment->ID;
            $meta->title = $attachment->post_title;
            $meta->caption = $attachment->post_excerpt;
            $meta->description = $attachment->post_content;
            $meta->alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);

            // Image properties
            $props = wp_get_attachment_image_src( $attachment->ID, $size, false );

            $meta->properties['url'] = $props[0];
            $meta->properties['width'] = $props[1];
            $meta->properties['height'] = $props[2];

            $objMeta[] = $meta;
        }

        return ( count( $attachments ) == 1 ) ? $meta : $objMeta;
    }
}


/**
 * LET EDITOR CHANGE WIDGETS
 */
$role = get_role('editor');
$role->add_cap('edit_theme_options');
function custom_admin_menu() {

  $user = new WP_User(get_current_user_id());
  if (!empty( $user->roles) && is_array($user->roles)) {
    foreach ($user->roles as $this_role)
      $role = $this_role;
  }

  if($role == "editor") {
    remove_submenu_page( 'themes.php', 'themes.php' );
    remove_submenu_page( 'themes.php', 'nav-menus.php' );
  }
}

//add_action('admin_menu', 'custom_admin_menu');
