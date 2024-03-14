var wd = jQuery.noConflict();
wd(document).ready(function(){             
    wd('.pdckl_WDConnect').click(function (event){
        var url = wd(this).attr("href");
        var windowName = "popUp";//wd(this).attr("name");
        var windowSize = "width=292,height=365,scrollbars=no";

        window.open(url, windowName, windowSize);
        event.preventDefault();
    });
    wd('#wd_submit').click(function (event){
        var x = screen.width/2 - 700/2;
        var y = screen.height/2 - 450/2;

        wd('#pdckl_gateway_form').prop('target', 'wdformpopup');
        window.open('', 'wdformpopup', 'width=292,height=365,scrollbars=no,left='+x+',top='+y);
    });
    wd('#cd_submit').click(function (event){
        var x = screen.width/2 - 700/2;
        var y = screen.height/2 - 450/2;

        wd('#pdckl_gateway_form').prop('target', 'wdformpopup');
        window.open('', 'wdformpopup', 'width=640,height=480,scrollbars=1,left='+x+',top='+y);
    });
    wd('#paypal_submit').click(function (event){
        wd('#pdckl_gateway_form').prop('target', 'PPDGFrame');
    });
});