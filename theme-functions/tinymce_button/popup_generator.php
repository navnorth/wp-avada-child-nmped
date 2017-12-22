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
                            <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="subpages">
                                <div class="nmped_snglimgtiny nmped_button">
                                    <i class="fa fa-file-text fa-4x" aria-hidden="true"></i>
                                </div>
                                <div class="nmped_snglttltiny">
                                    SubPages
                                </div>
                            </div>
                        </div>
                        <div class="nmped_sngltinyrow">
                            <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="table">
                                <div class="nmped_snglimgtiny nmped_button">
                                    <i class="fa fa-table fa-4x" aria-hidden="true"></i>
                                </div>
                                <div class="nmped_snglttltiny">
                                    Table
                                </div>
                            </div>
                            <div class="nmped_sngltinyclm" onclick="nmped_clicked(this);" data-shortcode="pdf">
                                <div class="nmped_snglimgtiny nmped_button">
                                    <i class="fa fa-file-pdf-o fa-4x" aria-hidden="true"></i>
                                </div>
                                <div class="nmped_snglttltiny">
                                    PDF
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
                    case \'bsgrid\':
					   var shortcode = \'[row]\n\n[column md="4"]\n\nYour first column content goes here!\n\n[/column]\n\n[column md="4"]\n\nYour second column content goes here!\n\n[/column]\n\n[column md="4"]\n\nYour third column content goes here!\n\n[/column]\n\n[/row]\';
					   break;
                    case \'spacer\':
					   var shortcode = \'[spacer height="16"]\';
					   break;
                    case \'accordion\':
                            var shortcode = \'[fusion_accordion][fusion_toggle title="Your Toggle Title Here" open="no" ]\n\nYour Content Goes Here\n\n[/fusion_toggle][/fusion_accordion]\';
                            break;
                    case \'button\':
					   var shortcode = \'[fusion_button link="url" title="Button Title Text" target="_self"]Put Your Button Text Here[/fusion_button]\';
					   break;
                    case \'subpages\':
					   var shortcode = \'[nmped_subpages title="" id=""]\';
					   break;
                    case \'table\':
                       var shortcode = \'<div class="table-2">\n<table width="99%">\n<thead>\n<tr>\n<th align="left">Heading One</th>\n<th align="left">Heading Two</th>\n<th align="left">Heading Three</th>\n<th align="left">Heading Four</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td align="left">Content In Row One, Cell One</td>\n<td align="left">Content In Row One, Cell Two</td>\n<td align="left">Content In Row One, Cell Three</td>\n<td align="left">Content In Row One, Cell Four</td>\n</tr>\n<tr>\n<td align="left">Content In Row Two, Cell One</td>\n<td align="left">Content In Row Two, Cell Two</td>\n<td align="left">Content In Row Two, Cell Three</td>\n<td align="left">Content In Row One, Cell Four</td>\n</tr>\n</tbody>\n</table>\n</div>\';
					   break;
                    case \'pdf\':
                       var shortcode = \'[wonderplugin_pdf src="/wp-content/uploads/2017/12/your-PDF-FileName.pdf"]\';
                       break;
                    default:
				   	   var shortcode = \'\';
				   	   break
				}
				return shortcode;
			}
			</script>';
	echo $return;
}
?>
