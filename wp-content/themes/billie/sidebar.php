<?php
/**
 * The sidebar containing the main widget area for posts.
 *
 * @package billie
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

?>		
<div id="secondary" class="widget-area" role="complementary">
	<h2 class="screen-reader-text"><?php _e( 'Sidebar', 'billie' ); ?></h2>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->
