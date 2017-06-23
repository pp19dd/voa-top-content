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
				
				<header class="single-basic-intro">
					<h1><?php the_title(); ?></h1>
				</header>
				
				<section class="content-part article-body">
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