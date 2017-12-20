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

?>