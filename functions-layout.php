<?php
require_once( "class.calendar.php" );

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
    include( "functions-admin-css.php" );
}

function voa_top_content_get_calendar($year, $month) {
    $cal = new VOA_Calendar($year, $month);

    return( $cal->getMonth() );
}

function voa_top_content_admin_menu() {
    $base = get_theme_root_uri() . '/' . get_template();

    $hardcoded_default = array(
        "row_count" => 3,
        "rows" => array(3, 2, 1)
    );

    // fixme: delete
    delete_option( "voa-layout-2017-01-23");
    update_option( "voa-layout-2017-01-23", $hardcoded_default );

    $layout = get_option( "voa-layout-default", $hardcoded_default );

    if( isset( $_GET['day']) ) {
        $retrieved_layout = get_option( "voa-layout-{$_GET['day']}", false );
        $layout = $retrieved_layout;
        if( $layout === false ) $layout = array(
            "row_count" => 1,
            "rows" => array(1)
        );
    }

    $current_year = 2017;
    $current_month = 1;
    $current_ts = strtotime("{$current_year}-{$current_month}-01");

    $month = voa_top_content_get_calendar($current_year, $current_month);
?>
<div class="wrap">
    <h1>Page Layout<?php

if( isset( $_GET['day']) ) {

}

?></h1>

<div class="voa-top-content-layout-nav-container">

<table class="voa-top-content-layout-nav">
    <thead>
        <caption><?php echo date("F Y", $current_ts) ?></caption>
        <tr>
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
        </tr>
    </thead>
    <tbody>
<?php foreach( $month as $week ) { ?>
        <tr>
<?php foreach( $week as $k => $day ) { ?>
<?php
    $has_layout = get_option("voa-layout-{$day}", false);
    $classes = [];
    if( $k === 0 || $k === 6) $classes[] = "satsun";
    if( $has_layout !== false ) $classes[] = "laid-out";
    if( $day === "" ) $classes[] = "not-a-day";
?>
            <td class="<?php echo implode(" ", $classes); ?>">
<?php if( $day === "") { ?>
                &nbsp;
<?php } else { ?>
                <a href="?page=voa-homepage-layout&amp;day=<?php echo $day ?>"><?php echo date("d", strtotime($day) ) ?></a>
<?php } ?>
            </td>
<?php } ?>
        </tr>
<?php } ?>
    </tbody>
</table>
<p>Green marks a day with a customized layout.</p>
<p>Unmarked days use a default pattern.</p>
<p><a href="?page=voa-homepage-layout&amp;day=default">Set default pattern.</a></p>
</div>

<?php if( isset( $_GET['day']) ) { ?>

    <p>Show <select id="voa-change-rows" name="voa-top-content-rows">
        <option value="0" <?php if( $retrieved_layout === false ) { echo 'selected'; } ?>>0 rows - use default</option>
<?php for( $i = 1; $i <= 30; $i++ ) { ?>
        <option value="<?php echo $i ?>" <?php if( $i === $rows ) { echo 'selected'; } ?>><?php echo $i ?> row<?php if( $i > 1 ) echo 's'; ?></option>
<?php } ?>
    </select> on the page.</p>

    <voa-layout>
        <voa-row>
            <voa-indicator>
                <img src="<?php echo $base ?>/img/layout-1.png" />
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
    jQuery("img", row).attr("src", "<?php echo $base ?>/img/layout-" + columns + ".png");
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
    if( rows === 0 ) {
        jQuery("voa-row").hide();
    } else {
        jQuery("voa-row").show();
    }
    voa_setRows(rows-1);
});

jQuery("voa-control button").click(function() {
    var columns = jQuery(this).attr("data-columns");
    var row = jQuery(this).parent().parent();
    voa_setColumns( row, columns );
});

// load current layout
var layout = <?php echo json_encode($retrieved_layout) ?>;

if( layout === false ) {
    jQuery("voa-row:eq(0)").hide();
} else {
    voa_setRows(layout.row_count - 1);
    jQuery("#voa-change-rows").val(layout.row_count);
    for( var i = 0; i < layout.row_count; i++ ) {
        voa_setColumns( jQuery("voa-row:eq(" + i + ")"), layout.rows[i] );
    }
}
</script>

<?php } # if isset day ?>

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
