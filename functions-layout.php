<?php


// a = full wide            1 post
// b = 1/2 + 1/4 + 1/4      3 posts
// c = 1/8 + 1/8            2 posts
function voa_top_content_get_row_layout() {
    $r = array(
        3, 2, 1
    );

    return( $r );
}

function voa_top_content_admin_menu() {
?>
<div class="wrap">
    <h1>Homepage Layout</h1>
</div>
<?php
}


/*
input: array of posts
output: array of array of posts
*/
function voa_top_content_breakup_posts( $row_layout, $posts_html ) {
    $ret = array();
    $temp = array();

    $copy_of_row_layout = $row_layout;

    $limiter = array_shift($row_layout);

    while( !empty($posts_html) ) {
        $temp[] = array_shift($posts_html);

        if( count($temp) >= $limiter ) {
            $ret[] = $temp;
            $temp = array();

            if( empty($row_layout) ) {
                $row_layout = $copy_of_row_layout;
            }

            $limiter = array_shift($row_layout);
        }
    }

    // any unattended leftovers?
    if( !empty($temp) ) {
        $ret[] = $temp;
    }

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
