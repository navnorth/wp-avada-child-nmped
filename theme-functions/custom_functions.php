<?php
if ( ! function_exists( 'nmped_display_sidenav' ) ) {
	/**
	 * Displays side navigation.
	 *
	 * @param  int $post_id The post ID.
	 * @return string
	 */
	function nmped_display_sidenav( $post_id ) {

		if ( is_page_template( 'side-navigation.php' ) && 0 !== get_queried_object_id() ) {
			$html = '<ul class="side-nav">';

			$parent = false;
			$post_ancestors = get_ancestors( $post_id, 'page' );
			$post_parent    = reset( $post_ancestors );
			$post_children = wp_list_pages( 'title_li=&child_of=' . $post_id . '&echo=0' );

			if (count($post_ancestors)<=1 && $post_children)
				$parent = true;

			$html .= ( is_page( $post_parent ) || $parent ) ? '<li class="current_page_item">' : '<li>';

			if (count($post_ancestors)>1){
				foreach ($post_ancestors as $ancestor) {
					if ( $post_parent==$ancestor ) {
						$html    .= '<a href="' . get_permalink( $ancestor ) . '" title="' . esc_html__( 'Back to Parent Page', 'Avada' ) . '">' . get_the_title( $ancestor ) . '</a></li>';
						$children = wp_list_pages( 'title_li=&child_of=' . $ancestor . '&echo=0' );
					}
				}
			} else {
				$html    .= '<a href="' . get_permalink( $post_id ) . '" title="' . esc_html__( 'Back to Parent Page', 'Avada' ) . '">' . get_the_title( $post_id ) . '</a></li>';
				$children = wp_list_pages( 'title_li=&child_of=' . $post_id . '&echo=0' );
			}

			if ( $children ) {
				$html .= $children;
			}

			$html .= '</ul>';

			return $html;
		}
	}
}

if ( ! function_exists( 'nmped_display_subpages' ) ) {
	/**
	 * Displays side navigation.
	 *
	 * @param  int $post_id The post ID.
	 * @return string
	 */
	function nmped_display_subpages( $post_id ) {

			$html = "";
			$parent = false;
			$children = "";

			$args = array(
				'post_parent' => $post_id,
				'post_type'   => 'page',
				'numberposts' => -1,
				'post_status' => 'publish'
			);
			$post_children = get_children( $args );

			$post_ancestors = get_ancestors( $post_id, 'page' );
			$post_parent    = reset( $post_ancestors );

			if (($post_children || count($post_ancestors)>1) && $post_parent) {

				$html = '<ul class="side-nav side-nav-widget">';

				if (count($post_ancestors)<=1 && $post_children)
					$parent = true;

				$html .= ( is_page( $post_parent ) || $parent ) ? '<li class="current_page_item">' : '<li>';

				if (count($post_ancestors)>1){
					$top_level = array_pop($post_ancestors);
					$post_ancestors = array_reverse($post_ancestors);

					$grand_ancestor = $post_ancestors[0];

					$html    .= '<a href="' . get_permalink( $grand_ancestor ) . '" title="' . esc_html__( 'Back to Parent Page', 'Avada' ) . '">' . get_the_title( $grand_ancestor ) . '</a></li>';
					$children .= wp_list_pages( 'title_li=&child_of=' . $grand_ancestor . '&echo=0' );
				} else {
					$html    .= '<a href="' . get_permalink( $post_id ) . '" title="' . esc_html__( 'Back to Parent Page', 'Avada' ) . '">' . get_the_title( $post_id ) . '</a></li>';
					$children = wp_list_pages( 'title_li=&child_of=' . $post_id . '&echo=0' );
				}

				if ( $children ) {
					$html .= $children;
				}

				$html .= '</ul>';
			}

			return $html;
	}
}

if ( ! function_exists( 'nmped_add_search_to_main_nav' ) ) {
	/**
	 * Add search to the main navigation.
	 *
	 * @param  string $items HTML for the main menu items.
	 * @param  array  $args  Arguments for the WP menu.
	 * @return string
	 */
	function nmped_add_search_to_main_nav( $items, $args ) {
		$ubermenu = false;

		if ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ubermenu_get_menu_instance_by_theme_location( $args->theme_location ) ) {

			// Disable woo cart on ubermenu navigations.
			$ubermenu = true;
		}

		if ( 'v6' != Avada()->settings->get( 'header_layout' ) && false == $ubermenu ) {
			if ( 'main_navigation' == $args->theme_location || 'sticky_navigation' == $args->theme_location ) {
				if ( Avada()->settings->get( 'main_nav_search_icon' ) ) {

					$items .= '<li class="fusion-custom-menu-item fusion-main-menu-search">';
						$items .= '<a class="fusion-main-menu-icon" tabindex="0" aria-label="Search" alt="Search"></a>';
						$items .= '<div class="fusion-custom-menu-item-contents">';
							$items .= get_search_form( false );
						$items .= '</div>';
					$items .= '</li>';
				}
			}
		}

		return $items;
	}
}

function nmped_related_posts_where( $where ) {
    return $where." AND post_type='post'";
}

if ( ! function_exists( 'nmped_render_footer_social_icons' ) ) {
	/**
	 * Output the footer social icons.
	 *
	 * @return void
	 */
	function nmped_render_footer_social_icons() {
		global $social_icons;

		// Render the social icons.
		if ( Avada()->settings->get( 'icons_footer' ) ) : ?>
			<div class="fusion-social-links-footer">
				<?php

				$footer_social_icon_options = array(
					'position'          => 'footer',
					'icon_colors'       => Avada()->settings->get( 'footer_social_links_icon_color' ),
					'box_colors'        => Avada()->settings->get( 'footer_social_links_box_color' ),
					'icon_boxed'        => Avada()->settings->get( 'footer_social_links_boxed' ),
					'icon_boxed_radius' => Fusion_Sanitize::size( Avada()->settings->get( 'footer_social_links_boxed_radius' ) ),
					'tooltip_placement' => Avada()->settings->get( 'footer_social_links_tooltip_placement' ),
					'linktarget'        => Avada()->settings->get( 'social_icons_new' ),
				);

				echo $social_icons->render_social_icons( $footer_social_icon_options ); // WPCS: XSS ok.
				?>
			</div>
		<?php endif;
	}
}

if ( ! function_exists( 'nmped_render_blog_post_content' ) ) {
	/**
	 * Get the post (excerpt).
	 *
	 * @return void Content is directly echoed.
	 */
	function nmped_render_blog_post_content() {
		if ( is_search() && ! Avada()->settings->get( 'search_excerpt' ) ) {
			return;
		}
		echo nmped_fusion_get_post_content(); // WPCS: XSS ok.
	}
}
add_action( 'avada_blog_post_content', 'nmped_render_blog_post_content', 10 );

if ( ! function_exists( 'nmped_fusion_get_post_content' ) ) {
	/**
	 * Return the post content, either excerpted or in full length.
	 *
	 * @param  string  $page_id        The id of the current page or post.
	 * @param  string  $excerpt        Can be either 'blog' (for main blog page), 'portfolio' (for portfolio page template) or 'yes' (for shortcodes).
	 * @param  integer $excerpt_length Length of the excerpts.
	 * @param  boolean $strip_html     Can be used by shortcodes for a custom strip html setting.
	 * @return string Post content.
	 **/
	function nmped_fusion_get_post_content( $page_id = '', $excerpt = 'blog', $excerpt_length = 55, $strip_html = false ) {

		$content_excerpted = false;

		// Main blog page.
		if ( 'blog' === $excerpt ) {

			// Check if the content should be excerpted.
			if ( 'excerpt' === strtolower( fusion_library()->get_option( 'content_length' ) ) ) {
				$content_excerpted = true;

				// Get the excerpt length.
				$excerpt_length = fusion_library()->get_option( 'excerpt_length_blog' );
			}

			// Check if HTML should be stripped from contant.
			if ( fusion_library()->get_option( 'strip_html_excerpt' ) ) {
				$strip_html = true;
			}
		} elseif ( 'portfolio' === $excerpt ) {
			// Check if the content should be excerpted.
			$portfolio_excerpt_length = fusion_get_portfolio_excerpt_length( $page_id );
			if ( false !== $portfolio_excerpt_length ) {
				$excerpt_length = $portfolio_excerpt_length;
				$content_excerpted = true;
			}

			// Check if HTML should be stripped from contant.
			if ( fusion_library()->get_option( 'portfolio_strip_html_excerpt' ) ) {
				$strip_html = true;
			}
		} elseif ( 'yes' === $excerpt ) {
			$content_excerpted = true;
		}

		// Sermon specific additional content.
		if ( 'wpfc_sermon' === get_post_type( get_the_ID() ) && class_exists( 'Avada' ) ) {
			return Avada()->sermon_manager->get_sermon_content( true );
		}

		// Return excerpted content.
		if ( $content_excerpted ) {
			return nmped_fusion_get_post_content_excerpt( $excerpt_length, $strip_html );
		}

		// Return full content.
		ob_start();
		the_content();
		return ob_get_clean();

	}
}// End if().

if ( ! function_exists( 'nmped_fusion_get_post_content_excerpt' ) ) {
	/**
	 * Do the actual custom excerpting for of post/page content.
	 *
	 * @param  string  $limit      Maximum number of words or chars to be displayed in excerpt.
	 * @param  boolean $strip_html Set to TRUE to strip HTML tags from excerpt.
	 * @return string 				The custom excerpt.
	 **/
	function nmped_fusion_get_post_content_excerpt( $limit = 285, $strip_html ) {
		global $more;

		// Init variables, cast to correct types.
		$content        = '';
		$read_more      = '';
		$custom_excerpt = false;
		$limit          = intval( $limit );
		$strip_html     = filter_var( $strip_html, FILTER_VALIDATE_BOOLEAN );

		// If excerpt length is set to 0, return empty.
		if ( 0 === $limit ) {
			return $content;
		}

		$post = get_post( get_the_ID() );

		// Filter to set the default [...] read more to something arbritary.
		$read_more_text = apply_filters( 'fusion_blog_read_more_excerpt', '&#91;...&#93;' );

		// If read more for excerpts is not disabled.
		if ( fusion_library()->get_option( 'disable_excerpts' ) ) {
			// Check if the read more [...] should link to single post.
			if ( fusion_library()->get_option( 'link_read_more' ) ) {
				$read_more = ' <a href="' . get_permalink( get_the_ID() ) . '" title="Read more" aria-label="Read more">' . $read_more_text . '</a>';
			} else {
				$read_more = ' ' . $read_more_text;
			}
		}

		// Construct the content.
		// Posts having a custom excerpt.
		if ( has_excerpt() ) {
			// WooCommerce products should use short description field, which is a custom excerpt.
			if ( 'product' === $post->post_type ) {
				$content = do_shortcode( $post->post_excerpt );

				// Strip tags, if needed.
				if ( $strip_html ) {
					$content = wp_strip_all_tags( $content, '<p>' );
				}
			} else { // All other posts with custom excerpt.
				$content = '<p>' . do_shortcode( get_the_excerpt() ) . '</p>';
			}
		} else { // All other posts (with and without <!--more--> tag in the contents).
			// HTML tags should be stripped.
			if ( $strip_html ) {
				$content = wp_strip_all_tags( get_the_content( '{{read_more_placeholder}}' ), '<p>' );

				// Strip out all attributes.
				$content = preg_replace( '/<(\w+)[^>]*>/', '<$1>', $content );
				$content = str_replace( '{{read_more_placeholder}}', $read_more, $content );

			} else { // HTML tags remain in excerpt.
				$content = get_the_content( $read_more );
			}

			$pattern = get_shortcode_regex();
			$content = preg_replace_callback( "/$pattern/s", 'fusion_extract_shortcode_contents', $content );

			// <!--more--> tag is used in the post.
			if ( false !== strpos( $post->post_content, '<!--more-->' ) ) {
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );

				if ( $strip_html ) {
					$content = do_shortcode( $content );
				}
			}
		}// End if().

		// Limit the contents to the $limit length.
		if ( ! has_excerpt() || 'product' === $post->post_type ) {
			// Check if the excerpting should be char or word based.
			if ( 'Characters' === fusion_library()->get_option( 'excerpt_base' ) ) {
				$content = mb_substr( $content, 0, $limit );
				$content .= $read_more;
			} else { // Excerpting is word based.
				$content = explode( ' ', $content, $limit + 1 );
				if ( count( $content ) > $limit ) {
					array_pop( $content );
					$content = implode( ' ', $content );
					$content .= $read_more;

				} else {
					$content = implode( ' ', $content );
				}
			}

			if ( $strip_html ) {
				$content = '<p>' . $content . '</p>';
			} else {
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );
			}

			$content = do_shortcode( $content );
		}

		return $content;
	}
}// End if().

?>