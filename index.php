<?php
/*
Template Name: Top Content Homepage
*/

get_header();




// ===========================================================================
// load layout config
// ===========================================================================
$row_layout = voa_top_content_get_row_layout();

// ===========================================================================
// load data
// ===========================================================================
$posts_html = array();
while( have_posts() ) {
    the_post();

    $redirect_url = voa_has_redirect_url( $post->ID );

    if ( !$redirect_url ) {
        $url = get_the_permalink( $post->ID );
    } else {
        $url = $redirect_url;
    }

    // full-width      half-width      quarter-width   quarter-width-small
    $posts_html[] = array(
        "id" => $post->ID,
        "title" => get_the_title(),
        "permalink" => $url,
        "thumbnail_id" => get_post_thumbnail_id( $post->ID ),
        "excerpt" => get_the_excerpt(),
        "content" => get_the_content()
    );
}



// ===========================================================================
// break up posts into layout-rows for easier rendering
// ===========================================================================

// REMOVE PRIOR TO LAUNCH - hardcoded for demo
//$posts_html = array_slice( $posts_html, 0, 6 );

$posts_html = voa_top_content_breakup_posts($row_layout, $posts_html);
?>
    <rows>
        <row class="rows_1">
            <breakup>
                <blank-space></blank-space>
                <h2>Monday, January 9</h2>
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
            get_template_part("partials/article", count($posts) );
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