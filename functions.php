<?php

/**
 * Add Shortcode
 **/
require_once( get_stylesheet_directory() . '/theme-functions/shortcode.php' );

/**
 * Shortcode Button.
 */
 require_once( get_stylesheet_directory() . '/theme-functions/tinymce_button/shortcode_button.php' );

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

// remove the default 'Posts' before we add a new one
add_action('admin_menu','remove_default_post_type');
function remove_default_post_type() {
	remove_menu_page('edit.php');
}

// add a new 'Posts' with '/news/' as the slug
add_action( 'init', 'my_NEWS_default_post_type', 1 );
function my_NEWS_default_post_type() {
    register_post_type( 'post', array(
        'labels' => array(
            'name_admin_bar' => _x( 'Post', 'add new on admin bar' ),
        ),
        'public'  => true,
        '_builtin' => false,
        '_edit_link' => 'post.php?post=%d',
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'hierarchical' => false,
        'rewrite' => array( 'slug' => 'news', 'with_front' => false, 'pagination' => true ),
        'query_var' => false,
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats' ),
    ) );
}

//Add rewrite tag use for news permalinks
add_action('init', 'my_NEWS_rewrite_tag', 10, 0);
function my_NEWS_rewrite_tag() {
    add_rewrite_tag( '%news%', '([^&]+)' );
}

//Add rewrite rule for News
add_action( 'init', 'my_NEWS_rewrite_rules', 10, 0 );
function my_NEWS_rewrite_rules(){
    add_rewrite_rule( '^news/page/([0-9]{1,})/?', 'index.php?post_type=post&paged=$matches[1]', 'top' );
}

//Flush rewrite rules after activating child theme
add_action( 'after_switch_theme', 'my_NEWS_rewrite_flush' );
function my_NEWS_rewrite_flush() {
    my_NEWS_default_post_type();
    flush_rewrite_rules();
}

// Add Categories and Tags on Pages
add_action( 'init' , 'add_categories_taxonomies_to_pages' );
function add_categories_taxonomies_to_pages() {
    register_taxonomy_for_object_type( 'post_tag' , 'page' );
    register_taxonomy_for_object_type( 'category' , 'page' );
}

// Include pages to category and tag archives
if ( ! is_admin() ) {
    add_action( 'pre_get_posts' , 'category_tag_archives' );
}
function category_tag_archives( $wp_query ) {
    $my_post_types = array( 'post', 'page' );
    
    // add page to category archive
    if ( $wp_query->get( 'category_name' ) || $wp_query->get( 'cat' ) )
	$wp_query->set( 'post_type' , $my_post_types );
    
    // add page to tag archive
    if ( $wp_query->get( 'tag' ) )
	$wp_query->set( 'post_type' , $my_post_types );
}