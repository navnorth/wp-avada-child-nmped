<?php
/**
 * Template Name: Homepage
 * @package wp-avada-child-nmped
 */

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
	exit('Direct script access denied.');
}

wp_enqueue_style('homepage-category-styles', get_stylesheet_directory_uri() . '/css/style.css', '1.0.1');

?>
<?php get_header('home'); ?>

	<section class="homepage-content">
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php echo fusion_render_rich_snippets_for_pages(); // WPCS: XSS ok. ?>
				<div class="post-content--v2">
					<?php if (function_exists('get_field')): ?>
						<?php $homepage_rows = get_field('homepage_row'); ?>
						<?php if ($homepage_rows): ?>
							<?php $i = 0; ?>
							<div class="homepage-rows">
								<?php foreach ($homepage_rows as $homepage_row): $i++;

									/** $callout_1 first callout in section  */
									$callout_1 = $homepage_row['callout_1'];
									$callout_1_bg = $callout_1['background_color'];
									$callout_1_img = $callout_1['callout_image'];
									$callout_1_section_heading = $callout_1['section_heading'];
									$callout_1_section_text = $callout_1['section_text'];
									$callout_1_button_text = $callout_1['button_text'];
									$callout_1_is_link_external = $callout_1['is_link_external'];
									$callout_1_internal_link = $callout_1['internal_link'];
									$callout_1_external_link = $callout_1['external_link'];
									$callout_1_link_title = $callout_1['link_title'];
									$callout_1_open_link_in_new_tab = $callout_1['open_link_in_new_tab'];
									$callout_1_link = $callout_1_is_link_external ? $callout_1_external_link : $callout_1_internal_link;

									/** $callout_2 second callout in section  */
									$callout_2 = $homepage_row['callout_2'];
									$callout_2_bg = $callout_2['background_color'];
									$callout_2_img = $callout_2['callout_image'];
									$callout_2_section_heading = $callout_2['section_heading'];
									$callout_2_section_text = $callout_2['section_text'];
									$callout_2_button_text = $callout_2['button_text'];
									$callout_2_is_link_external = $callout_2['is_link_external'];
									$callout_2_internal_link = $callout_2['internal_link'];
									$callout_2_external_link = $callout_2['external_link'];
									$callout_2_link_title = $callout_2['link_title'];
									$callout_2_open_link_in_new_tab = $callout_2['open_link_in_new_tab'];
									$callout_2_link = $callout_2_is_link_external ? $callout_2_external_link : $callout_2_internal_link;

									/** $large_callout large callout next to smaller callout sections (on tablet - up)  */
									$large_callout = $homepage_row['large_callout'];
									$large_callout_bg = $large_callout['background_color'];
									$large_callout_img = $large_callout['callout_image'];
									$large_callout_section_heading = $large_callout['section_heading'];
									$large_callout_section_text = $large_callout['section_text'];
									$large_callout_button_text = $large_callout['button_text'];
									$large_callout_is_link_external = $large_callout['is_link_external'];
									$large_callout_internal_link = $large_callout['internal_link'];
									$large_callout_external_link = $large_callout['external_link'];
									$large_callout_link_title = $large_callout['link_title'];
									$large_callout_open_link_in_new_tab = $large_callout['open_link_in_new_tab'];
									$large_callout_link = $large_callout_is_link_external ? $large_callout_external_link : $large_callout_internal_link;
									?>

									<div class="homepage-rows__row <?= $i % 2 === 0 ? 'homepage-rows__row--reverse' : '' ?>">

										<div class="homepage-rows__small-row">

											<!-- callout 1 -->
											<div class="callout callout--small callout--<?= $callout_1_bg ?>">
												<div class="callout__wrap callout__wrap--text">
													<h2 class="callout__title"><?= $callout_1_section_heading ?></h2>
													<div class="callout__text"><?= $callout_1_section_text ?></div>
													<a title="<?= $callout_1_link_title ?>"
													   target="<?= get_target_from_acf_link_field($callout_1_is_link_external) ?>"
													   href="<?= $callout_1_link ?>"
													   class="callout__button"><?= $callout_1_button_text ?></a>
												</div>

												<div class="callout__wrap callout__wrap--image">
													<div class="callout__image callout__image--small background-filter">
														<?= wp_get_attachment_image($callout_1_img['ID'], 'homepage-tile-small'); ?>
													</div>
												</div>
											</div>

											<!-- callout 2 -->
											<div class="callout callout--small callout--small-reverse callout--<?= $callout_2_bg ?>">
												<div class="callout__wrap callout__wrap--text">
													<h2 class="callout__title"><?= $callout_2_section_heading ?></h2>
													<div class="callout__text"><?= $callout_2_section_text ?></div>
													<a title="<?= $callout_2_link_title ?>"
													   target="<?= get_target_from_acf_link_field($callout_2_is_link_external) ?>"
													   href="<?= $callout_2_link ?>"
													   class="callout__button"><?= $callout_2_button_text ?></a>
												</div>

												<div class="callout__wrap callout__wrap--image">
													<div class="callout__image callout__image--small background-filter">
														<?= wp_get_attachment_image($callout_2_img['ID'], 'homepage-tile-small'); ?>
													</div>
												</div>
											</div>
										</div>

										<!-- callout 3 (large) -->
										<div class="callout callout--large callout--bg-image background-filter"
										     style="background-image:url(<?= $large_callout_img['url'] ?>)">
											<div class="callout__wrap callout__wrap--text">
												<h2 class="callout__title"><?= $large_callout_section_heading ?></h2>
												<div class="callout__text"><?= $large_callout_section_text ?></div>
												<a title="<?= $large_callout_link_title ?>"
												   target="<?= get_target_from_acf_link_field($large_callout_is_link_external) ?>"
												   href="<?= $large_callout_link ?>"
												   class="callout__button"><?= $large_callout_button_text ?></a>
											</div>

											<!--div class="callout__wrap callout__wrap--image">
												<div class="callout__image">
													<?= wp_get_attachment_image($large_callout_img['ID'], 'homepage-tile-large'); ?>
												</div>
											</div-->
										</div>

									</div>

								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>

				</div>
			</div>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</section>
<?php //do_action('avada_after_content'); ?>
<?php get_footer('home');


/* Omit closing PHP tag to avoid "Headers already sent" issues. */
