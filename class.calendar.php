<?php
/*
generates a web-rendered catalog, organized by week

usage:

    $month = new VOA_Calendar( 2015, 06 );
    $weeks = $month->getMonth();

*/

class VOA_Calendar {
    public $start;
    public $days;

    public $week = array(
        "Sun" => 0, "Mon" => 1, "Tue" => 2, "Wed" => 3,
        "Thu" => 4, "Fri" => 5, "Sat" => 6
    );

    function __construct( $year, $month ) {
        $this->start = new DateTime();
        $this->start->setDate( $year, $month, 1 );

        $this->days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $this->end = clone $this->start;
        $this->end->modify( "+1 month -1 day" );
    }

    function getMonth() {

        # beginning of a month empty days
        $first = $this->start->format("D");
        $offset = $this->week[$first];

        if( $offset > 0 ) {
            $days = array_fill(0, $offset, "");
        }

        $temp = clone $this->start;

        # fill in days of the month
        for( $i = 0; $i < $this->days; $i++ ) {
            $day = $temp->format("Y-m-d");
            $days[] = $day;

            $temp->add(new DateInterval("P1D"));
        }

        # reorganize by week
        $days = array_chunk($days, 7);

        # fill in missing days to fill grid
        $week = count($days) - 1;
        $last_week = $days[$week];
        $missing = 7 - count($last_week);

        if( $missing > 0 ) {
            $last_week_fill = array_fill(0, $missing, "");
            $days[$week] = array_merge($last_week, $last_week_fill);
        }

        return( $days );
    }
}
