<?php

/* share buttons for articles */

$share_link  = urlencode( get_permalink() );
$share_title = urlencode( get_the_title() );
$share_image = urlencode( voa_top_content_get_image_url( get_post_thumbnail_id( $post->ID ), "full-width") );

$twitter_account = 'VOANews';

/* tweet text length must be 60 chars or LESS after shortened url and via @VOANews */
$twitter_text = urlencode( text_shortenerer( get_the_title(), 60, '...' ) );

?>

<section class="content-part article-share">
	<div class="article-share-inner">
		<header><div>Share</div></header>
		<ul class="social-share-buttons">
			
			<li class="share-facebook"><a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
			
			<li class="share-googleplus"><a title="Share on Google+" href="https://plus.google.com/share?url=<?php echo $share_link; ?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
			
			<li class="share-linkedin"><a href=""><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
			
			<li class="share-pinterest"><a title="Pin this on Pinterest" href="https://www.pinterest.com/pin/create/button/?url=<?php echo $share_link; ?>&media=<?php echo $share_image; ?>&description=<?php echo $share_title; ?>"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
			
			<li class="share-reddit"><a title="Submit to reddit" href="http://www.reddit.com/submit?url=<?php echo $share_link; ?>&title=<?php echo $share_title; ?>"><i class="fa fa-reddit-alien" aria-hidden="true"></i></a></li>
			
			<li class="share-twitter"><a href="https://twitter.com/intent/tweet?url=<?php echo $share_link; ?>&text=<?php echo $twitter_text; ?>&via=<?php echo $twitter_account; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
			
		</ul>
	</div>
</section>
