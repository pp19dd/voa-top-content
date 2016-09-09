<article class="column column_<?php echo $k + 1 ?>">
    <inner>
        <part class="one"><a href="<?php echo $post["permalink"] ?>"><img src="<?php echo $image ?>" /></a></part>
        <part class="two">
            <headline><a href="<?php echo $post["permalink"] ?>"><?php echo $post["title"] ?></a></headline>
            <excerpt><?php echo /*trim_words(*/$post["excerpt"]/*, 40)*/ ?></excerpt>
        </part>
    </inner>
</article>
