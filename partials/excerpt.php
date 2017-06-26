
<article class="article-excerpt">
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<div class="excerpt-img"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'quarter-width-short' ); } ?></div>
		<div class="excerpt-text">
			<h2><?php the_title(); ?></h2>
			<p class="excerpt-meta">
				<time class="excerpt-date" datetime="<?php the_time( 'c' ); ?>"><?php the_time( 'F j, Y' ); ?></time>
				<?php /* TODO <span class="excerpt-author"><?php the_author(); ?></span> */ ?>
			</p>
			<?php the_excerpt(); ?>
		</div>
	</a>
</article>
