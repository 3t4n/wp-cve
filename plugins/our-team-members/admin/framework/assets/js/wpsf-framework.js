/**
 * -----------------------------------------------------------
 * WordPress-Settings-Framework Framework
 * A Lightweight and easy-to-use WordPress Options Framework
 * Copyright 2015 WordPress-Settings-Framework <info@codestarlive.com>
 * -----------------------------------------------------------
 */
;
(function ($, window, document, undefined) {
    'use strict';
    $.WPSFRAMEWORK = $.WPSFRAMEWORK || {};
    // caching selector
    var $wpsf_body = $('body');
    // caching variables
    var wpsf_is_rtl = $wpsf_body.hasClass('rtl');

    // WPSFRAMEWORK TAB NAVIGATION
    $.fn.WPSFRAMEWORK_TAB_NAVIGATION = function () {
        return this.each(function () {
            var wpsf_theme = $(this).attr("data-theme");
            var is_single_page = $(this).attr("data-single-page");

            if (wpsf_theme == 'modern') {
                var $this = $(this),
                    $nav = $this.find('.wpsf-nav'),
                    $reset = $this.find('.wpsf-reset'),
                    $reset_parent = $this.find('.wpsf_parent_section_id'),
                    $expand = $this.find('.wpsf-expand-all');

                $nav.find('ul:first a').on('click', function (e) {

                    e.preventDefault();

                    var $el = $(this),
                        $next = $el.next(),
                        $target = $el.data('section'),
                        $parent = $el.data("parent-section");


                    if ($next.is('ul')) {
                        $next.slideToggle('fast');
                        $el.closest('li').toggleClass('wpsf-tab-active');
                    } else {
                        if (is_single_page === 'yes') {
                            if ($parent) {
                                var $is_parent = $parent + '-';
                            } else {
                                var $is_parent = '';
                            }


                            $this.find('#wpsf-tab-' + $is_parent + $target).show().siblings().hide();
                            $nav.find('a').removeClass('wpsf-section-active');
                            $el.addClass('wpsf-section-active');
                            $reset.val($target);
                            $reset_parent.val($parent);
                        } else {
                            window.location.href = $el.attr("href");
                        }
                    }

                    $('body').trigger('wpsf_settings_nav_updated', [$parent,$target,$el]);

                });

                $expand.on('click', function (e) {
                    e.preventDefault();
                    $this.find('.wpsf-body').toggleClass('wpsf-show-all');
                    $(this).find('.fa').toggleClass('fa-eye-slash').toggleClass('fa-eye');
                });

            } else {

                var $this = $(this),
                    $main_nav = $this.find('.wpsf-main-nav'),
                    $sub_nav = $this.find('.wpsf-subnav-container'),
                    $reset_parent = $this.find('.wpsf_parent_section_id'),
                    $reset = $this.find('.wpsf-reset');

                $main_nav.find("a").on("click", function (e) {
                    e.preventDefault();

                    var $el = $(this),
                        $target = $el.data('section'),
                        $parent = $el.data('parent-section');

                    if (is_single_page === 'yes') {
                        var $cdiv = $this.find('#wpsf-tab-' + $target);
                        $cdiv.show().siblings().hide();

                        $main_nav.find("a").removeClass('nav-tab-active');
                        $el.addClass('nav-tab-active');
                        $reset.val($target);
                        var $parent = $cdiv.find("#wpsf-tab-" + $target + " a.current").data('section');
                        
                        $reset_parent.val($parent);
                    } else {
                        window.location.href = $el.attr("href");
                    }

                    $('body').trigger('wpsf_settings_nav_updated', [$target,$parent,$el]);

                });

                $sub_nav.find(".wpsf-submenus a").on("click", function (e) {
                    e.preventDefault();

                    var $el = $(this),
                        $target = $el.data('section'),
                        $parent = $el.data('parent-section');

                    $this.find('#wpsf-tab-' + $parent + '-' + $target).show().siblings().hide();
                    $sub_nav.find("#wpsf-tab-" + $parent + " a").removeClass('current');
                    $el.addClass('current');
                    $reset.val($target);
                    $reset_parent.val($parent);

                    $('body').trigger('wpsf_settings_nav_updated', [$parent,$target,$el]);
                })
            }
        })
    };

    // WPSFRAMEWORK DEPENDENCY
    $.WPSFRAMEWORK.DEPENDENCY = function (el, param) {

        // Access to jQuery and DOM versions of element
        var base = this;
        base.$el = $(el);
        base.el = el;

        base.init = function () {

            base.ruleset = $.deps.createRuleset();

            // required for shortcode attrs
            var cfg = {
                show: function (el) {
                    el.removeClass('hidden');
                },
                hide: function (el) {
                    el.addClass('hidden');
                },
                log: false,
                checkTargets: false
            };

            if (param !== undefined) {
                base.depSub();
            } else {
                base.depRoot();
            }

            $.deps.enable(base.$el, base.ruleset, cfg);

        };

        base.depRoot = function () {

            base.$el.each(function () {

                $(this).find('[data-controller]').each(function () {

                    var $this = $(this),
                        _controller = $this.data('controller').split('|'),
                        _condition = $this.data('condition').split('|'),
                        _value = $this.data('value').toString().split('|'),
                        _rules = base.ruleset;

                    $.each(_controller, function (index, element) {

                        var value = _value[index] || '',
                            condition = _condition[index] || _condition[0];

                        _rules = _rules.createRule('[data-depend-id="' + element + '"]', condition, value);
                        _rules.include($this);

                    });

                });

            });

        };

        base.depSub = function () {

            base.$el.each(function () {

                $(this).find('[data-sub-controller]').each(function () {

                    var $this = $(this),
                        _controller = $this.data('sub-controller').split('|'),
                        _condition = $this.data('sub-condition').split('|'),
                        _value = $this.data('sub-value').toString().split('|'),
                        _rules = base.ruleset;

                    $.each(_controller, function (index, element) {

                        var value = _value[index] || '',
                            condition = _condition[index] || _condition[0];

                        _rules = _rules.createRule('[data-sub-depend-id="' + element + '"]', condition, value);
                        _rules.include($this);

                    });

                });

            });

        };


        base.init();
    };

    $.fn.WPSFRAMEWORK_DEPENDENCY = function (param) {
        return this.each(function () {
            new $.WPSFRAMEWORK.DEPENDENCY(this, param);
        });
    };

    // WPSFRAMEWORK RESET CONFIRM
    $.fn.WPSFRAMEWORK_CONFIRM = function () {
        return this.each(function () {
            $(this).on('click', function (e) {
                if (!confirm('Are you sure?')) {
                    e.preventDefault();
                }
            });
        });
    };

    // WPSFRAMEWORK SAVE OPTIONS
    $.fn.WPSFRAMEWORK_SAVE = function () {
        return this.each(function () {

            var $this = $(this),
                $text = $this.data('save'),
                $value = $this.val(),
                $ajax = $('#wpsf-save-ajax');

            $(document).on('keydown', function (event) {
                if (event.ctrlKey || event.metaKey) {
                    if (String.fromCharCode(event.which).toLowerCase() === 's') {
                        event.preventDefault();
                        $this.trigger('click');
                    }
                }
            });

            $this.on('click', function (e) {

                if ($ajax.length) {

                    if (typeof tinyMCE === 'object') {
                        tinyMCE.triggerSave();
                    }

                    $this.prop('disabled', true).attr('value', $text);

                    var serializedOptions = $('.wpsf-form').serialize();

                    $.post('options.php', serializedOptions).error(function () {
                        alert('Error, Please try again.');
                    }).success(function () {
                        $this.prop('disabled', false).attr('value', $value);
                        $ajax.hide().fadeIn().delay(250).fadeOut();
                    });

                    e.preventDefault();

                } else {

                    $this.addClass('disabled').attr('value', $text);

                }

            });

        });
    };

    // ON WIDGET-ADDED RELOAD FRAMEWORK PLUGINS
    $.WPSFRAMEWORK.WIDGET_RELOAD_PLUGINS = function () {
        $(document).on('widget-added widget-updated', function (event, $widget) {
            $widget.WPSFRAMEWORK_RELOAD_PLUGINS();
            $widget.WPSFRAMEWORK_DEPENDENCY();
        });
    };

    // TOOLTIP HELPER
    $.fn.WPSFRAMEWORK_TOOLTIP = function () {
        return this.each(function () {
            var placement = (wpsf_is_rtl) ? 'right' : 'left';
            $(this).tooltip({
                html: true,
                placement: placement,
                container: 'body'
            });
        });
    };

    // CSFRAMEWORK STICKY HEADER
    $.fn.WPSFRAMEWORK_STICKYHEADER = function () {
        if (this.length) {
            var header = this,
                headerOffset = header.offset().top;

            if ($(this).hasClass('wpsf-sticky-header')) {
                $(window).on('scroll.wpsfStickyHeader', function () {
                    if ($(this).scrollTop() > 1) {
                        $('.wpsf-header').addClass("sticky").css('width', $('.wpsf-body').width());
                    } else {
                        $('.wpsf-header').removeClass("sticky").css('width', 'auto');
                    }
                });
            }

        }
    };

    // RELOAD FRAMEWORK PLUGINS
    $.fn.WPSFRAMEWORK_RELOAD_PLUGINS = function () {
        return this.each(function () {
            $('.chosen', this).WPSFRAMEWORK_FIELDS_CHOSEN();
            $('.select2', this).WPSFRAMEWORK_FIELDS_SELECT2();
            $('.icheck', this).WPSFRAMEWORK_FIELDS_ICHECK();
            $('.wpsf-field-image-select', this).WPSFRAMEWORK_FIELDS_IMAGE_SELECTOR();
            $('.wpsf-field-image', this).WPSFRAMEWORK_FIELDS_IMAGE_UPLOADER();
            $('.wpsf-field-gallery', this).WPSFRAMEWORK_FIELDS_IMAGE_GALLERY();
            $('.wpsf-field-sorter', this).WPSFRAMEWORK_FIELDS_SORTER();
            $('.wpsf-field-upload', this).WPSFRAMEWORK_FIELDS_UPLOADER();
            $('.wpsf-field-typography', this).WPSFRAMEWORK_FIELDS_TYPOGRAPHY();
            $('.wpsf-field-color-picker', this).WPSFRAMEWORK_FIELDS_COLORPICKER();
            $('.wpsf-help', this).WPSFRAMEWORK_TOOLTIP();
            $('.wpsf-wp-link', this).WPSFRAMEWORK_FIELDS_WPLINKS();
        });
    };

    $(window).load(function(){
        if($('.wpsf-wc-metabox-fields').length > 0){
            $('.wpsf-wc-metabox-fields').WPSFRAMEWORK_RELOAD_PLUGINS();
        }
    })

    $(document).ready(function () {
        $('.wpsf-framework').WPSFRAMEWORK_TAB_NAVIGATION();
        $('.wpsf-header').WPSFRAMEWORK_STICKYHEADER();
        $('.wpsf-reset-confirm, .wpsf-import-backup').WPSFRAMEWORK_CONFIRM();
        $('.wpsf-content, .wp-customizer, .widget-content, .wpsf-taxonomy').WPSFRAMEWORK_DEPENDENCY();
        $('.wpsf-field-group').WPSFRAMEWORK_FIELDS_GROUP();
        $('.wpsf-save').WPSFRAMEWORK_SAVE();
        $('.wpsf-taxonomy').WPSFRAMEWORK_FIELDS_TAXONOMY();
        $('.wpsf-framework, #widgets-right').WPSFRAMEWORK_RELOAD_PLUGINS();

        $.WPSFRAMEWORK_FIELDS.ICONS_MANAGER();
        $.WPSFRAMEWORK_FIELDS.SHORTCODE_MANAGER();
        $.WPSFRAMEWORK.WIDGET_RELOAD_PLUGINS();
    });

})(jQuery, window, document);
