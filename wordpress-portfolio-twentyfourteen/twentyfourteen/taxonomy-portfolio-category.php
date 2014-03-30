<?php
get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) { ?>

			<header class="archive-header">
				<h1 class="archive-title">
					<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>
                    <?php echo $term->name; ?>
				</h1>
			</header><!-- .archive-header -->
			
		  <div id="portfolio-items">

			<?php
			  while( have_posts() ) {
				  the_post();
				  $id = get_the_ID();
				  $title = get_the_title();
				  $excerpt = strip_tags( get_the_excerpt() );
				  $thumb_id = get_post_thumbnail_id( $id );
				  $image_full = wp_get_attachment_image_src( $thumb_id, 'full' );
				  $image_thumb = wp_get_attachment_image_src( $thumb_id, 'large' );
				  $permalink = get_permalink();
				  
			?>
				<div class="portfolio-item">
					<a href="<?php echo $image_full[0];?>" class="portfolio-lightbox" title="<?php echo $title; ?>">
						<img src="<?php echo $image_thumb[0];?>" alt="" />
					</a>
					<h2 class="portfolio-title"><?php echo $title; ?></h2>
					<p class="portfolio-excerpt"><?php echo $excerpt; ?></p>
					<p class="portfolio-more"><a href="<?php echo $permalink; ?>"><?php _e( 'Vedi progetto', 'tf-portfolio' ); ?></a></p>
					
				
				</div>
				  
		  <?php
			  }   
			
			?>
			
		  </div>
			
			
			<?php } ?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
