<?php

/* share buttons for articles */

$share_link  = urlencode( get_permalink() );
$share_title = urlencode( get_the_title() );
$share_image = urlencode( voa_top_content_get_image_url( get_post_thumbnail_id( $post->ID ), "full-width") );
$share_description = urlencode( get_the_excerpt() );

$fb_app_id = '490219017808002';

$linkedin_source = urlencode( 'VOA News' );

$twitter_account = esc_attr_x( 'VOANews', 'Service Twitter Username', 'voa-top-content' );

/* tweet text length must be 103 chars or LESS after shortened url and via @VOANews */
$twitter_text = urlencode( text_shortenerer( get_the_title(), 100, '...' ) );

?>

<section class="content-part article-share">
	<div class="article-share-inner">
		<header><div><?php _ex( 'Share', 'verb', 'voa-top-content' ); ?></div></header>
		<ul class="social-share-buttons">
			
			<li class="share-facebook"><a title="<?php esc_attr_e( 'Share on Facebook', 'voa-top-content' ); ?>" href="//www.facebook.com/dialog/share_open_graph?app_id=<?php echo $fb_app_id; ?>&display=popup&action_type=og.likes&action_properties=<?php echo urlencode( json_encode( array( "object" => $share_link ))); ?>&href=<?php echo $share_link; ?>&redirect_uri=<?php echo $share_link; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>			
			
			<li class="share-googleplus"><a title="<?php esc_attr_e( 'Share on Google+', 'voa-top-content' ); ?>" href="//plus.google.com/share?url=<?php echo $share_link; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
			
			<li class="share-linkedin"><a title="<?php esc_attr_e( 'Share on LinkedIn', 'voa-top-content' ); ?>" href="//www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_link; ?>&title=<?php echo $share_title; ?>&summary=<?php echo $share_description; ?>&source=<?php echo $linkedin_source; ?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
			
			<?php /* <li class="share-pinterest"><a title="<?php esc_attr_e( 'Pin this on Pinterest', 'voa-top-content' ); ?>" href="//www.pinterest.com/pin/create/button/?url=<?php echo $share_link; ?>&media=<?php echo $share_image; ?>&description=<?php echo $share_title; ?>"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li> */ ?>
			
			<li class="share-reddit"><a title="<?php esc_attr_e( 'Submit to reddit', 'voa-top-content' ); ?>" href="//www.reddit.com/submit?url=<?php echo $share_link; ?>&title=<?php echo $share_title; ?>"><i class="fa fa-reddit-alien" aria-hidden="true"></i></a></li>
			
			<li class="share-twitter"><a title="<?php esc_attr_e( 'Share on Twitter', 'voa-top-content' ); ?>" href="//twitter.com/intent/tweet?url=<?php echo $share_link; ?>&text=<?php echo $twitter_text; ?>&via=<?php echo $twitter_account; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
			
		</ul>
	</div>
</section>
