<?php get_header(); ?>

<?php
the_post();
#$id = get_the_ID();
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$image = voa_top_content_get_image_url($thumbnail_id, "full-width");

?>
<rows>
<row class="rows_1">
    <article>
<!--
        <headline><?php the_title(); ?></headline>
        <img src="<?php echo $image ?>" />
-->
        <content>
<?php the_content(); ?>
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
