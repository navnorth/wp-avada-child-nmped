<?php

global $nmped_toggle;

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
include_once wp_normalize_path( get_stylesheet_directory() . '/includes/widgets/class-nmped-subpages-widget.php' );

/**
 * Include NMPED Related Posts Widget
 */
include_once wp_normalize_path( get_stylesheet_directory() . '/includes/widgets/class-nmped-related-posts-widget.php' );

/**
 * Include NMPED Toggle Class
 */
include_once wp_normalize_path( get_stylesheet_directory() . '/includes/shortcodes/nmped-toggle.php' );

/**
 * Include NMPED PED Settings
 */
include_once wp_normalize_path( get_stylesheet_directory() . '/includes/settings.php' );

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
    wp_enqueue_script( 'external-script', get_stylesheet_directory_uri() . '/assets/js/external.js', array( 'jquery', 'underscore' ) );
    wp_enqueue_style( 'external-style', get_stylesheet_directory_uri() . '/assets/css/external.css', array() );

    wp_enqueue_script( 'custom-child-avada', get_stylesheet_directory_uri() . '/assets/js/custom-child-avada.js', '','',true);
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function nmpedadmin_enqueue_styles() {
    wp_enqueue_style( 'shortcode-styles', get_stylesheet_directory_uri() . '/theme-functions/tinymce_button/shortcode_button.css', array() );
    wp_enqueue_style( 'admin-styles', get_stylesheet_directory_uri() . '/assets/css/admin.css', array() );
}
add_action( 'admin_enqueue_scripts' , 'nmpedadmin_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
	// Remove Main Menu Search Icon
	remove_filter( 'wp_nav_menu_items', 'avada_add_search_to_main_nav', 20 );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

// Permissions
add_filter('wpcf7_map_meta_cap', 'change_cf7_capabilities',10,1);
function change_cf7_capabilities($meta_caps) {

    $meta_caps = array(
        'wpcf7_edit_contact_form' => 'cf7_edit_forms',
        'wpcf7_edit_contact_forms' => 'cf7_edit_forms',
        'wpcf7_read_contact_forms' => 'cf7_read_forms',
        'wpcf7_delete_contact_form' => 'cf7_delete_forms',
        'wpcf7_manage_integration' => 'cf7_manage_integration' );

    return $meta_caps;
}

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
	$wp_query->set( 'post_type' , array('post') );

    // add page to tag archive
    if ( $wp_query->get( 'tag' ) )
	$wp_query->set( 'post_type' , $my_post_types );
}

// custom functions for Home and Category templates
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
        'page_title'    => 'Category Template Search',
        'menu_title'    => 'Category Search',
        'menu_slug'     => 'category-search',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}

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
    	input[type=button]#qt_content_fusion_shortcodes_text_mode, #fusion_toggle_builder { display: none; }
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

// Add DOM event Listener to Contact Form
function nmped_cf7_footer() {
?>
<script type="text/javascript">
    document.addEventListener( 'wpcf7submit', function( event ) {
	setTimeout(function(){
	    jQuery('.wpcf7-response-output.wpcf7-validation-errors').attr('tabindex', '0');
	    jQuery('.wpcf7-response-output.wpcf7-validation-errors').focus();
	    /*jQuery(window).scrollTop(jQuery('.wpcf7-response-output.wpcf7-validation-errors').offset().top-120);*/
	}, 500);
}, false );
</script>
<?php
}
add_action( 'wp_footer', 'nmped_cf7_footer' );

function nmped_hide_menu_options(){
    if (!current_user_can('administrator')) {
    ?>
        <style>
    	#menu-posts-avada_faq,
    	#menu-posts-themefusion_elastic,
    	#menu-posts-avada_portfolio,
    	#menu-posts-slide,
    	#menu-comments,
    	#searchwp-index-errors-notice,
        #toplevel_page_avada,
        #toplevel_page_fusion-builder-options,
        .hide-if-no-customize { display:none; }
        </style>
    <?php
    }
}
add_action( 'admin_footer', 'nmped_hide_menu_options' );

add_action( 'wp_head', 'remove_default_blog_post_content' );
function remove_default_blog_post_content(){
	remove_action( 'avada_blog_post_content', 'avada_render_blog_post_content', 10 );
}

function nmped_list_categories_for_posts_only($args = ''){
    $defaults = array(
		'child_of'            => 0,
		'current_category'    => 0,
		'depth'               => 0,
		'echo'                => 1,
		'exclude'             => '',
		'exclude_tree'        => '',
		'feed'                => '',
		'feed_image'          => '',
		'feed_type'           => '',
		'hide_empty'          => 1,
		'hide_title_if_empty' => false,
		'hierarchical'        => true,
		'order'               => 'ASC',
		'orderby'             => 'name',
		'separator'           => '<br />',
		'show_count'          => 1,
		'show_option_all'     => '',
		'show_option_none'    => __( 'No categories' ),
		'style'               => 'list',
		'taxonomy'            => 'category',
		'title_li'            => '',
		'use_desc_for_title'  => 1,
	);

	$r = wp_parse_args( $args, $defaults );

	if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] )
		$r['pad_counts'] = false;

	// Descendants of exclusions should be excluded too.
	if ( true == $r['hierarchical'] ) {
		$exclude_tree = array();

		if ( $r['exclude_tree'] ) {
			$exclude_tree = array_merge( $exclude_tree, wp_parse_id_list( $r['exclude_tree'] ) );
		}

		if ( $r['exclude'] ) {
			$exclude_tree = array_merge( $exclude_tree, wp_parse_id_list( $r['exclude'] ) );
		}

		$r['exclude_tree'] = $exclude_tree;
		$r['exclude'] = '';
	}

	if ( ! isset( $r['class'] ) )
		$r['class'] = ( 'category' == $r['taxonomy'] ) ? 'categories' : $r['taxonomy'];

	if ( ! taxonomy_exists( $r['taxonomy'] ) ) {
		return false;
	}

	$show_option_all = $r['show_option_all'];
	$show_option_none = $r['show_option_none'];

	$categories = get_categories_by_post_type('post', $r );

	$output = '';
	if ( $r['title_li'] && 'list' == $r['style'] && ( ! empty( $categories ) || ! $r['hide_title_if_empty'] ) ) {
		$output = '<li class="' . esc_attr( $r['class'] ) . '">' . $r['title_li'] . '<ul>';
	}
	if ( empty( $categories ) ) {
		if ( ! empty( $show_option_none ) ) {
			if ( 'list' == $r['style'] ) {
				$output .= '<li class="cat-item-none">' . $show_option_none . '</li>';
			} else {
				$output .= $show_option_none;
			}
		}
	} else {
		if ( ! empty( $show_option_all ) ) {

			$posts_page = '';

			// For taxonomies that belong only to custom post types, point to a valid archive.
			$taxonomy_object = get_taxonomy( $r['taxonomy'] );
			if ( ! in_array( 'post', $taxonomy_object->object_type ) && ! in_array( 'page', $taxonomy_object->object_type ) ) {
				foreach ( $taxonomy_object->object_type as $object_type ) {
					$_object_type = get_post_type_object( $object_type );

					// Grab the first one.
					if ( ! empty( $_object_type->has_archive ) ) {
						$posts_page = get_post_type_archive_link( $object_type );
						break;
					}
				}
			}

			// Fallback for the 'All' link is the posts page.
			if ( ! $posts_page ) {
				if ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
					$posts_page = get_permalink( get_option( 'page_for_posts' ) );
				} else {
					$posts_page = home_url( '/' );
				}
			}

			$posts_page = esc_url( $posts_page );
			if ( 'list' == $r['style'] ) {
				$output .= "<li class='cat-item-all'><a href='$posts_page'>$show_option_all</a></li>";
			} else {
				$output .= "<a href='$posts_page'>$show_option_all</a>";
			}
		}

		if ( empty( $r['current_category'] ) && ( is_category() || is_tax() || is_tag() ) ) {
			$current_term_object = get_queried_object();
			if ( $current_term_object && $r['taxonomy'] === $current_term_object->taxonomy ) {
				$r['current_category'] = get_queried_object_id();
			}
		}

		if ( $r['hierarchical'] ) {
			$depth = $r['depth'];
		} else {
			$depth = -1; // Flat.
		}
		$output .= walk_category_tree( $categories, $depth, $r );
	}

	if ( $r['title_li'] && 'list' == $r['style'] && ( ! empty( $categories ) || ! $r['hide_title_if_empty'] ) ) {
		$output .= '</ul></li>';
	}

	/**
	 * Filters the HTML output of a taxonomy list.
	 *
	 * @since 2.1.0
	 *
	 * @param string $output HTML output.
	 * @param array  $args   An array of taxonomy-listing arguments.
	 */
	//$output = preg_replace('/<\/a> \(([0-9]+)\)/', ' <span class="count"> (\\1)</span></a>', $output);
	$output = preg_replace('/<\/a> \(([0-9]+)\)/', ' (\\1)</a>', $output);
	$html = $output;

	if ( $r['echo'] ) {
		echo $html;
	} else {
		return $html;
	}
}
function get_categories_by_post_type($post_type, $args = '') {
    $exclude = array();
    $results = array();
    $categories = array();

    //check all categories and exclude
    foreach (get_categories($args) as $category) {
        $posts = get_posts(array('post_type' => $post_type, 'category' => $category->cat_ID, 'post_status' => 'publish'));

        if (empty($posts)) { $exclude[] = $category->cat_ID; } else {
	    foreach ($posts as $post) {
		if ($post->post_type=="post") {
		    $results[] = array(
				'cat_ID' => $category->cat_ID,
				'category' => $category,
				'post' => $post
			);
		}
	    }
	}
    }

    $unique_categories = wp_list_pluck($results, 'category', 'cat_ID');

    $counts = array_count_values(array_column($results, 'cat_ID'));

    foreach($unique_categories as $category){
	foreach($counts as $key=>$val){
	    if ($category->cat_ID==$key){
		$category->category_count = $category->count = $val;
	    }
	}
    }

    return $unique_categories;
}
add_filter( 'wp_list_categories' , 'nmped_list_categories_for_post_type' );

/** Update Logo of Login Page **/
function nmped_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-retina-1.png);
		height:90px;
		width:320px;
		background-size: 320px 90px;
		background-repeat: no-repeat;
        box-shadow:0 1px 3px rgba(0,0,0,.13);
        }
	.center { text-align:center; }
	.login #nav a { color: #00a0d2; }
	.login #nav a:hover { text-decoration:underline; }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'nmped_login_logo' );

function nmped_require_alt_script() {
    $site_id = is_multisite() ? get_current_blog_id() : 0;

    /**
    * Filter the screen IDs in where the script should be displayed
    *
    * This filter allows us to limit or expand to multiple content types or other screens based on the the current site.
    *
    **/
    $screens = apply_filters( 'nmped_replace_alt_tags_screen_ids', array( 'post', 'page' ), $site_id );

    if ( in_array( get_current_screen()->id, $screens, true ) ) {
	wp_register_script( 'nmped_require_alt_tags', get_stylesheet_directory_uri() . '/assets/js/nmped-media-require.js', array( 'jquery', 'backbone', 'underscore' ), null, true );
	wp_register_style( 'nmped_require_alt_tags', get_stylesheet_directory_uri() . '/assets/css/nmped-require-image-alt-tags.css', array(), null );

	wp_enqueue_script( 'nmped_require_alt_tags' );
	wp_enqueue_style( 'nmped_require_alt_tags' );

	$disclaimer_copy = apply_filters( 'nmped_alt_tag_disclaimer', esc_html__( 'Please include an \'Alt Text\' before proceeding with inserting your image.', 'wp-avada-child-nmped' ) );

	wp_localize_script(
	    'nmped_require_alt_tags',
	    'nmpedTagsCopy',
	    array(
		    'txt'        => esc_html__( 'The following image(s) are missing alt text', 'wp-avada-child-nmped' ),
		    'editTxt'    => esc_html__( 'You must enter \'alt text\' for this image before you can proceed', 'wp-avada-child-nmped' ),
		    'disclaimer' => $disclaimer_copy,
	    )
	);
    }
}
add_action( 'admin_enqueue_scripts', 'nmped_require_alt_script' );

function nmped_filter_manage_media_columns( $columns ){
    $columns['alttext'] = esc_html__( 'Alt Text', 'wp-avada-child-nmped' );

    return $columns;
}
add_filter( 'manage_media_columns', 'nmped_filter_manage_media_columns' );

function nmped_action_manage_media_custom_column( $column_name, $post_id ) {

    if ( 'alttext' === $column_name && wp_attachment_is_image( $post_id ) ) {

	$alt_text = get_post_meta( $post_id, '_wp_attachment_image_alt', true );

	if ( empty( $alt_text ) ) {
		printf( '<span style="color: red;">%s</span>', esc_html__( 'Missing', 'wp-avada-child-nmped' ) );
	}
    }

}
add_action( 'manage_media_custom_column', 'nmped_action_manage_media_custom_column', 10, 3 );

function nmped_enqueue_media_scripts() {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts' , 'nmped_enqueue_media_scripts' );

function nmped_override_attachment_display() {
    add_action( 'admin_print_footer_scripts' , 'nmped_attachment_display' );
}
add_action( 'wp_enqueue_media' , 'nmped_override_attachment_display' );

function nmped_attachment_display() {
?>
    <script type="text/html" id="tmpl-nmped-attachment-details">
		<h2>
			<?php _e( 'Attachment Details' ); ?>
			<span class="settings-save-status">
				<span class="spinner"></span>
				<span class="saved"><?php esc_html_e('Saved.'); ?></span>
			</span>
		</h2>
		<div class="attachment-info">
			<div class="thumbnail thumbnail-{{ data.type }}">
				<# if ( data.uploading ) { #>
					<div class="media-progress-bar"><div></div></div>
				<# } else if ( 'image' === data.type && data.sizes ) { #>
					<img src="{{ data.size.url }}" draggable="false" alt="" />
				<# } else { #>
					<img src="{{ data.icon }}" class="icon" draggable="false" alt="" />
				<# } #>
			</div>
			<div class="details">
				<div class="filename">{{ data.filename }}</div>
				<div class="uploaded">{{ data.dateFormatted }}</div>

				<div class="file-size">{{ data.filesizeHumanReadable }}</div>
				<# if ( 'image' === data.type && ! data.uploading ) { #>
					<# if ( data.width && data.height ) { #>
						<div class="dimensions">{{ data.width }} &times; {{ data.height }}</div>
					<# } #>

					<# if ( data.can.save && data.sizes ) { #>
						<a class="edit-attachment" href="{{ data.editLink }}&amp;image-editor" target="_blank"><?php _e( 'Edit Image' ); ?></a>
					<# } #>
				<# } #>

				<# if ( data.fileLength ) { #>
					<div class="file-length"><?php _e( 'Length:' ); ?> {{ data.fileLength }}</div>
				<# } #>

				<# if ( ! data.uploading && data.can.remove ) { #>
					<?php if ( MEDIA_TRASH ): ?>
					<# if ( 'trash' === data.status ) { #>
						<button type="button" class="button-link untrash-attachment"><?php _e( 'Untrash' ); ?></button>
					<# } else { #>
						<button type="button" class="button-link trash-attachment"><?php _ex( 'Trash', 'verb' ); ?></button>
					<# } #>
					<?php else: ?>
						<button type="button" class="button-link delete-attachment"><?php _e( 'Delete Permanently' ); ?></button>
					<?php endif; ?>
				<# } #>

				<div class="compat-meta">
					<# if ( data.compat && data.compat.meta ) { #>
						{{{ data.compat.meta }}}
					<# } #>
				</div>
			</div>
		</div>

		<label class="setting" data-setting="url">
			<span class="name"><?php _e('URL'); ?></span>
			<input type="text" value="{{ data.url }}" readonly />
		</label>
		<# var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly'; #>
		<?php if ( post_type_supports( 'attachment', 'title' ) ) : ?>
		<label class="setting" data-setting="title">
			<span class="name"><?php _e('Title'); ?></span>
			<input type="text" value="{{ data.title }}" {{ maybeReadOnly }} />
		</label>
		<?php endif; ?>
		<# if ( 'audio' === data.type ) { #>
		<?php foreach ( array(
			'artist' => __( 'Artist' ),
			'album' => __( 'Album' ),
		) as $key => $label ) : ?>
		<label class="setting" data-setting="<?php echo esc_attr( $key ) ?>">
			<span class="name"><?php echo $label ?></span>
			<input type="text" value="{{ data.<?php echo $key ?> || data.meta.<?php echo $key ?> || '' }}" />
		</label>
		<?php endforeach; ?>
		<# } #>
		<label class="setting" data-setting="caption">
			<span class="name"><?php _e('Caption'); ?></span>
			<textarea {{ maybeReadOnly }}>{{ data.caption }}</textarea>
		</label>
		<# if ( 'image' === data.type ) { #>
			<label class="setting" data-setting="alt">
				<span class="name"><?php _e('Alt Text'); ?> <span class="red required">*</span></span>
				<input type="text" value="{{ data.alt }}" {{ maybeReadOnly }} />
			</label>
		<# } #>
		<label class="setting" data-setting="description">
			<span class="name"><?php _e('Description'); ?></span>
			<textarea {{ maybeReadOnly }}>{{ data.description }}</textarea>
		</label>
	</script>
     <script>
    jQuery(document).ready( function($) {
        if( typeof wp.media.view.Attachment.Details != 'undefined' ){
            wp.media.view.Attachment.Details.prototype.template = wp.media.template( 'nmped-attachment-details' );
        }
    });
    </script>
     <style>
	.media-sidebar .setting .name .red.required { float: none; color: #dc3232; }
     </style>
<?php
}

// Change lost password text
function change_lost_password( $text ) {
    if ($text == "Lost your password?") {
	   $text = 'For login issues, please email <a href="mailto:PED.HelpDesk@state.nm.us?subject=WordPress Login Issues" title="send email to PED.HelpDesk@state.nm.us">PED.HelpDesk@state.nm.us</a>.';
    }
    return $text;
}
add_filter( 'gettext' , 'change_lost_password' );

function nmped_lostpassword_url() {
    return "";
}
add_filter( 'lostpassword_url',  'nmped_lostpassword_url', 10, 0 );

function nmped_customize_login_error( $error ) {
    return "Invalid username/password";
}
add_filter( 'login_errors' , 'nmped_customize_login_error' );

function nmped_login_message( $message ) {
    if ( empty($message) ){
        return "<p class='center'><strong>Provide your DoIT data center credentials <br/>to login (PEDEUI).</strong></p>";
    } else {
        return $message;
    }
}
add_filter( 'login_message', 'nmped_login_message' );

function nmped_login_script(){
?>
    <script>
	jQuery(document).ready(function($){
	    $('#login #nav a').first().contents().unwrap();
	});
    </script>
<?php
}
add_action( 'login_footer' , 'nmped_login_script' );

/** Hide Widgets from Admin Dashboard **/
function nmped_remove_dashboard_widgets(){
    if (!current_user_can('administrator')) {
	//Welcome Panel
	remove_action('welcome_panel', 'wp_welcome_panel');
	//Wordpress Events and News
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	// Quick Drafts
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	// At a Glance
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	//ThemeFusion News
	remove_meta_box( 'themefusion_news', 'dashboard', 'normal' );
	// Google Analytics Dashboard
	remove_meta_box( 'gadwp-widget', 'dashboard', 'normal' );
    }
}
add_action('wp_dashboard_setup', 'nmped_remove_dashboard_widgets' );

/** Add PED Settings menu **/
function setup_ped_settings_menu() {
    add_submenu_page( "options-general.php" ,
			"PED Settings" ,
			"PED Settings" ,
			"manage_options" ,
			"ped-settings" ,
			"theme_ped_settings"
		     );
}
add_action( 'admin_menu' , 'setup_ped_settings_menu' );

/** Register PED Settings **/
function register_ped_settings() {
    
}
add_action ( 'admin_init' , 'register_ped_settings' );

function remove_posts_menu() {
    if (current_user_can('author')) {
	remove_menu_page('edit.php');
    }
}
add_action( 'admin_menu' , 'remove_posts_menu' );