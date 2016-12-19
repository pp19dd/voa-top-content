<?php
require_once( "functions-layout.php" );
require_once( "functions-widgets.php" );

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

function theme_slug_setup() {
    add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'theme_slug_setup' );

/*
voa_top_content_admin_menu function is located in functions-layout.php
*/
function voa_top_content_admin_menu_loader() {
    add_filter("admin_head", "voa_top_content_admin_menu_css" );
    add_menu_page("Homepage Layout", "Homepage Layout", "edit_posts", "voa-homepage-layout", "voa_top_content_admin_menu");
}
add_action('admin_menu', 'voa_top_content_admin_menu_loader');

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



/*
edit datelines are a meta field
*/

add_action( 'add_meta_boxes', 'voa_top_content_meta_dateline_add' );
function voa_top_content_meta_dateline_add() {
    add_meta_box(
        "voa-top-content-meta-02",
        "Dateline",
        "voa_top_content_meta_dateline",
        null,
        "side"
    );
}

add_action( 'save_post', 'voa_top_content_meta_dateline_save' );
function voa_top_content_meta_dateline_save( $post_id ) {
    if( !isset( $_POST['voa-dateline']) ) return;

    $value = $_POST['voa-dateline'];
    filter_var( $value, FILTER_SANITIZE_STRING );
    $value = trim($value);

    update_post_meta( $post_id, "_voa_dateline", $value );
}

function voa_top_content_meta_dateline($post) {
    $value = get_post_meta($post->ID, "_voa_dateline", true);
    filter_var( $value, FILTER_SANITIZE_STRING );
?>
    <input
        style="width:100%"
        name="voa-dateline"
        type="text"
        autocomplete="off"
        spellcheck="false"
        value="<?php echo $value ?>"
    />
<?php
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
}

add_action( 'widgets_init', 'voa_top_content_widgets_init' );

function my_image_class_filter($classes) {
    return $classes . ' another-image-class';
}
add_filter('get_image_tag_class', 'my_image_class_filter');
