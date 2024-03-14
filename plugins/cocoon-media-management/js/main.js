jQuery(function ($) {
    $(document).ready(function () {
        var ajaxTimeout = 60000;
      
        $.ajax({
            url: wp_vars.ajax_url,
            data: {
                action: "cn_get_sets"
            },
            type: "POST",
            timeout: ajaxTimeout,
            success: function (res) {
                if(res['status'] === 'error') return;

                $("#cn-sets-list").html(res);
                var cnSets = $(".cn-sets:first");
                cnSets.prop("checked", true).trigger("change");
            },
            error: function () {
                $("#cn-loader").hide();
            }
        });

        var selectedImage = {
            path: "",
            name: "",
            ext: "",
            size: "",
            dim: "",
            uploaded: ""
        };
        var setId;

        $(document).on("click", ".cn-thumb", function () {
            selectedImage.path = $(this).data("cnpath");
            selectedImage.name = $(this).data("cnname");
            selectedImage.ext = $(this).data("cnext");
            selectedImage.size = $(this).data("cnsize");
            selectedImage.dim = $(this).data("cndim");
            selectedImage.domain = $(this).data("cndomain");
            selectedImage.uploaded = $(this).data("cnuploaded");
            $(".cn-thumb").removeClass("cn-active");
            $(this).addClass("cn-active");
            $("#cn-form-insert").removeAttr("disabled");

            clearForm();
            $("#cn-sidebar-right .cn-sidebar-wrap").show();
            $("#cn-form-img").attr("src", $(this).data("web"));
            $("#cn-form-name").text(selectedImage.name + '.' + selectedImage.ext);
            $("#cn-form-size").text(selectedImage.size);
            $("#cn-form-dim").text(selectedImage.dim);
            $("#cn-form-uploaded").text(selectedImage.uploaded);
            $("#cn-form-url").val(selectedImage.path);
            $("#cn-form-thumb-size option:first").text("Full Size - " + selectedImage.dim);
        });

        $(document).on("click", "#cn-lib .cn-checkbox", function (e) {
            e.stopPropagation();
        });

        $(document).on("change", "#cn-lib .cn-checkbox", function () {
            if($("#cn-lib .cn-checkbox:checked").length > 0) {
                $("#cn-form-upload").prop("disabled", false);
            } else {
                $("#cn-form-upload").prop("disabled", true);
            }
        });

        $(document).on("click", "#cn-form-upload", function () {
            var checkedImgs = $("#cn-lib .cn-checkbox:checked");
            $("#cn-loader").show();

            (function startUpload(i) {
                if(i === checkedImgs.length) {
                    $("#cn-loader").hide();
                    return;
                }
				var cnPath=$(checkedImgs[i]).parent().data("cnpath");
				if($('#cn-form-thumb-types').val()!=""){
					cnPath=$(checkedImgs[i]).parent().data("cnpath").replace("/files/original",$('#cn-form-thumb-types').val());
				}
                $.ajax({
                    url: wp_vars.ajax_url,
                    data: {
                        action: "cn_upload_images",
                        path:cnPath,
                        ext: $(checkedImgs[i]).parent().data("cnext"),
                        name: $(checkedImgs[i]).parent().data("cnname")
                    },
                    type: "POST",
                    timeout: ajaxTimeout,
                    success: function () {
                        startUpload(++i);
                    },
                    error: function () {
                        startUpload(++i);
                    }
                })
            })(0);
        });

        $(document).on("change", ".cn-sets", function () {
            $("#cn-form-search").val("");
            setId = $(this).val();
            getThumbs(1);
        });

        $(document).on("click", ".cn-pagination .cn-page-item", function () {
            var page = $(this).data("page");
            getThumbs(page);
        });

        var getThumbs = function (page) {
            var keyword = $("#cn-form-search").val();

            if(setId === 'search' && !keyword) return;

            $("#cn-loader").show();
            $("#cn-form-insert").attr("disabled", true);
            $.ajax({
                url: wp_vars.ajax_url,
                data: {
                    action: "get_files_by_set",
                    setId: setId,
                    keyword: keyword,
                    pageNo: page ? page : 1
                },
                type: "POST",
                timeout: ajaxTimeout,
                success: function (res) {
                    if (!res || res['status'] === 'error') {
                        return;
                    }
                    var resObj = JSON.parse(res);
                    $("#cn-loader").hide();
                    $("#cn-content").html(resObj.data);
                    $(".cn-pagination").html(resObj.pagination);
                },
                error: function () {
                    $("#cn-loader").hide();
                }
            });
        };

        $("#cn-form-insert").on("click", function () {
            $("#cn-loader").show();
            var thumoType = $("#cn-form-thumb-types").val();

            var selectedImageExt = selectedImage.ext;
            var uploadUrl = (selectedImageExt === 'jpg' ||
                selectedImageExt === 'png' ||
                selectedImageExt === 'gif' ||
                selectedImageExt === 'bmp'
            )
                ? selectedImage.domain + thumoType + '/' + selectedImage.name + '.' + selectedImageExt
                : selectedImage.path;

            $.ajax({
                url: wp_vars.ajax_url,
                data: {
                    action: "cn_upload_image",
                    path: uploadUrl,
                    ext: selectedImageExt,
                    name: selectedImage.name,
                    title: $("#cn-form-title").val(),
                    caption: $("#cn-form-caption").val(),
                    alt: $("#cn-form-alt").val(),
                    size: $("#cn-form-thumb-size").val()
                },
                type: "POST",
                timeout: ajaxTimeout,
                success: function (res) {
                    if(res['status'] === 'error') return;
                    $("#cn-loader").hide();
                    var resObj = JSON.parse(res);
                    window.parent.send_to_editor(resObj['data']);
                },
                error: function () {
                    $("#cn-loader").hide();
                }
            });
        });

        var cnErrMsg = document.getElementById("cn-error-msg");
        if (!cnErrMsg) {
            $("#cn-cred-check-loader").fadeIn('fast');
            $.ajax({
                url: wp_vars.ajax_url,
                data: {
                    action: "check_creds"
                },
                type: "POST",
                timeout: ajaxTimeout,
                success: function (res) {
                    var resObj = JSON.parse(res);
                    if (resObj.status === 'OK') {
                        $("#cn-cred-check-loader").fadeOut('fast', function() {
                            $("#cn-thumb-up").fadeIn('fast');
                            $("#cn-thumb-down").fadeOut('fast');
                        });
                    } else if (resObj.status === 'error' && resObj['code'] === 2) {
                        $("#cn-cred-check-loader").fadeOut('fast', function() {
                            $("#cn-thumb-down").fadeIn('fast');
                            $("#cn-thumb-up").fadeOut('fast');
                        });
                    } else {
                        $("#cn-cred-check-loader").fadeOut('fast');
                    }
                },
                error: function () {
                    $("#cn-loader").hide();
                }
            });
        }

        function clearForm() {
            $("#cn-form-img").attr("src", "");
            $("#cn-form-name").text("");
            $("#cn-form-size").text("");
            $("#cn-form-dim").text("");
            $("#cn-form-uploaded").text("");
            $("#cn-form-url").val("");
            $("#cn-form-title").val("");
            $("#cn-form-caption").val("");
            $("#cn-form-alt").val("");
        }

        $("#cn-form-search").bind("enterKey", function (e) {
            setId = $(this).val() ? "search" : $(".cn-sets:checked").val();
            getThumbs(1);
        });
        $("#cn-form-search").keyup(function (e) {
            if (e.keyCode == 13) {
                $(this).trigger("enterKey");
            }
        });

        $.ajax({
            url: wp_vars.ajax_url,
            data: {
                action: "cn_get_tags"
            },
            type: "POST",
            timeout: ajaxTimeout,
            success: function (res) {
                if (!res || res['status'] === 'error') {
                    return;
                }
                var resObj = JSON.parse(res);

                var availableTags = resObj.data;
                $( "#cn-form-search" ).autocomplete({
                    source: availableTags
                });
            }
        });

        $("#cn-items-perpage").on("change", function() {
            $("#cn-loader").show();

            $.ajax({
                url: wp_vars.ajax_url,
                data: {
                    action: "cn_items_perpage",
                    items: $(this).val()
                },
                type: "POST",
                timeout: ajaxTimeout,
                success: function (res) {
                    if (!res || res['status'] === 'error') {
                        return;
                    }

                    getThumbs(1);
                }
            });
        })
    })
});