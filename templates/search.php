<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<div id="nmped-full-search" class="full-search" style="background:url('<?php echo get_stylesheet_directory_uri().'/assets/images/search-bg.jpg'; ?>')";>
    <div id="nmped-search">
        <h2 class="center uppercase">What Can We Help You Find?</h2>
        <?php get_search_form(); ?>
    </div>
</div>