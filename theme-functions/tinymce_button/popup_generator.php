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
                                <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="accordion">
                                    <div class="nmped_snglimgtiny">
                                        <img src="'.get_stylesheet_directory_uri().'/theme-functions/tinymce_button/images/accordion.png">
                                    </div>
                                    <div class="nmped_snglttltiny">
                                        Accordion
                                    </div>
                                </div>
                                <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="button">
                                    <div class="nmped_snglimgtiny nmped_button">
                                            <button class="btn custom-button nmped_top35">button</button>
                                    </div>
                                    <div class="nmped_snglttltiny">
                                            Button
                                    </div>
                                </div>
                            </div>
                            <div class="nmped_sngltinyrow">
                                <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="subpages">
                                    <div class="nmped_snglimgtiny nmped_button">
                                            <i class="fa fa-file-text fa-4x" aria-hidden="true"></i>
                                    </div>
                                    <div class="nmped_snglttltiny">
                                            SubPages
                                    </div>
                                </div>
                                <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="table">
                                    <div class="nmped_snglimgtiny nmped_button">
                                            <i class="fa fa-table fa-4x" aria-hidden="true"></i>
                                    </div>
                                    <div class="nmped_snglttltiny">
                                            Table
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
                                    case "accordion":
                                            var shortcode = "[nmped_accordion_group id=\'accordion1\'][nmped_accordion title=\'Accordion Item 1\' accordion_series=\'one\' expanded=\'\' group_id=\'accordion1\'] your content goes here [/nmped_accordion][nmped_accordion title=\'Accordion Item 2\' accordion_series=\'two\' expanded=\'\' group_id=\'accordion1\'] your content goes here [/nmped_accordion][nmped_accordion title=\'Accordion Item 3\' accordion_series=\'three\' expanded=\'\' group_id=\'accordion\'] your content goes here [/nmped_accordion][/nmped_accordion_group]";
                                            break;
                                    case "button":
					   var shortcode = "[nmped_button text=\'\' button_color=\'\' text_color=\'\' font_face=\'\' font_size=\'\' font_weight=\'\' url=\'\' new_window=\'yes/no\']";
					   break;
                                    case "subpages":
					   var shortcode = "[nmped_subpages title=\'\' id=\'\']";
					   break;
                                    case "table":
                                           var shortcode = "[fusion_table]<div class=\'table-2\'><table width=\'100%\'><thead><tr><th align=\'left\'>Column 0</th><th align=\'left\'>Column 1</th><th align=\'left\'>Column 2</th></tr></thead><tbody><tr><td align=\'left\'>Column 0 Value</td><td align=\'left\'>Column 1 Value</td><td align=\'left\'>Column 2 Value</td></tr></tbody></table></div>[/fusion_table]";
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