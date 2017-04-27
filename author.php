<?php

/*
Template Name: Archives
*/

get_header();

the_post();

// get author data for this page
$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

?>
<antirows>
	<row class="rows_1">
		<section class="article-intro">
			<header class="article-title">
				<div class="article-title-wrap">
					<h1 class="article-title-text"><?php the_archive_title(); ?></h1>
				</div>
			</header>
		</section>
	</row>
</antirows>
<rows>
	<row class="rows_1">
		
		<article>
		
			<content>
				
				<section class="content-part">
					<h1><?php the_archive_title; ?></h1>
				</section>

				<section class="content-part">
					<h2>Posts by <?php echo $curauth->nickname; ?>:</h2>
				</section>
				
				<?php if ( have_posts() ) : ?>
				<section class="archive-teasers">

					<?php while ( have_posts() ) : the_post(); ?>
						
						<article class="archive-teaser">
							<?php 
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'quarter-width-small' );
							$image = $image[0];
							?>
							<div class="teaser-img-container"><a class="teaser-img" href="<?php the_permalink(); ?>" 
								style="background-image: url(<?php echo $image; ?>);"><?php //the_time('F j, Y'); ?></a></div>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						</article>
						
					<?php endwhile; // end of The Loop ?>
					
				</section>
				
				<section class="content-part">
					<div class="nav-previous alignleft"><?php next_posts_link( 'Older posts' ); ?></div>
					<div class="nav-next alignright"><?php previous_posts_link( 'Newer posts' ); ?></div>
				</section>
				
				<?php else: ?>
				<section class="content-part no-results">
					<p><?php _e('No posts by this author.'); ?></p>
				</section>
				<?php endif; ?>
				
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
