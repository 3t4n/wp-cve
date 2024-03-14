(function ($) {

    "use strict";
     
    jQuery(document).on('ready', function () {
      //===== stellarnav js 



        /*===============================  
             Mobile Menu 
        ================================*/

        jQuery(".navbar-toggler").on('click', function () {
            jQuery(this).toggleClass('active');
        });

        jQuery(".navbar-nav a").on('click', function () {
            jQuery(".navbar-toggler").removeClass('active');
        });

        var subMenu = jQuery(".sub-menu-bar .navbar-nav .sub-menu");

        if (subMenu.length) {
            
            subMenu.parent('li').children('a').append(function () {
                return '<button class="sub-nav-toggler"> <i class="fa fa-angle-down"></i> </button>';
            });

            var subMenuToggler = jQuery(".sub-menu-bar .navbar-nav .sub-nav-toggler");

            subMenuToggler.on('click', function () {
                jQuery(this).parent().parent().children(".sub-menu").slideToggle();
                return false
            });

        }


        /*===============================  
             side menu Project 1
        ================================*/
        jQuery('.canvas_open').on('click', function () {
            jQuery('.offcanvas_menu_wrapper,.off_canvars_overlay').addClass('active')
        });

        jQuery('.canvas_close,.off_canvars_overlay').on('click', function () {
            jQuery('.offcanvas_menu_wrapper,.off_canvars_overlay').removeClass('active')
        });

        var $offcanvasNav = jQuery('.offcanvas_main_menu'),
            $offcanvasNavSubMenu = $offcanvasNav.find('.sub-menu');
        $offcanvasNavSubMenu.parent().prepend('<span class="menu-expand"><i class="fa fa-angle-down"></i></span>');

        $offcanvasNavSubMenu.slideUp();

        $offcanvasNav.on('click', 'li a, li .menu-expand', function (e) {
            var $this = $(this);
            if (($this.parent().attr('class').match(/\b(menu-item-has-children|has-children|has-sub-menu)\b/)) && ($this.attr('href') === '#' || $this.hasClass('menu-expand'))) {
                e.preventDefault();
                if ($this.siblings('ul:visible').length) {
                    $this.siblings('ul').slideUp('slow');
                } else {
                    $this.closest('li').siblings('li').find('ul:visible').slideUp('slow');
                    $this.siblings('ul').slideDown('slow');
                }
            }
            if ($this.is('a') || $this.is('span') || $this.attr('clas').match(/\b(menu-expand)\b/)) {
                $this.parent().toggleClass('menu-open');
            } else if ($this.is('li') && $this.attr('class').match(/\b('menu-item-has-children')\b/)) {
                $this.toggleClass('menu-open');
            }
        });


        if ($(".sticky-header__content").length) {
            let navContent = document.querySelector(".main-menu-9").innerHTML;
            let mobileNavContainer = document.querySelector(".sticky-header__content");
            mobileNavContainer.innerHTML = navContent;
        }
        $(window).on("scroll", function () {
            if ($(".stricked-menu").length) {
                var headerScrollPos = 130;
                var stricky = $(".stricked-menu");
                if ($(window).scrollTop() > headerScrollPos) {
                    stricky.addClass("stricky-fixed");
                } else if ($(this).scrollTop() <= headerScrollPos) {
                    stricky.removeClass("stricky-fixed");
                }
            }
        });
        
        

        //===== Sticky

        jQuery(window).on('scroll', function (event) {
            var scroll = jQuery(window).scrollTop();
            if (scroll < 110) {
                jQuery(".navigation").removeClass("sticky");
            } else {
                jQuery(".navigation").addClass("sticky");
            }
        });


        /*------------------Menu---------------------*/
        jQuery('.hamburger').on('click',function () {

            var $this = jQuery(this);

            if ($this.hasClass('is-active')) {
                jQuery('.fsmenu, .logo').removeClass('is-active');
                jQuery('.fsmenu, .logo').addClass('close-menu');
            } else {
                jQuery('.fsmenu, .logo').removeClass('close-menu');
                jQuery('.fsmenu, .logo').addClass('is-active');
            };
            $this.toggleClass('is-active');
        });

        jQuery(".fsmenu--list-element").on('hover',
            function () {
                jQuery(this).addClass('open');
                jQuery(this).removeClass('is-closing');
            },
            function () {
                jQuery(this).removeClass('open');
                jQuery(this).addClass('is-closing');
            }
        );


    });




})(jQuery);
