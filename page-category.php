<?php
/**
 * Template Name: Category
 * @package wp-avada-child-nmped
 */

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}

wp_enqueue_style('homepage-category-styles', get_stylesheet_directory_uri() . '/css/style.css', '1.0.1');

add_action( 'avada_after_main_container' , 'show_full_search' );
?>

<?php get_header('home'); ?>

	<section class="category-page">
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php echo fusion_render_rich_snippets_for_pages(); // WPCS: XSS ok. ?>
				<div class="post-content--v2 ">
					<?php if (function_exists('get_field')): ?>
						<div class="category-page__content post-content">

							<?php
							$category_title = get_field('category_title');
							$content = get_field('content');
							$bottom_content = get_field('bottom_content');
							$categories = get_field('categories');
							?>

							<h1 class="category-page__content-title"><?= $category_title ?></h1>
							<div class="category-page__content-wysiwyg">
								<?= $content ?>
							</div>
						</div>

						<div class="category-page__categories categories">
							<?php foreach ($categories as $category):
								$category_image = $category['category_image'];
								$category_title = $category['category_title'];
								$category_link = $category['category_link'];

								$category_is_link_external = $category['is_link_external'];
								$category_internal_link = $category['internal_link'];
								$category_external_link = $category['external_link'];
								$category_link_title = $category['link_title'];
								$category_open_link_in_new_tab = $category['open_link_in_new_tab'];
								$category_link = $category_is_link_external ? $category_external_link : $category_internal_link;
								?>
								<div class="categories__category category">
									<div class="category__img">
										<a title="<?= $category_link_title ?>"
										   target="<?= get_target_from_acf_link_field($category_open_link_in_new_tab) ?>"
										   class="category__link"
										   href="<?= $category_link ?>">
											<?= wp_get_attachment_image($category_image['ID'], 'portfolio-one'); ?>

											<h2 class="category__title">
												<?= $category_title ?>
											</h2>
										</a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<?php if (!empty($bottom_content)) : ?>
							<div class="category-page__content post-content">
								<div class="category-page__content-wysiwyg">
									<?= $bottom_content ?>
								</div>
							</div>
						<?php endif; ?>

					<?php endif; ?>

				</div>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</section>
<?php get_footer('home');


/* Omit closing PHP tag to avoid "Headers already sent" issues. */
