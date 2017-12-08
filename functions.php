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

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
    wp_enqueue_script( 'external-script', get_stylesheet_directory_uri() . '/assets/js/external.js', array( 'jquery', 'underscore' ) );
    wp_enqueue_style( 'external-style', get_stylesheet_directory_uri() . '/assets/css/external.css', array() );
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

//Sidebar Display : to add css if sidebar contains image and align image just below main menu as mockup
function add_css_for_featured_image_in_sidebar(){
    global $post;

    if(isset($post->ID)) {
        $templateName =get_post_meta( $post->ID, '_wp_page_template', true );

        if("side-navigation.php" == strtolower($templateName)){

            $parentPageId = wp_get_post_parent_id($post->ID);

            if(has_post_thumbnail($post) || ( isset($parentPageId) && has_post_thumbnail($parentPageId) ) ){ ?>
                <style>
                    .fusion-page-title-bar{
                        width:75%;
                    }
                    #sidebar{
                        margin-top: -120px;
                    }
                    #main .sidebar{
                        padding: 0 !important;
                    }
                </style>

                <script>
                    jQuery(document).ready(function(){
                        jQuery("p.widget-title").each(function() {
                            jQuery(this).prop("style")["font-size"] && jQuery(this).attr("data-inline-fontsize", !0), jQuery(this).prop("style")["font-size"] && jQuery(this).attr("data-inline-lineheight", !0), jQuery(this).attr("data-fontsize", parseInt(jQuery(this).css("font-size"))), jQuery(this).attr("data-lineheight", parseInt(jQuery(this).css("line-height")));
                        });
                    });
                </script>
            <?php }
        }
    }
 }
add_action('wp_footer','add_css_for_featured_image_in_sidebar');

// Load Custom Widget
add_action( 'widgets_init' , 'load_nmped_widget' );
function load_nmped_widget() {
    register_widget('NMPED_Subpages_Widget');
}

//replace footer widget titles h4(heading tag) to p
add_filter( 'dynamic_sidebar_params', 'custom_footer_widget_titles', 20 );
function custom_footer_widget_titles( array $params ) {

    $widget = &$params[0];

    if (preg_match('/avada-footer-widget/',$widget["id"])) {

        $footer_heading_fontsize = '';
        $footer_heading_lineheight = '';

        $fusion_options = get_option('fusion_options');

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
    return $params;
}