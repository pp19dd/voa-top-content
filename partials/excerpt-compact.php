
<article class="archive-teaser">
	<?php 
	$image = voa_wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'quarter-width-short' );
	$image = $image[0];
	?>
	<div class="teaser-img-container"><a class="teaser-img" href="<?php echo $url; ?>" style="background-image: url(<?php echo $image; ?>);"><?php //voa_language_service_tag( $post->ID, true ); ?></a></div>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
</article>

<?php /*
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
				<time class="excerpt-date" datetime="<?php the_time( 'c' ); ?>"><?php the_time( 'F j, Y' ); ?></time>
			</p>
			<?php the_excerpt(); ?>
		</div>
	</a>
</article>
*/ ?>
