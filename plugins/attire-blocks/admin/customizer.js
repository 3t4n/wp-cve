import Util from "../lib/util";

jQuery(window).load(function () {
    //show title on hove in post grid block
    jQuery(".post-module").hover(function () {
        jQuery(this).find(".description").stop().animate(
            {
                height: "toggle",
                opacity: "toggle"
            },
            300
        );
    });

    wp.customize('attire_options[primary_color]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-primary", newValue);
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-primary-rgb", Util.hexToRgba(newValue, 10));
        });
    });

    wp.customize('attire_options[primary_color_hover]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-primary-hover", newValue);
        });
    });

    wp.customize('attire_options[primary_color_active]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-primary-active", newValue);
        });
    });


    wp.customize('attire_options[secondary_color]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-secondary", newValue);
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-secondary-rgb", Util.hexToRgba(newValue, 10));
        });
    });

    wp.customize('attire_options[secondary_color_hover]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-secondary-hover", newValue);
        });
    });

    wp.customize('attire_options[secondary_color_active]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-secondary-active", newValue);
        });
    });


    wp.customize('attire_options[success_color]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-success", newValue);
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-success-rgb", Util.hexToRgba(newValue, 10));
        });
    });

    wp.customize('attire_options[success_color_hover]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-success-hover", newValue);
        });
    });

    wp.customize('attire_options[success_color_active]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-success-active", newValue);
        });
    });


    wp.customize('attire_options[danger_color]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-danger", newValue);
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-danger-rgb", Util.hexToRgba(newValue, 10));
        });
    });

    wp.customize('attire_options[danger_color_hover]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-danger-hover", newValue);
        });
    });

    wp.customize('attire_options[danger_color_active]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-danger-active", newValue);
        });
    });


    wp.customize('attire_options[warning_color]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-warning", newValue);
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-warning-rgb", Util.hexToRgba(newValue, 10));
        });
    });

    wp.customize('attire_options[warning_color_hover]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-warning-hover", newValue);
        });
    });

    wp.customize('attire_options[warning_color_active]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-warning-active", newValue);
        });
    });


    wp.customize('attire_options[info_color]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-info", newValue);
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-info-rgb", Util.hexToRgba(newValue, 10));
        });
    });

    wp.customize('attire_options[info_color_hover]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-info-hover", newValue);
        });
    });

    wp.customize('attire_options[info_color_active]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-info-active", newValue);
        });
    });


    wp.customize('attire_options[light_color]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-light", newValue);
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-light-rgb", Util.hexToRgba(newValue, 10));
        });
    });

    wp.customize('attire_options[light_color_hover]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-light-hover", newValue);
        });
    });

    wp.customize('attire_options[light_color_active]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-light-active", newValue);
        });
    });


    wp.customize('attire_options[dark_color]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-dark", newValue);
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-dark-rgb", Util.hexToRgba(newValue, 10));
        });
    });

    wp.customize('attire_options[dark_color_hover]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-dark-hover", newValue);
        });
    });

    wp.customize('attire_options[dark_color_active]', function (value) {
        value.bind(function (newValue) {
            jQuery(jQuery('iframe')[0]).contents().find('body')[0].style.setProperty("--color-dark-active", newValue);
        });
    });
});
