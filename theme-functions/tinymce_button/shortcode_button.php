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

/**
 * Add tinymce plugin
 **/
function nmped_add_tinymce_plugin($plugin_array) {
   	$plugin_array['oet_tinymce_plugin'] = get_stylesheet_directory_uri().'/theme-functions/tinymce_button/shortcode_button.js'; 
   	return $plugin_array;
}

/**
 * Register Tinymce Button
 **/
function nmped_register_tinymce_button($buttons) {
   array_push($buttons, "oet_tinymce_button");
   return $buttons;
}

?>