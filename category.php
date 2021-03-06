<?php get_header(); ?>

<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<header class="single-basic-intro">
					<h1><?php the_archive_title(); ?></h1>
				</header>

				<section class="content-part article-body">
					
					<?php
					if ( have_posts() ) {	
						while ( have_posts() ) : the_post();
							get_template_part( 'partials/excerpt', get_post_format() );
						endwhile; // end of The Loop
						
						get_template_part( 'partials/pagination' );
						
					} else {
					?>
					
					<p><?php _e( 'No results found for this category.', 'voa-top-content' ); ?></p>
					
					<div class="search-form-container"><?php get_search_form('true'); ?></div>
						
					<?php } ?>
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
