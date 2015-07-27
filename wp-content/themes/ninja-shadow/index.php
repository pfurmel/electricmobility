<?php
/**
 * The main template file.
 */

get_header(); ?>

	<div id="primary" class="">
		<main id="main" class="site-main" role="main">
			<?php if ( have_posts() ) : ?>
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php
						get_template_part( 'content', get_post_format() );
					?>
				<?php endwhile; ?>
				
				<div class="container">
					<?php the_posts_navigation(); ?>
				</div>

				<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
				
			<?php endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->
	
<?php get_footer(); ?>
