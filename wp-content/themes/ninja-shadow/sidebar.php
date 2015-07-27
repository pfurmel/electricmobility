<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package ninja-shadow
 */

if ( ! is_active_sidebar( 'sidebar-primary' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area" role="complementary">
	<?php dynamic_sidebar( 'sidebar-primary' ); ?>
</div><!-- #secondary -->
