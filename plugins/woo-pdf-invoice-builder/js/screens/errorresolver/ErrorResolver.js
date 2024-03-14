var ErrorResolver = /** @class */ (function () {
    function ErrorResolver() {
        var _this = this;
        this.analyzeButton = Ladda.create(document.querySelector('#analyze'));
        jQuery('.issueType').change(function (e) {
            if (jQuery(e.currentTarget).val() == 'order') {
                jQuery('#orderDetail').slideDown(200, function () { jQuery('#orderNumber').focus(); });
                _this.CheckOrderNumber();
            }
            else {
                jQuery('#orderDetail').slideUp();
                jQuery('#analyze').slideDown();
            }
        });
        jQuery('#orderNumber').on('input', function () {
            _this.CheckOrderNumber();
        });
        jQuery('#analyze').click(function () { _this.Analyze(); });
    }
    ErrorResolver.prototype.CheckOrderNumber = function () {
        if (jQuery('#orderNumber').val() == '') {
            jQuery('#analyze').slideUp();
        }
        else
            jQuery('#analyze').slideDown();
    };
    ErrorResolver.prototype.Analyze = function () {
        var _this = this;
        this.analyzeButton.start();
        jQuery.post(ajaxurl, { action: 'rednao_wcpdfinv_diagnose_error',nonce:rnErrorResolver.nonce, invoiceId: jQuery('#templateName').val(), testType: jQuery('.issueType:checked').val(), orderNumber: jQuery('#orderNumber').val() }, function (response) {
            alert('Sorry the error couuldn\'t be captured, please contact support to resolve this issue');
            _this.analyzeButton.stop();
        }).fail(function () {
            jQuery.post(ajaxurl, { action: 'rednao_wcpdfinv_get_latest_error' }, function (response) {
                if (response == '') {
                    alert('Sorry the error couuldn\'t be captured, please contact support to resolve this issue');
                    _this.analyzeButton.stop();
                }
                try {
                    var error = JSON.parse(response);
                    _this.PrintError(error);
                    _this.analyzeButton.stop();
                }
                catch (exception) {
                    alert('Sorry the error couuldn\'t be captured, please contact support to resolve this issue');
                    _this.analyzeButton.stop();
                }
            }).fail(function () {
                alert('Sorry the error couuldn\'t be captured, please contact support to resolve this issue');
                _this.analyzeButton.stop();
            });
        });
    };
    ErrorResolver.prototype.PrintError = function (error) {
        jQuery('#edErrorMessage').text(error.ErrorMessage);
        jQuery('#edErrorNumber').text(error.ErrorNumber);
        jQuery('#edErrorFile').text(error.ErrorFile);
        jQuery('#edErrorLine').text(error.ErrorLine);
        jQuery('#edErrorContext').text(JSON.stringify(error.ErrorContext));
        jQuery('#edErrorDetail').html(this.GetStringFromArray(error.Detail));
        jQuery('#ErrorDetail').css('display', 'block');
    };
    ErrorResolver.prototype.EscapeHtml = function (string) {
        var entityMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        };
        return string.replace(/[&<>"'`=\/]/g, function (s) {
            return entityMap[s];
        });
    };
    ;
    ErrorResolver.prototype.GetStringFromArray = function (Detail) {
        try {
            var parsedDetail = JSON.parse(Detail);
            var text = '';
            for (var _i = 0, parsedDetail_1 = parsedDetail; _i < parsedDetail_1.length; _i++) {
                var element = parsedDetail_1[_i];
                for (var property in element) {
                    text += '<strong>' + this.EscapeHtml(property.toString()) + ':</strong>' + this.EscapeHtml(element[property].toString()) + '<br/>';
                }
                text += '<br/><br/><br/>';
            }
            return text;
        }
        catch (Exception) {
            return Detail;
        }
    };
    return ErrorResolver;
}());
jQuery(function () {
    new ErrorResolver();
});
//# sourceMappingURL=ErrorResolver.js.map