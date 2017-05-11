
jQuery.fn.verifyClick = function(click_event) {
    var that = this;

    this.mouseout(function() {
        if( that.hasClass("action-verified") ) {
            that.removeClass("action-verified action-not-yet");
            that.text( that.attr("original-caption") );
            that.attr("disabled", null);
        }
    });

    this.click(function() {
        if( !that.hasClass("action-verified") ) {
            that.addClass("action-verified action-not-yet");
            that.attr("disabled", "disabled");
            that.attr("original-caption", that.text());
            that.text("Are you sure?  ");

            setTimeout(function() {
                if( that.hasClass("action-not-yet") ) {
                    that.removeClass("action-not-yet");
                    that.attr("disabled", null);
                }
            }, 2000);
        } else {
            click_event();
            that.removeClass("action-verified action-not-yet");
        }
    });

    return this;
};
