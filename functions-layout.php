<?php
require_once( "class.calendar.php" );
require_once( "functions-admin.php" );

// a = full wide            1 post
// b = 1/2 + 1/4 + 1/4      3 posts
// c = 1/8 + 1/8            2 posts
function voa_top_content_get_row_layout() {
    $r = array(
        3, 2, 1
    );

    return( $r );
}

function voa_top_content_stories_on_day($date) {
    global $wpdb;

    $statement = $wpdb->prepare(
        "select " .
            "ID, post_status, post_title " .
        "from " .
            "`{$wpdb->posts}` " .
        "where " .
            "date(post_date)=%s and " .
            "post_type='post' and " .
            "post_status in ('publish', 'draft')" .
            "order by post_date desc",
        $date
    );

    $res = $wpdb->get_results($statement);
    return( $res );
}

function voa_top_content_admin_menu_css() {
    include( "functions-admin-css.php" );
    echo "\n<script src='https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js'></script>\n";
}

function voa_top_content_get_calendar($year, $month) {
    $cal = new VOA_Calendar($year, $month);

    return( $cal->getMonth() );
}

function wpa_4471252017_callback() {
    $day = date("Y-m-d", strtotime($_POST['layout']['day']));
    if( $day != $_POST['layout']['day'] ) return;

    $key = "voa-layout-{$day}";
    delete_option( $key );
    update_option( $key, $_POST['layout'] );

    echo "OK {$key} saved";
}

function voa_top_content_admin_menu() {

    $base = get_theme_root_uri() . '/' . get_template();

    $hardcoded_default = array(
        "row_count" => 4,
        "rows" => array(3, 2, 1, 2)
    );

    // fixme: delete
    // delete_option( "voa-layout-2017-01-23");
    // update_option( "voa-layout-2017-01-23", $hardcoded_default );

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

<?php if( isset( $_GET['day'])) { ?>
    <h1>Page Layout for <?php echo $_GET['day'] ?> (<?php echo date("D", strtotime($_GET['day'])) ?>)</h1>
<?php } else { ?>
    <h1>Page Layout</h1>

    <p>Choose a day to edit layout for.</p>
<?php } ?>
<div class="voa-top-content-layout-nav-container">

<?php
if( !isset( $_GET['day']) ) {
    voa_top_content_display_calendar( $month, $current_ts );
}
?>

</div><!-- .voa-top-content-layout-nav-container -->

<?php if( isset( $_GET['day']) ) { ?>

    <show-items>
    <p>Show <select id="voa-change-rows" name="voa-top-content-rows">
        <option value="0" <?php if( $retrieved_layout === false ) { echo 'selected'; } ?>>0 rows - use default</option>
<?php for( $i = 1; $i <= 30; $i++ ) { ?>
        <option value="<?php echo $i ?>" <?php if( $i === $rows ) { echo 'selected'; } ?>><?php echo $i ?> row<?php if( $i > 1 ) echo 's'; ?></option>
<?php } ?>
    </select> on the page.</p>
    </show-items>

    <voa-today>
        <voa-layout>
            <voa-row>
                <voa-control>
                    <button data-columns="1">1</button>
                    <button data-columns="2">2</button>
                    <button data-columns="3">3</button>
                </voa-control>
                <voa-indicator>
                    <placeholder />
                </voa-indicator>
            </voa-row>
        </voa-layout>
        <available-stories>
<?php
$stories = voa_top_content_stories_on_day($_GET['day']);
foreach( $stories as $story ) {
?>
    <div class='voa-layout-story' data-id="<?php echo $story->ID ?>">
        <?php echo $story->post_title ?>
<?php if( $story->post_status == 'draft' ) { ?> (draft)<?php } ?>
    </div>
<?php
}
?>
        </available-stories>
    </voa-today>

    <save-things>
        <button id="save-layout">Save Layout and Story Order</button>
    </save-things>

</div>

<script>
function voa_setColumns(row, columns) {
    var ht = [
        "<table class='vtcmb'><tr><td style='width:100%'>@</td></tr></table>",
        "<table class='vtcmb'><tr><td style='width:50%'>@</td><td>@</td></tr></table>",
        "<table class='vtcmb'><tr><td style='width:50%'>@</td><td style='width:25%'>@</td><td>@</td></tr></table>"
    ];
    jQuery("placeholder", row).html( ht[columns-1].split("@").join("<div class='vtcmbdd'></div>") );
    reset_draggable();
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

    // move stories into their places
    for( var i = 0; i < layout.stories.length; i++ )(function(row, ids) {
        for( var j = 0; j < ids.length; j++ )(function(id, num) {
            var node_story = jQuery(".voa-layout-story[data-id='" + id + "']");
            var node_destination = jQuery("voa-row:eq(" + row + ") .vtcmbdd:eq(" + num + ")");

            jQuery(node_story).appendTo( node_destination );
            // console.info( node_story, node_destination );
        })(ids[j], j);
    })(i, layout.stories[i])

}

// dnd
var manager = null;

function reset_draggable() {
    try {
        //console.info( "destroy" );
        // manager.destroy();
        manager.containers = [ ];
    } catch( e ) {

    }

    var node = jQuery("available-stories")[0];

    manager = dragula([node], {
        accepts: function(el, target, source, sibling) {
            if( source == target ) return( false );

            if( jQuery(target).is("div.vtcmbdd") ) {
                var buddies = jQuery("div.voa-layout-story:not(.gu-transit)", jQuery(target));
                if( buddies.length === 0 ) {
                    return( true );
                } else {
                    return( false );
                }
            }

            return( true);
        }
    });

    jQuery(".vtcmbdd").each(function(e) {
        //console.info( "peck ", this );
        manager.containers.push(this);
    });

    //vtcmbdd
}

// reset_draggable();

function save_layout() {
    var new_layout = {
        day: "<?php echo date('Y-m-d', strtotime($_GET['day'])) ?>",
        row_count: jQuery("#voa-change-rows").val(),
        rows: [],
        stories: []
    };
    jQuery("voa-row").each(function(i, e) {
        var cells = jQuery(".vtcmbdd", this);
        new_layout.rows.push( cells.length );
        var new_a = [];
        cells.each(function(i2, e2) {
            var story = jQuery(".voa-layout-story", this);
            var story_id = jQuery(story).attr("data-id");
            if( typeof story_id === "undefined" ) story_id = "";

            new_a.push( story_id );
        });
        new_layout.stories.push( new_a );
    });

    jQuery.post( ajaxurl, {
        action: "wpa_4471252017",
        layout: new_layout,
    }).error(function() {
        alert( "error saving" );
    }).success( function(e) {
        console.info( "OK SAVED (js)");
        console.info( e );
    });

}

jQuery("#save-layout").click(function() {
    save_layout();
});

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
