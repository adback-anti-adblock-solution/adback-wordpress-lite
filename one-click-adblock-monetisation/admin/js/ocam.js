(function ($) {
    'use strict';
    $(document).ready(function () {
        if(typeof vex === 'object') {
            vex.defaultOptions.className = 'vex-theme-default';
        }
        $("#ocam-logout").on('click', _logout);
        if ($("#ocam-login").length > 0) {
            $("#ocam-login-adback").on('click', loginAdback);
            $("#ocam-register-adback").on('click', registerAdback);
            $("#ocam-username,#ocam-password").on('keyup', function (e) {
                var code = e.which; // recommended to use e.which, it's normalized across browsers
                if (code == 13) {
                    e.preventDefault();
                    loginAdback();
                }
            });
        }
        if ($("#ocam-select-slug").length > 0) {
            $("#ocam-select-slug-save").on('click', saveSlug);
        }
        if ($("#ocam-settings").length > 0) {
            $("#ocam-settings-submit").on('click', saveMessage);
        }
        if ($("#ocam-go-settings").length > 0) {
            $("#ocam-go-settings-submit").on('click', saveGoMessage);
        }
    });
    function saveSlug() {
        if ($("#ocam-select-slug-field").val() == "") return;
        var data = {
            'action': 'saveSlug',
            'slug': $("#ocam-select-slug-field").val()
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
        if ($("#ocam-settings-header-text").val() == "" || $("#ocam-settings-close-text").val() == "" || $("#ocam-settings-message").val() == "") {
            vex.dialog.alert(trans_arr.oops + ' ' + trans_arr.all_the_fields_should_be_fill);
            return;
        }
        $("#ocam-settings-submit").prop('disabled', true);
        var data = {
            'action': 'saveMessage',
            'header-text': $("#ocam-settings-header-text").val(),
            'close-text': $("#ocam-settings-close-text").val(),
            'message': $("#ocam-settings-message").val(),
            'display': $("#ocam-settings-display").is(":checked"),
            'hide-admin': $("#ocam-settings-hide-admin").is(":checked")
        };
        $.post(ajaxurl, data, function (response) {
            var obj = JSON.parse(response);
            $("#ocam-settings-submit").prop('disabled', false);
            if (obj.done === true) {
                window.location.reload();
            } else {
                vex.dialog.alert(trans_arr.oops + ' ' + trans_arr.error);
            }
        });
    }
    function loginAdback() {
        $('#ocam-login-adback').prop('disabled', true);
        var callback = encodeURI(window.location.href);
        window.location.href = 'https://www.adback.co/tokenoauth/site?redirect_url=' + callback;
    }
    function registerAdback(event) {
        $('#ocam-register-adback').prop('disabled', true);
        var callback = encodeURI(window.location.href);
        var local = $(event.target).data('local');
        window.location.href = 'https://www.adback.co/'
            + local
            + '/register/?redirect_url='
            + callback
            + '&email=' + $(event.target).data('email')
            + '&website=' + $(event.target).data('site-url');
    }
    function saveGoMessage() {
        $("#ocam-go-settings-submit").prop('disabled', true);
        var data = {
            'action': 'saveGoMessage',
            'display': $("#ocam-go-settings-display").is(":checked"),
        };
        $.post(ajaxurl, data, function (response) {
            var obj = JSON.parse(response);
            $("#ocam-go-settings-submit").prop('disabled', false);
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
})(jQuery);
