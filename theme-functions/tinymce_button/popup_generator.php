<?php
/** Load WordPress Bootstrap */
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

extract($_REQUEST);
if($action == "show_popup")
{
	$return = '';
	$return .= '<div id="nmped-shortcode-form">
                        <div id="nmped-table" class="form-table">
                            <div class="nmped_sngltinyrow">
                                <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="bsgrid">
                                    <div class="nmped_snglimgtiny">
                                            <div class="nmped_bs_row"></div>
                                            <div class="nmped_bs_row2"><div class="nmped_bs_col"></div><div class="nmped_bs_col"></div><div class="nmped_bs_col"></div></div>
                                    </div>
                                    <div class="nmped_snglttltiny">
                                            Bootstrap Grid
                                    </div>
                                </div>
                                <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="spacer">
                                    <div class="nmped_snglimgtiny nmped_spacer">
                                            <hr class="nmped_top35" />
                                    </div>
                                    <div class="nmped_snglttltiny">
                                            Spacer
                                    </div>
                                </div>
                                <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="embed_video">
                                    <div class="nmped_snglimgtiny">
                                        <img src="'.get_stylesheet_directory_uri().'/theme-functions/tinymce_button/images/featured_video.png">
                                    </div>
                                    <div class="nmped_snglttltiny">
                                        Embed Video
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
		<script type="text/javascript">
			function nmped_clicked(ref)
			{
				jQuery(".nmped_sngltinyclm").each(function(index, element) {
                                    jQuery(this).removeClass("nmped_snglslctd");
				});
				jQuery(ref).addClass("nmped_snglslctd");
                                placeshortcode();
			}
			function placeshortcode(){
				var shortcode_type = jQuery(".nmped_sngltinyclm.nmped_snglslctd").attr("data-shortcode");
				var shortcode = get_myshortcode(shortcode_type);
				if(typeof tinyMCE != "undefined" && ( ed = tinyMCE.activeEditor ) && !ed.isHidden()){
					tinyMCE.activeEditor.execCommand("mceInsertContent", 0, shortcode);
					tinymce.EditorManager.execCommand("mceRemoveControl",true, "content");
				}
				else
				{
					var cursor = jQuery("#content").prop("selectionStart");
					if(!cursor) cursor = 0;
					var content = jQuery("#content").val();
					var textBefore = content.substring(0,  cursor );
					var textAfter  = content.substring( cursor, content.length );
					jQuery("#content").val( textBefore + shortcode + textAfter );
				}
				tb_remove();
			}
			function get_myshortcode(shortcode_type)
			{
				switch (shortcode_type)
				{
                                    case "bsgrid":
					   var shortcode = "[row][column md=\'4\'] your 1st column content here[/column][column md=\'4\'] your 2nd column content here[/column][column md=\'4\'] your 3rd column content here[/column][/row]";
					   break;
                                    case "spacer":
					   var shortcode = "[spacer height=\'16\']";
					   break;
                                    case "embed_video":
                                            var shortcode = "[fusion_youtube id=\'\' alignment=\'\' width=\'\' height=\'\' autoplay=\'false\' api_params=\'\' hide_on_mobile=\'large-visibility\' class=\'\'][/fusion_youtube]";
                                            break;
                                    default:
				   	   var shortcode = "";
				   	   break
				}
				return shortcode;
			}
			</script>';
	echo $return;
}
?>