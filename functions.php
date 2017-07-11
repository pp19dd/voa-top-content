<?php
require_once( "functions-layout.php" );
require_once( "functions-widgets.php" );
require_once( "functions-meta.php" );
require_once( "functions-head-meta-tags.php" );

// used for on-demand image resizing
require_once( ABSPATH . 'wp-admin/includes/image.php' );

show_admin_bar( false );
remove_action( "wp_head", "print_emoji_detection_script", 7 );
remove_action( "wp_print_styles", "print_emoji_styles" );
remove_action( "wp_head", "wp_generator" );

wp_enqueue_script( "jquery" );

function voa_theme_setup_options() {
    add_theme_support( 'custom-header', array( 'width' => 974, 'height' => 190, 'header-text'=> false, 'default-image' => '' ) );
    add_theme_support( 'html5', array( 'search-form' ) );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'voa_theme_setup_options' );



// establish whether this is the Editor's Picks blog or not
$blog_name_sanitized = sanitize_title_for_query( get_bloginfo('name'));
define( 'VOA_EDITORS_PICKS', ($blog_name_sanitized == 'editors-picks' ? true : false ) );

// metrics tracking parameter to add to URLs (also on single.php for redirects)
define( 'VOA_METRICS_TRACKING_PARAMETER', ( VOA_EDITORS_PICKS ? '?src=voa-editor-picks' : '' ) );



// crop: x can be left, center, right
//       y can be top, center, bottom
add_image_size( 'full-width',              1200,  576, array("center", "center") );
add_image_size( 'full-width-2x',           2400, 1152, array("center", "center") ); // 2x for high-DPI screens
add_image_size( 'half-width-square',        576,  576, array("center", "center") );
add_image_size( 'half-width-square-2x',    1152, 1152, array("center", "center") ); // 2x for high-DPI screens
add_image_size( 'half-width-mid',           576,  375, array("center", "top"   ) );
add_image_size( 'half-width-mid-2x',       1152,  750, array("center", "top"   ) ); // 2x for high-DPI screens
add_image_size( 'half-width-landscape',     576,  207, array("center", "top"   ) );
add_image_size( 'half-width-landscape-2x', 1152,  414, array("center", "top"   ) ); // 2x for high-DPI screens
add_image_size( 'quarter-width-tall',       276,  576, array("center", "top"   ) );
add_image_size( 'quarter-width-tall-2x',    552, 1152, array("center", "top"   ) ); // 2x for high-DPI screens
add_image_size( 'quarter-width-mid',        276,  375, array("center", "top"   ) );
add_image_size( 'quarter-width-mid-2x',     552,  750, array("center", "top"   ) ); // 2x for high-DPI screens
add_image_size( 'quarter-width-short',      276,  207, array("center", "center") );
add_image_size( 'quarter-width-short-2x',   552,  414, array("center", "center") ); // 2x for high-DPI screens

add_image_size( 'fb-share-image',          1200,  600, array("center", "center") );

function top_content_custom_sizes( $sizes ) {
    return(
        array_merge(
            $sizes,
            array(
                'full-width'           => __( 'full-width' ),
                'half-width-square'    => __( 'half-width-square' ),
                'half-width-mid'       => __( 'half-width-mid' ),
                'half-width-landscape' => __( 'half-width-landscape' ),
                'quarter-width-tall'   => __( 'quarter-width-tall' ),
                'quarter-width-mid'    => __( 'quarter-width-mid' ),
                'quarter-width-short'  => __( 'quarter-width-short' )
            )
        )
    );
}

add_filter( 'image_size_names_choose', 'top_content_custom_sizes' );



// voa_top_content_admin_menu function is located in functions-layout.php
function voa_top_content_admin_menu_loader() {
    add_filter("admin_head", "voa_top_content_admin_menu_css" );
    add_menu_page("Homepage Layout", "Homepage Layout", "edit_posts", "voa-homepage-layout", "voa_top_content_admin_menu", "dashicons-schedule" );
    add_submenu_page("voa-homepage-layout", "Configure", "Configure", "edit_posts", "voa-homepage-config", "voa_top_content_admin_config_menu");
}
add_action('admin_menu', 'voa_top_content_admin_menu_loader');

// ajax save options (layout)
add_action( 'wp_ajax_wpa_4471252017', 'wpa_4471252017_callback' );
#add_action( 'wp_ajax_nopriv_wpa_4471252017', 'wpa_4471252017_callback' );

add_action( 'wp_ajax_wpa_4475122017', 'wpa_4475122017_callback' );
#add_action( 'wp_ajax_nopriv_wpa_4475122017', 'wpa_4475122017_callback' );



add_action( 'init', 'voa_register_blog_menus' );

function voa_register_blog_menus() {
    register_nav_menus( array(
        'blog_header_menu' => 'Header Navigation Menu',
        'blog_footer_menu' => 'Footer List Menu'
    ) );
}



/*
currently unused, but in case we need to force-trim excerpts
smekosh note: REPLACE WITH https://developer.wordpress.org/reference/functions/wp_trim_words/ ?
*/
function trim_words($text, $words = 20) {
    $temp = str_word_count($text, 1);
    if( count($temp) <= $words ) return( $text );

    $shorter = array_slice($temp, 0, $words);
    $out = implode(" ", $shorter) . " ...";

    return( $out );
}



/*
mostly used to shorten article titles for Tweets
*/
function text_shortenerer( $text, $maxlen = 60, $more = '...' ) {
    if ( mb_strlen( $text ) <= $maxlen ) {
        return $text;
    } else {
        $text_maxlen = (int) $maxlen - mb_strlen( $more );
        $word_estimate = round( $text_maxlen / 5 );

        while ( mb_strlen( $text ) >= $text_maxlen ) {
            $text = wp_trim_words( $text, $word_estimate, '' );
            $word_estimate--;
        }

        return $text . $more;
    }
}



/*
get FB comment count for a URL; returns int
*/
function voa_fb_comment_count( $url ) {

    $count = 0;

    // random article with lots of FB comments for testing
    //$url = 'https://www.buzzfeed.com/claudiakoerner/four-people-in-custody-after-man-tortured-in-disturbing-face';

    if ($url) {
        $query =
            sprintf(
                'https://graph.facebook.com/v2.5/?id=%s&access_token=%d|%s',
                $url,
                FB_APP_ID,
                FB_APP_SECRET
        );

        // should probably switch this to curl...
        $result = json_decode( file_get_contents( $query ) );
        $count  = $result->share->comment_count;

        if (!$count) { $count = 0; }

    }

    return $count;
}


function voa_has_redirect_url( $post_id ) {
    $redirect = get_post_meta($post_id, "_voa_redirect_url", true);
    if( strlen($redirect) > 0 ) {
        return( trim($redirect) );
    } else {
        return (bool) false;
    }
}


function voa_language_service_tag( $post_id, $display = true ) {

    $cat = get_the_category( $post_id );

    if ( $cat[0]->slug && $cat[0]->slug != 'uncategorized' ) {

        if ( $display == true ) {

            echo '<div class="language-service lang-'.$cat[0]->slug.'"><div class="language-service-inner">'.$cat[0]->name.'</div></div>';

        } else {

            return $cat[0]->name;
        }

    }
}



/*
add ability to set focus point - right next to #remove-post-thumbnail
*/
function voa_top_content_widgets_init() {

    register_sidebar( array(
        'name'          => 'Article right sidebar',
        'id'            => 'sidebar_article_right',
        'before_widget' => '<div class="sidebar-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="sidebar-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => 'Archive Page Sidebar',
        'id'            => 'sidebar_archive_page',
        'before_widget' => '<div class="sidebar-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="sidebar-title">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => 'Basic Page Sidebar',
        'id'            => 'sidebar_basic_page',
        'before_widget' => '<div class="sidebar-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="sidebar-title">',
        'after_title'   => '</h2>',
    ) );
}

add_action( 'widgets_init', 'voa_top_content_widgets_init' );

function my_image_class_filter($classes) {
    return $classes . ' another-image-class';
}
add_filter('get_image_tag_class', 'my_image_class_filter');



// adds social media links to user profile page
// added by smekosh on 2014-08-26
function voa_social_media_links($profile_fields) {

    // Add new fields
    $profile_fields['facebook']   = 'Facebook URL';
    $profile_fields['googleplus'] = 'Google+ URL';
    $profile_fields['instagram']  = 'Instagram URL';
    $profile_fields['pinterest']  = 'Pinterest URL';
    $profile_fields['soundcloud'] = 'SoundCloud URL';
    $profile_fields['twitter']    = 'Twitter URL';
    $profile_fields['youtube']    = 'YouTube URL';

    return $profile_fields;
}

add_filter('user_contactmethods', 'voa_social_media_links');



function generate_missing_image_size( $image_ID ) {

    echo $image_ID;
    
    $image->ID = $image_ID;
    
    $fullsizepath = get_attached_file( $image->ID );

    if ( false === $fullsizepath || ! file_exists( $fullsizepath ) ) {
        //$this->die_json_error_msg( $image->ID, sprintf( __( 'The originally uploaded image file cannot be found at %s', 'regenerate-thumbnails' ), '<code>' . esc_html( $fullsizepath ) . '</code>' ) );
        echo 'uploaded file not found';
    }

    $metadata = wp_generate_attachment_metadata( $image->ID, $fullsizepath );

    if ( is_wp_error( $metadata ) ) {
        //$this->die_json_error_msg( $image->ID, $metadata->get_error_message() );
        echo 'wp error thrown';
    }
    
    if ( empty( $metadata ) ) {
        //$this->die_json_error_msg( $image->ID, __( 'Unknown failure reason.', 'regenerate-thumbnails' ) );
        echo 'unknown failure';
    }

    // If this fails, then it just means that nothing was changed (old value == new value)
    wp_update_attachment_metadata( $image->ID, $metadata );
    
    echo 'done';
}



include( "functions-amp.php" );
