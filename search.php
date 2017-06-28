<?php get_header(); ?>

<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<header class="single-basic-intro">
					<h1>Search Results for: "<?php echo get_search_query(); ?>"</h1>
				</header>
				
				<section class="content-part article-body">
					
					<?php
					if ( have_posts() ) {	
						while ( have_posts() ) : the_post();
							get_template_part( 'partials/excerpt', get_post_format() );
						endwhile; // end of The Loop
						
						// Previous/next page navigation.
						the_posts_pagination( array(
							//'prev_text'          => __( 'Previous page', 'twentysixteen' ),
							//'next_text'          => __( 'Next page', 'twentysixteen' ),
							//'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
						) );

						?>
						<div class="search-form-container"><?php get_search_form('true'); ?></div>
						<?php
						
					} else {
					?>
					
					<p>No results found for your search. Search again?</p>
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
