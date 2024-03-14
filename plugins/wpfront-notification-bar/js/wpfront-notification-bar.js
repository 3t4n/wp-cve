/*
 WPFront Notification Bar Plugin
 Copyright (C) 2013, WPFront.com
 Website: wpfront.com
 Contact: syam@wpfront.com
 
 WPFront Notification Bar Plugin is distributed under the GNU General Public License, Version 3,
 June 2007. Copyright (C) 2007 Free Software Foundation, Inc., 51 Franklin
 St, Fifth Floor, Boston, MA 02110, USA
 
 */

(function () {
    //displays the notification bar
    window.wpfront_notification_bar = function (data, process) {
        var log = function (msg) {
            if (data.log) {
                console.log(data.log_prefix + ' ' + msg);
            }
        };

        if (typeof jQuery === "undefined" || (data.keep_closed && typeof Cookies === "undefined") || (data.set_max_views && typeof Cookies === "undefined")) {
            log('Waiting for ' + (typeof jQuery === "undefined" ? 'jQuery.' : 'Cookies.'));
            setTimeout(function () {
                wpfront_notification_bar(data, process);
            }, 100);
            return;
        }

        if (data.position == 2 && process !== true) {
            jQuery(function () {
                wpfront_notification_bar(data, true);
            });
            return;
        }

        var get_element_id = function (id) {
            return id + data.id_suffix;
        }

        var $ = jQuery;

        var keep_closed_cookie = data.keep_closed_cookie;

        var bar_views = 0;
        var max_views_cookie = data.max_views_cookie;

        var spacer = $(get_element_id("#wpfront-notification-bar-spacer")).removeClass('hidden');
        var bar = $(get_element_id("#wpfront-notification-bar"));
        var open_button = $(get_element_id("#wpfront-notification-bar-open-button"));

        //set the position
        if (data.position == 1) {
            log('Setting notification bar at top.');
            var top = 0;
            if (data.fixed_position && data.is_admin_bar_showing) {
                top = $("html").css("margin-top");
                if (top == "0px")
                    top = $("html").css("padding-top");
                top = parseInt(top);
            }
            if (data.fixed_position) {
                top += data.position_offset;
            }
            bar.css("top", top + "px");
            open_button.css("top", top + "px");
            spacer.css("top", data.position_offset + "px");
            var $body = $("body")
            var $firstChild = $body.children().first();
            if ($firstChild.hasClass("wpfront-notification-bar-spacer")) {
                while (true) {
                    var $next = $firstChild.next();
                    if ($next.hasClass("wpfront-notification-bar-spacer")) {
                        $firstChild = $next;
                    } else {
                        $firstChild.after(spacer);
                        break;
                    }
                }
            } else {
                $body.prepend(spacer);
            }

            $(function () {
                if ($body.children().first().hasClass("wpfront-notification-bar-spacer")) {
                    return;
                }

                if (data.fixed_position && !$body.children().first().is(spacer)) {
                    $body.prepend(spacer);
                }
            });
        } else {
            log('Setting notification bar at bottom.');
            var $body = $("body");
            if (!$body.children().last().is(spacer)) {
                $body.append(spacer);
            }
            $(function () {
                if (!$body.children().last().is(spacer)) {
                    $body.append(spacer);
                }
            });
        }

        var height = bar.height();
        if (data.height > 0) {
            height = data.height;
            bar.find("table, tbody, tr").css("height", "100%");
        }

        bar.height(0).css({"position": (data.fixed_position ? "fixed" : "relative"), "visibility": "visible"});
        open_button.css({"position": (data.fixed_position ? "fixed" : "absolute")});

        //function to set bar height based on options
        var closed = false;
        var user_closed = false;

        function setHeight(height, callback, userclosed) {
            callback = callback || $.noop;

            if (userclosed)
                user_closed = true;

            if (height == 0) {
                if (closed)
                    return;
                closed = true;
            } else {
                if (!closed)
                    return;
                closed = false;
            }

            if (height == 0 && data.keep_closed && userclosed) {
                if (data.keep_closed_for > 0)
                    Cookies.set(keep_closed_cookie, 1, {path: "/", expires: data.keep_closed_for});
                else
                    Cookies.set(keep_closed_cookie, 1, {path: "/"});
            }

            if (height !== 0 && data.set_max_views) {
                bar_views = Cookies.get(max_views_cookie);
                if (typeof bar_views === "undefined") {
                    bar_views = 0;
                } else {
                    bar_views = parseInt(bar_views);
                }
                if (data.max_views_for > 0) {
                    Cookies.set(max_views_cookie, bar_views + 1, {path: "/", expires: data.max_views_for});
                } else {
                    Cookies.set(max_views_cookie, bar_views + 1, {path: "/"});
                }
                log('Setting view count to ' + (bar_views + 1) + '.');
            }

            var fn = callback;
            callback = function () {
                fn();
                if (height > 0) {
                    //set height to auto if in case content wraps on resize
                    if (data.height == 0)
                        bar.height("auto");

                    if (data.display_open_button) {
                        log('Setting reopen button state to hidden.');
                        open_button.addClass('hidden');
                    }

                    closed = false;
                }

                if (height == 0 && data.display_open_button) {
                    log('Setting reopen button state to visible.');
                    open_button.removeClass('hidden');
                }
            };

            //set animation
            if (height > 0)
                log('Setting notification bar state to visible.');
            else
                log('Setting notification bar state to hidden.');

            if (data.animate_delay > 0) {
                bar.stop().animate({"height": height + "px"}, {
                    "duration": data.animate_delay * 1000,
                    "easing": "swing",
                    "complete": function () {
                        if (data.fixed_position) {
                            spacer.height(height);
                        }
                        handle_theme_sticky(height);
                        callback();
                    },
                    "step": function (progress) {
                        if (data.fixed_position) {
                            spacer.height(progress);
                        }
                        handle_theme_sticky(progress);
                    }
                });
            } else {
                bar.height(height);
                if (data.fixed_position) {
                    spacer.height(height);
                }
                handle_theme_sticky(height);
                callback();
            }
        }

        var theme_sticky_selector_position = null;
        var theme_sticky_interval_id = 0;
        var handle_theme_sticky_needed = data.fixed_position && data.theme_sticky_selector != "";
        function handle_theme_sticky(recursive) {
            if (!handle_theme_sticky_needed) {
                return 0;
            }

            if (recursive !== true) {
                clearInterval(theme_sticky_interval_id);
                var intervalCount = 0;
                theme_sticky_interval_id = setInterval(function () {
                    handle_theme_sticky(true);

                    intervalCount++;

                    if (intervalCount > 100) {
                        clearInterval(theme_sticky_interval_id);
                        return;
                    }

                }, 10);
            }

            if ($(data.theme_sticky_selector).length == 0) {
                return 0;
            }

            if (data.position == 1) {
                if (theme_sticky_selector_position === null) {
                    theme_sticky_selector_position = $(data.theme_sticky_selector).position().top;
                }
                if (bar.is(":visible")) {
                    $(data.theme_sticky_selector).css("top", (bar.height() + bar.position().top) + "px");
                } else {
                    $(data.theme_sticky_selector).css("top", theme_sticky_selector_position + "px");
                }
            }

            if (data.position == 2) {
                if (theme_sticky_selector_position === null) {
                    theme_sticky_selector_position = $(data.theme_sticky_selector).height() + parseFloat($(data.theme_sticky_selector).css("bottom"));
                }
                if (bar.is(":visible")) {
                    $(data.theme_sticky_selector).css("bottom", (bar.height() + parseFloat(bar.css("bottom"))) + "px");
                } else {
                    $(data.theme_sticky_selector).css("bottom", theme_sticky_selector_position + "px");
                }
            }

        }

        if (handle_theme_sticky_needed) {
            $(window).on("scroll resize", function () {
                handle_theme_sticky();
            });
        }

        if (data.close_button) {
            spacer.on('click', '.wpfront-close', function () {
                setHeight(0, null, true);
            });
        }

        //close button action
        if (data.button_action_close_bar) {
            spacer.on('click', '.wpfront-button', function () {
                setHeight(0, null, true);
            });
        }

        if (data.display_open_button) {
            spacer.on('click', get_element_id('#wpfront-notification-bar-open-button'), function () {
                setHeight(height);
            });
        }

        if (data.keep_closed) {
            if (Cookies.get(keep_closed_cookie)) {
                log('Keep closed enabled and keep closed cookie exists. Hiding notification bar.');
                setHeight(0, function(){
                    bar.removeClass('keep-closed');
                });
                return;
            }
        }
        bar.removeClass('keep-closed');

        if (data.set_max_views) {
            bar_views = Cookies.get(max_views_cookie);
            if (typeof bar_views === "undefined") {
                bar_views = 0;
            }
            if (bar_views >= data.max_views) {
                log('Reached max views, hiding notification bar.');
                setHeight(0, function(){
                    bar.removeClass('max-views-reached');
                });
                return;
            }
        }
        bar.removeClass('max-views-reached');

        closed = true;

        if (data.display_scroll) {
            log('Display on scroll enabled. Hiding notification bar.');
            setHeight(0);

            $(window).on('scroll', function () {
                if (user_closed)
                    return;

                if ($(this).scrollTop() > data.display_scroll_offset) {
                    setHeight(height);
                } else {
                    setHeight(0);
                }
            });
        } else {
            //set open after seconds and auto close seconds.
            log('Setting notification bar open event after ' + data.display_after + ' second(s).');
            setTimeout(function () {
                setHeight(height, function () {
                    if (data.auto_close_after > 0) {
                        log('Setting notification bar auto close event after ' + data.auto_close_after + ' second(s).');
                        setTimeout(function () {
                            setHeight(0, null, true);
                        }, data.auto_close_after * 1000);
                    }
                });
            }, data.display_after * 1000);
        }
    };
})();