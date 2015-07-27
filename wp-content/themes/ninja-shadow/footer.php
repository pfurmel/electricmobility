<?php
/**
 * The template for displaying the footer.
 */
?>

		<!-- Footer -->
		<div class="footer">
			<div class="footer-container">
				<div class="container">
					<div class="top-footer">
						<div class="row">
							<div class="one-third column">

								<div class="widget-area" role="complementary">
									<?php dynamic_sidebar( 'footer-01' ); ?>
								</div>

							</div>
							<div class="one-third column">
								<div class="widget-area" role="complementary">
									<?php dynamic_sidebar( 'footer-02' ); ?>
								</div>
							</div>
							<div class="one-third column">
								<div class="widget-area" role="complementary">
									<?php dynamic_sidebar( 'footer-02' ); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="middle-footer">
						<nav id="footer-navigation" class="footer-navigation" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'footer' ) ); ?>
						</nav>
					</div>
					<div class="bottom-footer">
						<div class="site-info">
							<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'ninja-shadow' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'ninja-shadow' ), 'WordPress' ); ?></a>
							<span class="sep"> | </span>
							<?php printf( __( 'Theme: %1$s by %2$s.', 'ninja-shadow' ), 'ninja-shadow', '<a href="http://alisiddique.com/" rel="designer">Ali Siddique</a>' ); ?>
						</div><!-- .site-info -->
					</div>
				</div>
			</div>
		</div><!-- /footer -->
		
	</div><!-- /wrapper-container -->
</div><!-- /wrapper -->
<!-- End Document
---------------------------------------------- -->

<?php wp_footer(); ?>

</body>
</html>
