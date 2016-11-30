<article class="column column_<?php echo $k + 1 ?>">
    <inner>
        <anchor>
            <headline><span><a href="<?php echo $post["permalink"] ?>"><?php echo $post["title"] ?></a></span></headline>
            <?php get_template_part( "partials/byline" ); ?>
            <excerpt><span><span><?php echo $post["excerpt"] ?></span></span></excerpt>
            <continue><span><a href="<?php echo $post["permalink"] ?>">Continue Reading</a></span></continue>
        </anchor>
        <?php if ( $cat = get_the_category( $post['id'] )) { if ( $cat[0]->slug && $cat[0]->slug != 'uncategorized' ) { ?>
        <div class="language-service"><div class="language-service-inner"><?php echo $cat[0]->name; ?></div></div>
        <?php } } ?>
        <a href="<?php echo $post["permalink"] ?>"><img src="<?php echo $image ?>" /></a>
    </inner>
</article>
