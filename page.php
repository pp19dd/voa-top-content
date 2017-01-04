<?php

/*
Template Name: Standard Page Template
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