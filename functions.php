<?php

show_admin_bar( false );
add_theme_support( "post-thumbnails" );
remove_action( "wp_head", "print_emoji_detection_script", 7 );
remove_action( "wp_print_styles", "print_emoji_styles" );
wp_enqueue_script( "jquery" );

// crop: x can be left, center, right
//       y can be top, center, bottom
add_image_size( 'full-width', 1200, 675, array("center", "center") );
add_image_size( 'half-width', 582, 582, array("center", "center") );
add_image_size( 'quarter-width', 273, 582, array("center", "center") );
add_image_size( 'quarter-width-small', 273, 218, array("left", "top") );

add_filter( 'image_size_names_choose', 'top_content_custom_sizes' );

function top_content_custom_sizes( $sizes ) {
    return(
        array_merge(
            $sizes,
            array(
                'full-width' => __( 'full-width' ),
                'half-width' => __( 'half-width' ),
                'quarter-width' => __( 'quarter-width' ),
                'quarter-width-small' => __( 'quarter-width-small' ),
            )
        )
    );
}

/*
currently unused, but in case we need to force-trim excerpts
*/
function trim_words($text, $words = 20) {
    $temp = str_word_count($text, 1);
    if( count($temp) <= $words ) return( $text );

    $shorter = array_slice($temp, 0, $words);
    $out = implode(" ", $shorter) . " ...";

    return( $out );
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

/*
edit bylines are a meta field
*/

add_action( 'add_meta_boxes', 'voa_top_content_meta_byline_add' );
function voa_top_content_meta_byline_add() {
    add_meta_box(
        "voa-top-content-meta-01",
        "Byline",
        "voa_top_content_meta_byline",
        null,
        "side"
    );
}

add_action( 'save_post', 'voa_top_content_meta_byline_save' );
function voa_top_content_meta_byline_save( $post_id ) {
    if( !isset( $_POST['voa-byline']) ) return;

    $value = $_POST['voa-byline'];
    filter_var( $value, FILTER_SANITIZE_STRING );
    $value = trim($value);

    update_post_meta( $post_id, "_voa_byline", $value );
}

function voa_top_content_meta_byline($post) {
    $value = get_post_meta($post->ID, "_voa_byline", true);
    filter_var( $value, FILTER_SANITIZE_STRING );
?>
    <input
        style="width:100%"
        name="voa-byline"
        type="text"
        autocomplete="off"
        spellcheck="false"
        value="<?php echo $value ?>"
    />
<?php
}
