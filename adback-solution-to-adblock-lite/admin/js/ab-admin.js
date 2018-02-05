(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    function loginAdback() {
        $('#ab-login-adback').prop('disabled', true);
        var callback = encodeURI(window.location.href);
        window.location.href = 'https://www.adback.co/tokenoauth/site?redirect_url=' + callback;
    }

    function registerAdback(event) {
        $('#ab-register-adback').prop('disabled', true);
        var callback = encodeURI(window.location.href);
        window.location.href = 'https://www.adback.co/en/register/?redirect_url='
            + callback
            + '&email=' + $(event.target).data('email')
            + '&website=' + $(event.target).data('site-url');
    }

    function saveSlug() {
        if ($("#ab-select-slug-field").val() == "") return;

        var data = {
            'action': 'saveSlug',
            'slug': $("#ab-select-slug-field").val()
        };

        $.post(ajaxurl, data, function (response) {
            var obj = JSON.parse(response);
            if (obj.done === true) {
                window.location.reload();
            } else {
                vex.dialog.alert(trans_arr.oops + ' ' + trans_arr.error);
            }
        });
    }

    function saveMessage() {
        if ($("#ab-settings-header-text").val() == "" || $("#ab-settings-close-text").val() == "" || $("#ab-settings-message").val() == "") {
            vex.dialog.alert(trans_arr.oops + ' ' + trans_arr.all_the_fields_should_be_fill);
            return;
        }

        $("#ab-settings-submit").prop('disabled', true);
        var data = {
            'action': 'saveMessage',
            'header-text': $("#ab-settings-header-text").val(),
            'close-text': $("#ab-settings-close-text").val(),
            'message': $("#ab-settings-message").val(),
            'display': $("#ab-settings-display").is(":checked"),
            'hide-admin': $("#ab-settings-hide-admin").is(":checked")
        };

        $.post(ajaxurl, data, function (response) {
            var obj = JSON.parse(response);
            $("#ab-settings-submit").prop('disabled', false);
            if (obj.done === true) {
                window.location.reload();
            } else {
                vex.dialog.alert(trans_arr.oops + ' ' + trans_arr.error);
            }
        });
    }

    function saveGoMessage() {
        $("#ab-go-settings-submit").prop('disabled', true);
        var data = {
            'action': 'saveGoMessage',
            'display': $("#ab-go-settings-display").is(":checked"),
        };

        $.post(ajaxurl, data, function (response) {
            var obj = JSON.parse(response);
            $("#ab-go-settings-submit").prop('disabled', false);
            if (obj.done === true) {
                window.location.reload();
            } else {
                vex.dialog.alert(trans_arr.oops + ' ' + trans_arr.error);
            }
        });
    }

    function _logout() {
        var data = {
            'action': 'ab_logout'
        };

        $.post(ajaxurl, data, function (response) {
            var obj = JSON.parse(response);
            if (obj.done === true) {
                window.location.reload();
            } else {
                vex.dialog.alert(trans_arr.oops + ' ' + trans_arr.error);
            }
        });
    }

    $(document).ready(function () {
        // Alert
        if(typeof vex === 'object') {
            vex.defaultOptions.className = 'vex-theme-default';
        }

        $("#ab-logout").on('click', _logout);

        if ($("#ab-login").length > 0) {
            $("#ab-login-adback").on('click', loginAdback);
            $("#ab-register-adback").on('click', registerAdback);


            $("#ab-username,#ab-password").on('keyup', function (e) {
                var code = e.which; // recommended to use e.which, it's normalized across browsers
                if (code == 13) {
                    e.preventDefault();
                    loginAdback();
                }
            });
        }

        if ($("#ab-select-slug").length > 0) {
            $("#ab-select-slug-save").on('click', saveSlug);
        }

        if ($("#ab-settings").length > 0) {
            $("#ab-settings-submit").on('click', saveMessage);
        }

        if ($("#ab-go-settings").length > 0) {
            $("#ab-go-settings-submit").on('click', saveGoMessage);
        }

    });


})(jQuery);
