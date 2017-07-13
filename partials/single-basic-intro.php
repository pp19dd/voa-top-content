<?php
$caption = trim(get_post(get_post_thumbnail_id())->post_content);
$subhed  = trim( get_post_meta( $post->ID, "_voa_post_subhed", true ));
?>

<header class="single-basic-intro">
	
	<h1><?php the_title(); ?></h1>
	
	<?php if( $subhed != '' ) { ?>
	<h2><?php echo $subhed; ?></h2>
	<?php } ?>
	
	<?php if ( has_post_thumbnail() ) { ?>
	<div class="featured-img">
		<?php voa_generate_missing_image_size( get_post_thumbnail_id(), 'full-width' ); ?>
		<?php the_post_thumbnail('full-width'); ?>
		<?php if ($caption != '') { ?>
		<p><?php echo $caption; ?></p>
		<?php } ?>
	</div>
	<?php } ?>
	
</header>
