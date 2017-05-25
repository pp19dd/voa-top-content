<?php

// add special meta tags to the <head>
function voa_extra_meta() {
?>
<meta value="testing" />
<?
}

add_action( 'wp_head', 'voa_extra_meta' );
