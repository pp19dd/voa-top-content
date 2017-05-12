<?php
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$image        = voa_top_content_get_image_url($thumbnail_id, "hero-intro");
$subhed       = trim( get_post_meta( $post->ID, "_voa_post_subhed", true ));
$caption      = trim(get_post($thumbnail_id)->post_content);
$hero_style   = trim( get_post_meta( $post->ID, "_voa_hero_text_style", true ));
?>

<antirows>
	<row class="hero-row">
		<section class="hero-intro <?php echo ($hero_style != '' ? $hero_style : 'hero-light-on-dark'); ?>" style="background-image: url(<?php echo $image; ?>);">
			
			<header class="hero-title-container">
				<div class="hero-title">
					<h1><?php the_title(); ?></h1>
					<?php if( $subhed != '' ) { ?>
					<h2><?php echo $subhed; ?></h2>
					<?php } ?>
				</div>
			</header>
			
			<?php if ($caption != '') { ?>
			<div class="hero-undermedia-caption">
				<p><?php echo $caption; ?></p>
			</div>
			<?php } ?>
			
		</section>
	</row>
</antirows>
