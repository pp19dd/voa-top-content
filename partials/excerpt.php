<article class="article-excerpt">
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'quarter-width-short' ); } ?>
		<h2><?php the_title(); ?></h2>
		<?php the_excerpt(); ?>
	</a>
</article>
