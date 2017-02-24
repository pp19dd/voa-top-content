<?php

/* used to construct all stuff below */
function voa_top_content_get_meta_fields() {
    return( array(
        array(
            "caption" => "Byline",
            "field_name" => "voa-byline",
            "meta_key" => "_voa_byline",
        ),
        array(
            "caption" => "Dateline",
            "field_name" => "voa-dateline",
            "meta_key" => "_voa_dateline"
        ),
        array(
            "caption" => "Redirect URL",
            "validate" => "url",
            "type" => "textarea",
            "style" => "min-height:80px",
            "field_name" => "voa-redirect-url",
            "meta_key" => "_voa_redirect_url"
        ),
        array(
            "caption" => "Show Video Icon?",
            "field_name" => "voa-show-video-icon",
            "meta_key" => "_voa_show_video_icon",
            "type" => "radio",
            "options" => array(
                "yes" => "Yes",
                "no" => "No"
            ),
            "default" => "no"
        ),
    ));
}

add_action( 'add_meta_boxes', 'voa_top_content_meta_add' );
function voa_top_content_meta_add() {
    add_meta_box(
        "voa-top-content-meta-01",
        "VOA Metadata",
        "voa_top_content_meta",
        null,
        "side"
    );
}

add_action( 'save_post', 'voa_top_content_meta_save' );
function voa_top_content_meta_save( $post_id ) {
    $fields = voa_top_content_get_meta_fields();

    foreach( $fields as $field ) {
        if( !isset( $_POST[$field["field_name"]]) ) return;

        $value = $_POST[$field["field_name"]];
        filter_var( $value, FILTER_SANITIZE_STRING );
        $value = trim($value);

        update_post_meta( $post_id, $field["meta_key"], $value );
    }
}

function voa_top_content_meta_js_validate_url($container_id, $field_id) {

?>

<script>
(function() {
    var container = jQuery("#<?php echo $container_id ?>");
    var field = jQuery("#<?php echo $field_id ?>");

    function doValidate() {
        var url = field.val().trim();

        if( url.length === 0 ) {
            container.removeClass("voa-bad-field voa-bad-url");
            return;
        }

        if( url.split(" ").length !== 1 ) {
            container.addClass("voa-bad-field voa-bad-url");
            return;
        }

        if(
            url.substring(0,7) === "http://" ||
            url.substring(0,8) === "https://" ||
            url.substring(0,2) === "//"
        ) {
            container.removeClass("voa-bad-field voa-bad-url");
        } else {
            container.addClass("voa-bad-field voa-bad-url");
        }
    }

    field.on("keyup keydown change", function() {
        doValidate();
    });

    doValidate();
})();
</script>

<?php

}


function voa_top_content_meta($post) {
    $fields = voa_top_content_get_meta_fields();

    foreach( $fields as $k => $field ) {
        $value = get_post_meta($post->ID, $field["meta_key"], true);
        filter_var( $value, FILTER_SANITIZE_STRING );

        echo "<div class='voa-meta-field' id='voa_input_container_{$k}'>\n";

        if( $field["type"] == "radio" ) {
?>
    <p><?php echo $field["caption"] ?></p>

<?php foreach( $field["options"] as $option_value => $option_caption ) { ?>
    <label>
        <input
            id="voa_input_<?php echo $k ?>"
            name="<?php echo $field["field_name"] ?>"
            type="radio"
<?php
if(
    ($value === $option_value) ||
    ($value === "" && $field["default"] == $option_value)
) {
    echo "checked='checked'";
}
?>
            value="<?php echo $option_value ?>"
    /> <?php echo $option_caption ?></label>
<?php } ?>

<?php
        } elseif( $field["type"] == "textarea" ) {
?>

<p><?php echo $field["caption"] ?></p>
<textarea
    id="voa_input_<?php echo $k ?>"
    style="width:100%; <?php if( isset($field["style"]) ) { echo $field['style']; } ?>"
    name="<?php echo $field["field_name"] ?>"
    type="text"
    autocomplete="off"
    spellcheck="false"
><?php echo $value ?></textarea>

<?php
        } else {
    ?>
    <p><?php echo $field["caption"] ?></p>
    <input
        id="voa_input_<?php echo $k ?>"
        style="width:100%; <?php if( isset($field["style"]) ) { echo $field['style']; } ?>"
        name="<?php echo $field["field_name"] ?>"
        type="text"
        autocomplete="off"
        spellcheck="false"
        value="<?php echo $value ?>"
    />

    <?php
        } # type

        if( isset( $field["validate"]) ) {
            switch( $field["validate"] ) {
                case 'url':
                    voa_top_content_meta_js_validate_url(
                        "voa_input_container_{$k}",
                        "voa_input_{$k}"
                    );
                break;
            }
        }

        echo "</div>\n";

    } # foreach
}
