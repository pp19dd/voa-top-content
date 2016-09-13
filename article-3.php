<article class="column column_<?php echo $k + 1 ?>">
    <inner>
        <anchor>
            <headline><span><a href="<?php echo $post["permalink"] ?>"><?php echo $post["title"] ?></a></span></headline>
            <?php get_template_part("byline") ?>
            <excerpt><span><span><?php echo $post["excerpt"] ?></span></span></excerpt>
            <continue><span><a href="<?php echo $post["permalink"] ?>">Continue Reading</a></span></continue>
        </anchor>
        <a href="<?php echo $post["permalink"] ?>"><img src="<?php echo $image ?>" /></a>
    </inner>
</article>
