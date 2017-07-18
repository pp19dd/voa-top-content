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
    
    //load_theme_textdomain( 'voa-top-content', get_template_directory() . '/languages' );
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
                'full-width'           => 'full-width',
                'half-width-square'    => 'half-width-square',
                'half-width-mid'       => 'half-width-mid',
                'half-width-landscape' => 'half-width-landscape',
                'quarter-width-tall'   => 'quarter-width-tall',
                'quarter-width-mid'    => 'quarter-width-mid',
                'quarter-width-short'  => 'quarter-width-short'
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

    // categories for this post
    $categories = get_the_category( $post_id );
    
    $good_cats = array();
    
    foreach ( $categories as $cat ) {
        if ( $cat->slug != 'uncategorized' ) {
            $good_cats[] = $cat;
        }
    }
    
    if ( VOA_EDITORS_PICKS ) {
        
        $voa_lang_serv_cat_meta = get_term_by( 'slug', 'voa-language-services', 'category', 'ARRAY_A', 'raw' );
        $voa_lang_serv_cat_ID   = $voa_lang_serv_cat_meta['term_id'];
        
        $lang_cats = array();
        
        foreach ( $good_cats as $cat ) {
            if ( $cat->category_parent == $voa_lang_serv_cat_ID && $cat->slug != 'voa-english' ) {
                $lang_cats[] = $cat;
            }
        }
        
        $good_cats = $lang_cats;
    }
    
    if ( count( $good_cats ) > 0 ) {
        
        if ( $display == true ) {
            echo '<div class="language-service lang-'.$good_cats[0]->slug.'"><div class="language-service-inner">'.$good_cats[0]->name.'</div></div>';
        } else {
            return $good_cats[0]->name;
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



// wrapper for wp_get_attachment_image_src() that generates the requested image size if it doesn't already exist
function voa_wp_get_attachment_image_src( $image_ID, $named_image_size, $force_resize = false, $debug = false ) {
    $img = wp_get_attachment_image_src( $image_ID, $named_image_size );
    
    if ( $img[3] === false ) {
        voa_generate_missing_image_size( $image_ID, $named_image_size, $force_resize, $debug );
        $img = wp_get_attachment_image_src( $image_ID, $named_image_size );
    }
    
    return $img;
}



function voa_generate_missing_image_size( $image_ID, $named_image_size, $force_resize = false, $debug = false ) {
    
    global $_wp_additional_image_sizes;
    
    // get the originally uploaded image path
    $o_img = get_attached_file( $image_ID );
    
    if ($debug) { echo $o_img.'<br>'; }
    
    if ( false === $o_img || ! file_exists( $o_img ) ) {
        if ($debug) { echo sprintf( 'The originally uploaded image file cannot be found at %s', esc_html( $o_img ) ); }
        die;
    }
    
    $meta = wp_get_attachment_metadata( $image_ID );
    
    $o_img_meta_size_exists = in_array( $named_image_size, $meta['sizes'] );
    
    if ( $force_resize === true || $o_img_meta_size_exists === false ) {
        
        // this will NOT work for thumbnail, medium, medium_large, or large!
        $named_img_specs = array( 
            'width'  => $_wp_additional_image_sizes[$named_image_size]['width'], 
            'height' => $_wp_additional_image_sizes[$named_image_size]['height'], 
            'crop'   => $_wp_additional_image_sizes[$named_image_size]['crop']
        );
        
        // resize original uploaded image
        $o_resized = image_make_intermediate_size( 
            $o_img, 
            $named_img_specs['width'], 
            $named_img_specs['height'], 
            array(
                $named_img_specs['crop'][0], 
                $named_img_specs['crop'][1]
            )
        );
        
        if ( $o_resized === false ) {
        
            if ($debug) { 
                echo 'image_make_intermediate_size failed. maybe original image is too small to downsize?';
                pre( print_r( $o_resized ) );
            }
        
        } else {
            
            // add new cropped image to WP attachment metadata
            $meta['sizes'][$named_image_size] = $o_resized;
            
            // replace old metadata; failure means new meta == old meta
            wp_update_attachment_metadata( $image_ID, $meta );
        }
    }

    // REMOVE THIS LATER: regenerates ALL image sizes
    // $meta = wp_generate_attachment_metadata( $image_ID, $o_img );

    if ( is_wp_error( $meta ) ) {
        if ($debug) { echo $meta->get_error_message(); }
        die;
    }
    
    if ( empty( $meta ) ) {
        if ($debug) { echo 'unknown failure'; }
        die;
    }
    
    return (bool) true;
}



include( "functions-amp.php" );
