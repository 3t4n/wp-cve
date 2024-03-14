/**
 * Created by Michael on 19/06/2015.
 */

jQuery(document).ready(function($) {


    $('.navbar-collapse').on('show.bs.collapse', function () {
        //Stack menu when collapsed
        $('.nav-pills, .nav-tabs').addClass('nav-stacked').addClass(' zznavbar-nav');
        $('.btn-group.navbar-btn, .navbar-btn').addClass('btn-group-vertical').addClass(' zznavbar-nav');

    }).on('hide.bs.collapse', function () {
        //Unstack menu when not collapsed
        $('.nav-pills, .nav-tabs').removeClass('nav-stacked').removeClass(' navbar-nav');
        $('.btn-group.navbar-btn, .navbar-btn').removeClass('btn-group-vertical').removeClass(' navbar-nav');
    });

    //Manages the onclick event for Submenus
    $('ul.dropdown-menu [data-toggle=dropdown]:not(span)').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).parent().siblings().removeClass('open');
        $(this).parent().toggleClass('open');
    });

    $('ul.dropdown-menu span[data-toggle=dropdown]').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).parent().parent().siblings().removeClass('open');
        $(this).parent().parent().toggleClass('open');
    });

});