var $j = jQuery.noConflict();
$j('document').ready(function()
{
    eLinks = $j('a.geo-address');
    eLinks.each(function(){
        eLink = $j(this);
        str = eLink.attr('href').replace(/.*=/,'')
            .replace(/[a-z0-9]/ig, function(chr) {
                var cc = chr.charCodeAt(0);
                if (cc >= 65 && cc <= 90) cc = 65 + ((cc - 52) % 26);
                else if (cc >= 97 && cc <= 122) cc = 97 + ((cc - 84) % 26);
                return String.fromCharCode(cc);
            });
        str = decodeURIComponent(str).replace(/A/g,'.').replace(/N/g,'@');
        eLink.attr('href', 'mailto:'+str);
    });

});