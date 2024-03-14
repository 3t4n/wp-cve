(function($) {

    var Element_Ready_Adv_Accordion_Script_Handle = function($scope, $) {


        var $advanceAccordion = $scope.find(".element__ready__adv__accordion"),
            $accordionHeader = $scope.find(".element__ready__accordion__header"),
            $accordionType = $advanceAccordion.data("accordion-type"),
            $accordionSpeed = $advanceAccordion.data("toogle-speed");

        /*--------------------------------
            OPEN DEFAULT ACTIVED TAB
        ----------------------------------*/
        $accordionHeader.each(function() {
            if ($(this).hasClass("active-default")) {
                $(this).addClass("show active");
                $(this).next().slideDown($accordionSpeed);
            }
        });

        /*--------------------------------------------------
            REMOVE MULTIPLE CLICK EVENT FOR NESTED ACCORDION
        ----------------------------------------------------*/
        $accordionHeader.unbind("click");
        $accordionHeader.click(function(e) {
            e.preventDefault();
            var $this = $(this);

            if ($accordionType === "accordion") {
                if ($this.hasClass("show")) {
                    $this.removeClass("show active");
                    $this.next().slideUp($accordionSpeed);
                } else {
                    $this.parent().parent().find(".element__ready__accordion__header").removeClass("show active");
                    $this.parent().parent().find(".element__ready__accordion__content").slideUp($accordionSpeed);
                    $this.toggleClass("show active");
                    $this.next().slideToggle($accordionSpeed);
                }
            } else {
                /*-------------------------------
                    FOR ACCCORDION TYPE 'TOGGLE'
                --------------------------------*/
                if ($this.hasClass("show")) {
                    $this.removeClass("show active");
                    $this.next().slideUp($accordionSpeed);
                } else {
                    $this.addClass("show active");
                    $this.next().slideDown($accordionSpeed);
                }
            }
        });
    };

    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Adv_Accordion.default', Element_Ready_Adv_Accordion_Script_Handle);
       
    });
})(jQuery);