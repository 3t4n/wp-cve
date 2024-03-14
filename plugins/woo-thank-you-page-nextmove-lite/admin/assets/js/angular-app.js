(function ($, window) {
    function sortObj(obj, order) {
        "use strict";
        var key, tempArry = [], i, tempObj = {};

        for (key in obj) {
            tempArry.push(key);
        }

        tempArry.sort(
            function (a, b) {
                return a.toLowerCase().localeCompare(b.toLowerCase());
            }
        );

        if (order === 'desc') {
            for (i = tempArry.length - 1; i >= 0; i--) {
                tempObj[tempArry[i]] = obj[tempArry[i]];
            }
        } else {
            for (i = 0; i < tempArry.length; i++) {
                tempObj[tempArry[i]] = obj[tempArry[i]];
            }
        }

        return tempObj;
    }

    window.xlwcty_return_font_val = function ($icon_num) {
        if ($icon_num.length === 3) {
            return $icon_num;
        } else if ($icon_num.length === 2) {
            return '0' + $icon_num;
        } else if ($icon_num.length === 1) {
            return '00' + $icon_num;
        } else {
            return '001';
        }
    };

    var app = angular.module('xlwcty-builder', []);

    app.controller('xlwcty_builder_pre', function ($scope) {
        var sortableEnable = false, count = 0;
        $scope.builder_componets = xlwcty_layout_preview[0];
        $scope.builder_template = xlwcty_layout_preview_template[0];
        var checked_html = '<div class="xlwcty_checked"><div class="dashicons xl-dashicons-yes">&nbsp;</div></div>';
        var defaultComponents = function () {
            return {basic: {first: []}, two_column: {first: [], second: [], third: []}, mobile: {first: []}};
        };
        var serchComponents = function (obj, key) {
            var find = {index: -1, "column": ''};
            if (typeof obj != "undefined") {
                if (Object.keys(obj).length > 0) {
                    for (var cp in obj) {
                        var components = obj[cp];
                        if (components.length > 0) {
                            for (var i = 0; i < components.length; i++) {
                                if (components[i].slug == key) {
                                    find.index = i;
                                    find.column = cp;
                                    return find;
                                }
                            }
                        }
                    }
                }
            }
            return find;
        };

        var removedHashKey = function (obj) {
            if (typeof obj != "undefined") {
                if (Object.keys(obj).length > 0) {
                    for (var cp in obj) {
                        var components = obj[cp];
                        if (components.length > 0) {
                            for (var i = 0; i < components.length; i++) {
                                delete components[i].$$hashKey;
                                obj[cp][i] = components[i];
                            }
                        }
                    }
                }
            }
            return obj;
        };
        var update_layout = function () {
            var saveDComponents = defaultComponents();
            saveDComponents.basic = removedHashKey($scope.builder_componets.basic);
            saveDComponents.two_column = removedHashKey($scope.builder_componets.two_column);
            saveDComponents.mobile = removedHashKey($scope.builder_componets.mobile);

            if ($scope.builder_template == "basic") {
                count = saveDComponents[$scope.builder_template].first.length;
            } else if ($scope.builder_template == "two_column") {
                count = (saveDComponents[$scope.builder_template].first.length) + (saveDComponents[$scope.builder_template].second.length) + (saveDComponents[$scope.builder_template].third.length);
            }

            if (count > 0) {
                isEmptyLayout = false;
            } else {
                isEmptyLayout = true;
            }
            if (XLWCTY_ContentLoaded === true) {
                Formchanges = true;
            }

            document.getElementById("_xlwcty_builder_layout").value = JSON.stringify(saveDComponents);
            document.getElementById("_xlwcty_builder_template").value = $scope.builder_template == "undefined" ? "basic" : $scope.builder_template;
            if (document.getElementById("_xlwcty_choose_template") != null) {
                var get_template = document.getElementById("_xlwcty_choose_template").value;
                document.getElementById("_wp_page_template").value = get_template;
            }
            $scope.$apply();
        };
        var getAttribute = function (el) {
            var out = {}, id = "", name = "", component = "";
            id = el.getAttribute("data-slug");
            name = el.getAttribute("data-title");
            component = el.getAttribute("data-component");
            if (id != "undefined") {
                out = {slug: id, name: name, component: component};
            }
            return out;
        };

        var getSortableObject = function (selector) {
            var out = [];
            var leftCol = document.querySelectorAll(selector);
            if (leftCol.length > 0) {
                for (var l = 0; l < leftCol.length; l++) {
                    var ot = getAttribute(leftCol[l]);
                    out.push(ot);
                }
            }
            return out;
        };
        $scope.enable_sortable = function () {
            var $tempWrap = $(".xlwcty_template_wrap");
            $(".xlTemplateUi").sortable({
                connectWith: ".xlTemplateUi",
                start: function (event, ui) {
                    ui.item.addClass("xlwctyhighlight");
                    if ($tempWrap.length > 0) {
                        $tempWrap.addClass("xlwcty_drag_on");
                    }
                },
                stop: function (event, ui) {
                    ui.item.removeClass("xlwctyhighlight");
                    if ($tempWrap.length > 0) {
                        $tempWrap.removeClass("xlwcty_drag_on");
                    }
                    $scope.layout_fields();
                },
            }).disableSelection();

            $(".xlTemplateMobileUi").sortable({
                connectWith: ".xlTemplateMobileUi",
                start: function (event, ui) {
                    ui.item.addClass("xlwctyhighlight");
                    if ($tempWrap.length > 0) {
                        $tempWrap.addClass("xlwcty_drag_on");
                    }
                },
                stop: function (event, ui) {
                    ui.item.removeClass("xlwctyhighlight");
                    if ($tempWrap.length > 0) {
                        $tempWrap.removeClass("xlwcty_drag_on");
                    }
                    $scope.layout_fields();
                },
            }).disableSelection();

        };
        setTimeout(function () {
            $scope.enable_sortable();
        }, 1500);

        $scope.xlwcty_change_template = function (template, el) {
            if (typeof template != "undefined" && template != "undefined" && template != "") {
                var old_temp = $scope.builder_template;
                if (template == old_temp) {
                    return false;
                }
                $(".xlwcty_layouts ul li.layout_selected").removeClass("layout_selected");
                $(el).addClass("layout_selected");
                var old_temp_data = $scope.builder_componets[old_temp];
                var defaut_template_data = defaultComponents();
                $scope.builder_componets[old_temp] = defaut_template_data[old_temp];
                var temp_data = [];
                if (Object.keys(old_temp_data).length > 0) {
                    for (var c in old_temp_data) {
                        for (var k = 0; k < old_temp_data[c].length; k++) {
                            delete old_temp_data[c][k].$$hashKey;
                            temp_data.push(old_temp_data[c][k]);
                        }
                    }
                }
                $scope.builder_componets[template].first = temp_data;
                $scope.builder_template = template;
                setTimeout(function () {
                    $scope.enable_sortable();
                    update_layout();
                }, 800);
            }
        };
        $scope.layout_fields = function () {
            var layout_fields = [];
            $scope.xlwcty_added_fields = {};

            if ($scope.builder_template == "basic") {
                $scope.builder_componets.basic.first = getSortableObject(".xlwcty_layout_prev_content .xlwcty_layout_components");
            }
            if ($scope.builder_template == "two_column") {
                $scope.builder_componets.two_column.first = getSortableObject(".xlwcty_layout_prev_content_l .xlwcty_layout_components");
                $scope.builder_componets.two_column.second = getSortableObject(".xlwcty_layout_prev_content_r .xlwcty_layout_components");
                $scope.builder_componets.two_column.third = getSortableObject(".xlwcty_layout_prev_content_t .xlwcty_layout_components");
            }

            //get all mobile components
            $scope.builder_componets.mobile.first = getSortableObject(".xlwcty_layout_mobile_content .xlwcty_layout_components");
            update_layout();
        };
        $scope.addField = function (slug, name, component) {
            component = component == "undefined" ? "" : component;
            var search;
            if (slug != "" && name != "") {
                if ($scope.builder_template == "basic") {
                    search = serchComponents($scope.builder_componets.basic, slug);
                    if (search.index == -1) {
                        $scope.builder_componets.basic.first.push({slug: slug, name: name, component: component});
                    }
                }
                if ($scope.builder_template == "two_column") {
                    search = serchComponents($scope.builder_componets.two_column, slug);
                    if (search.index == -1) {
                        $scope.builder_componets.two_column.first.push({slug: slug, name: name, component: component});
                    }
                }

                //add field from mobile layout
                search = serchComponents($scope.builder_componets.mobile, slug);
                if (search.index == -1) {
                    $scope.builder_componets.mobile.first.push({slug: slug, name: name, component: component});
                }

                update_layout();
            }
        };
        $scope.removeField = function (slug) {
            var search;
            if ($scope.builder_template == "basic") {
                search = serchComponents($scope.builder_componets.basic, slug);
                if (search.index > -1) {
                    $scope.builder_componets.basic[search.column].splice(search.index, 1);
                }
            }
            if ($scope.builder_template == "two_column") {
                search = serchComponents($scope.builder_componets.two_column, slug);
                if (search.index > -1) {
                    $scope.builder_componets.two_column[search.column].splice(search.index, 1);
                }
            }

            //remove field from mobile layout
            search = serchComponents($scope.builder_componets.mobile, slug);
            if (search.index > -1) {
                $scope.builder_componets.mobile[search.column].splice(search.index, 1);
            }
            update_layout();
        };

        $("#_xlwcty_choose_template").on("change", function () {
            update_layout();
        });

        $(".xlwcty_is_enable input[type='radio']").on("change", function () {
            var is_enable = $(this).val();
            var parent = $(this).parents(".cmb2_xlwcty_wrapper_ac");
            var slug = parent.attr("data-slug");
            var title = parent.attr("data-title");
            var component = parent.attr("data-component");
            var btn = $(".xlwcty_field_btn[data-slug='" + slug + "']");
            var formElemGif = $(".xlwcty_builder_right_wrap").find(".xlwcty_freeze_screen");
            btn.find(".xlwcty_checked").remove();
            formElemGif.show();
            if (is_enable == 1) {
                btn.addClass("xlwcty_selected");
                btn.append(checked_html);
                $scope.addField(slug, title, component);
            }
            if (is_enable != 1) {
                btn.removeClass("xlwcty_selected");
                $scope.removeField(slug);
                if ($(".xlwcty_add_more_componets[data-component='" + component + "']").length > 0) {
                    var repeater_field = $(".xlwcty_add_more_componets[data-component='" + component + "']");
                    var available_component = repeater_field.attr("data-available-slug");
                    if ($(".xlwct_hide_this_componets[data-component='" + component + "']:visible").length > 1) {
                        var max_reapeat = repeater_field.attr("data-max");
                        max_reapeat = parseInt(max_reapeat);
                        available_component = JSON.parse(available_component);
                        var available_count = repeater_field.find("strong").text();
                        available_count = parseInt(available_count);
                        if (available_component != null && typeof available_component == "object") {
                            if (typeof available_component[slug] == "undefined") {
                                available_component[slug] = "";
                            }
                            available_component[slug] = "1";
                            $(".xlwcty_field_btn[data-slug='" + slug + "']").hide();
                            available_count = available_count + 1;
                            if (available_count < max_reapeat) {
                                repeater_field.find("strong").text(available_count);
                                repeater_field.removeClass("xl_no_more_components");
                            }
                        }
                        repeater_field.attr("data-available-slug", JSON.stringify(available_component));
                    }
                }
            }
            setTimeout(function () {
                formElemGif.hide();
            }, 1000);
        });
        $("#_xlwcty_order_buil_in").on("change", function () {
            var val = $(this).val();
            var ecomm_font_icon_val = $(this).val();
            if (ecomm_font_icon_val > 0) {
                $(".xlwcty_icon_preview").html('<i class="xlwcty_custom_icon xlwcty-ecommerce' + xlwcty_return_font_val(ecomm_font_icon_val) + '"></i>');
            }
        });

        if ($('.xlwcty_add_more_items').length > 0) {
            $(document).on("click", ".xlwcty_add_more_items", function () {
                var $this = $(this);
                if ($this.hasClass('xlwcty_btn_open')) {
                    $this.removeClass('xlwcty_btn_open');
                    $this.closest('.xlwcty_field_btn').find('.xlwcty_btn_layouts').slideUp();
                } else {
                    $this.addClass('xlwcty_btn_open');
                    $this.closest('.xlwcty_field_btn').find('.xlwcty_btn_layouts').slideDown();
                }
            });
            $(".xlwcty_add_more_componets").on("click", function () {
                var $this = $(this);
                var $active_slug = "";
                var available_component = $this.attr("data-available-slug");
                var available_count = $this.find("strong").text();
                available_count = parseInt(available_count);
                available_component = JSON.parse(available_component);
                if (Object.keys(available_component).length > 0) {
                    if (available_component != null && typeof available_component == "object") {
                        available_component = sortObj(available_component, "asc");
                        for (var component in available_component) {
                            $(".xlwcty_field_btn[data-slug='" + component + "']").show();
                            $(".xlwcty_field_btn[data-slug='" + component + "']").trigger("click");
                            available_count = available_count - 1;
                            if (available_count > -1) {
                                $this.find("strong").text(available_count);
                            }
                            if (available_count == 0) {
                                $this.addClass("xl_no_more_components");
                            }
                            delete available_component[component];
                            $this.attr("data-available-slug", JSON.stringify(available_component));
                            $(".xlwcty_btn_layouts").hide();
                            $(".xlwcty_btn_layouts").parents(".xlwcty_field_btn").removeClass("active");
                            break;
                        }
                    }
                } else {
                    $this.addClass("xl_no_more_components");
                }
            });
        }
    });


    $(window).on('load', function () {

        if ($(".xlwcty_add_more_componets").length > 0) {
            $(".xlwcty_add_more_componets").each(function () {
                var components = $(this).attr("data-component");
                if ($(".xlwcty_field_btn[data-component='" + components + "']:visible").length == 0) {
                    setTimeout(function (components) {
                        $(".xlwcty_add_more_componets[data-component='" + components + "']").trigger("click");
                    }, 300, components);

                }
            });
        }

        if ($("#_xlwcty_choose_template").length > 0) {
            var tval = $("#_wp_page_template").val();
            if (tval != "") {
                $("#_xlwcty_choose_template").val(tval);
            }
            $("#_xlwcty_choose_template").xlChosen({"disable_search": true});

        }
        if ($(".preview_order_id").length > 0) {
            $(".preview_order_id").on("change", function () {
                var key = $(this).find("option:selected");
                if (key.length > 0) {
                    var strArray = key.val().split("||");
                    $("input[name='_xlwcty_chosen_order_preview']").val(strArray[1]);
                    $("form#order_preview_form").find("input[name='key']").val(strArray[0]);
                    $("form#order_preview_form").find("input[name='order_id']").val(strArray[1]);
                }
            });

            if ($(".preview_order_id").length > 0) {
                $(".preview_order_id").xlAjaxChosen({
                    type: 'POST',
                    minTermLength: 3,
                    afterTypeDelay: 500,
                    data: {
                        'action': 'xlwcty_get_orders_cmb2'
                    },
                    url: ajaxurl,
                    dataType: 'json'
                }, function (data) {
                    var results = [];
                    $.each(data, function (i, val) {
                        results.push({value: val.value, text: val.text});
                    });

                    return results;
                });
            }
            $(".preview_order_id").trigger("change");
        }
//        console.clear();
    });

})(jQuery, window);
