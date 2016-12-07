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
			<div class="undermedia"></div>
		</section>
	</row>
</antirows>
<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
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
							<!-- Convert the image to a background image for better responsive fit on height? -->
							<?php the_post_thumbnail( $post->ID, 'medium' ); ?>
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
					
					[NEED ARCHIVE PAGE SIDEBAR]
					<?php //dynamic_sidebar( 'sidebar_article_right' ); ?>

				</sidebar-inner>
			</sidebar>
		</article>
	</row>
</rows>

<?php get_footer(); ?>