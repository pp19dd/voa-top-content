<article class="column column_<?php echo $k + 1 ?>">
    <inner>
        <part class="one">
        	<div class="part-one-img">
        		<?php voa_language_service_tag( $post['id'], true ); ?>
	        	<a href="<?php echo $post["permalink"] ?>"><img src="<?php echo $image ?>" /></a>
                
                <?php if ( get_post_meta( $post['id'], "_voa_show_video_icon", true ) === "yes") { ?>
                <div class="video-icon"><div class="video-icon-inner"><i class="fa fa-video-camera" aria-hidden="true"></i></div></div>
                <?php } ?>
	        </div>
        </part>
        <part class="two">
            <headline><a href="<?php echo $post["permalink"] ?>"><?php echo $post["title"] ?></a></headline>
            <?php //get_template_part( "partials/byline" ); ?>
            <excerpt><?php echo /*trim_words(*/$post["excerpt"]/*, 40)*/ ?></excerpt>
        </part>
    </inner>
</article>
