<?php
function theme_ped_settings() {
    if (!current_user_can('manage_options')) {
	wp_die('You do not have sufficient permissions to access this page.');
    }
    ?>
    <div class="wrap">
    <h1><?php _e( "PED Settings", "wp-avada-child-nmped" ); ?></h1>
    <form method="post" action="options.php">
        <fieldset>
            <legend><?php _e( "Out-Of-Date Content Reminder" , "wp-avada-child-nmped"); ?></legend>
        <?php
            settings_fields( 'ped-settings-group' );
            do_settings_sections( 'ped-settings-group' );
            submit_button("Update Settings");
        ?>
        </fieldset>
    </form>
    </div>
    <?php
}

?>