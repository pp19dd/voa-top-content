<?php

/*
Template Name: Archives
*/

get_header();

the_post();

?>
<antirows>
	<row class="rows_1">
		<section class="article-intro">
			<header class="article-title">
				<div class="article-title-wrap">
					<h1 class="article-title-text"><?php the_title(); ?></h1>
				</div>
			</header>
		</section>
	</row>
</antirows>
<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<div class="page-content"><?php the_content(); ?></div>
				
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
						?>
						
						<article class="archive-teaser">
							<div class="teaser-img-container"><a class="teaser-img" href="<?php the_permalink(); ?>" style="background-image: url(<?php echo voa_top_content_get_image_url( get_post_thumbnail_id( $post->ID ), 1, "half-width" ); ?>);"><span class="language-service"><span class="language-service-inner">VOA English</span></span></a></div>
							<!-- <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $post->ID, 'medium' ); ?></a> -->
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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