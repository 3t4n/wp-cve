jQuery(document).ready(function ($) {
    //Regenerate Video XML sitemap
    $("#siteseo-video-regenerate").click(function () {
        url = siteseoAjaxVdeoRegenerate.siteseo_video_regenerate;
        action = 'siteseo_video_xml_sitemap_regenerate';
        _ajax_nonce = siteseoAjaxVdeoRegenerate.siteseo_nonce;

        self.process_offset2(0, self, url, action, _ajax_nonce);
    });

    process_offset2 = function (
        offset,
        self,
        url,
        action,
        _ajax_nonce
    ) {
        i18n = siteseoAjaxMigrate.i18n.video;
        $.ajax({
            method: 'POST',
            url: url,
            data: {
                action: action,
                offset: offset,
                _ajax_nonce: _ajax_nonce,
            },
            success: function (data) {
                if ("done" == data.data.offset) {
                    $("#siteseo-video-regenerate").removeAttr(
                        "disabled"
                    );
                    $(".spinner").css("visibility", "hidden");
                    $("#tab_siteseo_tool_video .log").css("display", "block");
                    $("#tab_siteseo_tool_video .log").html("<div class='siteseo-notice is-success'><p>" + i18n + "</p></div>");

                    if (data.data.url != "") {
                        $(location).attr("href", data.data.url);
                    }
                } else {
                    self.process_offset2(
                        parseInt(data.data.offset),
                        self,
                        url,
                        action,
                        _ajax_nonce
                    );
                    if (data.data.total) {
                        progress = (data.data.count / data.data.total * 100).toFixed(2);
                        $("#tab_siteseo_tool_video .log").css("display", "block");
                        $("#tab_siteseo_tool_video .log").html("<div class='siteseo-notice'><p>" + progress + "%</p></div>");
                    }
                }
            },
        });
    };
    $("#siteseo-video-regenerate").on("click", function () {
        $(this).attr("disabled", "disabled");
        $("#tab_siteseo_tool_video .spinner").css(
            "visibility",
            "visible"
        );
        $("#tab_siteseo_tool_video .spinner").css("float", "none");
        $("#tab_siteseo_tool_video .log").html("");
    });

    //Select toggle
    $("#select-wizard-redirects, #select-wizard-import")
        .change(function (e) {
            e.preventDefault();

            var select = $(this).val();
            if (select == "none") {
                $(
                    "#select-wizard-redirects option, #select-wizard-import option"
                ).each(function () {
                    var ids_to_hide = $(this).val();
                    $("#" + ids_to_hide).hide();
                });
            } else {
                $(
                    "#select-wizard-redirects option:selected, #select-wizard-import option:selected"
                ).each(function () {
                    var ids_to_show = $(this).val();
                    $("#" + ids_to_show).show();
                });
                $(
                    "#select-wizard-redirects option:not(:selected), #select-wizard-import option:not(:selected)"
                ).each(function () {
                    var ids_to_hide = $(this).val();
                    $("#" + ids_to_hide).hide();
                });
            }
        })
        .trigger("change");

    //Import from SEO plugins
    const seo_plugins = [
        "yoast",
        "aio",
        "seo-framework",
        "rk",
        "squirrly",
        "seo-ultimate",
        "wp-meta-seo",
        "premium-seo-pack",
        "wpseo",
        "platinum-seo",
        "smart-crawl",
        "seopressor",
        "slim-seo",
        "metadata",
    ];
    seo_plugins.forEach(function (item) {
        $("#siteseo-" + item + "-migrate").on("click", function (e) {
            e.preventDefault();
            id = item;
            switch (e.target.id) {
                case "siteseo-yoast-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_yoast_migrate
                            .siteseo_yoast_migration;
                    action = "siteseo_yoast_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_yoast_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-aio-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_aio_migrate
                            .siteseo_aio_migration;
                    action = "siteseo_aio_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_aio_migrate.siteseo_nonce;
                    break;
                case "siteseo-seo-framework-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_seo_framework_migrate
                            .siteseo_seo_framework_migration;
                    action = "siteseo_seo_framework_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_seo_framework_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-rk-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_rk_migrate
                            .siteseo_rk_migration;
                    action = "siteseo_rk_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_rk_migrate.siteseo_nonce;
                    break;
                case "siteseo-squirrly-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_squirrly_migrate
                            .siteseo_squirrly_migration;
                    action = "siteseo_squirrly_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_squirrly_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-seo-ultimate-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_seo_ultimate_migrate
                            .siteseo_seo_ultimate_migration;
                    action = "siteseo_seo_ultimate_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_seo_ultimate_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-wp-meta-seo-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_wp_meta_seo_migrate
                            .siteseo_wp_meta_seo_migration;
                    action = "siteseo_wp_meta_seo_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_wp_meta_seo_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-premium-seo-pack-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_premium_seo_pack_migrate
                            .siteseo_premium_seo_pack_migration;
                    action = "siteseo_premium_seo_pack_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_premium_seo_pack_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-wpseo-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_wpseo_migrate
                            .siteseo_wpseo_migration;
                    action = "siteseo_wpseo_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_wpseo_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-platinum-seo-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_platinum_seo_migrate
                            .siteseo_platinum_seo_migration;
                    action = "siteseo_platinum_seo_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_platinum_seo_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-smart-crawl-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_smart_crawl_migrate
                            .siteseo_smart_crawl_migration;
                    action = "siteseo_smart_crawl_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_smart_crawl_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-seopressor-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_seopressor_migrate
                            .siteseo_seopressor_migration;
                    action = "siteseo_seopressor_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_seopressor_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-slim-seo-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_slim_seo_migrate
                            .siteseo_slim_seo_migration;
                    action = "siteseo_slim_seo_migration";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_slim_seo_migrate
                            .siteseo_nonce;
                    break;
                case "siteseo-metadata-migrate":
                    url =
                        siteseoAjaxMigrate.siteseo_metadata_csv
                            .siteseo_metadata_export;
                    action = "siteseo_metadata_export";
                    _ajax_nonce =
                        siteseoAjaxMigrate.siteseo_metadata_csv
                            .siteseo_nonce;
                    break;
                default:
            }
            self.process_offset(0, self, url, action, _ajax_nonce, id);
        });

        process_offset = function (
            offset,
            self,
            url,
            action,
            _ajax_nonce,
            id,
            post_export,
            term_export
        ) {
            i18n = siteseoAjaxMigrate.i18n.migration;
            if (id == "metadata") {
                i18n = siteseoAjaxMigrate.i18n.export;
            }
            $.ajax({
                method: "POST",
                url: url,
                data: {
                    action: action,
                    offset: offset,
                    post_export: post_export,
                    term_export: term_export,
                    _ajax_nonce: _ajax_nonce,
                },
                success: function (data) {
                    if ("done" == data.data.offset) {
                        $("#siteseo-" + id + "-migrate").removeAttr(
                            "disabled"
                        );
                        $(".spinner").css("visibility", "hidden");
                        $("#" + id + "-migration-tool .log").css("display", "block");
                        $("#" + id + "-migration-tool .log").html("<div class='siteseo-notice is-success'><p>" + i18n + "</p></div>");

                        if (data.data.url != "") {
                            $(location).attr("href", data.data.url);
                        }
                    } else {
                        self.process_offset(
                            parseInt(data.data.offset),
                            self,
                            url,
                            action,
                            _ajax_nonce,
                            id,
                            data.data.post_export,
                            data.data.term_export
                        );
                        if (data.data.total) {
                            progress = (data.data.count / data.data.total * 100).toFixed(2);
                            $("#" + id + "-migration-tool .log").css("display", "block");
                            $("#" + id + "-migration-tool .log").html("<div class='siteseo-notice'><p>" + progress + "%</p></div>");
                        }
                    }
                },
            });
        };
        $("#siteseo-" + item + "-migrate").on("click", function () {
            $(this).attr("disabled", "disabled");
            $("#" + item + "-migration-tool .spinner").css(
                "visibility",
                "visible"
            );
            $("#" + item + "-migration-tool .spinner").css("float", "none");
            $("#" + item + "-migration-tool .log").html("");
        });
    });
});
