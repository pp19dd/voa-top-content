<?php get_header(); ?>

<?php
the_post();
#$id = get_the_ID();
$thumbnail_id = get_post_thumbnail_id( $post->ID );
$image = voa_top_content_get_image_url($thumbnail_id, "full-width");

?>
<rows>
<row class="rows_1">
    <article style="background-image: url(<?php echo $image ?>)">
        <headline><?php the_title(); ?></headline>
<?php the_content(); ?>
    </article>
</row>
</rows>

<?php get_footer(); ?>
