<?php

/* custom date localization for VOA Top Content theme */

function date_i18n_voa( $dateformatstring, $unixtimestamp = false, $gmt = false ) {
	
	pre($dateformatstring);
	pre($unixtimestamp);
	
	$i = $unixtimestamp;

	if ( false === $i ) {
		if ( ! $gmt )
			$i = current_time( 'timestamp' );
		else
			$i = time();
		// we should not let date() interfere with our
		// specially computed timestamp
		$gmt = true;
	}
	
	/*
	 * Store original value for language with untypical grammars.
	 * See https://core.trac.wordpress.org/ticket/9396
	 */
	$req_format = $dateformatstring;

	$datefunc = $gmt? 'gmdate' : 'date';

	if ( ( !empty( $wp_locale->month ) ) && ( !empty( $wp_locale->weekday ) ) ) {
		$datemonth = $wp_locale->get_month( $datefunc( 'm', $i ) );
		$datemonth_abbrev = $wp_locale->get_month_abbrev( $datemonth );
		$dateweekday = $wp_locale->get_weekday( $datefunc( 'w', $i ) );
		$dateweekday_abbrev = $wp_locale->get_weekday_abbrev( $dateweekday );
		$datemeridiem = $wp_locale->get_meridiem( $datefunc( 'a', $i ) );
		$datemeridiem_capital = $wp_locale->get_meridiem( $datefunc( 'A', $i ) );
		$dateformatstring = ' '.$dateformatstring;
		$dateformatstring = preg_replace( "/([^\\\])D/", "\\1" . backslashit( $dateweekday_abbrev ), $dateformatstring );
		$dateformatstring = preg_replace( "/([^\\\])F/", "\\1" . backslashit( $datemonth ), $dateformatstring );
		$dateformatstring = preg_replace( "/([^\\\])l/", "\\1" . backslashit( $dateweekday ), $dateformatstring );
		$dateformatstring = preg_replace( "/([^\\\])M/", "\\1" . backslashit( $datemonth_abbrev ), $dateformatstring );
		$dateformatstring = preg_replace( "/([^\\\])a/", "\\1" . backslashit( $datemeridiem ), $dateformatstring );
		$dateformatstring = preg_replace( "/([^\\\])A/", "\\1" . backslashit( $datemeridiem_capital ), $dateformatstring );

		$dateformatstring = substr( $dateformatstring, 1, strlen( $dateformatstring ) -1 );
	}
	$timezone_formats = array( 'P', 'I', 'O', 'T', 'Z', 'e' );
	$timezone_formats_re = implode( '|', $timezone_formats );
	if ( preg_match( "/$timezone_formats_re/", $dateformatstring ) ) {
		$timezone_string = get_option( 'timezone_string' );
		if ( $timezone_string ) {
			$timezone_object = timezone_open( $timezone_string );
			$date_object = date_create( null, $timezone_object );
			foreach ( $timezone_formats as $timezone_format ) {
				if ( false !== strpos( $dateformatstring, $timezone_format ) ) {
					$formatted = date_format( $date_object, $timezone_format );
					$dateformatstring = ' '.$dateformatstring;
					$dateformatstring = preg_replace( "/([^\\\])$timezone_format/", "\\1" . backslashit( $formatted ), $dateformatstring );
					$dateformatstring = substr( $dateformatstring, 1, strlen( $dateformatstring ) -1 );
				}
			}
		}
	}
	
	pre($dateformatstring);
	pre($i);
	
	//$j = @$datefunc( $dateformatstring, $i );

	/**
	 * Filters the date formatted based on the locale.
	 *
	 * @since 2.8.0
	 *
	 * @param string $j          Formatted date string.
	 * @param string $req_format Format to display the date.
	 * @param int    $i          Unix timestamp.
	 * @param bool   $gmt        Whether to convert to GMT for time. Default false.
	 */
	//$j = apply_filters( 'date_i18n', $j, $req_format, $i, $gmt );
	//return $j;
}
