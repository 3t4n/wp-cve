g_ua = navigator.userAgent;
g_nS4 = document.layers ? 1 : 0;
g_iE = document.all && !window.innerWidth && g_ua.indexOf("MSIE") != -1 ? 1 : 0;
g_oP = g_ua.indexOf("Opera") != -1 && document.clear ? 1 : 0;
g_oP7 = g_oP && document.appendChild ? 1 : 0;
g_oP4 = g_ua.indexOf("Opera") != -1 && !document.clear;
g_kN = g_ua.indexOf("Konqueror") != -1 && parseFloat(g_ua.substring(g_ua.indexOf("Konqueror/") + 10)) < 3.1 ? 1 : 0;
g_count = g_content.length;
g_cur = 1;
g_cl = 0;
g_d = g_slideDirection ? -1 : 1;
g_TIM = 0;
g_fontSize2 = g_nS4 && navigator.platform.toLowerCase().indexOf("win") != -1 ? g_fontSizeNS4 : g_fontSize;
g_canPause = 0;
function g_getOS(a) {
    return g_iE ? document.all[a].style : g_nS4 ? document.layers["g_container"].document.layers[a] : document.getElementById(a).style;
}
function g_start() {
    var o, px;
    o = g_getOS("g_1");
    px = (g_oP && !g_oP7) || g_nS4 ? 0 : "px";
    if (parseInt(o.top) == g_paddingTop) {
        g_canPause = 1;
        if (g_count > 1) g_TIM = setTimeout("g_canPause=0;g_slide()", g_timeout);
        return;
    }
    o.top = (parseInt(o.top) - g_slideStep * g_d) * g_d > g_paddingTop * g_d ? parseInt(o.top) - g_slideStep * g_d + px : g_paddingTop + px;
    if (g_oP && o.visibility.toLowerCase() != "visible") o.visibility = "visible";
    setTimeout("g_start()", g_slideSpeed);
}
function g_slide() {
    var o, o2, px;
    o = g_getOS("g_" + g_cur);
    o2 = g_getOS("g_" + (g_cur < g_count ? g_cur + 1 : 1));
    px = (g_oP && !g_oP7) || g_nS4 ? 0 : "px";
    if (parseInt(o2.top) == g_paddingTop) {
        if (g_oP) o.visibility = "hidden";
        o.top = g_height * g_d + px;
        g_cur = g_cur < g_count ? g_cur + 1 : 1;
        g_canPause = 1;
        g_TIM = setTimeout("g_canPause=0;g_slide()", g_timeout);
        return;
    }
    if (g_oP && o2.visibility.toLowerCase() != "visible") o2.visibility = "visible";
    if ((parseInt(o2.top) - g_slideStep * g_d) * g_d > g_paddingTop * g_d) {
        o.top = parseInt(o.top) - g_slideStep * g_d + px;
        o2.top = parseInt(o2.top) - g_slideStep * g_d + px;
    } else {
        o.top = -g_height * g_d + px;
        o2.top = g_paddingTop + px;
    }
    setTimeout("g_slide()", g_slideSpeed);
}
if (g_nS4 || g_iE || g_oP || (document.getElementById && !g_kN && !g_oP4)) {
    document.write(
        "<style>.vnewsticker,a.vnewsticker{font-family:" +
            g_font +
            ";font-size:" +
            g_fontSize2 +
            ";color:" +
            g_fontColor +
            ";text-decoration:" +
            g_textDecoration +
            ";font-weight:" +
            g_fontWeight +
            "}a.vnewsticker:hover{font-family:" +
            g_font +
            ";font-size:" +
            g_fontSize2 +
            ";color:" +
            g_fontColorHover +
            ";text-decoration:" +
            g_textDecorationHover +
            "}</style>"
    );
    g_temp =
        "<div " +
        (g_nS4 ? "name" : "id") +
        "=g_container style='position:" +
        g_position +
        ";top:" +
        g_top +
        "px;left:" +
        g_left +
        "px;width:" +
        g_width +
        "px;height:" +
        g_height +
        "px;background:" +
        g_bgColor +
        ";layer-background" +
        (g_bgColor.indexOf("url(") == 0 ? "-image" : "-color") +
        ":" +
        g_bgColor +
        ";clip:rect(0," +
        g_width +
        "," +
        g_height +
        ",0);overflow:hidden'>" +
        (g_iE ? "<div style='position:absolute;top:0px;left:0px;width:100%;height:100%;clip:rect(0," + g_width + "," + g_height + ",0)'>" : "");
    for (g_i = 0; g_i < g_count; g_i++)
        g_temp +=
            "<div " +
            (g_nS4 ? "name" : "id") +
            "=g_" +
            (g_i + 1) +
            " style='position:absolute;top:" +
            g_height * g_d +
            "px;left:" +
            g_paddingLeft +
            "px;width:" +
            (g_width - g_paddingLeft * 2) +

            "px;height:" +
            (g_height - g_paddingTop * 2) +
            "px;clip:rect(0," +
            (g_width - g_paddingLeft * 2) +
            "," +
            (g_height - g_paddingTop * 2) +
            ",0);overflow:hidden" +
            (g_oP ? ";visibility:hidden" : "") +
            ";text-align:" +
            g_textAlign +
            "' class=vnewsticker>" +
            (!g_nS4
                ? "<table width=" +
                  (g_width - g_paddingLeft * 2) +
                  " height=" +
                  (g_height - g_paddingTop * 2) +
                  " cellpadding=0 cellspacing=0 border=0><tr><td style='border : 0px solid' width=" +
                  (g_width - g_paddingLeft * 2) +
                  " height=" +
                  (g_height - g_paddingTop * 2) +
                  " align=" +
                  g_textAlign +
                  " valign=" +
                  g_textVAlign +
                  " class=vnewsticker>"
                : "") +
            (g_content[g_i][0] != ""
                ? "<a href='" +
                  g_content[g_i][0] +
                  "' target='" +
                  g_content[g_i][2] +
                  "' class=vnewsticker" +
                  (g_pauseOnMouseOver ? " onmouseover='if(g_canPause&&g_count>1){clearTimeout(g_TIM);g_cl=1}' onmouseout='if(g_canPause&&g_count>1&&g_cl)g_TIM=setTimeout(\"g_canPause=0;g_slide();g_cl=0\"," + g_timeout + ")'" : "") +
                  ">"
                : "<span" +
                  (g_pauseOnMouseOver ? " onmouseover='if(g_canPause&&g_count>1){clearTimeout(g_TIM);g_cl=1}' onmouseout='if(g_canPause&&g_count>1&&g_cl)g_TIM=setTimeout(\"g_canPause=0;g_slide();g_cl=0\"," + g_timeout + ")'" : "") +
                  ">") +
            g_content[g_i][1] +
            (g_content[g_i][0] != "" ? "</a>" : "</span>") +
            (!g_nS4 ? "</td></tr></table>" : "") +
            "</div>";
    g_temp += (g_iE ? "</div>" : "") + "</div>";
    document.write(g_temp);
    setTimeout("g_start()", 1000);
    if (g_nS4)
        onresize = function () {
            location.reload();
        };
}
