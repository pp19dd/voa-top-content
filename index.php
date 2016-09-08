<?php get_header(); ?>

<?php
// ===========================================================================
// load layout config
// ===========================================================================
// a = full wide            1 post
// b = 1/2 + 1/4 + 1/4      3 posts
// c = 1/8 + 1/8            2 posts
$row_layout = array(
    3, 2, 1
);

// ===========================================================================
// load data
// ===========================================================================
$posts_html = array();
while( have_posts() ) {
    the_post();

    // full-width      half-width      quarter-width   quarter-width-small
    $posts_html[] = array(
        "id" => $post->ID,
        "title" => get_the_title(),
        "thumbnail_id" => get_post_thumbnail_id( $post->ID ),
        "excerpt" => get_the_excerpt(),
        "content" => get_the_content()
    );
}

// ===========================================================================
// break up posts into layout-rows for easier rendering
// ===========================================================================
$posts_html = voa_top_content_breakup_posts($row_layout, $posts_html);
?>
    <rows>
        <row class="rows_1">
            <h1>Top Content</h1>
        </row>
<?php foreach( $posts_html as $posts ) { ?>
        <row class="rows_<?php echo count($posts) ?>">
<?php foreach( $posts as $k => $post ) { ?>
<?php $image = voa_top_content_get_image_url($post["thumbnail_id"], $k, count($posts) ); ?>
            <article class="column column_<?php echo $k + 1 ?>">
<?php if( count($posts) === 2 ) { ?>
                <inner>
                    <part>
                        <img src="<?php echo $image ?>" />
                    </part>
                    <part class="two">
                        <headline><?php echo $post["title"] ?></headline>
                        <excerpt><?php echo /*trim_words(*/$post["excerpt"]/*, 40)*/ ?></excerpt>
                    </part>
                </inner>

<?php } else { ?>
                <inner>
                    <img src="<?php echo $image ?>" />
                    <headline><?php echo $post["title"] ?></headline>
                    <excerpt><?php echo $post["excerpt"] ?></excerpt>
                </inner>
<?php } ?>
            </article>
<?php } ?>
        </row>
<?php } ?>
    </rows>


<?php get_footer(); ?>
