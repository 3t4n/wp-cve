jQuery(function ($) {
    var dropdownButton = document.getElementsByClassName(
        "dik-dropdown-button"
    )[0];
    if (dropdownButton) {
        dropdownButton.addEventListener("click", function () {
            dropdownList.classList.toggle("show");
        });

        var dropdownList =
            document.getElementsByClassName("dik-dropdown-list")[0];

        if (dropdownList) {
            dropdownList.addEventListener("click", function (event) {
                var dropdownName = event.target.innerHTML;
                dropdownButton.innerHTML = dropdownName;
                dropdownList.classList.toggle("show");
            });
        }
    }

    function checkForInput(element) {
        // element is passed to the function ^

        if ($(element).val().length > 0) {
            $(".dik-search").addClass("dik-search-have-input");
        } else {
            $(".dik-search").removeClass("dik-search-have-input");
        }
    }

    // The lines below are executed on page load
    $("input.dik-search-input").each(function () {
        checkForInput(this);
    });

    // The lines below (inside) are executed on change & keyup
    $("input.dik-search-input").on("change keyup", function () {
        checkForInput(this);
    });

    var DIK_AdvancedUpload = (function () {
        var div = document.createElement("div");
        return (
            ("draggable" in div || ("ondragstart" in div && "ondrop" in div)) &&
            "FormData" in window &&
            "FileReader" in window
        );
    })();

    var $form = $(".dik-drag-drop");

    if (DIK_AdvancedUpload) {
        var droppedFiles = false;

        $form
            .on(
                "drag dragstart dragend dragover dragenter dragleave drop",
                function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            )

            .on("dragover dragenter", function () {
                $form.addClass("is-dragover");
            })

            .on("dragleave dragend drop", function () {
                $form.removeClass("is-dragover");
            })
            .on("drop", function (e) {
                droppedFiles = e.originalEvent.dataTransfer.files;
                $form
                    .find("#twp-content-file-upload")
                    .prop("files", droppedFiles);

                if (
                    $("#twp-content-file-upload").length &&
                    document.getElementById("twp-content-file-upload").files
                        .length != 0
                ) {
                    $(".two-upload-status").empty();
                    $(".two-upload-status").text(droppedFiles[0].name);
                }
            });
    }

    $("#twp-content-file-upload").on("change", function (e) {
        if ($("#twp-content-file-upload").val() !== "") {
            if (
                $("#twp-content-file-upload").length &&
                document.getElementById("twp-content-file-upload").files
                    .length != 0
            ) {
                var droppedFiles = $("#twp-content-file-upload").val();
                var filename = droppedFiles.replace(/C:\\fakepath\\/i, "");
                $(".two-upload-status").empty();
                $(".two-upload-status").text(filename);
            }
        }
    });

    $(".dik-primary-tab").click(function () {
        if (!$(this).hasClass("dik-primary-tab-active")) {
            $(".dik-primary-tab").removeClass("dik-primary-tab-active");
            $(this).addClass("dik-primary-tab-active");

            demo_import_kit_tab();

            var PrimaryCat = $(this).attr("ptab-data");
            var ajaxurl = dik.ajax_url;
            var data = {
                action: "demo_import_kit_grid_primary_tab",
                PrimaryCat: PrimaryCat,
                _wpnonce: dik.ajax_nonce,
            };

            $.post(ajaxurl, data, function (response) {
                $(".dik-content-wrapper").empty();
                $(".dik-content-wrapper").html(response);

                $(".dik-grid-panel").imagesLoaded(function () {
                    var $grid = $(".dik-grid-panel").isotope({
                        // options
                        itemSelector: ".dik-columns",
                        layoutMode: "fitRows",
                    });
                });

                demo_import_kit_tab();

                $(".action-import-grid").on("click", function () {
                    $(".dik-content-wrapper").addClass("dik-grid-importing");
                    $(".dik-content-wrapper").empty();
                    $(".dik-content-wrapper").append(
                        "<div><h2>" +
                            dik.importing_title +
                            "</h2><p>" +
                            dik.importing_message +
                            '</p><div class="dik-import-loading"></div></div>'
                    );

                    // Grid import AJAX call
                    var data = new FormData();
                    data.append("action", "dik_import_demo_data");
                    data.append("security", dik.ajax_nonce);
                    data.append("selected", $(this).attr("thumbid"));
                    data.append("demoSlug", $(this).attr("demo-slug"));

                    ajaxCall(data);
                });
            });
        }
    });

    $("#twp-zip-file-upload").on("click", function () {
        // Prepare data for the AJAX call
        var data = new FormData();
        data.append("action", "dik_import_demo_data");
        data.append("security", dik.ajax_nonce);

        if (
            $("#twp-content-file-upload").length &&
            document.getElementById("twp-content-file-upload").files.length != 0
        ) {
            data.append(
                "content_file",
                $("#twp-content-file-upload")[0].files[0]
            );
        } else {
            return;
        }

        $(".dik-drag-drop").addClass("dik-demo-importing");
        $(".dik-plugin-masthead").addClass("dik-demo-importing");
        $(".dik-drag-drop").empty();
        $(".js-dik-ajax-response").empty();
        $(".dik-content-wrapper").empty();
        $(".dik-header-upload").empty();
        $(".dik-drag-drop").append(
            "<div><h2>" +
                dik.importing_title +
                "</h2><p>" +
                dik.importing_message +
                '</p><div class="dik-import-loading"></div></div>'
        );
        $(".dik-header-upload").append(
            "<div><h2>" +
                dik.importing_title +
                "</h2><p>" +
                dik.importing_message +
                '</p><div class="dik-import-loading"></div></div>'
        );

        ajaxCall(data);
    });

    $(".action-import-grid").on("click", function () {
        //alert('dsds');

        $(".dik-content-wrapper").addClass("dik-grid-importing");
        $(".dik-content-wrapper").empty();
        $(".dik-content-wrapper").append(
            '<div class="dik-wrapper dik-notification-message"><h2>' +
                dik.importing_title +
                "</h2><p>" +
                dik.importing_message +
                '</p><div class="dik-import-loading"></div></div>'
        );

        // Grid import AJAX call
        var data = new FormData();
        data.append("action", "dik_import_demo_data");
        data.append("security", dik.ajax_nonce);
        data.append("selected", $(this).attr("thumbid"));
        data.append("demoSlug", $(this).attr("demo-slug"));

        ajaxCall(data);
    });

    function ajaxCall(data) {
        $.ajax({
            method: "POST",
            url: dik.ajax_url,
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $(".js-dik-ajax-loader").show();
            },
        })
            .done(function (response) {
                if (
                    "undefined" !== typeof response.status &&
                    "newAJAX" === response.status
                ) {
                    ajaxCall(data);
                } else {
                    $(".dik-content-wrapper").removeClass("dik-grid-importing");
                    $(".dik-content-wrapper").removeClass("dik-grid-imported");
                    $(".dik-content-wrapper").empty();
                    $(
                        ".dik-demo-importing .dik-header-upload, .dik-demo-importing.dik-drag-drop"
                    ).empty();
                    $(
                        ".dik-demo-importing .dik-header-upload, .dik-demo-importing.dik-drag-drop"
                    ).append(
                        "<div><h2>" +
                            dik.import_status +
                            "</h2><p>" +
                            response +
                            '</p><div class="dik-import-loading"></div></div>'
                    );

                    if (
                        !$(".dik-plugin-masthead, .dik-drag-drop").hasClass(
                            "dik-demo-importing"
                        )
                    ) {
                        $(".dik-content-wrapper").append(
                            '<div class="dik-wrapper dik-notification-message"><h2>' +
                                dik.import_status +
                                "</h2><p>" +
                                response +
                                '</p><div class="dik-import-loading"></div></div>'
                        );
                    }

                    $(".dik-plugin-masthead").removeClass("dik-demo-importing");
                    $(".dik-drag-drop").removeClass("dik-demo-importing");
                }
            })
            .fail(function (error) {
                //$('.dik-plugin-masthead').removeClass('dik-demo-importing');
                $(
                    ".dik-content-wrapper, .dik-demo-importing.dik-drag-drop"
                ).removeClass("dik-grid-importing");
                $(
                    ".dik-content-wrapper, .dik-demo-importing.dik-drag-drop"
                ).empty();

                $(
                    ".dik-content-wrapper, .dik-demo-importing.dik-drag-drop"
                ).append(
                    '<div class="notice  notice-error  is-dismissible"><p>Error: ' +
                        error.statusText +
                        " (" +
                        error.status +
                        ")" +
                        "</p></div>"
                );
            });
    }

    demo_import_kit_tab();
    demo_import_kit_search();
});

function demo_import_kit_tab() {
    jQuery(function ($) {
        $(".dik-grid-panel").imagesLoaded(function () {
            var $grid = $(".dik-grid-panel").isotope({
                // options
                itemSelector: ".dik-columns",
                layoutMode: "fitRows",
            });
        });

        $(".dik-tab").click(function () {
            var tabid = $(this).attr("tabid");
            $(".dik-nav-filters a").removeClass("dik-tab-active");
            $(this).addClass("dik-tab-active");

            $("#dik-search-input").val("");
            var filterValue = $(this).attr("data-filter");
            var filterCurrent = $(this).attr("data-current");

            $(".dik-grid-items").each(function () {
                $(this).removeClass("dik-search-filter");

                if ($(this).hasClass(filterCurrent)) {
                    $(this).addClass("dik-search-filter");
                } else if (filterCurrent == "*") {
                    $(this).addClass("dik-search-filter");
                } else {
                }
            });

            $(".dik-grid-panel").imagesLoaded(function () {
                var $grid = $(".dik-grid-panel").isotope({
                    // options
                    itemSelector: ".dik-columns",
                    layoutMode: "fitRows",
                });

                $grid.isotope({ filter: filterValue });

                $(".dik-search-filter").each(function () {
                    var cStyle = $(this).attr("style");
                    cStyle = cStyle.replace("display: none;", "");

                    $(this).attr("style", cStyle);
                });
            });

            $(".dik-grid-panel").imagesLoaded(function () {
                var $grid = $(".dik-grid-panel").isotope({
                    // options
                    itemSelector: ".dik-columns",
                    layoutMode: "fitRows",
                });
                $grid.isotope({ filter: filterValue });
            });
        });

        // var $grid = $('.dik-grid-main').isotope({
        //   itemSelector: '.dik-grid-items',
        //   layoutMode: 'fitRows'
        // });
        // // bind filter on select change
        // $("#dik-select-filters").on("change", function() {
        //   // get filter value from option value
        //   filterValue = $(this).val();
        //   console.log(filterValue);
        //   $grid.isotope();
        // });

        var $filters = $(".dik-header-filter [data-filter]"),
            $boxes = $(".dik-grid-main [data-category]");

        $filters.on("click", function (e) {
            e.preventDefault();
            var $this = $(this);

            $filters.removeClass("active");
            $this.addClass("active");

            var $filterClass = $this.attr("data-filter");

            if ($filterClass == "all") {
                $boxes
                    .removeClass("is-animated")
                    .fadeOut()
                    .promise()
                    .done(function () {
                        $boxes.addClass("is-animated").fadeIn();
                    });
            } else {
                $boxes
                    .removeClass("is-animated")
                    .fadeOut()
                    .promise()
                    .done(function () {
                        $boxes
                            .filter('[data-category = "' + $filterClass + '"]')
                            .addClass("is-animated")
                            .fadeIn();
                    });
            }
        });
    });
}

function demo_import_kit_search() {
    jQuery(function ($) {
        $("#dik-search-input").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".dik-search-filter").filter(function () {
                $(this).toggle(
                    $(this).text().toLowerCase().indexOf(value) > -1
                );

                $(".dik-grid-panel").imagesLoaded(function () {
                    var $grid = $(".dik-grid-panel").isotope({
                        // options
                        itemSelector: ".dik-columns",
                        layoutMode: "fitRows",
                    });
                });
            });
        });

        // Header Search Popup End
        $(".dik-plugin-search").click(function () {
            $(".dik-plugin-masthead-center").toggleClass("dik-search-active");
        });
    });
}
