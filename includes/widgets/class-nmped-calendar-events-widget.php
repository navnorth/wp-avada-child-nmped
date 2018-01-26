<?php
/**
 *
 * Calendar Events Widget
 *
 **/
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

class NMPED_Calendar_Events_Widget extends WP_Widget {
    //Initialized Widget and passing widget parameters
    function __construct() {
        $widget_ops = array( 'classname' => 'nmped-calendar-widgets', 'description' => __('A widget that displays upcoming events according to category of a post/page ', 'wp-avada-child-nmped') );
        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'nmped-calendar-events-widget' );
        parent::__construct( 'nmped-calendar-events-widget', __('NMPED Calendar Events', 'wp-avada-child-nmped'), $widget_ops, $control_ops );
    }

    //Display Widget Function
    function widget( $args, $instance ) {
        global $post, $wpdb;

        extract( $args );

        $title = apply_filters('widget_title', $instance['title'] );
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : false;
        $posts_count  = $instance['posts_count'];

        echo $before_widget;

        $pID = $post->ID;
        $post_categories = get_the_category($post->ID);
	
	if (empty($post_categories))
		return;

        $cat_ids = array();
        foreach($post_categories as $cat){
            $cat_ids[] = $cat->cat_ID;
        }
        $cat_ids = implode( ',' , $cat_ids );

	
	$sql = "SELECT
			np.ID,
			FROM_UNIXTIME(nae.start) as startdate
		FROM
			{$wpdb->prefix}posts np
		INNER JOIN
			{$wpdb->prefix}ai1ec_events nae
		ON
			np.ID=nae.post_id
		LEFT JOIN
			{$wpdb->prefix}term_relationships ntr
		ON
			np.ID = ntr.object_id
		WHERE
			FROM_UNIXTIME(nae.start)>=curdate() AND
			np.post_status='publish' AND
			np.post_type='ai1ec_event' AND  
			ntr.term_taxonomy_id IN ($cat_ids)
		GROUP BY
			np.ID
		ORDER BY
			np.post_date ASC
		LIMIT 0, $posts_count";
	
	$results = $wpdb->get_results( $sql, OBJECT );
	
	$cPosts = array();
	
	foreach ($results as $row) {
		$cPost = get_post($row->ID);
		$cPost->start_date = $row->startdate;
		$cPosts[] = $cPost;
	}
	
	if (count($cPosts)>0) :

?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul>
		<?php foreach($cPosts as $cPost): ?>
			<li>
                        <?php if ( $show_thumbnail ) : ?>
                            <?php if ( has_post_thumbnail($cPost->ID) ) ?>
				<a href="<?php echo get_permalink($cPost->ID); ?>"><?php echo get_the_post_thumbnail($cPost->ID); ?></a>
			<?php endif; ?>
				<a href="<?php echo get_permalink($cPost->ID); ?>"><?php echo get_the_title($cPost->ID); ?></a>
			<?php if ( $show_date ) : ?>
				<br/><span class="post-date"><?php echo date('l, F j, Y', strtotime($cPost->start_date)); ?></span>
			<?php endif; ?>
			</li>
                    <?php //endif; ?>
		<?php endforeach; ?>
		</ul>
		<p><a href="<?php echo site_url('/calendar/'); ?>" class="calendar-link"><?php _e( "View Calendar" , "wp-avada-child-nmped"); ?></a></p>
		<?php echo $args['after_widget']; ?>
<?php
	    endif;

            // Reset the global $the_post as this query will have stomped on it
	    wp_reset_postdata();
            
        echo $after_widget;
    }

    //Update Widget Settings Function
    function update( $new_instance, $old_instance ) {
         $instance = array();

        //Strip tags from title and name to remove HTML
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['show_date'] = $new_instance['show_date'];
        $instance['show_thumbnail'] = $new_instance['show_thumbnail'];
        $instance['posts_count'] = $new_instance['posts_count'];

        return $instance;
    }

    //Display The Widget Form For User Defined Settings
    function form( $instance ){
        //Set up some default widget settings.
        $defaults = array( 'title' => '', 'show_date' => 'on', 'show_thumbnail' => 'off', 'posts_count' => 5 );
        $instance = wp_parse_args( (array) $instance, $defaults );

        // Widget Title: Text Input ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wp-avada-child-nmped'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>
        <?php // Show  Date Checkbox ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance['show_date'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e('Display post date?', 'wp-avada-child-nmped'); ?></label>
        </p>
         <?php // Show Thumbnail Checkbox ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance['show_thumbnail'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'show_thumbnail' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_thumbnail' ); ?>"><?php _e('Display thumbnail?', 'wp-avada-child-nmped'); ?></label>
        </p>
        <?php
        //Post Count Text Input
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'posts_count' ); ?>"><?php _e('Number of posts to display?', 'wp-avada-child-nmped'); ?></label>
            <input id="<?php echo $this->get_field_id( 'posts_count' ); ?>" name="<?php echo $this->get_field_name( 'posts_count' ); ?>" value="<?php echo $instance['posts_count']; ?>" size="3" />
        </p>
        <?php
    }
}
?>