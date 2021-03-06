<?php
/**
 * The theme's index.php file.
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
    <?php
        $page_id     = avada_get_current_page_id();
        $page_title = get_post_meta( $page_id , 'pyre_page_title', true );

        //Showing Title with Default Title bar setting for posts page
        if('default' == $page_title){
            $page_title_bar_contents = avada_get_page_title_bar_contents( $page_id  );
            avada_page_title_bar( $page_title_bar_contents[0], $page_title_bar_contents[1], $page_title_bar_contents[2] );
        }
        else{
            avada_current_page_title_bar($page_id);
        }

    ?>

	<?php get_template_part( 'templates/blog', 'layout' ); ?>
	</section>
	<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
