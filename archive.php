<?php

/*
Template Name: Archives
*/

get_header();

the_post();

?>

<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<header class="single-basic-intro">
					<h1><?php the_archive_title(); ?></h1>
				</header>
				
				<?php 
				$posts = new WP_Query(array('posts_per_page' => -1));
				if ($posts->have_posts()) {
					$date_format = 'l, F j';
					$prev_day = '';
					?>
					
					<section>
					
					<?php
					while ($posts->have_posts()) {
						
						$posts->the_post();
						if (get_the_date( $date_format ) != $prev_day) {
							if ($prev_day !== '') { 
								?>
								
								</section></section><section>
								
								<?php
							}
							$prev_day = get_the_date( $date_format );
							?>
							
							<header class="archive-date">
								<breakup>
									<blank-space></blank-space>
									<h2><?php echo $prev_day; ?></h2>
									<blank-space></blank-space>
								</breakup>
							</header>
							
							<section class="archive-teasers">
							
							<?php
						}

						$url = get_the_permalink( $post->ID );
						?>
						
						<article class="archive-teaser">
							<?php 
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'quarter-width-small' );
							$image = $image[0];
							?>
							<div class="teaser-img-container"><a class="teaser-img" href="<?php echo $url; ?>" 
								style="background-image: url(<?php echo $image; ?>);"><?php voa_language_service_tag( $post->ID, true ); ?></a></div>
							<h2><a href="<?php echo $url; ?>"><?php the_title(); ?></a></h2>
						</article>
						
						<?php
					}
					?>
					
					</section></section>
					
					<?php
				}
				?>
				
			</content>
			
			
			
			<sidebar>
				<sidebar-inner>
					
					<?php dynamic_sidebar( 'sidebar_archive_page' ); ?>

				</sidebar-inner>
			</sidebar>
		</article>
	</row>
</rows>

<?php get_footer(); ?>