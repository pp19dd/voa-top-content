<?php
if ( $post['thumbnail_id'] == '' ) {
	$post['cls'] = 'card-noimg';
	
} elseif ( $post['cls'] == '' ) {
	$post['cls'] = 'card-txt';
	
} elseif ( in_array($_GET['postclsoverride'], array('card-noimg', 'card-txt', 'card-img')) ) {
	$post['cls'] = $_GET['postclsoverride'];	
}

if ( $post['thumbnail_id'] != '' ) { 
	// used for all .card-img and .card-txt hovers
	$bg_hover_gradient = 'linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) )';
	
	// WARNING: re-enable the next line to regenerate (and destroy crops) for ALL images on this page
	// wp_generate_attachment_metadata( $post['thumbnail_id'], get_attached_file( $post['thumbnail_id'] ));
	
	$breakpoint_img_mobile = voa_wp_get_attachment_image_src( $post['thumbnail_id'], ($post['cls'] == 'card-img' ? 'half-width-square' : 'quarter-width-short'))[0];
	$breakpoint_img_larger = voa_top_content_get_image_url_2( $post['thumbnail_id'], $post['siz'], $post['cls'], $row_meta['count'], ( $_GET['debugimages'] == 'yes' ? true : false ) );
?>
<style type="text/css">
	/* default to mobile layout */
	.sc-<?php echo $post['id']; ?> > a       > .bg-container { background-image: url(<?php echo $breakpoint_img_mobile; ?>); }
    .sc-<?php echo $post['id']; ?> > a:hover > .bg-container { background-image: <?php echo $bg_hover_gradient; ?>, url(<?php echo $breakpoint_img_mobile; ?>); }
    
    @media (min-width: 450px) {
    	.sc-<?php echo $post['id']; ?> > a       > .bg-container { background-image: url(<?php echo $breakpoint_img_larger; ?>); }
    	.sc-<?php echo $post['id']; ?> > a:hover > .bg-container { background-image: <?php echo $bg_hover_gradient; ?>, url(<?php echo $breakpoint_img_larger; ?>); }
    }
</style>
<?php }

?><article class="story-card card-<?php echo $k + 1 ?> sc-<?php echo $post['id']; ?> <?php echo $post['cls'] ?> <?php echo $post['siz'] ?>">
	<a href="<?php echo $post["permalink"] ?>">
		<div class="bg-container"></div>
		
		<section class="text">
			<h2 class="clearfix"><?php echo $post["title"] ?></h2>
			<p class="clearfix teaser"><?php echo $post["excerpt"] ?></p>
			<p class="continue">Continue reading</p>
		</section>

		<?php
		$lang_service = trim( voa_language_service_tag( $post['id'], false ));
		if ( $lang_service != '' ) {
			echo '<div class="card-label-category">'.$lang_service.'</div>';
		}
		?>

		<?php if ( get_post_meta( $post['id'], "_voa_show_video_icon", true ) === "yes") { ?>
		<div class="video-icon"><i class="fa fa-video-camera" aria-hidden="true"></i></div>
		<?php } ?>
	</a>
</article>
