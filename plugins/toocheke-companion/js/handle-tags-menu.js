jQuery(document).ready(function ($) {
    $.urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }
    $(".menu-icon-genres#menu-posts-genres")
        .removeClass("wp-not-current-submenu")
        .addClass("wp-has-current-submenu wp-menu-open")

    $("#menu-posts-genres a.wp-not-current-submenu")
        .removeClass("wp-not-current-submenu")
        .addClass("wp-has-current-submenu wp-menu-open")

    //console.log($.urlParam('taxonomy'));
    switch ($.urlParam('taxonomy')) {
        case 'series_tags':
            $('.toplevel_page_toocheke-menu')
                .removeClass('wp-not-current-submenu')
                .addClass('wp-has-current-submenu wp-menu-open')
                .find('li').has('a[href*="edit-tags.php?taxonomy=series_tags"]')
                .addClass('current');
            break;
        case 'genres':
            $('.toplevel_page_toocheke-menu')
                .removeClass('wp-not-current-submenu')
                .addClass('wp-has-current-submenu wp-menu-open')
                .find('li').has('a[href*="edit-tags.php?taxonomy=genres"]')
                .addClass('current');
            break;
        case 'collections':
            $('.toplevel_page_toocheke-menu')
                .removeClass('wp-not-current-submenu')
                .addClass('wp-has-current-submenu wp-menu-open')
                .find('li').has('a[href*="edit-tags.php?taxonomy=collections"]')
                .addClass('current');
            break;
        case 'chapters':
            $('.toplevel_page_toocheke-menu')
                .removeClass('wp-not-current-submenu')
                .addClass('wp-has-current-submenu wp-menu-open')
                .find('li').has('a[href*="edit-tags.php?taxonomy=chapters"]')
                .addClass('current');
            break;
        case 'comic_tags':
            $('.toplevel_page_toocheke-menu')
                .removeClass('wp-not-current-submenu')
                .addClass('wp-has-current-submenu wp-menu-open')
                .find('li').has('a[href*="edit-tags.php?taxonomy=comic_tags"]')
                .addClass('current');
            break;
        case 'comic_locations':
            $('.toplevel_page_toocheke-menu')
                .removeClass('wp-not-current-submenu')
                .addClass('wp-has-current-submenu wp-menu-open')
                .find('li').has('a[href*="edit-tags.php?taxonomy=comic_locations"]')
                .addClass('current');
            break;
        case 'comic_characters':
            $('.toplevel_page_toocheke-menu')
                .removeClass('wp-not-current-submenu')
                .addClass('wp-has-current-submenu wp-menu-open')
                .find('li').has('a[href*="edit-tags.php?taxonomy=comic_characters"]')
                .addClass('current');
            break;

        default:
        // code block
    }




});
