<?php
require_once( "class.calendar.php" );
require_once( "functions-admin.php" );

function get_voa_is_row_tall($posts) {

    foreach( $posts as $post ) {
        $pos = stripos($post["siz"], "card-tall");

        if( $pos !== false ) {
            return( true );
        }
    }
    return(false);
}

// used on main page (index), figures out needed query logic and returns posts
function get_voa_top_posts() {

    $range = get_option("voa-layout-range", "daily");
    // var_dump($range);

    // load layout config
    if( isset( $_GET['vday']) ) {
        $voa_day = date("Y-m-d", strtotime($_GET['vday']) );
    } else {
        $voa_day = voa_top_content_get_most_recently_published_day();
    }
    $voa_day_previous = voa_top_content_get_next_day($voa_day);

    $ret = array(
        "voa_day" => $voa_day,
        "voa_day_previous" => $voa_day_previous,
        "posts" => array()
    );

    // load data
    if( !isset( $_GET['preview_layout']) ) {
        $posts_html = voa_top_content_get_day_posts($voa_day, false, $range);
        $row_layout = voa_top_content_get_row_layout($voa_day, $range);
    } else {
        if( is_user_logged_in() ) {

            $preview_layout = $_GET;
            $preview_layout["day"] = $preview_layout["preview_day"];

            // ensure these post IDs are sanitized integers
            $preview_ids = array();
            foreach( $preview_layout["stories"] as $k => $v ) {
                foreach( $v as $k2 => $v2 ) {
                    $preview_ids[] = intval($v2);
                    #$preview_layout["stories"][$k][$k2] = intval($v2);
                }
            }



            $posts_html = voa_top_content_get_day_posts($voa_day, $preview_ids);

            // extract date from first post
            #$first = array_values($posts_html)[0];
            #$preview_layout["day"] = date("Y-m-d", strtotime($first["pubdate"]));

            $row_layout = $preview_layout;
        }
    }

    // break up posts into layout-rows for easier rendering
    $ret["posts"] = voa_top_content_breakup_posts($row_layout, $posts_html);
    return($ret);
}

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

function voa_top_content_get_day_posts( $date = false, $preview_ids_array = false, $range = "daily" ) {

    $posts_html = array();

    // sanitize day
    if( $date === false ) {
        $voa_top_content_day = voa_top_content_get_most_recently_published_day();
    } else {
        $voa_top_content_day = date("Y-m-d", strtotime($date) );
    }
    $voa_top_content_day_ts = strtotime($voa_top_content_day);

    // get posts
    $date_query = array(
        "year" => date("Y", $voa_top_content_day_ts),
        "month" => date("m", $voa_top_content_day_ts),
        "day" => date("d", $voa_top_content_day_ts)
    );

    switch( $range ) {
        case "daily": break;
        case "weekly": break;
        case "monthly": unset($date_query["day"]); break;
    }

    $args = array(
        "post_type" => "post",
        "post_status" => "publish",
        "posts_per_page" => 100,
        "date_query" => $date_query
    );

    // editing layout needs preview
    if( $preview_ids_array !== false ) {
        $args["post_status"] = array("publish", "future", "draft");
        unset( $args["date_query"] );
        $args["post__in"] = $preview_ids_array;
    }

    $voa_top_content_query = new WP_Query($args);

    while( $voa_top_content_query->have_posts() ) {
        $voa_top_content_query->the_post();
        // full-width      half-width      quarter-width   quarter-width-small

        $id = get_the_ID();

        $posts_html[$id] = array(
            "id" => $id,
            "title" => get_the_title(),
            "pubdate" => get_the_date(),
            "permalink" => get_the_permalink( $post->ID ),
            "thumbnail_id" => get_post_thumbnail_id( $post->ID ),
            "excerpt" => get_the_excerpt(),
            "content" => get_the_content()
        );
    }
    #pre( $args );
    #pre($voa_top_content_query->request);
    #var_dump($posts_html); die;
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
function voa_top_content_get_row_layout($day = false, $range = "daily") {

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

function voa_layout_day_to_actual_date_range($day) {

    $ret = array(
        "range" => "daily",
        "start" => 0,
        "end" => 0
    );

    // daily is a default
    $ret["start"] = strtotime($day);
    $ret["end"] = $ret["start"];

    // monthly
    if( substr($day, 0, 2) === "ym" ) {
        $ret["range"] = "monthly";
        $ret["start"] = strtotime(substr($day,2) . "-01");
        $ret["end"] = strtotime("+1 month -1 day", $ret["start"]);
    }

    // weekly
    if( substr($day, 0, 2) === "yw" ) {
        $ret["range"] = "weekly";

        $start_year = intval(substr($day, 2, 4));
        $start_week = intval(substr($day, 7)) - 1;
        $end_week = $start_week + 1;
        $ret["start"] = strtotime("+{$start_week} week +1 day", strtotime("{$start_year}-01-01"));
        $ret["end"] = strtotime("+{$end_week} week", strtotime("{$start_year}-01-01"));

    }

    return( $ret );
}

// date can be in one of three formats:
//     YYYY-mm-dd   (daily)
//     ymYYYY-mm    (monthly)
//     ywYYYY-w     (weekly)

function voa_top_content_stories_on_day($date, $range) {
    global $wpdb;

    $ts = voa_layout_day_to_actual_date_range($date);
    $range_start = date("Y-m-d 00:00:00", $ts["start"]);
    $range_end = date("Y-m-d 23:59:59", $ts["end"]);

    $statement = $wpdb->prepare(
        "select " .
            "ID, post_status, post_title " .
        "from " .
            "`{$wpdb->posts}` " .
        "where " .
            "post_date between %s and %s and " .
            "post_type='post' and " .
            "post_status in ('publish', 'draft')" .
            "order by post_date desc",
        $range_start,
        $range_end
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

function wpa_4475122017_callback() {
    $allowed_ranges = array(
        "daily" => "daily",
        "weekly" => "weekly",
        "monthly" => "monthly"
    );

    $requested_range = $_POST['layout']['range'];
    if( !isset($allowed_ranges[$requested_range]) ) {
        echo "ERROR: range not allowed?";
        wp_die();
    } else {
        $requested_range = $allowed_ranges[$requested_range];
    }

    delete_option( "voa-layout-range" );
    update_option( "voa-layout-range", $requested_range );

    echo "Changed range to {$requested_range}";
    wp_die();
}

function wpa_4471252017_callback() {

    switch( $_POST['mode'] ) {
        case "save_layout":
            #$day = date("Y-m-d", strtotime($_POST['layout']['day']));
            $day = $_POST['layout']['day'];
            #if( $day != $_POST['layout']['day'] ) return;

            $key = "voa-layout-{$day}";
            delete_option( $key );
            update_option( $key, $_POST['layout'] );

            // publish any drafts?
            if(
                $_POST['layout']['publish_drafts'] == "yes"
            ) {
                foreach( $_POST["layout"]["stories"] as $row => $ids ) {
                    foreach( $ids as $story ) {
                        $story_id = $story["id"];
                        $wp_story_id = intval($story_id);
                        $status = get_post_status( $wp_story_id );
                        if( $status === "draft" ) {
                            // wp_publish_post( $wp_story_id );
                            wp_update_post(array(
                                "ID" => $wp_story_id,
                                "post_status" => "publish"
                            ));
                        }
                    }
                }
            }

            echo "OK {$key} saved";
        break;

        case "delete_layout":
            #$day = date("Y-m-d", strtotime($_POST['day']));
            $day = $_POST['day'];
            $key = "voa-layout-{$day}";
            delete_option( $key );
            echo "OK {$key} deleted";
        break;
    }

    wp_die();
}

function voa_top_content_admin_config_menu() {

    $range = get_option("voa-layout-range", "daily" );

    ?>

    <div class="wrap">
        <h1>Homepage Layout Configuration</h1>

        <div class="card voa-layout-config">
            <h3>Navigation</h3>
            <p>How do you want to group posts on the homepage?</p>

            <p><label><input type="radio" <?php if( $range === "daily" ) { echo "checked='checked'"; } ?> name="voa-layout-range" value="daily" /><strong> Daily </strong> (Recommended if you post several times per day.)</label></p>
            <p><label><input type="radio" <?php if( $range === "weekly" ) { echo "checked='checked'"; } ?> name="voa-layout-range" value="weekly" /><strong> Weekly </strong> (Recommended if you post few times per week.)</label></p>
            <p><label><input type="radio" <?php if( $range === "monthly" ) { echo "checked='checked'"; } ?> name="voa-layout-range" value="monthly" /><strong> Monthly </strong> (Recommended if you post few times per month.)</label></p>

            <p>Warning: don't change this setting frequently, as it may break paginated links.</p>

        </div>

        <p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="layout_config_submit" name="submit"></p>
        <div class="notice notice-success" id="status" style="display:none"></div>

        <script>
        jQuery("#layout_config_submit").click(function() {

            var new_layout_config = {
                range: jQuery("input[name=voa-layout-range]:checked").val()
            };

            jQuery.post( ajaxurl, {
                action: "wpa_4475122017",
                mode: "save_layout_config",
                layout: new_layout_config,
            }).error(function() {
                jQuery("#status").show().html( "<p style='color:crimson'>Error Saving.</p>" );
            }).success( function(e) {
                jQuery("#status").show().html( "<p>Saved: "  + e + "</p>");
            });
        });

        </script>

    </div>

    <?php
}

function voa_top_content_admin_menu() {

    $range = get_option("voa-layout-range", "daily" );
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
    <h1>Page Layout for <?php

    if( $range === "daily") {
        // format YYYY-mm-dd
        echo "<span class='calendar-signifier'>" . $_GET['day'] . "</span> (" . date("F m, Y - l", strtotime($_GET['day']))  . ")";
    } elseif( $range === "weekly" ) {
        // format ywYYYY-W
        $start_year = intval(substr($_GET['day'], 2, 4));
        $start_week = intval(substr($_GET['day'], 7)) - 1;
        $end_week = $start_week + 1;

        $start_ts = strtotime("+{$start_week} week +1 day", strtotime("{$start_year}-01-01"));
        $end_ts = strtotime("+{$end_week} week", strtotime("{$start_year}-01-01"));

        echo "<span class='calendar-signifier'>" . $_GET['day'] . "</span> (" . date("F d, Y", $start_ts) . " - " . date("F d", $end_ts) . ")";
    } elseif( $range === "monthly" ) {
        // format ymYYYY-MM
        echo "<span class='calendar-signifier'>" . $_GET['day'] . "</span> (" . date("F Y", strtotime(substr($_GET['day'],2)))  . ")";
    }

?></h1>
<?php } else { ?>
    <h1>Page Layout</h1>

    <?php if( $range === "daily" ) { ?>
    <p>Choose a day to edit layout for.</p>
    <?php } elseif( $range === "weekly" ) { ?>
    <p>Choose a week to edit layout for.</p>
    <?php } elseif( $range === "monthly" ) { ?>
    <p>Choose a month to edit layout for.</p>
    <?php } ?>

<?php } ?>
<div class="voa-top-content-layout-nav-container">

<?php
if( !isset( $_GET['day']) ) {

    $posts = voa_top_content_get_posts_for_month($current_year, $current_month);

    voa_top_content_display_calendar( $month, $current_ts, $posts, $range );
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
    <p><a id="id-preview" href="#">Preview Layout</a> (Opens in new window)</p>
    </show-items>

    <voa-today class="disable-select">
        <voa-layout>
            <voa-row>
                <voa-control>
                    <button onclick="voa_setColumns(jQuery(this).parent().parent(), 1)">1</button>
                    <button class="more-options" onclick="voa_setColumns(jQuery(this).parent().parent(), 2)">2</button>
                    <button class="more-options" onclick="voa_setColumns(jQuery(this).parent().parent(), 3)">3</button>
                    <button onclick="voa_setColumns(jQuery(this).parent().parent(), 4)">4</button>
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
$stories = voa_top_content_stories_on_day($_GET['day'], $range);
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

    <p>Note: there are two versions of the 3-column option. Click on it a couple of times to switch between left and right versions.</p>
    <p>Note 2: there are two versions of the 2-column option. Click on it a couple of times to switch between short and tall versions.</p>
    <p>Note 3: click on a draggable headline to toggle between image-heavy and text-heavy versions.</p>

    <save-things>
        <button id="save-layout">Save Layout and Story Order</button>
        <button class="verify-action" id="delete-layout">Delete Layout for This Day</button>
    </save-things>
    <p>
        <input type="checkbox" id="publish_drafts"/><label for="publish_drafts"> Also Publish drafts</label>
    </p>
    <div id="modal-window-id" style="display:none;">
        <p>Lorem Ipsum sit dolla amet.</p>
    </div>
</div>

<?php
$extra = "";
if( isset( $_GET['calendar-nav-y']) ) {
    $extra .= "&calendar-nav-y=" . $_GET['calendar-nav-y'];
}
if( isset( $_GET['calendar-nav-m']) ) {
    $extra .= "&calendar-nav-m=" . $_GET['calendar-nav-m'];
}
?>

<script>

// used by layout js
var layout = <?php echo json_encode($retrieved_layout) ?>;
var home_url = "<?php echo home_url() ?>/";
var extra = "<?php echo $extra; ?>";

//  var payload_day = "<?php echo date('Y-m-d', strtotime($_GET['day'])) ?>";
var payload_day = <?php echo json_encode($_GET['day']) ?>;

</script>

<script src="<?php echo get_template_directory_uri() ?>/functions-verifyclick.js?rand=<?php echo time() ?>"></script>
<script src="<?php echo get_template_directory_uri() ?>/functions-layout.js?rand=<?php echo time() ?>"></script>

<?php } # if isset day ?>

<?php
}

/*
input: [2, 3], [4, 6, 5], [1]
output: [2, 3, 4, 6, 5, 1]
*/

function voa_top_content_layout_size($column_total, $column_index, $virtual_columns ) {

    if( $virtual_columns === 6 ) return("card-half card-tall");
    if( $column_total === 4 ) return( "card-quarter");
    if( $column_total === 2 ) return( "card-half");
    if( $column_total === 1 ) return( "card-full");

    if( $virtual_columns === 3 ) {
        switch( $column_index ) {
            case 0: return("card-half"); break;
            default: return("card-quarter"); break;
        }
    }

    if( $virtual_columns === 5 ) {
        switch( $column_index ) {
            case 2: return("card-half"); break;
            default: return("card-quarter"); break;
        }
    }
}

function voa_top_content_layout_order_by_id( $row_layout ) {
    $o = array();

    if( !isset( $row_layout["stories"]) ) return( $o );
    foreach( $row_layout["stories"] as $row_k => $row ) {
        foreach( $row as $story_k => $story_id ) {
            $id = intval($story_id);
            if( $id != 0 ) {

                // defaults
                $story_cls = "card-img";
                $story_siz = "card-full";

                // preference may not exist for older entries
                if( isset( $row_layout["stories_cls"][$row_k][$story_k] ) ) {
                    $preference = $row_layout["stories_cls"][$row_k][$story_k];
                    $story_cls = ($preference === "i") ? "card-img" : "card-txt";
                }

                // slip in card size hint (f, h, q)
                if(
                    !isset($row_layout["virtual_columns"]) ||
                    !isset($row_layout["virtual_columns"][$row_k])
                ) {
                    $virtual_columns = 3;
                } else {
                    $virtual_columns = intval($row_layout["virtual_columns"][$row_k]);
                }
                $story_siz = voa_top_content_layout_size(count($row), $story_k, $virtual_columns);

                $o[] = array(
                    "story_id" => $story_id,
                    "story_cls" => $story_cls,
                    "story_siz" => $story_siz
                );
            }
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

    // bugfix: in case we get a blank input
    if( !is_array($unordered_posts_html) ) return($ret);

    // sort $posts_html by preferred layout ID
    $order = voa_top_content_layout_order_by_id($row_layout);

    $posts_html = array();
    foreach( $order as $order_item ) {

        $id = $order_item["story_id"];

        // could be a draft?
        if( isset($unordered_posts_html[$id]) ) {

            // slip in card type and size hint (i, t  -- f, h, q)
            $unordered_posts_html[$id]["cls"] = $order_item["story_cls"];
            $unordered_posts_html[$id]["siz"] = $order_item["story_siz"];

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
