<?php
if ( $post['thumbnail_id'] != '' ) {
	$image = voa_top_content_get_image_url_2($post["thumbnail_id"], $post['siz'], $post['cls'], get_voa_is_row_tall($posts) );
	set_query_var( "image", $image );
?>

<style type="text/css">
.sc-<?php echo $post['id']; ?> > a       { background-image: url(<?php echo $image; ?>); }
.sc-<?php echo $post['id']; ?> > a:hover { background-image: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(<?php echo $image; ?>); }
</style>

<?php } else {
	$post['cls'] = 'card-noimg';
} ?>
<?php if ($_GET['postclsoverride']) { $post['cls'] = trim(stripcslashes(strip_tags($_GET['postclsoverride']))); } ?>
<article class="story-card card-<?php echo $k + 1 ?> sc-<?php echo $post['id']; ?> <?php echo $post['cls'] ?> <?php echo $post['siz'] ?>">
	<!-- <?php //var_dump($post); ?> -->
	<a href="<?php echo $post["permalink"] ?>">
		<section class="text">
			<h2 class="clearfix"><?php echo $post["title"] ?></h2>
			<p class="teaser clearfix"><?php echo $post["excerpt"] ?></p>
			<p class="continue">Continue reading</p>
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
