<?php

/* custom date localization for VOA Top Content theme */



// arguments for voa_date_localizer() match WP's date_i18n() though not all are used
function voa_date_localizer( $dateformatstring, $unixtimestamp = false, $gmt = false ) {
    
    // subset of PHP's date() formats
    $accepted_date_formats = array( 'd', 'D', 'j', 'l', 'F', 'm', 'M', 'n', 'Y', 'y', 'a', 'A', 'g', 'G', 'h', 'H', 'i', 's', 'e', 'T' );
    
    // 
    $format_parts = preg_split( '/(\W+)/', $dateformatstring, -1, PREG_SPLIT_DELIM_CAPTURE );
    
    foreach ( $format_parts as $k => $v ) {
    	// the conditional is basically to keep spacing and punctuation intact
        $local_format_parts[$k] = ( in_array( $v, $accepted_date_formats) ? __( date( $v, $unixtimestamp ), 'voa-top-content' ) : $v );
    }
    
    // combine the translated parts
    $localized_date = implode( '', $local_format_parts );
    
    return $localized_date;
}



voa_date_localizer_translatable_strings();

function voa_date_localizer_translatable_strings() {
	
	$vdlts = array();
	
	$vdlts['weekday'][0] = /* translators: weekday */ __('Sunday', 'voa-top-content');
	$vdlts['weekday'][1] = /* translators: weekday */ __('Monday', 'voa-top-content');
	$vdlts['weekday'][2] = /* translators: weekday */ __('Tuesday', 'voa-top-content');
	$vdlts['weekday'][3] = /* translators: weekday */ __('Wednesday', 'voa-top-content');
	$vdlts['weekday'][4] = /* translators: weekday */ __('Thursday', 'voa-top-content');
	$vdlts['weekday'][5] = /* translators: weekday */ __('Friday', 'voa-top-content');
	$vdlts['weekday'][6] = /* translators: weekday */ __('Saturday', 'voa-top-content');

	// The first letter of each day.
	$vdlts['weekday_initial'][ __( 'Sunday', 'voa-top-content' ) ]    = /* translators: one-letter abbreviation of the weekday */ _x( 'S', 'Sunday initial', 'voa-top-content' );
	$vdlts['weekday_initial'][ __( 'Monday', 'voa-top-content' ) ]    = /* translators: one-letter abbreviation of the weekday */ _x( 'M', 'Monday initial', 'voa-top-content' );
	$vdlts['weekday_initial'][ __( 'Tuesday', 'voa-top-content' ) ]   = /* translators: one-letter abbreviation of the weekday */ _x( 'T', 'Tuesday initial', 'voa-top-content' );
	$vdlts['weekday_initial'][ __( 'Wednesday', 'voa-top-content' ) ] = /* translators: one-letter abbreviation of the weekday */ _x( 'W', 'Wednesday initial', 'voa-top-content' );
	$vdlts['weekday_initial'][ __( 'Thursday', 'voa-top-content' ) ]  = /* translators: one-letter abbreviation of the weekday */ _x( 'T', 'Thursday initial', 'voa-top-content' );
	$vdlts['weekday_initial'][ __( 'Friday', 'voa-top-content' ) ]    = /* translators: one-letter abbreviation of the weekday */ _x( 'F', 'Friday initial', 'voa-top-content' );
	$vdlts['weekday_initial'][ __( 'Saturday', 'voa-top-content' ) ]  = /* translators: one-letter abbreviation of the weekday */ _x( 'S', 'Saturday initial', 'voa-top-content' );

	// Abbreviations for each day.
	$vdlts['weekday_abbrev'][__('Sunday', 'voa-top-content')]    = /* translators: three-letter abbreviation of the weekday */ __('Sun', 'voa-top-content');
	$vdlts['weekday_abbrev'][__('Monday', 'voa-top-content')]    = /* translators: three-letter abbreviation of the weekday */ __('Mon', 'voa-top-content');
	$vdlts['weekday_abbrev'][__('Tuesday', 'voa-top-content')]   = /* translators: three-letter abbreviation of the weekday */ __('Tue', 'voa-top-content');
	$vdlts['weekday_abbrev'][__('Wednesday', 'voa-top-content')] = /* translators: three-letter abbreviation of the weekday */ __('Wed', 'voa-top-content');
	$vdlts['weekday_abbrev'][__('Thursday', 'voa-top-content')]  = /* translators: three-letter abbreviation of the weekday */ __('Thu', 'voa-top-content');
	$vdlts['weekday_abbrev'][__('Friday', 'voa-top-content')]    = /* translators: three-letter abbreviation of the weekday */ __('Fri', 'voa-top-content');
	$vdlts['weekday_abbrev'][__('Saturday', 'voa-top-content')]  = /* translators: three-letter abbreviation of the weekday */ __('Sat', 'voa-top-content');

	// The Months
	$vdlts['month']['01'] = /* translators: month name */ __( 'January', 'voa-top-content' );
	$vdlts['month']['02'] = /* translators: month name */ __( 'February', 'voa-top-content' );
	$vdlts['month']['03'] = /* translators: month name */ __( 'March', 'voa-top-content' );
	$vdlts['month']['04'] = /* translators: month name */ __( 'April', 'voa-top-content' );
	$vdlts['month']['05'] = /* translators: month name */ __( 'May', 'voa-top-content' );
	$vdlts['month']['06'] = /* translators: month name */ __( 'June', 'voa-top-content' );
	$vdlts['month']['07'] = /* translators: month name */ __( 'July', 'voa-top-content' );
	$vdlts['month']['08'] = /* translators: month name */ __( 'August', 'voa-top-content' );
	$vdlts['month']['09'] = /* translators: month name */ __( 'September', 'voa-top-content' );
	$vdlts['month']['10'] = /* translators: month name */ __( 'October', 'voa-top-content' );
	$vdlts['month']['11'] = /* translators: month name */ __( 'November', 'voa-top-content' );
	$vdlts['month']['12'] = /* translators: month name */ __( 'December', 'voa-top-content' );

	// The Months, genitive
	$vdlts['month_genitive']['01'] = /* translators: month name, genitive */ _x( 'January', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['02'] = /* translators: month name, genitive */ _x( 'February', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['03'] = /* translators: month name, genitive */ _x( 'March', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['04'] = /* translators: month name, genitive */ _x( 'April', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['05'] = /* translators: month name, genitive */ _x( 'May', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['06'] = /* translators: month name, genitive */ _x( 'June', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['07'] = /* translators: month name, genitive */ _x( 'July', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['08'] = /* translators: month name, genitive */ _x( 'August', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['09'] = /* translators: month name, genitive */ _x( 'September', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['10'] = /* translators: month name, genitive */ _x( 'October', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['11'] = /* translators: month name, genitive */ _x( 'November', 'genitive', 'voa-top-content' );
	$vdlts['month_genitive']['12'] = /* translators: month name, genitive */ _x( 'December', 'genitive', 'voa-top-content' );

	// Abbreviations for each month.
	$vdlts['month_abbrev'][ __( 'January', 'voa-top-content' ) ]   = /* translators: three-letter abbreviation of the month */ _x( 'Jan', 'January abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'February', 'voa-top-content' ) ]  = /* translators: three-letter abbreviation of the month */ _x( 'Feb', 'February abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'March', 'voa-top-content' ) ]     = /* translators: three-letter abbreviation of the month */ _x( 'Mar', 'March abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'April', 'voa-top-content' ) ]     = /* translators: three-letter abbreviation of the month */ _x( 'Apr', 'April abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'May', 'voa-top-content' ) ]       = /* translators: three-letter abbreviation of the month */ _x( 'May', 'May abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'June', 'voa-top-content' ) ]      = /* translators: three-letter abbreviation of the month */ _x( 'Jun', 'June abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'July', 'voa-top-content' ) ]      = /* translators: three-letter abbreviation of the month */ _x( 'Jul', 'July abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'August', 'voa-top-content' ) ]    = /* translators: three-letter abbreviation of the month */ _x( 'Aug', 'August abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'September', 'voa-top-content' ) ] = /* translators: three-letter abbreviation of the month */ _x( 'Sep', 'September abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'October', 'voa-top-content' ) ]   = /* translators: three-letter abbreviation of the month */ _x( 'Oct', 'October abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'November', 'voa-top-content' ) ]  = /* translators: three-letter abbreviation of the month */ _x( 'Nov', 'November abbreviation', 'voa-top-content' );
	$vdlts['month_abbrev'][ __( 'December', 'voa-top-content' ) ]  = /* translators: three-letter abbreviation of the month */ _x( 'Dec', 'December abbreviation', 'voa-top-content' );

	// The Meridiems
	$vdlts['meridiem']['am'] = __('am', 'voa-top-content');
	$vdlts['meridiem']['pm'] = __('pm', 'voa-top-content');
	$vdlts['meridiem']['AM'] = __('AM', 'voa-top-content');
	$vdlts['meridiem']['PM'] = __('PM', 'voa-top-content');
}
