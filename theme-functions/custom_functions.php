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

			// Display thumbnail on the top side navigation
			if (has_post_thumbnail($post_id)) {
				$post_thumbnail = get_the_post_thumbnail($post_id);
				echo $post_thumbnail;
			} else {
				echo '<img src="'.get_stylesheet_directory_uri().'/assets/images/nmped.png" class="default-post-thumbnail size-post-thumbnail" alt="'.get_the_title($post_id).'" />';
			}
			
			$parent = false;
			$post_ancestors = get_ancestors( $post_id, 'page' );
			$post_parent    = reset( $post_ancestors );
			$post_children = wp_list_pages( 'title_li=&child_of=' . $ancestor . '&echo=0' );
			
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

?>