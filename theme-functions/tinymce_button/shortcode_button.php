<?php
/**
 * Adding TinyMCE Button
 **/
add_action('admin_head', 'nmped_add_tinymce_button');
function nmped_add_tinymce_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   	return;
    }
    
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
    
    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
            add_filter("mce_external_plugins", "nmped_add_tinymce_plugin");
            add_filter('mce_buttons', 'nmped_register_tinymce_button');
    }
}

add_action('media_buttons', 'nmped_media_buttons_context');
//fallback if media_buttons fail
add_action('media_buttons_context', 'nmped_media_buttons_context');
function nmped_media_buttons_context($context)
{
	global $post_ID, $temp_ID;
	$iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
	$out = '<a id="add_shortcode" style="display:none" href="'.get_stylesheet_directory_uri().'/theme-functions/tinymce_button/popup_generator.php?action=show_popup&width=800&height=550" class="hide-if-no-js thickbox" title="Add shortcode"><img src="'.get_stylesheet_directory_uri().'/theme-functions/tinymce_button/images/shortcode.png" alt="Add Shortcode" /></a>';
	return $context . $out;
}

add_action('admin_print_footer_scripts', 'nmped_add_quicktags', 100);
function nmped_add_quicktags()
{
	?>
	<script type="text/javascript">
	if ( window.QTags !== undefined ) {
		QTags.addButton( 'shortcodes', '</>', function(){ jQuery('#add_shortcode').click() } );
	}
	</script>
<?php
}

/**
 * Add tinymce plugin
 **/
function nmped_add_tinymce_plugin($plugin_array) {
   	$plugin_array['nmped_tinymce_plugin'] = get_stylesheet_directory_uri().'/theme-functions/tinymce_button/shortcode_button.js'; 
   	return $plugin_array;
}

/**
 * Register Tinymce Button
 **/
function nmped_register_tinymce_button($buttons) {
   array_push($buttons, "nmped_tinymce_button");
   return $buttons;
}

?>