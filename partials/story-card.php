
<style type="text/css">
.sc-<?php echo $post['id']; ?> > a       { background-image: url(<?php echo $image; ?>); }
.sc-<?php echo $post['id']; ?> > a:hover { background-image: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo $image; ?>); }
</style>

<article class="story-card card-<?php echo $k + 1 ?> sc-<?php echo $post['id']; ?>">
	
	<a href="<?php echo $post["permalink"] ?>">
		<section class="text">
			<h2 class="clearfix"><?php echo $post["title"] ?></h2>
			<p class="teaser clearfix"><?php echo $post["excerpt"] ?></p>
			<div class="continue">Continue reading</div>
		</section>
		
		<?php
		$lang_service = trim( voa_language_service_tag( $post['id'], false ));
		if ( $lang_service != '' ) {
			echo '<div class="category">'.$lang_service.'</div>';
		}
		?>
		
		<?php if ( get_post_meta( $post['id'], "_voa_show_video_icon", true ) === "yes") { ?>
		<div class="video-icon"><i class="fa fa-video-camera" aria-hidden="true"></i></div>
		<?php } ?>
	</a>
</article>
