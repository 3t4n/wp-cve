jQuery(function ($) {

    var pisol_state = {
        zone: '',
        method: ''
    }

    var shipping_obj = new shippingNavigation();
    shipping_obj.init();

    function shippingNavigation() {
        this.init = function () {
            this.selectZone();
            this.selectMethod();
            this.submit();
        }

        this.selectZone = function () {
            var parent = this;
            $(".pisol-shipping-zone").on('click', function () {
                parent.removeActive(".pisol-shipping-zone");
                $(this).addClass('pisol_active');
                var zone = $(this).data('zone');
                var method_id = ".pi_zone_method_" + zone;
                parent.hideAllMethod();
                parent.resetForm();
                $(method_id).fadeIn();
            })
        }

        this.removeActive = function (class_name) {
            $(class_name).removeClass('pisol_active');
        }

        this.hideAllMethod = function () {
            $('.pisol-shipping-method').fadeOut();
        }

        this.selectMethod = function () {
            var parent = this;
            $(".pisol-shipping-method").on('click', function () {
                parent.removeActive(".pisol-shipping-method");
                var min = $(this).data('minimum');
                var max = $(this).data('maximum');
                var zone = $(this).data('zone');
                var method = $(this).data('method');
                var method_name = $(this).data('method_name');
                var method_title = $(this).data('method_title');
                $(this).addClass('pisol_active');
                parent.resetForm();
                parent.fillForm(min, max, zone, method, method_name, method_title);
            });
        }

        this.fillForm = function (min, max, zone, method, method_name, method_title) {
            $("#pisol-min-max-form").fadeIn();
            $('#pisol-form-minimum').val(min)
            $('#pisol-form-maximum').val(max)
            $('#pisol-form-zone').val(zone)
            $('#pisol-form-method').val(method)
            $('#pisol-form-method-name').val(method_name)
            $("#pisol-form-method-title").html(method_title);
        }

        this.resetForm = function () {
            $("#pisol-min-max-form").fadeOut();
            $('#pisol-form-minimum').val("");
            $('#pisol-form-maximum').val("");
            $('#pisol-form-zone').val("");
            $('#pisol-form-method').val("");
            $('#pisol-form-method-name').val("");
        }

        this.submit = function () {
            var parent = this;
            $("#pisol-min-max-form").submit(function (e) {
                e.preventDefault();
                var min = parseInt($('#pisol-form-minimum').val());
                var max = parseInt($('#pisol-form-maximum').val());
                var zone = parseInt($('#pisol-form-zone').val());
                var method = parseInt($('#pisol-form-method').val());
                var method_name = ($('#pisol-form-method-name').val());
                var method_title = $('#pisol-form-method-title').html();

                if (validateForm() === true && parent.validate(min, max, zone, method, method_name) === true) {
                    $("#pisol-min-max-form").parent().addClass('pi-block-condition-row');
                    $.ajax({
                        url: ajaxurl,
                        method: 'post',
                        data: {
                            action: 'pisol_update_method',
                            min_days: min,
                            max_days: max,
                            zone: zone,
                            method: method,
                            method_name: method_name,
                            method_title: method_title
                        },
                        success: function (result) {
                            parent.error(result);
                            if (result.includes('updated successfully')) {
                                $("#pisol-method-" + method).data('minimum', min);
                                $("#pisol-method-" + method).data('maximum', max);
                            }
                        }
                    }).always(function () {
                        $("#pisol-min-max-form").parent().removeClass('pi-block-condition-row');
                    })
                }
            })
        }

        this.validate = function (min, max, zone, method, method_name) {
            if (isNaN(min) || isNaN(max) || isNaN(zone) || isNaN(method)) {
                this.error('Minimum and Maximum days should be integer number');
                return false;
            }

            if (method_name == "") {
                this.error('There is some error please refresh the page and try again');
                return false;
            }

            if (parseInt(min) > parseInt(max)) {
                this.error('Maximum days should be grater then or equal to the Minimum days');
                return false;
            }

            return true;
        }

        this.error = function (message) {
            var html = '<div class="alert alert-warning">' + message + '</div>';
            $(".pisol-error").html(html);
        }
    }

    function hideProFeature() {
        var load_status = localStorage.getItem('pisol-edd-pro-feature-state');
        if (load_status == '' || load_status == undefined || load_status == 'show') {
            jQuery("#hid-pro-feature").html('Click to Hide Pro feature');
            jQuery(".free-version, .pro-feature").fadeIn();
        } else {
            jQuery("#hid-pro-feature").html('Click to Show Pro feature');
            jQuery(".free-version, .pro-feature").fadeOut();
        }

        jQuery("#hid-pro-feature").on("click", function () {
            var state = localStorage.getItem('pisol-edd-pro-feature-state');
            if (state == '' || state == undefined || state == 'show') {
                localStorage.setItem('pisol-edd-pro-feature-state', 'hidden');
                jQuery("#hid-pro-feature").html('Click to Show Pro feature');
                jQuery(".free-version, .pro-feature").fadeOut();
            } else {
                localStorage.setItem('pisol-edd-pro-feature-state', 'show');
                jQuery("#hid-pro-feature").html('Click to Hide Pro feature');
                jQuery(".free-version, .pro-feature").fadeIn();
            }
        });
    }

    hideProFeature();

});