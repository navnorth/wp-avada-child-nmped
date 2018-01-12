<?php
function theme_ped_settings() {
    if (!current_user_can('manage_options')) {
	wp_die('You do not have sufficient permissions to access this page.');
    }
    
    $child_theme = wp_get_theme("wp-avada-child-nmped");
    
    ?>
    <div class="wrap">
    <h1><?php _e( "PED Settings", "wp-avada-child-nmped" ); ?></h1>
    <form method="post" id="ped_settings" action="options.php">
        <fieldset>
            <legend><?php _e( "Out-Of-Date Content Reminder" , "wp-avada-child-nmped"); ?></legend>
        <?php
            settings_fields( 'nmped_general_settings' );
            do_settings_sections( 'nmped_settings' );
	    echo "<p class='submit'>";
	    submit_button( "Notify Now" , "secondary", "notify_now", false );
            submit_button( "Update Settings", "primary", "update_settings", false );
	    echo "</p>";
        ?>
        </fieldset>
    </form>
    <form method="post" id="event_settings" action="options.php">
        <fieldset>
            <legend><?php _e( "Event Theme" , "wp-avada-child-nmped"); ?></legend>
	    <p><?php _e( "This feature updates the All-in-One Event Calendar PED theme files in wp-content/themes-ai1ec from the PED Avada child theme directory and flushes the plugin's cache so the changes will appear to end users." , "wp-avada-child-nmped" ); ?></p>
        <?php
            submit_button( "Update AI1EC Theme" );
        ?>
        </fieldset>
    </form>
    <div class="plugin-footer">
	<p class="right"><?php echo $child_theme->Name. " " . $child_theme->Version; ?></p>
    </div>
    </div>
    <?php
}


function out_of_date_content_dashboard_display() {
    $outPosts = new WP_Query(
		    apply_filters(
			'widget_post_args' ,
			array(
			    'post_type' => array( 'post', 'page' ),
			    'posts_per_page' => 25 ,
			    'post_status' => 'publish',
			    'orderby' => 'modified',
			    'order' => 'ASC'
			)
		    )
		);
    ?>
	<div class="wrap">
	    <div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<form method="post">
						</form>
					</div>
				</div>
			</div>
	    </div>
	</div>
    <?php
}
?>