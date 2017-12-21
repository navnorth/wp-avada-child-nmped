<?php

/**
 * Add Shortcode
 **/
require_once( get_stylesheet_directory() . '/theme-functions/shortcode.php' );

/**
 * Shortcode Button.
 **/
require_once( get_stylesheet_directory() . '/theme-functions/tinymce_button/shortcode_button.php' );

/**
 * Include Custom Function
 **/
require_once( get_stylesheet_directory() . '/theme-functions/custom_functions.php' );

/**
 * Include NMPED SubPages Widget
 */
include_once wp_normalize_path( get_stylesheet_directory() . '/includes/widget/class-nmped-subpages-widget.php' );

/**
 * Include NMPED Related Posts Widget
 */
include_once wp_normalize_path( get_stylesheet_directory() . '/includes/widget/class-nmped-related-posts-widget.php' );

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
    wp_enqueue_script( 'external-script', get_stylesheet_directory_uri() . '/assets/js/external.js', array( 'jquery', 'underscore' ) );
    wp_enqueue_style( 'external-style', get_stylesheet_directory_uri() . '/assets/css/external.css', array() );

    wp_enqueue_script( 'custom-child-avada', get_stylesheet_directory_uri() . '/assets/js/custom-child-avada.js', '','',true);
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function nmpedadmin_enqueue_styles() {
    wp_enqueue_style( 'shortcode-styles', get_stylesheet_directory_uri() . '/theme-functions/tinymce_button/shortcode_button.css', array() );
}
add_action( 'admin_enqueue_scripts' , 'nmpedadmin_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
	// Remove Main Menu Search Icon
	remove_filter( 'wp_nav_menu_items', 'avada_add_search_to_main_nav', 20 );
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

//Reorder Page Attributes Side Metabox
add_action( 'admin_menu' , 'remove_page_attributes_metabox' );
function remove_page_attributes_metabox() {
    if ( is_admin() ) {
	remove_meta_box( 'pageparentdiv', 'page', 'side' );
    }
}

//Put back Page Attributes Metabox up above
add_action( 'add_meta_boxes', 'add_page_attributes_metabox' );
function add_page_attributes_metabox() {
    add_meta_box( 'pageparentdiv' , __('Page Attributes') , 'page_attributes_meta_box' , 'page' , 'side', 'high' );
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

include 'functions-custom.php';

// Load Custom Widget
add_action( 'widgets_init' , 'load_nmped_widgets' );
function load_nmped_widgets() {
    register_widget('NMPED_Subpages_Widget');
    register_widget('NMPED_Related_Posts_Widget');
}

//replace footer widget titles h4(heading tag) to p
add_filter( 'dynamic_sidebar_params', 'custom_footer_widget_titles', 20 );
function custom_footer_widget_titles( array $params ) {

    $widget = &$params[0];

    $fusion_options = get_option('fusion_options');

    if (preg_match('/avada-footer-widget/',$widget["id"])) {

        $footer_heading_fontsize = '';
        $footer_heading_lineheight = '';

        if (isset($fusion_options['footer_headings_typography']) && is_array($fusion_options['footer_headings_typography']) && !empty($fusion_options['footer_headings_typography'])) {

            $arrFooter_headings_typography = $fusion_options['footer_headings_typography'];

            if (isset($arrFooter_headings_typography['font-size']) && !empty($arrFooter_headings_typography['font-size'])) {

                $footer_heading_fontsize = filter_var($arrFooter_headings_typography['font-size'],
                    FILTER_SANITIZE_NUMBER_INT);
            }

            if (isset($arrFooter_headings_typography['line-height']) && !empty($arrFooter_headings_typography['line-height'])) {

                $footer_heading_lineheight = $arrFooter_headings_typography['line-height'];
            }
        }

        $fontsizestyle = (false == empty($footer_heading_fontsize)) ? 'data-fontsize="' . $footer_heading_fontsize . '"' : '';
        $fontlineheightstyle = (false == empty($footer_heading_fontsize)) ? 'data-lineheight="' . $footer_heading_lineheight . '"' : '';

        // $params will ordinarily be an array of 2 elements, we're only interested in the first element
        $widget['before_title'] = '<p class="widget-title" ' . $fontsizestyle . ' ' . $fontlineheightstyle . '>';
        $widget['after_title'] = '</p>';

        return $params;
    }
    else{
        //replace side nav h4(heading tag for title) to h2
        $sidenav_heading_fontsize = '';

        if (isset($fusion_options['sidew_font_size']) && !empty($fusion_options['sidew_font_size'])) {

            $arrSide_heading_size = $fusion_options['sidew_font_size'];

            if (isset($arrSide_heading_size) && !empty($arrSide_heading_size)) {
                $sidenav_heading_fontsize = filter_var($arrSide_heading_size, FILTER_SANITIZE_NUMBER_INT);
            }

        }

        $fontsizestyle = (false == empty($sidenav_heading_fontsize)) ? 'data-fontsize="' . $sidenav_heading_fontsize . '"' : '';

        // $params will ordinarily be an array of 2 elements, we're only interested in the first element
        $widget['before_title'] = '<h2 class="widget-title" ' . $fontsizestyle . '>';
        $widget['after_title'] = '</h2>';

        return $params;
    }
    return $params;
}

// Replace MyVRSpot URL to embed
add_filter( 'the_content' , 'replace_myvrspot_to_embed' );
function replace_myvrspot_to_embed($content) {
    $pattern = '@(<p.+|\n|^)(http|https)://(live\.)?myvrspot[^\s]*(?=\n|$)@i';

    $matches = array();

    preg_match_all($pattern, $content, $matches);

    foreach ($matches[0] as $match) {
	$match_url = strip_tags($match);
	$embed_code = '<div class="video-container"><iframe src="' . $match_url . '" title="Video Player Embed" frameborder="0" scrolling="no" allowfullscreen mozallowfullscreen webkitallowfullscreen></iframe></div>';
	$content = str_replace($match, $embed_code, $content);

    }
    return $content;
}

// remove Side Navigation and Contact templates (Avada defaults)
function nmped_remove_page_templates( $templates ) {
    unset( $templates['side-navigation.php'] );
    unset( $templates['contact.php'] );
    return $templates;
}
add_filter( 'theme_page_templates', 'nmped_remove_page_templates' );

// Display Full-width search
function show_full_search() {
    get_template_part( 'templates/search' );
}

// Disable Search Menu on Main Nav and reinstantiate it on the child theme
add_filter( 'wp_nav_menu_items' , 'nmped_add_search_to_main_nav', 20, 4 );

//Custom function to get current page_id inside & outside of query loop
function avada_get_current_page_id(){

    $post_id = false;

    if ( in_the_loop() ) {
        $post_id = get_the_ID();
    } else {
        global $wp_query;
        $post_id = $wp_query->get_queried_object_id();
    }

    return $post_id;
}

// Hide Fusion Button on HTML Editor
function hide_fusion_css(){
    if (!current_user_can('administrator')) {
    echo '
	<style>
	input[type=button]#qt_content_fusion_shortcodes_text_mode {
	display: none;
	}
	</style>
    ';
    }
}
add_action( 'admin_head', 'hide_fusion_css' );

// Hide Fusion Builder button on tinymce editor
function nmped_tinymce_buttons( $buttons ) {

    if (!current_user_can('administrator')) {
      //Remove the fusion builder button
      $remove = 'fusion_button';

      //Find the array key and then unset
      if ( ( $key = array_search( $remove, $buttons ) ) !== false )
		unset( $buttons[$key] );

    }
      return $buttons;

 }
add_filter( 'mce_buttons', 'nmped_tinymce_buttons', 20 );

// Hide Fusion Page Options
function hide_fusion_metaboxes() {
    if (!current_user_can('administrator')) {
	remove_meta_box( 'pyre_page_options', 'page', 'advanced' );
	remove_meta_box( 'pyre_post_options', 'post', 'advanced' );
    }
}
add_action( 'do_meta_boxes', 'hide_fusion_metaboxes' );

// Add Default Categories to Events
function add_default_categories_to_events() {
    unregister_taxonomy_for_object_type( 'events_categories', 'ai1ec_event' );
    register_taxonomy_for_object_type( 'category' , 'ai1ec_event' );
}
add_action( 'init' , 'add_default_categories_to_events', 100 );
