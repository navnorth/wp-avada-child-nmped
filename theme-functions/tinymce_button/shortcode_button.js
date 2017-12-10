(function() {
    tinymce.PluginManager.add('nmped_tinymce_plugin', function( editor, url ) {
	var popup_generator = url+"/popup_generator.php";
        editor.addButton( 'nmped_tinymce_button', {
            title: 'Custom Shortcode',
		image : url + '/images/shortcode.png',
		both: true,
            onclick : function() {
		tb_show( 'Custom Shortcode', popup_generator+"?action=show_popup&width=400&height=400" );	
	    }
        });
    });
})()