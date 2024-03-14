(function ($) {

    $(".acf-ct-toggle-btn").on("click",function() {
       var parent = $(this).closest('.acf-ct-toggle-wrap');
        parent.toggleClass('acf-ct-toggle-divs');
        $(this).toggleClass('open');

        if($(this).hasClass("open")){
            $(this).text('Hide');
        }else{
            $(this).text('Show All');
        }
    });

    $(".acf-ct-toggle-sql").on("click",function() {
        var preview = $(".acf-ct-sql-preview");
        preview.toggleClass("small-height");
        if(preview.hasClass("small-height")){
            $(this).text('Show SQL Query');
        }else{
            $(this).text('Hide SQL Query');
        }
    });


})(jQuery);