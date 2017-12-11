<?php
/**
 * Bootstrap Row
 * Shortcode Example : [row]
 */
 add_shortcode("row", "bootstrap_row_func");
 function bootstrap_row_func( $atts, $content = null ) {

    $atts = shortcode_atts( array(
      "xclass" => false,
      "data"   => false
	), $atts );

    $class  = 'row';
    $class .= ( $atts['xclass'] )   ? ' ' . $atts['xclass'] : '';

    $data_props = parse_data_attributes( $atts['data'] );

    return sprintf(
      '<div class="%s"%s>%s</div>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
  }

/**
* Bootstrap Column
* Shortcode Example : [column lg='12']
*/
add_shortcode("column", "bootstrap_column_func");
function bootstrap_column_func( $atts, $content = null ) {

$atts = shortcode_atts( array(
      "lg"          => false,
      "md"          => false,
      "sm"          => false,
      "xs"          => false,
      "offset_lg"   => false,
      "offset_md"   => false,
      "offset_sm"   => false,
      "offset_xs"   => false,
      "pull_lg"     => false,
      "pull_md"     => false,
      "pull_sm"     => false,
      "pull_xs"     => false,
      "push_lg"     => false,
      "push_md"     => false,
      "push_sm"     => false,
      "push_xs"     => false,
      "xclass"      => false,
      "data"        => false
	), $atts );

    $class  = '';
    $class .= ( $atts['lg'] )			                                ? ' col-lg-' . $atts['lg'] : '';
    $class .= ( $atts['md'] )                                           ? ' col-md-' . $atts['md'] : '';
    $class .= ( $atts['sm'] )                                           ? ' col-sm-' . $atts['sm'] : '';
    $class .= ( $atts['xs'] )                                           ? ' col-xs-' . $atts['xs'] : '';
    $class .= ( $atts['offset_lg'] || $atts['offset_lg'] === "0" )      ? ' col-lg-offset-' . $atts['offset_lg'] : '';
    $class .= ( $atts['offset_md'] || $atts['offset_md'] === "0" )      ? ' col-md-offset-' . $atts['offset_md'] : '';
    $class .= ( $atts['offset_sm'] || $atts['offset_sm'] === "0" )      ? ' col-sm-offset-' . $atts['offset_sm'] : '';
    $class .= ( $atts['offset_xs'] || $atts['offset_xs'] === "0" )      ? ' col-xs-offset-' . $atts['offset_xs'] : '';
    $class .= ( $atts['pull_lg']   || $atts['pull_lg'] === "0" )        ? ' col-lg-pull-' . $atts['pull_lg'] : '';
    $class .= ( $atts['pull_md']   || $atts['pull_md'] === "0" )        ? ' col-md-pull-' . $atts['pull_md'] : '';
    $class .= ( $atts['pull_sm']   || $atts['pull_sm'] === "0" )        ? ' col-sm-pull-' . $atts['pull_sm'] : '';
    $class .= ( $atts['pull_xs']   || $atts['pull_xs'] === "0" )        ? ' col-xs-pull-' . $atts['pull_xs'] : '';
    $class .= ( $atts['push_lg']   || $atts['push_lg'] === "0" )        ? ' col-lg-push-' . $atts['push_lg'] : '';
    $class .= ( $atts['push_md']   || $atts['push_md'] === "0" )        ? ' col-md-push-' . $atts['push_md'] : '';
    $class .= ( $atts['push_sm']   || $atts['push_sm'] === "0" )        ? ' col-sm-push-' . $atts['push_sm'] : '';
    $class .= ( $atts['push_xs']   || $atts['push_xs'] === "0" )        ? ' col-xs-push-' . $atts['push_xs'] : '';
    $class .= ( $atts['xclass'] )                                       ? ' ' . $atts['xclass'] : '';

    $data_props = parse_data_attributes( $atts['data'] );

    return sprintf(
      '<div class="%s"%s>%s</div>',
      esc_attr( $class ),
      ( $data_props ) ? ' ' . $data_props : '',
      do_shortcode( $content )
    );
}

/**
 * Spacer
 * Shortcode Example : [spacer height='20']
 */
 add_shortcode("spacer", "spacer_func");
 function spacer_func($attribute) {

	if (is_array($attribute)) extract($attribute);

	if (isset($height) && !empty($height)) {
		$height = " height:".((strpos($height,"px")>0)?$height:$height."px");
	} else {
		$height = " height:12px;";
	}

	$return = '<div class="clearfix" style="clear:both;'. $height .'"></div>';

	return $return;

 }

/**
 * Accordion Group & Accordion
 * Shortcode Example : [nmped_accordion_group][nmped_accordion title="" accordion_series="one" expanded=""] your content goes here [/nmped_accordion][/nmped_accordion_group]
 */
add_shortcode('nmped_accordion_group', 'nmped_accordion_group_func');
function nmped_accordion_group_func($atts, $content = null)
{
	$accordion_id = "accordion";
	
	if (!empty($atts)) {
		extract($atts);
		if ($id)
			$accordion_id = $id;
	}
	
	$return = '';
	$return .= '<div class="panel-group" id="'.$accordion_id.'" role="tablist" aria-multiselectable="true">';
			$content = str_replace( "<p>","", $content );
			$content = str_replace( "</p>","", $content );
			$return .= do_shortcode($content);
	$return .= '</div>';
	return $return;
}

 
add_shortcode('nmped_accordion', 'nmped_accordion_func');
function nmped_accordion_func($atts, $content = null)
{
$group_id = "accordion";

  extract($atts);
  $return = '';

  if(isset($accordion_series) && !empty($accordion_series))
  {
		$return .= '<div class="panel panel-default">';

		$return .= '<div class="panel-heading" role="tab" id="heading'. $group_id. $accordion_series .'">';
		  $return .= '<h5 class="panel-title">';

			  if(isset($expanded) && !empty($expanded) && strtolower($expanded) == "true")
			  {
				  $class = "";
				  $uptcls = "in";
			  }
			  else
			  {
				  $class = "collapsed";
				  $uptcls = '';
			  }

			  $return .= '<a class="'.$class.'" data-toggle="collapse" data-parent="#'.$group_id.'" href="#collapse'. $group_id. $accordion_series .'" aria-expanded="false" aria-controls="collapse'. $accordion_series .'">';
			  $return .= $title;
			$return .= '</a>';
		 $return .= ' </h5>';
		$return .= '</div>';

		$return .= '<div id="collapse'. $group_id. $accordion_series .'" class="panel-collapse collapse '.$uptcls.'" role="tabpanel" aria-labelledby="heading'. $accordion_series .'">';
		  $return .= '<div class="panel-body">';
			//$content = apply_filters('the_content', $content);
			$return .= $content;
		  $return .= '</div>';
		$return .= '</div>';

		$return .= '</div>';

		return $return;
  }
}

/**
 * Button
 * Shortcode Example : [btn button_color ='' text='' text_color='#ffffff']
 */
 add_shortcode("nmped_button", "nmped_button_func");
 function nmped_button_func($attr, $content = null) {

	if (is_array($attr)) extract($attr);

	//Checks if content is provided otherwise display the text attribute as button text
	$buttonText = (isset($text) && !empty($text)) ? $text : "Button";
	if (!empty($content)) {
		$buttonText = $content;
	}

	$btnStyle = '';

	//Button Color
	if (isset($button_color) && !empty($button_color)) {
		if (strpos($button_color,"#")===false)
			$button_color = "#".$button_color;
		$btnStyle .= "background-color:".$button_color.";";
	}

	//Button Text color
	if (isset($text_color) && !empty($text_color)) {
		if (strpos($text_color,"#")===false)
			$text_color = "#".$text_color;
		$btnStyle .= "color:".$text_color.";";
	}

	//Button Font Face
	if (isset($font_face) && !empty($font_face)) {
		$btnStyle .= "font-family:".$font_face.";";
	}

	//Button Font Size
	if (isset($font_size) && !empty($font_size)) {
		$btnStyle .= "font-size:".$font_size."px;";
	}

	//Button Font Weight
	if (isset($font_weight) && !empty($font_weight)) {
		$btnStyle .= "font-weight:".$font_weight.";";
	}

	//Button Code
	$buttonStart = "<button class='btn custom-button' style='".$btnStyle."'>";
	$buttonEnd = "</button>";

	$return = $buttonStart.$buttonText.$buttonEnd;

	if (isset($url) && !empty($url)) {
		$urlStart = "<a href='".$url."'";
		if (isset($new_window) && ($new_window=="yes")) {
			$urlStart .= " onmousedown='ga(\"send\", \"event\",\"Outbound\",window.location.pathname,\"".$url."\",0);' target='_blank'";
		}
		$urlStart .= ">";
		$urlEnd = "</a>";
		$return = $urlStart.$return.$urlEnd;
	}

	return $return;
 }
 
 /**
 * SubPages
 * Shortcode Example : [nmped_subpages title='' id='']
 */
 add_shortcode("nmped_subpages", "nmped_subpages_func");
 function nmped_subpages_func($attr, $content = null) {

      if (is_array($attr)) extract($attr);

      $html = "";
      
      if (! empty($title)){
       $html.= '<h4 class="widget-title">' . $title . '</h4>';
      }
      
      if (! empty($id)){
	 $html .= nmped_display_subpages($id);
      } else {
	 $queried_object = get_queried_object();
	 if ($queried_object) {
            $html .= nmped_display_subpages($queried_object->ID);
	 }
      }
      return $html;
 }

/*--------------------------------------------------------------------------------------
*
* Parse data-attributes for shortcodes
*
*-------------------------------------------------------------------------------------*/
function parse_data_attributes( $data ) {

	$data_props = '';

	if( $data ) {
	  $data = explode( '|', $data );

	  foreach( $data as $d ) {
	    $d = explode( ',', $d );
	    $data_props .= sprintf( 'data-%s="%s" ', esc_html( $d[0] ), esc_attr( trim( $d[1] ) ) );
	  }
	}
	else {
	  $data_props = false;
	}
	return $data_props;
}
?>