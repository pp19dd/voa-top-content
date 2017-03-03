<?php get_header(); ?>

<antirows>
	<row class="rows_1">
		<section class="article-intro">
			<header class="article-title">
				<div class="article-title-wrap">
					<h1 class="article-title-text"><?php the_archive_title(); ?></h1>
				</div>
			</header>
		</section>
	</row>
</antirows>

<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<section class="archive-teasers">

					<?php while ( have_posts() ) : the_post(); ?>
						
						<article class="archive-teaser">
							<?php 
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'quarter-width-small' );
							$image = $image[0];
							?>
							<div class="teaser-img-container"><a class="teaser-img" href="<?php echo $url; ?>" 
								style="background-image: url(<?php echo $image; ?>);"><?php voa_language_service_tag( $post->ID, true ); ?></a></div>
							<h2><a href="<?php echo $url; ?>"><?php the_title(); ?></a></h2>
						</article>
						
					<?php endwhile; // end of The Loop ?>
					
				</section>

			</content>
			
			
			
			<sidebar>
				<sidebar-inner>
					
					<?php dynamic_sidebar( 'sidebar_archive_page' ); ?>

				</sidebar-inner>
			</sidebar>
		</article>
	</row>
</rows>

<?php get_footer();
