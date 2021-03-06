<?php
/**
 * Header template.
 *
 * @package    Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}
?>
<!DOCTYPE html>
<?php global $woocommerce; ?>
<html class="<?php echo (Avada()->settings->get('smooth_scrolling')) ? 'no-overflow-y' : ''; ?>" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?php Avada()->head->the_viewport(); ?>

	<?php wp_head(); ?>

	<?php $object_id = get_queried_object_id(); ?>
	<?php $c_page_id = Avada()->fusion_library->get_page_id(); ?>

	<script type="text/javascript">
        var doc = document.documentElement;
        doc.setAttribute('data-useragent', navigator.userAgent);
	</script>

	<?php
	/**
	 *
	 * The settings below are not sanitized.
	 * In order to be able to take advantage of this,
	 * a user would have to gain access to the database
	 * in which case this is the least on your worries.
	 */
	echo Avada()->settings->get('google_analytics'); // WPCS: XSS ok.
	echo Avada()->settings->get('space_head'); // WPCS: XSS ok.
	?>
</head>

<?php
$wrapper_class = (is_page_template('blank.php')) ? 'wrapper_blank' : '';

if ('modern' === Avada()->settings->get('mobile_menu_design')) {
	$mobile_logo_pos = strtolower(Avada()->settings->get('logo_alignment'));
	if ('center' === strtolower(Avada()->settings->get('logo_alignment'))) {
		$mobile_logo_pos = 'left';
	}
}

?>
<body <?php body_class(); ?>>
<!-- Skip Content Navigation -->
	<div id="skipcontent"><a class="assistive-text" href="#main" title="<?php esc_attr_e( 'Skip to Content', 'wp-avad-child-nmped' ); ?>"><?php _e( 'Skip to Content', 'wp-avad-child-nmped' ); ?></a></div>
<?php do_action('avada_before_body_content');

$boxed_side_header_right = false;
$page_bg_layout = ($c_page_id) ? get_post_meta($c_page_id, 'pyre_page_bg_layout', true) : 'default';
?>
<?php if ((('Boxed' === Avada()->settings->get('layout') && ('default' === $page_bg_layout || '' == $page_bg_layout)) || 'boxed' === $page_bg_layout) && 'Top' != Avada()->settings->get('header_position')) : ?>
<?php if (Avada()->settings->get('slidingbar_widgets') && !is_page_template('blank.php') && ('Right' == Avada()->settings->get('header_position') || 'Left' == Avada()->settings->get('header_position'))) : ?>
	<?php get_template_part('slidingbar'); ?>
	<?php $boxed_side_header_right = true; ?>
<?php endif; ?>
<div id="boxed-wrapper">
	<?php endif; ?>
	<?php if ((('Boxed' === Avada()->settings->get('layout') && 'default' === $page_bg_layout) || 'boxed' === $page_bg_layout) && 'framed' === Avada()->settings->get('scroll_offset')) : ?>
		<div class="fusion-sides-frame"></div>
	<?php endif; ?>
	<div id="wrapper" class="<?php echo esc_attr($wrapper_class); ?>">
		<div id="home" style="position:relative;top:-1px;"></div>
		<?php if (Avada()->settings->get('slidingbar_widgets') && !is_page_template('blank.php') && !$boxed_side_header_right) : ?>
			<?php get_template_part('slidingbar'); ?>
		<?php endif; ?>
		<?php if (false !== strpos(Avada()->settings->get('footer_special_effects'), 'footer_sticky')) : ?>
		<div class="above-footer-wrapper">
			<?php endif; ?>

			<?php avada_header_template('Below'); ?>
			<?php if ('Left' === Avada()->settings->get('header_position') || 'Right' === Avada()->settings->get('header_position')) : ?>
				<?php avada_side_header(); ?>
			<?php endif; ?>

			<div id="sliders-container">
				<?php
				$slider_page_id = '';
				if (!is_search()) {
					$slider_page_id = '';
					if ((!is_home() && !is_front_page() && !is_archive() && isset($object_id)) || (!is_home() && is_front_page() && isset($object_id))) {
						$slider_page_id = $object_id;
					}
					if (is_home() && !is_front_page()) {
						$slider_page_id = get_option('page_for_posts');
					}
					if (class_exists('WooCommerce') && is_shop()) {
						$slider_page_id = get_option('woocommerce_shop_page_id');
					}

					if (('publish' === get_post_status($slider_page_id) && !post_password_required()) || (current_user_can('read_private_pages') && in_array(get_post_status($slider_page_id), array('private', 'draft', 'pending')))) {
						avada_slider($slider_page_id);
					}
				}
				?>
			</div>
			<?php
			$slider_fallback = get_post_meta($slider_page_id, 'pyre_fallback', true);
			?>
			<?php if ($slider_fallback) : ?>
				<div id="fallback-slide">
					<img src="<?php echo esc_url_raw($slider_fallback); ?>" alt=""/>
				</div>
			<?php endif; ?>
			<?php avada_header_template('Above'); ?>

			<?php
			$main_css = '';
			$row_css = '';
			$main_class = '';

			if (Avada()->layout->is_hundred_percent_template()) {
				$main_css = 'padding-left:0px;padding-right:0px;';
				$hundredp_padding = get_post_meta($c_page_id, 'pyre_hundredp_padding', true);
				if (Avada()->settings->get('hundredp_padding') && !$hundredp_padding) {
					$main_css = 'padding-left:' . Avada()->settings->get('hundredp_padding') . ';padding-right:' . Avada()->settings->get('hundredp_padding');
				}
				if ($hundredp_padding) {
					$main_css = 'padding-left:' . $hundredp_padding . ';padding-right:' . $hundredp_padding;
				}
				$row_css = 'max-width:100%;';
				$main_class = 'width-100';
			}
			do_action('avada_before_main_container');
			?>
			<main id="main" role="main" class="clearfix <?php echo esc_attr($main_class); ?>">
