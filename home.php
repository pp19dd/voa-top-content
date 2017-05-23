<?php
/*
Template Name: Top Content Homepage
*/

// ajax mode doesn't require header/footer
if( !isset( $_GET['vday'])) get_header();

$posts_html = get_voa_top_posts();
$query_range = voa_layout_day_to_actual_date_range($posts_html["voa_day"]);
    
switch ( $query_range['range'] ) {
    case 'monthly':
        $post_breakup_heading = date( 'F Y', $query_range['start'] ); # January 2017
        break;
        
    case 'weekly':
        $post_breakup_heading = date( 'F j', $query_range['start'] ). " &ndash; ".date( 'F j', $query_range['end'] ); # January 9 - January 16
        break;
        
    default:
        // daily, like Editor's Picks
        $post_breakup_heading = date( 'l, F j', $query_range['start'] ); # Monday, January 9
}
?>
    
    <rows>
        <row class="rows_1">
            <breakup>
                <blank-space></blank-space>
                <h2><?php echo $post_breakup_heading; ?></h2>
                <blank-space></blank-space>
            </breakup>
        </row>
<?php foreach( $posts_html["posts"] as $posts ) { ?>
        <row class="card-row card-row-<?php echo count($posts) ?> <?php echo (get_voa_is_row_tall($posts) ? 'card-row-tall' : 'card-row-short'); ?>">
<?php
        foreach( $posts as $k => $post ) {
            set_query_var( "k", $k );
            get_template_part("partials/story-card");
        }
     ?>
        </row>
<?php } #foreach posts_html  ?>
    </rows>
<?php if( $posts_html["voa_day_previous"] !== false ) { ?>
    <div class="lazy-load-button" onclick="voa_load_page('<?php echo $posts_html["voa_day_previous"] ?>', this)"><span class="lazy-load-button-text">show more stories</span></div>
<?php } ?>

<?php
// ajax mode doesn't require header/footer
if( !isset( $_GET['vday'])) {
?>

<script>
/*
function voa_apply_events_cluster(selector) {

    jQuery("article", selector).mouseover(function() {
        jQuery(this).addClass("hovering");
    }).mouseout(function() {
        jQuery(this).removeClass("hovering");
    });
    jQuery("row.rows_3 article", selector).mouseover(function() {
        jQuery("excerpt, continue", this).stop().animate({opacity: 1}, 200);
    });
    jQuery("row.rows_3 article", selector).mouseout(function() {
        jQuery("excerpt, continue", this).stop().animate({opacity: 0}, 100);
    });


    jQuery("row.rows_1 article", selector).mouseover(function() {
        jQuery("continue", this).stop().animate({opacity: 1}, 200);
    });
    jQuery("row.rows_1 article", selector).mouseout(function() {
        jQuery("continue", this).stop().animate({opacity: 0}, 200);
    });

    jQuery("row.rows_2 article", selector).mouseover(function() {
        jQuery(this).addClass("hovering");
    }).mouseout(function() {
        jQuery(this).removeClass("hovering");
    });

    jQuery(
        "row.rows_3 article excerpt, " +
        "row.rows_3 article continue, " +
        "row.rows_1 article continue",
        selector
    ).css({
        opacity: 0
    });
}

voa_apply_events_cluster(jQuery("rows"));
*/

function voa_load_page(vday, that) {
    jQuery.get("?vday=" + vday, function(rh) {
        jQuery(that).attr("disabled", "disabled");
        jQuery(that).after(rh);
        jQuery(that).hide();

        var new_node = jQuery(that).next();
        //voa_apply_events_cluster( new_node );

        new_node.hide().slideToggle();
    });
}

</script>

<?php get_footer(); ?>

<?php } # end if !vday ?>
