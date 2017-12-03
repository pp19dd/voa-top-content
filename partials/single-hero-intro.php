<?php
$thumbnail_id = get_post_thumbnail_id( $post->ID );

$subhed       = trim( get_post_meta( $post->ID, "_voa_post_subhed", true ));
//$caption      = trim( get_post( $thumbnail_id )->post_content );
$caption      = trim( get_the_post_thumbnail_caption( $post->ID ));
$hero_style   = trim( get_post_meta( $post->ID, "_voa_hero_text_style", true ));
?>

<style type="text/css">

		.hero-intro { background-image: url( <?php echo voa_wp_get_attachment_image_src( $thumbnail_id, 'half-width-square' )[0]; ?> ); }
	
	@media (min-width: 450px) {
		.hero-intro { background-image: url( <?php echo voa_wp_get_attachment_image_src( $thumbnail_id, 'half-width-mid-2x' )[0]; ?> ); }
	}
	
	@media (min-width: 750px) {
		.hero-intro { background-image: url( <?php echo voa_wp_get_attachment_image_src( $thumbnail_id, 'full-width' )[0]; ?> ); }
	}
	
	@media (min-width: 960px) {
		.hero-intro { background-image: url( <?php echo voa_wp_get_attachment_image_src( $thumbnail_id, 'full-width-2x' )[0]; ?> ); }
	}
</style>

<antirows>
	<row class="hero-row">
		<section class="hero-intro <?php echo ($hero_style != '' ? $hero_style : 'hero-light-on-dark'); ?>">
			
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
