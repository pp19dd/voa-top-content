<?php get_header(); ?>

<?php
the_post();
#$id = get_the_ID();
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$image = voa_top_content_get_image_url($thumbnail_id, "full-width");

?>
<rows>
    <row class="rows_1">
        <h1><?php bloginfo("name") ?></h1>
    </row>
</rows>
<antirows>
    <row class="rows_1">
        <leading>
            <inner class="hovering">
                <anchor>
                    <headline><span><a><?php the_title(); ?></a></span></headline>
                    <excerpt>test</excerpt>
                    <continue>Click Me</continue>
                </anchor>
                <a href="#">
                    <img src="<?php echo $image ?>" />
                </a>
            </inner>

        </leading>

    </row>
</antirows>
<rows>
    <row class="rows_1">
        <article>
            <content>
<?php the_content(); ?>
<section class="prevnext">
previous/next
</section>
            </content>
            <sidebar>
                <sidebar-inner>
                    <?php dynamic_sidebar( 'sidebar_article_right' ); ?>
                </sidebar-inner>
            </sidebar>
        </article>
    </row>
</rows>

<?php get_footer(); ?>
