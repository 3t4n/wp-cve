(function($) {
"use strict";

    /* ===============================  Navbar Menu  =============================== */

    var wind = $(window);

    wind.on("scroll", function () {

        var bodyScroll = wind.scrollTop(),
            navbar = $(".better-navbar"),
            logodark = $(".better-navbar.change .logo> img.dark"),
            logowhite = $(".better-navbar.change .logo> img.white");

        if (bodyScroll > 300) {

            navbar.addClass("nav-scroll");
            logodark.removeClass("d-none");
            logowhite.addClass("d-none");

        } else {

            navbar.removeClass("nav-scroll");
            logodark.addClass("d-none");
            logowhite.removeClass("d-none");
        }


    });

    // Navigation menu
    if ($('.better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav li').length) {
        $(".better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav li").addClass("nav-item");
    }

    if ($('.better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav a').length) {
        $(".better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav a").addClass("nav-link custom-font");
    }

    if ($('.better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav .menu-item-has-children>a').length) {
        $(".better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav .menu-item-has-children>a").attr('data-toggle', 'dropdown');
        $(".better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav .menu-item-has-children>a").addClass("dropdown-toggle");
    }

    if ($('.better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav li ul').length) {
        $(".better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav li ul").addClass("dropdown-menu");
    }

    if ($('.better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav li ul a').length) {
        $(".better-navigation-menu.style-1 #navbarSupportedContent .navbar-nav li ul a").addClass("dropdown-item");
    }

    // Full navigation bar
    if ($('.better-navbar #navbarSupportedContent .navbar-nav li').length) {
        $(".better-navbar #navbarSupportedContent .navbar-nav li").addClass("nav-item");
    }

    // Full navigation bar
    if ($('.better-navbar #navbarSupportedContent .navbar-nav > ul').length) {
        $(".better-navbar #navbarSupportedContent .navbar-nav > ul").addClass("navbar-nav");
    }

    if ($('.better-navbar #navbarSupportedContent .navbar-nav a').length) {
        $(".better-navbar #navbarSupportedContent .navbar-nav a").addClass("nav-link");
        $(".better-navbar.style-1 #navbarSupportedContent .navbar-nav a").addClass("custom-font");
    }

    if ($('.better-navbar #navbarSupportedContent .navbar-nav .menu-item-has-children>a').length) {
        $(".better-navbar #navbarSupportedContent .navbar-nav .menu-item-has-children>a").attr('data-toggle', 'dropdown');
        $(".better-navbar #navbarSupportedContent .navbar-nav .menu-item-has-children>a").addClass("dropdown-toggle");
    }

    if ($('.better-navbar #navbarSupportedContent .navbar-nav li ul').length) {
        $(".better-navbar #navbarSupportedContent .navbar-nav li ul").addClass("dropdown-menu");
    }

    if ($('.better-navbar #navbarSupportedContent .navbar-nav li ul a').length) {
        $(".better-navbar #navbarSupportedContent .navbar-nav li ul a").addClass("dropdown-item");
    }

    $('.better-navbar.style-2 .search .icon').on('click', function () {
        $(".better-navbar.style-2 .search .search-form").fadeIn();
    });

    $('.better-navbar.style-2 .search .search-form .close').on('click', function () {
        $(".better-navbar.style-2 .search .search-form").fadeOut();
    });

    $('.better-navbar.style-2 .cart .icon').on('click', function () {
        $(".better-navbar.style-2 .cart .cart-side").fadeIn();
    });

    $('.better-navbar.style-2 .cart .cart-side .clos').on('click', function () {
        $(".better-navbar.style-2 .cart .cart-side").fadeOut();
    });


    function noScroll() {
        window.scrollTo(0, 0);
    }

    wind.on("scroll", function () {

        var bodyScroll = wind.scrollTop(),
            navbar = $(".topnav");

        if (bodyScroll > 300) {

            navbar.addClass("nav-scroll");

        } else {

            navbar.removeClass("nav-scroll");
        }
    });

})(jQuery);