<?php
/**
 * The template for displaying all single posts.
 *
 * @package ninja-shadow
 */

get_header(); ?>
<div class="content">
	<div class="content-container">
		<div class="container">
			<div id="primary" class="content-area article">
				<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'single' ); ?>

					<?php the_post_navigation(); ?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>

				<?php endwhile; // end of the loop. ?>

				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
	</div>
</div><!-- /content -->
<?php get_footer(); ?>
