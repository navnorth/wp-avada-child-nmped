<?php
/**
 * Archives template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
//Custom - removing default Avada Title Bar from header
add_action( 'avada_override_current_page_title_bar' , 'disable_page_title_bar_in_header', 10, 1 );
function disable_page_title_bar_in_header($page_id) {
    return;
}
?>
<?php get_header(); ?>
<section id="content" <?php Avada()->layout->add_class( 'content_class' ); ?> <?php Avada()->layout->add_style( 'content_style' ); ?>>
    <!-- Custom - Adding Title Bar before content-->
    <?php avada_current_page_title_bar( $c_page_id ); ?>
	<?php if ( category_description() ) : ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class( 'fusion-archive-description' ); ?>>
			<div class="post-content">
				<?php echo category_description(); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php get_template_part( 'templates/blog', 'layout' ); ?>
</section>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
