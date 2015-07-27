<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package store
 */
?>

	</div><!-- #content -->

	<?php get_sidebar('footer'); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info container">
			<?php printf(_('Electric Mobility, Camross, Foulksmills Co. Wexford, tel 086 035 7779. ')); ?>
			<?php printf( __( 'Website Designed by %1$s.', 'store' ), '<a href="'.esc_url("http://mvcgroup.ie").'" rel="designer">MVCgroup</a>' ); ?>
			 
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	
</div><!-- #page -->


<?php wp_footer(); ?>

</body>
</html>
