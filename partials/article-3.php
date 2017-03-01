<article class="column column_<?php echo $k + 1 ?>">
    <inner>
        <anchor>
            <headline><span><a href="<?php echo $post["permalink"] ?>"><?php echo $post["title"] ?></a></span></headline>
            <?php //get_template_part( "partials/byline" ); ?>
            <excerpt><span><span><?php echo $post["excerpt"] ?></span></span></excerpt>
            <continue><span><a href="<?php echo $post["permalink"] ?>">Continue reading</a></span></continue>
        </anchor>
        
        <?php if ( get_post_meta( $post['id'], "_voa_show_video_icon", true ) === "yes") { ?>
        <div class="video-icon"><div class="video-icon-inner"><i class="fa fa-video-camera" aria-hidden="true"></i></div></div>
        <?php } ?>
        
        <?php voa_language_service_tag( $post['id'], true ); ?>
        
        <a href="<?php echo $post["permalink"] ?>"><img src="<?php echo $image ?>" /></a>
    </inner>
</article>
