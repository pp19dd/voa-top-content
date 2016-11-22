<?php


// a = full wide            1 post
// b = 1/2 + 1/4 + 1/4      3 posts
// c = 1/8 + 1/8            2 posts
function voa_top_content_get_row_layout() {
    $r = array(
        3, 2, 1
    );

    return( $r );
}

function voa_top_content_admin_menu_css() {
?>

<style>
voa-layout { display: flex; flex-direction:column }
voa-row { display: flex; flex-direction: row; padding-bottom:1em }
voa-indicator { padding-top: 1em }
voa-control { padding: 1em; display: flex; flex-direction: column }
voa-control button { }
</style>

<?php
}

function voa_top_content_admin_menu() {
    $base = get_theme_root_uri() . '/' . get_template();
    $rows = get_option( "voa-top-content-rows", 5 );
?>
<div class="wrap">
    <h1>Homepage Layout</h1>

    <p>Show <select id="voa-change-rows" name="voa-top-content-rows">
<?php for( $i = 1; $i <= 30; $i++ ) { ?>
        <option <?php if( $i === $rows ) { echo 'selected'; } ?>><?php echo $i ?> row<?php if( $i > 1 ) echo 's'; ?></option>
<?php } ?>
    </select> on homepage to start.</p>

    <voa-layout>
        <voa-row>
            <voa-indicator>
                <img src="<?php echo $base ?>/layout-1.png" />
            </voa-indicator>
            <voa-control>
                <button data-columns="1">1</button>
                <button data-columns="2">2</button>
                <button data-columns="3">3</button>
            </voa-control>
        </voa-row>
    </voa-layout>
</div>

<script>
function voa_setColumns(row, columns) {
    jQuery("img", row).attr("src", "<?php echo $base ?>/layout-" + columns + ".png");
}

function voa_setRows(rows) {

    var all = jQuery("voa-row");

    // remove excess
    jQuery("voa-row:gt(" + rows + ")").remove();

    // add as needed
    if( all.length <= rows ) {
        for( var i = 0; i <= (rows - all.length); i++ )(function() {
            var first = jQuery("voa-row:eq(0)").clone(true);
            jQuery(first).appendTo(jQuery("voa-layout"));
        })();
    }

}

jQuery("#voa-change-rows").change(function() {
    var rows = parseInt(jQuery(this).val());
    voa_setRows(rows-1);
});

voa_setRows(<?php echo $rows ?>);

jQuery("voa-control button").click(function() {
    var columns = jQuery(this).attr("data-columns");
    var row = jQuery(this).parent().parent();
    voa_setColumns( row, columns );
});
</script>

<?php
}


/*
input: array of posts
output: array of array of posts
*/
function voa_top_content_breakup_posts( $row_layout, $posts_html ) {
    $ret = array();
    $temp = array();

    $copy_of_row_layout = $row_layout;

    $limiter = array_shift($row_layout);

    while( !empty($posts_html) ) {
        $temp[] = array_shift($posts_html);

        if( count($temp) >= $limiter ) {
            $ret[] = $temp;
            $temp = array();

            if( empty($row_layout) ) {
                $row_layout = $copy_of_row_layout;
            }

            $limiter = array_shift($row_layout);
        }
    }

    // any unattended leftovers?
    if( !empty($temp) ) {
        $ret[] = $temp;
    }

    return( $ret );
}

// full-width      half-width      quarter-width   quarter-width-small
function voa_top_content_get_image_url($thumbnail_id, $col, $cols = 1) {
    switch( $cols ) {
        case 1:
            $size = "full-width";
        break;

        case 2:
            $size = "quarter-width-small";
        break;

        case 3:
            $size = "quarter-width";
            if( $col === 0 ) $size = "half-width";
        break;

        // allows to directly override size
        default:
            $size = $col;
        break;
    }
    $image = wp_get_attachment_image_src($thumbnail_id, $size);
    $image = $image[0];
    return( $image );
}


// used to show previous/next posts with thumbnail images
// $direction can be 'previous' or 'next'
// returns an array or FALSE if no post found
function voa_top_content_adjacent_post($direction = 'previous') {
    global $post;

    switch ($direction) {
        case 'next':
            $previous = (bool) false;
            break;
        
        default:
            $previous = (bool) true;
            break;
    }

    $adjacent = get_adjacent_post( false, '', $previous );

    if ( $adjacent ) {

        $adjacent_thumbnail_id = get_post_thumbnail_id( $adjacent->ID );
        $adjacent_image_url = voa_top_content_get_image_url($adjacent_thumbnail_id, "quarter-width-small", 2);

        $voa_adjacent = array(
            'ID' => $adjacent->ID,
            'post_title' => $adjacent->post_title,
            'permalink' => get_permalink( $adjacent->ID ),
            'image_url' => $adjacent_image_url
        );

        return $voa_adjacent;

    } else {
        return (bool) false;
    }

}
