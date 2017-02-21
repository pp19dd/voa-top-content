<?php
/*
Template Name: Top Content Homepage
*/

if( !isset( $_GET['vday'])) get_header();




// ===========================================================================
// load layout config
// ===========================================================================
if( isset( $_GET['vday']) ) {
    $voa_day = date("Y-m-d", strtotime($_GET['vday']) );
} else {
    $voa_day = voa_top_content_get_most_recently_published_day();
}
$voa_day_previous = voa_top_content_get_next_day($voa_day);

// ===========================================================================
// load data
// ===========================================================================
if( !isset( $_GET['preview_layout']) ) {
    $posts_html = voa_top_content_get_day_posts($voa_day);
    $row_layout = voa_top_content_get_row_layout($voa_day);
} else {

    if( is_user_logged_in() ) {

        // reconstruct row-layout based on pulled queries
        $preview_layout = array(
            "day" => "",
            "row_count" => 0,
            "rows" => array(),
            "stories" => array()
        );
        $rows = explode("|", $_GET['stories']);
        $row_layout = array();
        foreach( $rows as $row ) {
            $temp_row = array();
            $ids = explode(",", $row);
            foreach( $ids as $id ) {
                $preview_layout[] = intval($id);
                $temp_row[] = intval($id);
            }
            $preview_layout["stories"][] = $temp_row;
            $preview_layout["rows"][] = count($temp_row);
        }
        $preview_layout["row_count"] = count($preview_layout["stories"]);

        $posts_html = voa_top_content_get_day_posts($voa_day, $preview_layout);

        // extract date from first post
        $first = array_values($posts_html)[0];
        $preview_layout["day"] = date("Y-m-d", strtotime($first["pubdate"]));

        $row_layout = $preview_layout;
    } else {
        echo "<h1 style='color:crimson'>ERROR: You must be logged-in to preview layouts.</h1>";
    }
}

// ===========================================================================
// break up posts into layout-rows for easier rendering
// ===========================================================================

$posts_html = voa_top_content_breakup_posts($row_layout, $posts_html);

?>
    <rows>
        <row class="rows_1">
            <breakup>
                <blank-space></blank-space>
                <h2><?php echo date("l, F j", strtotime($voa_day)); # Monday, January 9 ?></h2>
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
<?php if( $voa_day_previous !== false ) { ?>
    <div class="lazy-load-button" onclick="voa_load_page('<?php echo $voa_day_previous ?>', this)"><span class="lazy-load-button-text">load previous <?php echo $voa_day_previous ?></span></div>
<?php } ?>

<?php if( !isset( $_GET['vday'])) { ?>

<script>
function voa_apply_events_cluster(selector) {

    jQuery("article", selector).mouseover(function() {
        jQuery(this).addClass("hovering");
    }).mouseout(function() {
        jQuery(this).removeClass("hovering");
    });
    jQuery("row.rows_3 article", selector).mouseover(function() {
        jQuery("excerpt, continue", this).stop().animate({opacity: 1}, 200);
    });
    jQuery("row.rows_3 article", selector).mouseout(function() {
        jQuery("excerpt, continue", this).stop().animate({opacity: 0}, 100);
    });


    jQuery("row.rows_1 article", selector).mouseover(function() {
        jQuery("continue", this).stop().animate({opacity: 1}, 200);
    });
    jQuery("row.rows_1 article", selector).mouseout(function() {
        jQuery("continue", this).stop().animate({opacity: 0}, 200);
    });

    jQuery("row.rows_2 article", selector).mouseover(function() {
        jQuery(this).addClass("hovering");
    }).mouseout(function() {
        jQuery(this).removeClass("hovering");
    });

    jQuery("row.rows_3 article excerpt, row.rows_3 article continue, row.rows_1 article continue", selector).css({opacity: 0});
}

voa_apply_events_cluster(jQuery("rows"));

function voa_load_page(vday, that) {
    jQuery.get("?vday=" + vday, function(rh) {
        jQuery(that).attr("disabled", "disabled");
        jQuery(that).after(rh);
        jQuery(that).hide();

        var new_node = jQuery(that).next();
        voa_apply_events_cluster( new_node );

        new_node.hide().slideToggle();
    });
}

</script>

<?php } # end if vday ?>

<?php if( !isset( $_GET['vday'])) get_footer(); ?>
