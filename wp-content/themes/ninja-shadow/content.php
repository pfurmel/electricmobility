<?php
/**
 * @package ninja-shadow
 */
?>
<!-- Content -->
<div class="content">
	<div class="content-container">
		<div class="container">
			
			<div class="article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="tumb entry-tumb">
					<?php 
					if ( has_post_thumbnail() ) {
						$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
						echo '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute( 'echo=0' ) . '" >';
						echo get_the_post_thumbnail( $post->ID, 'post-thumb-1170' ); 
						echo '</a>';
					}
					?>
					<?php //the_post_thumbnail( 'post-thumb-1170' ); ?>
				</div>
				<header class="entry-header">
					<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

					<?php if ( 'post' == get_post_type() ) : ?>
					
					<div class="entry-meta">
						<?php ninja_shadow_posted_on(); ?>
					</div><!-- .entry-meta -->
					
					<?php endif; ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php
						/* translators: %s: Name of current post */
						the_content( sprintf(
							__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'ninja-shadow' ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						) );
					?>

					<?php
						wp_link_pages( array(
							'before' => '<div class="page-links">' . __( 'Pages:', 'ninja-shadow' ),
							'after'  => '</div>',
						) );
					?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<?php ninja_shadow_entry_footer(); ?>
				</footer><!-- .entry-footer -->
				
				
			</div>
		</div>
	</div>
</div><!-- /content -->
