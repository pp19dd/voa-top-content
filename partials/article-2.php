<article class="column column_<?php echo $k + 1 ?>">
    <inner>
        <part class="one">
        	<div class="part-one-img">
        		<?php /* if ( $cat = get_the_category( $post['id'] )) { if ( $cat[0]->slug && $cat[0]->slug != 'uncategorized' ) { ?>
	        	<div class="language-service"><div class="language-service-inner"><?php echo $cat[0]->name; ?></div></div>
	        	<?php } } */ ?>
	        	<a href="<?php echo $post["permalink"] ?>"><img src="<?php echo $image ?>" /></a>
	        </div>
        </part>
        <part class="two">
            <headline><a href="<?php echo $post["permalink"] ?>"><?php echo $post["title"] ?></a></headline>
            <?php //get_template_part( "partials/byline" ); ?>
            <excerpt><?php echo /*trim_words(*/$post["excerpt"]/*, 40)*/ ?></excerpt>
        </part>
    </inner>
</article>
