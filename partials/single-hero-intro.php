<?php
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$image = voa_top_content_get_image_url($thumbnail_id, "full-width");
$caption = trim(get_post($thumbnail_id)->post_content);
?>

<antirows>
	<row class="rows_1">
		<section class="article-intro">
			<header class="article-title">
				<div class="article-title-wrap">
					<h1 class="article-title-text"><?php the_title(); ?></h1>
				</div>
			</header>
			<div class="undermedia"><img src="<?php echo $image; ?>" /></div>
			<?php if ($caption != '') { ?>
			<div class="undermedia-caption"><p><?php echo $caption; ?></p></div>
			<?php } ?>
		</section>
	</row>
</antirows>