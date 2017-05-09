<?php

/*
Template Name: Standard Page Template
*/

get_header();

the_post();

?>

<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<?php //if (!$hero) { get_template_part( 'partials/single-basic-intro' ); } ?>
				
				<section class="content-part">
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
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

<?php get_footer(); ?>