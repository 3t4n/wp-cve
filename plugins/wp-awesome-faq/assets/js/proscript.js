jQuery(document).ready(function($){
    $('.faq-color-picker').wpColorPicker();
    jQuery(".jltmaf-fonticon-picker, #jltmaf_mb_open_icon, #jltmaf_mb_close_icon").fontIconPicker();

    jQuery('.faq_collapse_style td').addClass('jltmaf-disabled');
    jQuery('.faq_collapse_style').append( jltmaf_admin_scripts.upgrade_pro );
    
    jQuery('.faq_heading_tags td').addClass('jltmaf-disabled');
    jQuery('.faq_heading_tags').append( jltmaf_admin_scripts.upgrade_pro );

});




// $(document).on('click', '.panel-heading span.clickable', function (e) {
//     var $this = $(this);
//     if (!$this.hasClass('panel-collapsed')) {
//         $this.parents('.panel').find('.panel-body').slideUp();
//         $this.addClass('panel-collapsed');
//         $this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
//     } else {
//         $this.parents('.panel').find('.panel-body').slideDown();
//         $this.removeClass('panel-collapsed');
//         $this.find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
//     }
// });
// $(document).on('click', '.panel div.clickable', function (e) {
//     var $this = $(this);
//     if (!$this.hasClass('panel-collapsed')) {
//         $this.parents('.panel').find('.panel-body').slideUp();
//         $this.addClass('panel-collapsed');
//        // $this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
//     } else {
//         $this.parents('.panel').find('.panel-body').slideDown();
//         $this.removeClass('panel-collapsed');
//       //  $this.find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
//     }
// });
// $(document).ready(function () {
//     $('.panel-heading span.clickable').click();
//     $('.panel div.clickable').click();
// });





            // var $advanceAccordion = $scope.find(".ma-advanced-accordion"),
            //     $accordionHeader = $scope.find(".ma-advanced-accordion-header"),
            //     $accordionType = $advanceAccordion.data("accordion-type"),
            //     $accordionSpeed = $advanceAccordion.data("toogle-speed");

            // // Open default actived tab
            // $accordionHeader.each(function () {
            //     if ($(this).hasClass("active-default")) {
            //         $(this).addClass("show active");
            //         $(this)
            //             .next()
            //             .slideDown($accordionSpeed);
            //     }
            // });

            // // Remove multiple click event for nested accordion
            // $accordionHeader.unbind("click");

            // $accordionHeader.click(function (e) {
            //     e.preventDefault();

            //     var $this = $(this);

            //     if ($accordionType === "accordion") {
            //         if ($this.hasClass("show")) {
            //             $this.removeClass("show active");
            //             $this.next().slideUp($accordionSpeed);
            //         } else {
            //             $this
            //                 .parent()
            //                 .parent()
            //                 .find(".ma-advanced-accordion-header")
            //                 .removeClass("show active");
            //             $this
            //                 .parent()
            //                 .parent()
            //                 .find(".ma-accordion-tab-content")
            //                 .slideUp($accordionSpeed);
            //             $this.toggleClass("show active");
            //             $this.next().slideDown($accordionSpeed);
            //         }
            //     } else {

            //         // For acccordion type 'toggle'
            //         if ($this.hasClass("show")) {
            //             $this.removeClass("show active");
            //             $this.next().slideUp($accordionSpeed);
            //         } else {
            //             $this.addClass("show active");
            //             $this.next().slideDown($accordionSpeed);
            //         }
            //     }
            // });
