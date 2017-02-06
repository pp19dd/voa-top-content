<?php

function voa_top_content_display_calendar($month, $current_ts) {

    $next_ts = strtotime("+1 month", $current_ts);
    $prev_ts = strtotime("-1 month", $current_ts);

    $next_y = date("Y", $next_ts);
    $next_m = date("m", $next_ts);

    $prev_y = date("Y", $prev_ts);
    $prev_m = date("m", $prev_ts);

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

<?php
}
