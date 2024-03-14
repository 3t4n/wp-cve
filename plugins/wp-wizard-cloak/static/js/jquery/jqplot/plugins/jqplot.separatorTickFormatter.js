(function($) {
    $.jqplot.addSeparatorsNF = function(nStr, inD, outD, sep) {
        if ( undefined === inD ) { inD = '.'; }
        if ( undefined === outD ) { outD = '.'; }
        if ( undefined === sep ) { sep = ','; }
        nStr += '';
        var dpos = nStr.indexOf(inD);
        var nStrEnd = '';
        if (dpos != -1) {
                nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
                nStr = nStr.substring(0, dpos);
        }
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(nStr)) {
                nStr = nStr.replace(rgx, '$1' + sep + '$2');
        }
        return nStr + nStrEnd;
    };

    $.jqplot.SeparatorTickFormatter = function (format, val) {
        if (typeof val == 'number') {
            if (!format) {
                format = '%.1f';
            }
            return $.jqplot.addSeparatorsNF($.jqplot.sprintf(format, val));
        }
        else {
            return String(val);
        }
    };
})(jQuery);