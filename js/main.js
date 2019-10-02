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
