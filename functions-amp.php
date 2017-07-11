<?php
add_filter( 'amp_post_template_data', 'xyz_amp_set_site_icon_url' );

function xyz_amp_set_site_icon_url( $data ) {
    // Ideally a 32x32 image
    $data[ 'site_icon_url' ] = '//blogs.voanews.com/img/voa-logo-AMP_32x32.png';
    return $data;
}

function xyz_amp_additional_css_styles( $amp_template ) {
// only CSS here please...
?>
nav.amp-wp-title-bar {
    background-color: #1330BF;
}
<?php
}
add_action( 'amp_post_template_css', 'xyz_amp_additional_css_styles' );


function xyz_amp_add_custom_analytics( $analytics ) {
    if ( ! is_array( $analytics ) ) {
        $analytics = array();
    }

    // https://developers.google.com/analytics/devguides/collection/amp-analytics/
    $analytics['xyz-googleanalytics'] = array(
        'type' => 'googleanalytics',
        'attributes' => array(
            // 'data-credentials' => 'include',
        ),
        'config_data' => array(
            'vars' => array(
                'account' => "UA-62930133-4"
            ),
            'triggers' => array(
                'trackPageview' => array(
                    'on' => 'visible',
                    'request' => 'pageview',
                ),
            ),
        ),
    );

    return $analytics;
}
add_filter( 'amp_post_template_analytics', 'xyz_amp_add_custom_analytics' );
