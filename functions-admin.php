<?php

function voa_top_content_display_calendar($month, $current_ts, $posts, $range = "daily") {

    $next_ts = strtotime("+1 month", $current_ts);
    $prev_ts = strtotime("-1 month", $current_ts);

    $next_y = date("Y", $next_ts);
    $next_m = date("m", $next_ts);

    $prev_y = date("Y", $prev_ts);
    $prev_m = date("m", $prev_ts);

    $extra = "";
    if( isset( $_GET['calendar-nav-y']) ) {
        $extra .= "&amp;calendar-nav-y=" . $_GET['calendar-nav-y'];
    }
    if( isset( $_GET['calendar-nav-m']) ) {
        $extra .= "&amp;calendar-nav-m=" . $_GET['calendar-nav-m'];
    }
?>

    <table class="voa-top-content-layout-nav">
        <thead>
            <caption><?php echo date("F Y", $current_ts) ?></caption>
            <tr>
                <td colspan="3"><a href="?page=voa-homepage-layout&amp;calendar-nav-y=<?php echo $prev_y ?>&amp;calendar-nav-m=<?php echo $prev_m ?>">&lt; <?php echo date("M", $prev_ts) ?></a></td>
                <td><a href="?page=voa-homepage-layout">Now</a></td>
                <td colspan="3" style="text-align:right"><a href="?page=voa-homepage-layout&amp;calendar-nav-y=<?php echo $next_y ?>&amp;calendar-nav-m=<?php echo $next_m ?>"><?php echo date("M", $next_ts) ?> &gt;</a></td>
            </tr>
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

        $month_str = $month = "ym" . date("Y-m", $current_ts);
        $week_str = "yw" . date("Y-W", strtotime($day));

        if( $day !== "" ) {
            $classes[] = "week-{$week_str}";
            $classes[] = "month-{$month_str}";
        }

        if( isset($posts[$day]) ) $classes[] = "has-posts";
    ?>
                <td data-month="<?php echo $month_str ?>" data-week="<?php echo $week_str ?>" <?php if(isset($posts[$day])) { ?>title="Posts: <?php echo $posts[$day] ?>"<?php } ?> class="<?php echo implode(" ", $classes); ?>">
    <?php if( $day === "") { ?>
                    &nbsp;
    <?php } else { ?>

    <?php if( $range == "daily" ) { ?>
                    <a href="?page=voa-homepage-layout&amp;day=<?php echo $day . $extra ?>"><?php echo date("d", strtotime($day) ) ?></a>
    <?php } # daily ?>

    <?php if( $range == "weekly" ) { ?>
                    <a href="?page=voa-homepage-layout&amp;day=<?php echo $week_str . $extra ?>"><?php echo date("d", strtotime($day) ) ?></a>
    <?php } # daily ?>

    <?php if( $range == "monthly" ) { ?>
                    <a href="?page=voa-homepage-layout&amp;day=<?php echo $month_str . $extra ?>"><?php echo date("d", strtotime($day) ) ?></a>
    <?php } # daily ?>

    <?php } # day not blank ?>
                </td>
    <?php } ?>
            </tr>
    <?php } ?>
        </tbody>
    </table>

    <?php if( $range === "weekly" ) { ?>
    <script>
    jQuery("table.voa-top-content-layout-nav td").mouseover(function() {
        var current_week = jQuery(this).attr("data-week");
        jQuery("table.voa-top-content-layout-nav td.week-" + current_week).addClass("calendar-range-hovering");
    }).mouseout(function() {
        jQuery("table.voa-top-content-layout-nav td").removeClass("calendar-range-hovering");
    });
    </script>
    <?php } ?>

    <?php if( $range === "monthly" ) { ?>
    <script>
    jQuery("table.voa-top-content-layout-nav td").mouseover(function() {
        var current_month = jQuery(this).attr("data-month");
        jQuery("table.voa-top-content-layout-nav td.month-" + current_month).addClass("calendar-range-hovering");
    }).mouseout(function() {
        jQuery("table.voa-top-content-layout-nav td").removeClass("calendar-range-hovering");
    });
    </script>
    <?php } ?>

    <div class="voa-top-layout-legend">
        <h3>Legend:</h3>
        <p><span style="background-color:#FF4136">&nbsp;&nbsp;&nbsp;&nbsp;</span> Posts exist (either published or draft), but layout is missing</p>
        <p><span style="background-color:#7fdbff">&nbsp;&nbsp;&nbsp;&nbsp;</span> Layout exists, but no posts.</p>
        <p><span style="background-color:#0074d9">&nbsp;&nbsp;&nbsp;&nbsp;</span> Layout exists, posts exist.</p>
        <p><a href="?page=voa-homepage-layout&amp;day=default">Set default layout pattern.</a></p>
    </div>

<?php
}
