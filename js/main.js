var Utils = {
    assocArrayJoin: function (obj, glue) {
        var str = '';
        $.each(obj, function (id, val) {
            str += val + glue;
        });
        str = str.substring(0, str.length - glue.length);
        return str;
    }
};

$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
    if (options.type.toLowerCase() == 'post') {
        options.data += '&ajax=true';
        if (options.data.charAt(0) == '&') {
            options.data = options.data.substr(1);
        }
    }

    var optionsSuccess = options.success;
    options.success = function (data, textStatus, jqXHR) {
        if (data) {
            if (data.state == "no-job-show-message") {
                alert(data.data.message);
                return;
            }
        }
        if (optionsSuccess) {
            return optionsSuccess(data, textStatus, jqXHR);
        }
    }
});

/*$(document).ajaxComplete(function (event, xhr, settings) {
    alert('4444');
    // if (settings.url == "ajax/test.html") {
    //     $(".log").text("Triggered ajaxSuccess handler. The Ajax response was: " +
    //         xhr.responseText);
    // }
});*/
