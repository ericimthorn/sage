<?php
class SchitMaps extends WP_Widget {

    function SchitMaps() {
        $widget_ops = array('classname' => 'schitmaps', 'description' => 'Desciption' );
        $this->WP_Widget('SchitMaps', 'Title', $widget_ops);
    }

    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array(
            'title' => ''
        ));
        $title = $instance['title'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Titel: <input required class="widefat" id="title" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"  />
            </label>
        </p>
        

    <?php
    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        echo $before_widget;
        ?>
        <div class="widget_text">
            <?php echo $before_title; ?>
            <?php echo $instance['title']; ?>
            <?php echo $after_title; ?>
        </div>
        <?php
        echo $after_widget;
    }

}
add_action( 'widgets_init', create_function('', 'return register_widget("SchitMaps");') );