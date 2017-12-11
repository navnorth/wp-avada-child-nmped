<?php
/**
 * Created by PhpStorm.
 * User: kisgal21
 * Date: 12/11/17
 * Time: 5:17 PM
 */

add_image_size( 'homepage-tile-small', 400, 400, array( 'center', 'center' ) ); // Hard crop center
add_image_size( 'homepage-tile-large', 800, 800, array( 'center', 'center' ) ); // Hard crop center - large

function get_target_from_acf_link_field($link_field){
	if($link_field){
		return '_blank';
	}else{
		return '_self';
	}
}

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Category Template Search',
		'menu_title'	=> 'Category Search',
		'menu_slug' 	=> 'category-search',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

}