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
				
				<section class="content-part article-body">
					
					<?php 
					$cat_args = array(
						'title_li'   => '',
						'number'     => 10,
						'orderby'    => 'count',
						'order'      => 'DESC',
						'hide_empty' => true
					);
					
					if ( VOA_EDITORS_PICKS ) { 
						// only display sub-categories (languages) from the "VOA Language Services" category
						$voa_lang_serv_cat_meta = get_term_by( 'slug', 'voa-language-services', 'category', 'ARRAY_A', 'raw' );
						$cat_args['child_of'] 	= $voa_lang_serv_cat_meta['term_id'];
					}
					
					$categories = get_categories( $cat_args );
					
					foreach ( $categories as $cat ) {
						echo '<h2><a href="'.esc_url( get_category_link( $cat->cat_ID ) ).'" title="'.esc_attr( $cat->cat_name ).'">'.$cat->cat_name.'</a></h2>';
						
						$posts = new WP_Query( array( 
							'cat' => $cat->cat_ID,
							'posts_per_page' => 3
						));
						
						if ( $posts->have_posts() ) {
							echo '<section class="archive-teasers">';
							while ( $posts->have_posts() ) {
								$posts->the_post();
								get_template_part( 'partials/excerpt-compact' );
							}
							echo '</section>';
						}
					}
					?>
					
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

<?php get_footer(); ?>