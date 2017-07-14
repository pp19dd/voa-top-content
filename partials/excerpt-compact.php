
<article class="archive-teaser">
	<?php $image = voa_wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'quarter-width-short' ); ?>
	<div class="teaser-img-container"><a class="teaser-img" href="<?php the_permalink(); ?>" style="background-image: url(<?php echo $image[0]; ?>);"><?php //voa_language_service_tag( $post->ID, true ); ?></a></div>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
</article>
