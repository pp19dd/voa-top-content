

function move_stories_out(row) {
    var stories = row.find("div.voa-layout-story");
    stories.each(function(k,v) {
        jQuery("available-stories").append( v );
    });
}

function voa_setColumns(row, columns) {

    // set new columns
    var placeholder = jQuery("placeholder", row);
    var num_virtual_cols_there = parseInt(placeholder.attr("columns"));
    var num_actual_cols_there = jQuery(".vtcmbdd", row).length;

    // move any stories potentially affected by this row reconfiguration
    move_stories_out(row);

    // rudimentary layout template
    var ht = [
        "<table class='vtcmb'><tr><td style='width:100%'>@</td></tr></table>",
        "<table class='vtcmb'><tr><td style='width:50%'>@</td><td>@</td></tr></table>",
        "<table class='vtcmb'><tr><td style='width:50%'>@</td><td style='width:25%'>@</td><td>@</td></tr></table>",
        "<table class='vtcmb'><tr><td style='width:25%'>@</td><td style='width:25%'>@</td><td style='width:25%'>@</td><td style='width:25%'>@</td></tr></table>",

        // flipped 3 is the 5th column
        "<table class='vtcmb'><tr><td style='width:25%'>@</td><td>@</td><td style='width:50%'>@</td></tr></table>",

        // tall 2
        "<table class='vtcmb'><tr><td style='width:50%;padding-top:30px;padding-bottom:30px'>@</td><td style='padding-top:30px;padding-bottom:30px'>@</td></tr></table>"
    ];

    // bugfix: 0 columns situation
    if( typeof ht[columns-1] == "undefined" ) {
        reset_draggable();
        return;
    }

    // flip direction of variant #3
    if( columns === 3 && num_actual_cols_there === 3 ) {
        if( num_virtual_cols_there === 3 ) {
            columns = 5;
            placeholder.attr("columns", columns);
        } else {
            columns = 3;
            placeholder.attr("columns", columns);
        }
    } else {
        placeholder.attr("columns", columns)
    }

    // flip direction of variant #2
    if( columns === 2 && num_actual_cols_there === 2 ) {
        if( num_virtual_cols_there === 2 ) {
            columns = 6;
            placeholder.attr("columns", columns);
        } else {
            columns = 2;
            placeholder.attr("columns", columns);
        }
    } else {
        placeholder.attr("columns", columns)
    }

    // reconstruct row from above template
    var row_html = ht[columns-1].split("@").join("<div class='vtcmbdd'></div>");
    placeholder.html( row_html );

    reset_draggable();
}

function voa_setRows(rows) {

    var all = jQuery("voa-row");

    // move any stories potentially affected by this row removal
    jQuery("voa-row:gt(" + rows + ")").each(function(k,v) {
        move_stories_out(jQuery(v));
    });

    // remove excess
    jQuery("voa-row:gt(" + rows + ")").remove();

    // add as needed
    if( all.length <= rows ) {
        for( var i = 0; i <= (rows - all.length); i++ )(function() {
            var first = jQuery("voa-row:eq(0)").clone(false);
            jQuery("placeholder", first).html(
                "<table class='vtcmb'><tr><td style='width:100%'><div class='vtcmbdd'></div></td></tr></table>"
            );
            jQuery(first).appendTo(jQuery("voa-layout"));
        })();
    }

    reset_draggable();
}

jQuery("#voa-change-rows").change(function() {
    var rows = parseInt(jQuery(this).val());
    if( rows === 0 ) {
        jQuery("voa-row").hide();
    } else {
        jQuery("voa-row").show();
    }
    voa_setRows(rows-1);
});

if( layout === false ) {
    jQuery("voa-row:eq(0)").hide();
} else {
    voa_setRows(layout.row_count - 1);
    jQuery("#voa-change-rows").val(layout.row_count);
    for( var i = 0; i < layout.row_count; i++ ) {
        var temp_columns = layout.rows[i];

        if(
            typeof layout.virtual_columns != "undefined" &&
            typeof layout.virtual_columns[i] != "undefined"
        ) {
            temp_columns = parseInt(layout.virtual_columns[i]);
        }
        voa_setColumns( jQuery("voa-row:eq(" + i + ")"), temp_columns );
    }

    // move stories into their places
    if( typeof layout.stories == "undefined" ) layout.stories = [];

    for( var i = 0; i < layout.stories.length; i++ )(function(row, ids) {
        for( var j = 0; j < ids.length; j++ )(function(id, num) {
            var node_story = jQuery(".voa-layout-story[data-id='" + id + "']");
            var node_destination = jQuery("voa-row:eq(" + row + ") .vtcmbdd:eq(" + num + ")");

            // check whether there is i or t information
            if(
                typeof layout.stories_cls != "undefined" &&
                typeof layout.stories_cls[i] != "undefined" &&
                typeof layout.stories_cls[i][j] != "undefined"
            ) {
                if( layout.stories_cls[i][j] == "t") {
                    node_story.addClass("text-heavy");
                }

                if( layout.stories_cls[i][j] == "to") {
                    node_story.addClass("text-only");
                }
            }

            jQuery(node_story).appendTo( node_destination );
        })(ids[j], j);
    })(i, layout.stories[i])

    repaint_tds();
}

// dnd
var manager = null;

function reset_draggable() {
    jQuery(document).ready(function() {

        try {
            manager.destroy();
            manager.containers = [ ];
        } catch( e ) {

        }

        var node = jQuery("available-stories")[0];

        manager = dragula([node], {
            accepts: function(el, target, source, sibling) {
                if( source == target ) return( false );

                if( jQuery(target).is("div.vtcmbdd") ) {
                    var buddies = jQuery("div.voa-layout-story:not(.gu-transit)", jQuery(target));
                    if( buddies.length === 0 ) {
                        return( true );
                    } else {
                        return( false );
                    }
                }

                return( true);
            }
        });

        manager.on("drop", function() {
            repaint_tds();
        });

        jQuery(".vtcmbdd").each(function(e) {
            manager.containers.push(this);
        });
    });
}

function voa_admin_last_url() {
    return( "?page=voa-homepage-layout" + extra );
}

function get_layout_payload() {
    var ret = {
        day: payload_day,
        rows: [],
        stories: [],
        stories_cls: [],
        virtual_columns: []
    }

    jQuery("voa-row").each(function(i, e) {
        var virtual_columns = jQuery("placeholder", this).attr("columns");
        var cells = jQuery(".vtcmbdd", this);
        ret.rows.push( cells.length );
        ret.virtual_columns.push( parseInt(virtual_columns) );
        var new_a = [];
        var new_b = [];
        cells.each(function(i2, e2) {
            var story = jQuery(".voa-layout-story", this);
            var story_id = jQuery(story).attr("data-id");

            var story_class = "i";
            if( story.hasClass("text-heavy") ) story_class = "t";
            if( story.hasClass("text-only") ) story_class = "to";

            if( typeof story_id === "undefined" ) story_id = "";

            // clumsy, but preserves old layout format
            new_a.push(story_id);
            new_b.push(story_class);
        });
        ret.stories.push( new_a );
        ret.stories_cls.push( new_b );
    });

    return( ret );
}

function get_warnings() {
    var empty_cells = jQuery("div.vtcmbdd:empty");
    return( empty_cells.length );
}

function save_layout() {
    jQuery("#save-layout").attr('disabled', true);
    var new_layout = {
        day: payload_day,
        row_count: jQuery("#voa-change-rows").val(),
        rows: [],
        stories: [],
        publish_drafts: (jQuery("#publish_drafts:checked").length == 0) ? "no" : "yes"
    };

    var temp = get_layout_payload();
    new_layout.rows = temp.rows;
    new_layout.stories = temp.stories;
    new_layout.stories_cls = temp.stories_cls;
    new_layout.virtual_columns = temp.virtual_columns;

    jQuery.post( ajaxurl, {
        action: "wpa_4471252017",
        mode: "save_layout",
        layout: new_layout,
    }).error(function() {
        alert( "error saving" );
    }).success( function(e) {
        window.location = voa_admin_last_url();
    });
}

function repaint_tds() {
    jQuery(".vtcmb td").each(function() {
        jQuery(this).removeClass("text-heavy-td text-only-td");
        var f = jQuery(this).find("div.text-heavy");
        if( f.length > 0 ) {
            jQuery(this).addClass("text-heavy-td");
        }

        var f = jQuery(this).find("div.text-only");
        if( f.length > 0 ) {
            jQuery(this).addClass("text-only-td");
        }
    });
}

function voa_layout_startup() {

    jQuery(".voa-layout-story").click(function() {

        if(
            !jQuery(this).hasClass("text-heavy") &&
            !jQuery(this).hasClass("text-only")
        ) {
            jQuery(this).addClass("text-heavy");
        } else {
            if( jQuery(this).hasClass("text-heavy") ) {
                jQuery(this).removeClass("text-heavy");
                jQuery(this).addClass("text-only");
            } else {
                jQuery(this).removeClass("text-only");
            }
        }
//        jQuery(this).toggleClass("text-heavy");

        // css hint
        //jQuery(this).closest("td").toggleClass("text-heavy-td");
        repaint_tds();
    });

    jQuery("#id-preview").click(function(e) {
        var temp = get_layout_payload();
        temp.preview_day = temp.day;
        delete( temp.day );

        var preview_url =
            home_url +
            "?preview_layout" +
            "&rand1=" + Math.random() +
            "&rand2=" + Math.random() +
            "&" + jQuery.param(temp);

        window.open( preview_url );
        return(false);
    });

    jQuery("#save-layout").click(function() {
        // jQuery("#save-layout").save-layout();
        // if( get_warnings() > 0 ) {
        //     alert( "Warning: empty spots");
        //     return( false );
        // }
        save_layout();
    });

    jQuery("#delete-layout").verifyClick(function() {
        jQuery.post( ajaxurl, {
            action: "wpa_4471252017",
            mode: "delete_layout",
            day: payload_day
        }).error(function() {
            alert( "error deleting layout" );
        }).success( function(e) {
            window.location = voa_admin_last_url();
        });
    });
}

jQuery(document).ready(function() {
    voa_layout_startup();
});
