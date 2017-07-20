
<article class="article-excerpt">
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<div class="excerpt-img"><?php 
			if ( has_post_thumbnail() ) {
				voa_generate_missing_image_size( get_post_thumbnail_id(), 'quarter-width-short' );
				the_post_thumbnail( 'quarter-width-short' );
			} 
		?></div>
		<div class="excerpt-text">
			<h2><?php the_title(); ?></h2>
			<p class="excerpt-meta">
				<?php get_template_part( 'partials/article-date' ); ?>
			</p>
			<?php the_excerpt(); ?>
		</div>
	</a>
</article>
