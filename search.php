<?php get_header(); ?>

<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<section class="content-part">
					
					<h1>Search Results</h1>
					
					<?php
					if ( have_posts() ) {	
						while ( have_posts() ) : the_post();
							get_template_part( 'partials/excerpt', get_post_format() );
						endwhile; // end of The Loop
						
					} else {
					?>
					
					<p>No results found for your search. Search again?</p>
					<div class="search-form-container"><?php get_search_form('true'); ?></div>
						
					<?php } ?>
				</section>
				
			</content>
			
			
			
			<sidebar>
				<sidebar-inner>
					
					<?php dynamic_sidebar( 'sidebar_basic_page' ); ?>

				</sidebar-inner>
			</sidebar>
		</article>
	</row>
</rows>

<?php get_footer();
