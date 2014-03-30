<?php
get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
			?>
				<div id="portfolio-items">
					<div class="portfolio-item single">
						<?php
							$id = get_the_ID();
							$thumb_id = get_post_thumbnail_id( $id );
							$image_full = wp_get_attachment_image_src( $thumb_id, 'full' );
							$image_thumb = wp_get_attachment_image_src( $thumb_id, 'large' );
							$title = get_the_title();
							$excerpt = strip_tags( get_the_content() );

						?>
						<a href="<?php echo $image_full[0];?>" class="portfolio-lightbox" title="<?php echo $title; ?>">
							<img src="<?php echo $image_thumb[0];?>" alt="" />
						</a>
						<h2 class="portfolio-title"><?php echo $title; ?></h2>
						<div class="portfolio-excerpt"><?php echo $excerpt; ?></div>
					</div>
				
				</div>
			
			<?php

				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
