<?php
require_once( "functions-layout.php" );
require_once( "functions-widgets.php" );
require_once( "functions-meta.php" );

show_admin_bar( false );
add_theme_support( "post-thumbnails" );
remove_action( "wp_head", "print_emoji_detection_script", 7 );
remove_action( "wp_print_styles", "print_emoji_styles" );
wp_enqueue_script( "jquery" );

// crop: x can be left, center, right
//       y can be top, center, bottom
add_image_size( 'hero-intro',          2400, 1152, array("center", "center") );
add_image_size( 'full-width',          1200,  576, array("center", "center") );
add_image_size( 'half-width-square',    576,  576, array("center", "center") );
add_image_size( 'half-width-landscape', 576,  207, array("center", "top") );
add_image_size( 'quarter-width-short',  276,  207, array("center", "center") );

//add_image_size( 'quarter-width-small', 273, 218, array("left", "top") );

/******* DONE --- but saving for reference; numbers slightly different above
image sizes     w  x  h

cards

full-img    = 1200 x 576
half-img    =  576 x 576
quarter-img =  276 x 576

full-txt    =  576 x 576 (same as half-img)
half-txt    =  576 x 275
quarter-txt =  276 x 207 (card-row-2 card-row-short card-txt will use this size too)

card row 2, short:
half-img    =  576 x 207 (576x275 is too big, i think?)
half-txt    =  276 x 207

articles

hero-intro  = 2400 x 1152
full width  =  800 x  500
half width  =  400 x  250
*******/

add_filter( 'image_size_names_choose', 'top_content_custom_sizes' );

function voa_theme_setup_options() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', array( 'search-form' ) );
}
add_action( 'after_setup_theme', 'voa_theme_setup_options' );

/*
voa_top_content_admin_menu function is located in functions-layout.php
*/
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
