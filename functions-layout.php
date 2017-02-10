<?php
require_once( "class.calendar.php" );
require_once( "functions-admin.php" );


function voa_top_content_get_posts_for_month($year, $month) {
    global $wpdb;

    $iyear = intval($year);
    $imonth = intval($month);

    $query = $wpdb->prepare(
        "select " .
            "count(*) as rows, " .
            "date(post_date) as `day` " .
        "from " .
            "{$wpdb->posts} " .
        "where " .
            "post_type='post' and " .
            "post_status in ('publish', 'draft') and " .
            "year(post_date)=%s and ".
            "month(post_date)=%s" .
        "group by " .
            "day",
        $iyear,
        $imonth
    );

    $res = $wpdb->get_results($query);
    $ret = array();
    foreach( $res as $k => $v ) {
        $ret[$v->day] = $v->rows;
    }
    return( $ret );
}

function voa_top_content_get_day_posts( $date = false ) {

    // sanitize day
    if( $date === false ) {
        $voa_top_content_day = voa_top_content_get_most_recently_published_day();
    } else {
        $voa_top_content_day = date("Y-m-d", strtotime($date) );
    }
    $voa_top_content_day_ts = strtotime($voa_top_content_day);

    // get posts
    $args = array(
        "post_type" => "post",
        "post_status" => "publish",
        "posts_per_page" => 100,
        "date_query" => array(
            "year" => date("Y", $voa_top_content_day_ts),
            "month" => date("m", $voa_top_content_day_ts),
            "day" =>date("d", $voa_top_content_day_ts)
        )
    );
    $voa_top_content_query = new WP_Query($args);

    while( $voa_top_content_query->have_posts() ) {
        $voa_top_content_query->the_post();
        // full-width      half-width      quarter-width   quarter-width-small

        $id = get_the_ID();

        $posts_html[$id] = array(
            "id" => $id,
            "title" => get_the_title(),
            "permalink" => get_the_permalink( $post->ID ),
            "thumbnail_id" => get_post_thumbnail_id( $post->ID ),
            "excerpt" => get_the_excerpt(),
            "content" => get_the_content()
        );
    }

    return( $posts_html );
}

function voa_top_content_get_next_day($date = false ) {
    global $wpdb;

    if( $date === false ) {
        $date = voa_top_content_get_most_recently_published_day();
    }

    // sanitize and normalize
    $date = date("Y-m-d", strtotime($date));

    $recently_recent = $wpdb->get_row(
        "select " .
            "ID, " .
            "date(post_date) as `latest` " .
        "from " .
            "{$wpdb->posts} " .
        "where " .
            "post_status='publish' and " .
            "post_type='post' and " .
            "post_date<'{$date}' " .
        "order by " .
            "post_date desc " .
        "limit " .
            "1"
    );

    if( is_null($recently_recent) ) return(false);
    return( $recently_recent->latest );
}

// returns date in Y-m-d format
function voa_top_content_get_most_recently_published_day() {
    global $wpdb;

    $recent = $wpdb->get_row(
        "select " .
            "ID, " .
            "date(post_date) as `latest` " .
        "from " .
            "{$wpdb->posts} " .
        "where " .
            "post_status='publish' and " .
            "post_type='post' " .
        "order by " .
            "post_date desc " .
        "limit " .
            "1"
    );

    return( $recent->latest );
}

// a = full wide            1 post
// b = 1/2 + 1/4 + 1/4      3 posts
// c = 1/8 + 1/8            2 posts
function voa_top_content_get_row_layout($day = false) {

    // find most recently published day
    if( $day == false ) {
        $day = voa_top_content_get_most_recently_published_day();
    }

    // retrieve layout
    $hardcoded_default = array(
        "row_count" => 4,
        "rows" => array(3, 2, 1, 2)
    );
    $default_layout = get_option( "voa-layout-default", $hardcoded_default );
    $retrieved_layout = get_option( "voa-layout-{$day}", false );

    // if( $retrieved_layout === false ) return( $default_layout );
    if( $retrieved_layout === false ) return( $hardcoded_default );
    return( $retrieved_layout );
}

function pre($a) {
    $r = rand(128, 255);
    $g = rand(128, 255);
    $b = rand(128, 255);
    echo "<PRE style='padding:1em; background-color:rgb({$r},{$g},{$b})'>";
    echo htmlentities(print_r( $a, true ));
    echo "</PRE>";
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

    switch( $_POST['mode'] ) {
        case "save_layout":
            $day = date("Y-m-d", strtotime($_POST['layout']['day']));
            if( $day != $_POST['layout']['day'] ) return;

            $key = "voa-layout-{$day}";
            delete_option( $key );
            update_option( $key, $_POST['layout'] );

            echo "OK {$key} saved";

        break;

        case "delete_layout":
            $day = date("Y-m-d", strtotime($_POST['day']));
            $key = "voa-layout-{$day}";
            delete_option( $key );
            echo "OK {$key} deleted";
        break;
    }

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

    $current_year = date("Y");
    $current_month = date("m");

    if( isset( $_GET['calendar-nav-y']) ) {
        $current_year = intval($_GET['calendar-nav-y']);
    }
    if( isset( $_GET['calendar-nav-m']) ) {
        $current_month = intval($_GET['calendar-nav-m']);
    }

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

    $posts = voa_top_content_get_posts_for_month($current_year, $current_month);

    voa_top_content_display_calendar( $month, $current_ts, $posts );
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
                    <button onclick="voa_setColumns(jQuery(this).parent().parent(), 1)">1</button>
                    <button onclick="voa_setColumns(jQuery(this).parent().parent(), 2)">2</button>
                    <button onclick="voa_setColumns(jQuery(this).parent().parent(), 3)">3</button>
                </voa-control>
                <voa-indicator>
                    <placeholder>
                        <table class='vtcmb'><tr><td style='width:100%'><div class='vtcmbdd'></div></td></tr></table>
                    </placeholder>
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
<?php if( $story->post_status == 'draft' ) { ?> <span class='voa-draft'>(draft)</span><?php } ?>
    </div>
<?php
}
?>
        </available-stories>
    </voa-today>

    <save-things>
        <button id="save-layout">Save Layout and Story Order</button>
        <button class="verify-action" id="delete-layout">Delete Layout for This Day</button>
        <!--<button class="verify-action" id="publish-drafts">Publish Drafts</button>-->

    </save-things>

</div>

<script>

function move_stories_out(row) {
    var stories = row.find("div.voa-layout-story");
    stories.each(function(k,v) {
        jQuery("available-stories").append( v );
    });
}

function voa_setColumns(row, columns) {

    // move any stories potentially affected by this row reconfiguration
    move_stories_out(row);

    var ht = [
        "<table class='vtcmb'><tr><td style='width:100%'>@</td></tr></table>",
        "<table class='vtcmb'><tr><td style='width:50%'>@</td><td>@</td></tr></table>",
        "<table class='vtcmb'><tr><td style='width:50%'>@</td><td style='width:25%'>@</td><td>@</td></tr></table>"
    ];
    // bugfix: 0 columns situation
    if( typeof ht[columns-1] != "undefined" ) {
        jQuery("placeholder", row).html( ht[columns-1].split("@").join("<div class='vtcmbdd'></div>") );
    }
    reset_draggable();
}

function voa_setRows(rows) {

    var all = jQuery("voa-row");

    // move any stories potentially affected by this row removal
    jQuery("voa-row:gt(" + rows + ")").each(function(k,v) {
        move_stories_out(jQuery(v));
    });

    // remove excess
    jQuery("voa-row:gt(" + rows + ")").remove();

    // add as needed
    if( all.length <= rows ) {
        for( var i = 0; i <= (rows - all.length); i++ )(function() {
            var first = jQuery("voa-row:eq(0)").clone(false);
            jQuery("placeholder", first).html(
                "<table class='vtcmb'><tr><td style='width:100%'><div class='vtcmbdd'></div></td></tr></table>"
            );
            jQuery(first).appendTo(jQuery("voa-layout"));
        })();
    }

    reset_draggable();
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
    if( typeof layout.stories == "undefined" ) layout.stories = [];

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
    jQuery(document).ready(function() {

    try {
        //console.info( "destroy" );
        manager.destroy();
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
});

}

//

// reset_draggable();

function voa_admin_last_url() {
    <?php
    $extra = "";
    if( isset( $_GET['calendar-nav-y']) ) {
        $extra .= "&calendar-nav-y=" . $_GET['calendar-nav-y'];
    }
    if( isset( $_GET['calendar-nav-m']) ) {
        $extra .= "&calendar-nav-m=" . $_GET['calendar-nav-m'];
    }
    ?>
    return( "?page=voa-homepage-layout<?php echo $extra ?>" );
}

function save_layout() {
    jQuery("#save-layout").attr('disabled', true);
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
        mode: "save_layout",
        layout: new_layout,
    }).error(function() {
        alert( "error saving" );
    }).success( function(e) {
        window.location = voa_admin_last_url();
    });

}

jQuery("#save-layout").click(function() {
    save_layout();
});

jQuery.fn.verifyClick = function(click_event) {
    var that = this;

    this.mouseout(function() {
        if( that.hasClass("action-verified") ) {
            that.removeClass("action-verified action-not-yet");
            that.text( that.attr("original-caption") );
            that.attr("disabled", null);
        }
    });

    this.click(function() {
        if( !that.hasClass("action-verified") ) {
            that.addClass("action-verified action-not-yet");
            that.attr("disabled", "disabled");
            that.attr("original-caption", that.text());
            that.text("Are you sure?");

            setTimeout(function() {
                if( that.hasClass("action-not-yet") ) {
                    that.removeClass("action-not-yet");
                    that.attr("disabled", null);
                }
            }, 2000);
        } else {
            click_event();
            that.removeClass("action-verified action-not-yet");
        }
    });

    return this;
};

jQuery("#delete-layout").verifyClick(function() {
    jQuery.post( ajaxurl, {
        action: "wpa_4471252017",
        mode: "delete_layout",
        day: "<?php echo date('Y-m-d', strtotime($_GET['day'])) ?>"
    }).error(function() {
        alert( "error deleting layout" );
    }).success( function(e) {
        window.location = voa_admin_last_url();
    });
});

jQuery("#publish-drafts").verifyClick(function() {

});

</script>

<?php } # if isset day ?>

<?php
}

/*
input: [2, 3], [4, 6, 5], [1]
output: [2, 3, 4, 6, 5, 1]
*/

function voa_top_content_layout_order_by_id( $row_layout ) {
    $o = array();
    if( !isset( $row_layout["stories"]) ) return( $o );
    foreach( $row_layout["stories"] as $row ) {
        foreach( $row as $story_id ) {
            $id = intval($story_id);
            if( $id != 0 ) $o[] = $story_id;
        }
    }
    return( $o );
}

/*
input: layout preference, array of posts
output: array of array of posts
*/
function voa_top_content_breakup_posts( $row_layout, $unordered_posts_html ) {
    $ret = array();
    $temp = array();

    // sort $posts_html by preferred layout ID
    $order = voa_top_content_layout_order_by_id($row_layout);
    $posts_html = array();
    foreach( $order as $id ) {

        // could be a draft?
        if( isset($unordered_posts_html[$id]) ) {
            $posts_html[] = $unordered_posts_html[$id];
            unset( $unordered_posts_html[$id] );
        }
    }

    // any remaining items that haven't been assigned?
    foreach( $unordered_posts_html as $post ) {
        $posts_html[] = $post;
    }

    $copy_of_row_layout = $row_layout["rows"];

    $limiter = array_shift($row_layout["rows"]);

    while( !empty($posts_html) ) {
        $temp[] = array_shift($posts_html);

        if( count($temp) >= $limiter ) {
            $ret[] = $temp;
            $temp = array();

            if( empty($row_layout["rows"]) ) {
                $row_layout["rows"] = $copy_of_row_layout;
            }

            $limiter = array_shift($row_layout["rows"]);
        }
    }

    // any unattended leftovers?
    if( !empty($temp) ) {
        $ret[] = $temp;
    }

    // limit by stated number of rows, ignore rest
    $ret = array_slice($ret, 0, $row_layout["row_count"]);

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
