<?php
/**
 * Custom SubPages Widget based from Avada's sidebar navigation
 **/

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * Widget class.
 */
class NMPED_Subpages_Widget extends WP_Widget {
    
    function __construct() {
        parent::__construct(
                            'NMPED_Subpages_Widget' ,
                            __( 'NMPED Subpages Widget' , 'wp-avada-child-nmped' ),
                            array( 'description' => __( 'This widget displays sub pages navigation' , 'wp-avada-child-nmped' ) )
                            );
    }
    
    public function widget( $args , $instance ) {
        $title = apply_filters( 'widget_title' , $instance['title'] );
        
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];
 
        // Get Object ID
        $queried_object = get_queried_object();
        
        if ($queried_object) {
            echo nmped_display_subpages($queried_object->ID);
        }
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( '', 'wp-avada-child-nmped' );
        }
        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
    <?php 
    }
    
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}

?>