<?php

function voa_top_content_display_calendar($month, $current_ts) {

?>

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

<?php
}
