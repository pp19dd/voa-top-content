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
        "permalink" => get_the_permalink( $post->ID ),
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
            <h1><?php bloginfo("name") ?></h1>
        </row>
        <row class="rows_1">
            <breakup>
                <blank-space></blank-space>
                <h2>Tuesday, August 23</h2>
                <blank-space></blank-space>
            </breakup>
        </row>
<?php foreach( $posts_html as $posts ) { ?>
        <row class="rows_<?php echo count($posts) ?>">
<?php
        foreach( $posts as $k => $post ) {
            $image = voa_top_content_get_image_url($post["thumbnail_id"], $k, count($posts) );
            set_query_var( "k", $k );
            set_query_var( "image", $image );
            get_template_part("article", count($posts) );
        }
     ?>
        </row>
<?php } #foreach posts_html  ?>
    </rows>

<script>
jQuery("article").mouseover(function() {
    jQuery(this).addClass("hovering");
}).mouseout(function() {
    jQuery(this).removeClass("hovering");
});
jQuery("row.rows_3 article").mouseover(function() {
    //jQuery(this).addClass("hovering");
    jQuery("excerpt, continue", this).stop().animate({opacity: 1}, 200);
});
jQuery("row.rows_3 article").mouseout(function() {
    //jQuery(this).removeClass("hovering");
    jQuery("excerpt, continue", this).stop().animate({opacity: 0}, 100);
});


jQuery("row.rows_1 article").mouseover(function() {
    //jQuery(this).addClass("hovering");
    jQuery("continue", this).stop().animate({opacity: 1}, 200);
});
jQuery("row.rows_1 article").mouseout(function() {
    //jQuery(this).removeClass("hovering");
    jQuery("continue", this).stop().animate({opacity: 0}, 200);
});
/*
jQuery("row.rows_2 article, row.rows_1 article").mouseover(function() {
    jQuery(this).addClass("hovering");
}).mouseout(function() {
    jQuery(this).removeClass("hovering");
});
*/

jQuery("row.rows_3 article excerpt, row.rows_3 article continue, row.rows_1 article continue").css({opacity: 0});
</script>

<?php get_footer(); ?>
