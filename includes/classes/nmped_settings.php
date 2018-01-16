<?php

class NMPED_Settings_Page {
    private $_debug = FALSE;

    public static $cron_action_hook = "nmped_notification_cron";

    private static $_menu_slug = "nmped-admin";

    public static $option_name = "nmped_general_settings";

    private static $_setting_title = "PED Settings";
    
    public function __construct()
    {
        add_action("admin_menu", array($this, "setup_ped_settings_menu"));
        add_action("admin_init", array($this, "register_ped_settings"));
    }
    
    /** Add PED Settings menu **/
    public function setup_ped_settings_menu() {
	$hook = add_submenu_page( "options-general.php" ,
			    "PED Settings" ,
			    "PED Settings" ,
			    "manage_options" ,
			    "ped-settings" ,
			    array($this,"theme_ped_settings")
			 );
	add_action("load-" . $hook, array($this, "setup_cron"));
    }
    
    /** Register PED Settings **/
    public function register_ped_settings() {
	//Create General Section
	add_settings_section(
		'nmped_general_settings',
		'',
		array($this,'nmped_general_settings_callback'),
		'nmped_settings'
	);
	
	//Add Settings field for Enable notification
	add_settings_field(
		'nmped_enable_notification',
		'',
		array($this,'setup_settings_field'),
		'nmped_settings',
		'nmped_general_settings',
		array(
			'uid' => 'nmped_enable_notification',
			'type' => 'checkbox',
			'class' => 'notification_option',
			'name' =>  __( 'Enable automated notifications of out-of-date content', 'wp-avada-child-nmped' ),
			'value' => '1'
		)
	);
	
	//Add Settings field for Content Age
	add_settings_field(
		'nmped_age_days',
		'',
		array($this,'setup_settings_field'),
		'nmped_settings',
		'nmped_general_settings',
		array(
			'uid' => 'nmped_age_days',
			'type' => 'number',
			'size' => 3,
			'class' => 'text_option input_option',
			'title' =>  __( 'Age:', 'wp-avada-child-nmped' ),
			'description' => __( 'days since last modified' , 'wp-avada-child-nmped' ),
			'default' => 90
		)
	);
	
	//Add Settings field for Frequency
	add_settings_field(
		'nmped_notification_frequency',
		'',
		array($this,'setup_settings_field'),
		'nmped_settings',
		'nmped_general_settings',
		array(
			'uid' => 'nmped_notification_frequency',
			'type' => 'selectbox',
			'class' => 'select_option input_option',
			'title' =>  __( 'Frequency:', 'wp-avada-child-nmped' ),
			'values' => array( 'daily' => "Daily", 'weekly' => "Weekly" , 'monthly' => "Monthly" )
		)
	);
	
	//Add Settings field for send to last author
	add_settings_field(
		'nmped_to_last_author',
		'',
		array($this,'setup_settings_field'),
		'nmped_settings',
		'nmped_general_settings',
		array(
			'uid' => 'nmped_to_last_author',
			'type' => 'checkbox',
			'class' => 'checkbox_option input_option',
			'title' =>  __( 'Send to:', 'wp-avada-child-nmped' ),
			'description' =>  __( 'last author of the page', 'wp-avada-child-nmped' ),
			'value' => '1'
		)
	);
	
	//Add Settings field for send to all Editors
	add_settings_field(
		'nmped_to_all_editors',
		'',
		array($this,'setup_settings_field'),
		'nmped_settings',
		'nmped_general_settings',
		array(
			'uid' => 'nmped_to_all_editors',
			'type' => 'checkbox',
			'class' => 'checkbox_option_nolabel input_option',
			'description' =>  __( 'all Editors', 'wp-avada-child-nmped' ),
		)
	);
	
	//Add Settings field for send to all Division Leads
	add_settings_field(
		'nmped_to_all_division_leads',
		'',
		array($this,'setup_settings_field'),
		'nmped_settings',
		'nmped_general_settings',
		array(
			'uid' => 'nmped_to_all_division_leads',
			'type' => 'checkbox',
			'class' => 'checkbox_option_nolabel input_option',
			'description' =>  __( 'all Division Leads', 'wp-avada-child-nmped' ),
		)
	);
	
	//Add Settings field for send to additional Recipient(s)
	add_settings_field(
		'nmped_to_additional_recipients',
		'',
		array($this,'setup_settings_field'),
		'nmped_settings',
		'nmped_general_settings',
		array(
			'uid' => 'nmped_to_additional_recipients',
			'type' => 'checkbox',
			'class' => 'checkbox_option_nolabel input_option',
			'description' =>  __( 'Additional Recipient(s)', 'wp-avada-child-nmped' ),
		)
	);
	
	//Add Settings field for Content Age
	add_settings_field(
		'nmped_recipient_emails',
		'',
		array($this,'setup_settings_field'),
		'nmped_settings',
		'nmped_general_settings',
		array(
			'uid' => 'nmped_recipient_emails',
			'type' => 'textbox',
			'class' => 'email_option_nolabel input_option',
			'default' => 'PEDHelpDesk@state.nm.us'
		)
	);
	
	register_setting( 'nmped_general_settings' , 'nmped_enable_notification' );
	register_setting( 'nmped_general_settings' , 'nmped_age_days' );
	register_setting( 'nmped_general_settings' , 'nmped_notification_frequency' );
	register_setting( 'nmped_general_settings' , 'nmped_to_last_author' );
	register_setting( 'nmped_general_settings' , 'nmped_to_all_editors' );
	register_setting( 'nmped_general_settings' , 'nmped_to_all_division_leads' );
	register_setting( 'nmped_general_settings' , 'nmped_to_additional_recipients' );
	register_setting( 'nmped_general_settings' , 'nmped_recipient_emails' );
    }
    
    public function nmped_general_settings_callback() {}
    
    public function setup_settings_field( $arguments ) {
	$selected = "";
	$size = "";
	$class = "";
	$disabled = "";

	$value = get_option($arguments['uid']);

	if (isset($arguments['indent'])){
		echo '<div class="indent">';
	}

	if (isset($arguments['class'])) {
		$class = $arguments['class'];
		$class = " class='".$class."' ";
	}

	if (isset($arguments['pre_html'])) {
		echo $arguments['pre_html'];
	}

	switch($arguments['type']){
		case "textbox":
			$size = 'size="50"';
			if (isset($arguments['title']))
				$title = $arguments['title'];
			if (isset($arguments['default']) && !isset($value))
			    $value = $arguments['default'];
			echo '<label for="'.$arguments['uid'].'"><strong>'.$title.'</strong></label><input name="'.$arguments['uid'].'" id="'.$arguments['uid'].'" type="'.$arguments['type'].'" value="' . $value . '" ' . $size . ' ' .  $selected . ' />';
			break;
		case "checkbox":
		case "radio":
			$display_value = "";
			$selected = "";

			if ($value=="1" || $value=="on"){
				$selected = "checked='checked'";
				$display_value = "value='1'";
			} elseif ($value===false){
				$selected = "";
				if (isset($arguments['default'])) {
					if ($arguments['default']==true){
						$selected = "checked='checked'";
					}
				}
			} else {
				$selected = "";
			}

			if (isset($arguments['disabled'])){
				if ($arguments['disabled']==true)
					$disabled = " disabled";
			}
			
			if (isset($arguments['title'])){
			    $title = $arguments['title'];
			    echo '<label for="'.$arguments['uid'].'"><strong>'.$title.'</strong></label>';
			}

			echo '<input name="'.$arguments['uid'].'" id="'.$arguments['uid'].'" '.$class.' type="'.$arguments['type'].'" ' . $display_value . ' ' . $size . ' ' .  $selected . ' ' . $disabled . '  />';
			
			if (isset($arguments['name'])){
			    echo '<label for="'.$arguments['id'].'"><strong>'.$arguments['name'].'</strong></label>';    
			}
			
			if (isset($arguments['uid']) && $arguments['uid']=='nmped_enable_notification') {
			    $next_schedule = wp_next_scheduled(self::$cron_action_hook);
			    echo "<span class='next-schedule'> ( Next Run: ".date("Y-m-d H:i:s", $next_schedule)." ) </span>";
			}
			
			break;
		case "textarea":
			echo '<label for="'.$arguments['uid'].'"><h3><strong>'.$arguments['name'];
			if (isset($arguments['inline_description']))
				echo '<span class="inline-desc">'.$arguments['inline_description'].'</span>';
			echo '</strong></h3></label>';
			echo '<textarea name="'.$arguments['uid'].'" id="'.$arguments['uid'].'" rows="10">' . $value . '</textarea>';
			break;
		case "selectbox":
			if (isset($arguments['title']))
				$title = $arguments['title'];
				
			echo '<label for="'.$arguments['uid'].'"><strong>'.$title.'</strong></label>';
			echo '<select name="'.$arguments['uid'].'" id="'.$arguments['uid'].'">';
			
			if ($values = $arguments['values']){
			    foreach ($values as $key=>$value) {
				?>    
				<option value="<?php echo $key ?>" <?php selected(get_option($arguments['uid']), $key); ?>><?php echo $value; ?></option>;
				<?php
			    }
			}
			echo '</select>';
			break;
		default:
			$size = 'size="50"';
			if (isset($arguments['size']))
			    $size = 'size="'. $arguments['size'] . '"';
			    
			if (isset($arguments['value']))
			    $value = $arguments['value'];
			    
			if (isset($arguments['title']))
				$title = $arguments['title'];
				
			echo '<label for="'.$arguments['uid'].'"><strong>'.$title.'</strong></label><input name="'.$arguments['uid'].'" id="'.$arguments['uid'].'" type="'.$arguments['type'].'" value="' . $value . '" ' . $size . ' ' .  $selected . ' />';
			break;
	}

	//Show Helper Text if specified
	if (isset($arguments['helper'])) {
		printf( '<span class="helper"> %s</span>' , $arguments['helper'] );
	}

	//Show Description if specified
	if( isset($arguments['description']) ){
		printf( '<span class="description">%s</span>', $arguments['description'] );
	}

	if (isset($arguments['indent'])){
		echo '</div>';
	}
    }
 
    public function theme_ped_settings() {
	if (!current_user_can('manage_options')) {
	    wp_die('You do not have sufficient permissions to access this page.');
	}
	
	$child_theme = wp_get_theme("wp-avada-child-nmped");
	
	if (isset($_GET['settings-updated'])){
	    $this->setup_cron();
	}
	
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
    
    /**
     * Setup Cron
     * Description
     */
    public function setup_cron()
    {
	$notification = get_option('nmped_enable_notification');
	
	if($notification)
	{
	    $frequency = get_option('nmped_notification_frequency');
	
	    $timestamp = wp_next_scheduled(self::$cron_action_hook);
	    
	    // 3 AM
	    $initial_time = strtotime('03:00:00');

	    // Schedule
	    if($timestamp == FALSE)
	    {
		wp_schedule_event($initial_time, $frequency, self::$cron_action_hook);
	    }
	    else
	    {
		// Re-schedule
		$schedule = wp_get_schedule(self::$cron_action_hook);

		if(strcmp($schedule, $frequency))
		{
		    wp_unschedule_event($timestamp, self::$cron_action_hook);
		    wp_reschedule_event($initial_time, $frequency, self::$cron_action_hook);
		}
	    }
	}
    }
}
?>