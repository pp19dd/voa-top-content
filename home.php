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
        //$post_breakup_heading = date( 'F Y', $query_range['start'] ); # January 2017
        $post_breakup_heading = date_i18n( _x( 'F Y', 'date month range', 'voa-top-content' ), $query_range['start'] );
        break;
        
    case 'weekly':
        //$post_breakup_heading = date( 'F j', $query_range['start'] ). " &ndash; ".date( 'F j', $query_range['end'] ); # January 9 - January 16
        $post_breakup_heading = date_i18n( _x( 'F j', 'date week range', 'voa-top-content' ), $query_range['start'] ) . " &dash; " . date_i18n( _x( 'F j', 'date week range', 'voa-top-content' ), $query_range['end'] );
        break;
        
    default:
        // daily, like Editor's Picks
        //$post_breakup_heading = date( 'l, F j', $query_range['start'] ); # Monday, January 9
        $post_breakup_heading = date_i18n( _x( 'l, F j', 'date daily range', 'voa-top-content' ), $query_range['start'] );
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
        <?php $is_tall = get_voa_is_row_tall($posts); ?>
        <row class="card-row card-row-<?php echo count($posts); if ( count($posts) == 2 ) { echo ' ' . ($is_tall ? 'card-row-tall' : 'card-row-short'); } ?>">
        <?php
        foreach( $posts as $k => $post ) {
            set_query_var( 'k', $k );
            set_query_var( 
                'row_meta', 
                array( 
                    'count' => count($posts), 
                    'tall_short' => ($is_tall ? 'tall' : 'short')
                )
            );
            get_template_part("partials/story-card");
        }
     ?>
        </row>
<?php } #foreach posts_html  ?>
    </rows>
<?php if( $posts_html["voa_day_previous"] !== false ) { ?>
    <div class="lazy-load-button" onclick="voa_load_page('<?php echo $posts_html["voa_day_previous"] ?>', this)"><span class="lazy-load-button-text"><?php _ex( 'show more stories', 'button', 'voa-top-content' ); ?></span></div>
<?php } ?>

<?php
// ajax mode doesn't require header/footer
if( !isset( $_GET['vday'])) {
?>

<script>

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
