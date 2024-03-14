var CustomFieldBuilder = /** @class */ (function () {
    function CustomFieldBuilder() {
        var _this = this;
        jQuery(function () {
            jQuery('.rednaoName').val(rednaoCustomFieldParam.name);
            jQuery('.rednaoCode').val(rednaoCustomFieldParam.code);
            jQuery('.rednaoType').val(rednaoCustomFieldParam.type);
            jQuery('.save').click(function () {
                var name = jQuery('.rednaoName').val();
                var code = '';
                if (_this.codeMirror != null)
                    code = _this.codeMirror.getValue();
                if (name.trim() == '') {
                    toastr.error('Name is required');
                    return;
                }
                if (code.trim() == '') {
                    toastr.error('Code is required');
                    return;
                }
                var type = jQuery('.rednaoType').val();
                if (code.indexOf('return ') < 0)
                    code = 'return ' + code;
                _this.lSave.start();
                jQuery.post(ajaxurl, { action: 'rednao_wcpdfinv_save_custom_field', data: JSON.stringify({ type: type, name: name, code: code, id: rednaoCustomFieldParam.Id }) }, function (result) {
                    result = JSON.parse(result);
                    if (result.success) {
                        rednaoCustomFieldParam.Id = result.result.row_id;
                        toastr.success('Custom field saved successfully');
                    }
                    else {
                        toastr.error(result.errorMessage);
                    }
                    _this.lSave.stop();
                });
            });
            _this.lSave = Ladda.create(document.querySelector('.save'));
            _this.codeMirror = CodeMirror.fromTextArea(jQuery('.rednaoCode')[0], {
                extraKeys: { "Ctrl-Space": "autocomplete" },
                lineNumbers: true,
                mode: "text/x-php",
                gutters: ["CodeMirror-lint-markers"],
                lint: true
            });
            _this.codeMirror.on('changes', function (instance, change) {
            });
        });
    }
    return CustomFieldBuilder;
}());
new CustomFieldBuilder();
//# sourceMappingURL=custom_field_builder.js.map