<?php

/*
Template Name: Archives
*/

get_header();

the_post();

// get author data for this page
$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

?>

<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<section class="content-part">
					<?php get_template_part( 'partials/author-bio' ); ?>
				</section>
				
				<section class="content-part article-body">
					
					<?php if ( have_posts() ) {	?>
					
					<h2>Recent posts by <?php echo $curauth->display_name; ?>:</h2>
					
					<?php
						while ( have_posts() ) : the_post();
							get_template_part( 'partials/excerpt', get_post_format() );
						endwhile; // end of The Loop
						
						get_template_part( 'partials/pagination' );
						
					} else {
					?>
					<p><?php _e('No posts by this author.'); ?></p>
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
