import VueDraggableResizable from 'vue-draggable-resizable'

(function ($) {
    $(document).ready(function () {
        Vue.component('vue-draggable-resizable', VueDraggableResizable);
        new Vue({
            el: '#certificate_builder',
            components: {
                VueDraggableResizable,
                'photoshop-picker': VueColor.Photoshop
            },
            data: function () {
                return {
                    certificates: [
                        {
                            id: '',
                            data: {
                                orientation: 'landscape',
                                fields: []
                            }
                        }
                    ],
                    currentCertificate: 0,
                    activeField: 0,
                    color: '',
                    fields: [],
                    categories: [],
                    loading: true,
                    loaded: false,
                    saved: false,
                }
            },
            mounted: function () {
                let _this = this;
                _this.getFields();
                _this.getCategories();
                _this.getCertificates();
                _this.loaded = true;
            },
            methods: {
                getCertificates: function () {
                    let _this = this;

                    let url = stm_lms_ajaxurl + '?action=stm_get_certificates&nonce=' + stm_lms_nonces['stm_get_certificates'];

                    _this.$http.get(url).then(function (res) {
                        _this.$set(_this, 'certificates', res.body);
                        setTimeout(function () {
                            $('body').on('click', '.accordion-header', function () {
                                $(this).parent().toggleClass('open');
                            });
                            _this.loading = false;
                        }, 100);
                    });

                },
                getFields: function () {
                    let _this = this;

                    let url = stm_lms_ajaxurl + '?action=stm_get_certificate_fields&nonce=' + stm_lms_nonces['stm_get_certificate_fields'];

                    _this.$http.get(url).then(function (res) {
                        _this.$set(_this, 'fields', res.body);
                    });

                },
                getCategories: function () {
                    let _this = this;

                    let url = stm_lms_ajaxurl + '?action=stm_get_certificate_categories&nonce=' + stm_lms_nonces['stm_get_certificate_categories'];

                    _this.$http.get(url).then(function (res) {
                        _this.$set(_this, 'categories', res.body);
                    });

                },
                addCertificate: function () {
                    let _this = this;
                    let newCertificate = {
                        id: '',
                        title: 'New template',
                        thumbnail_id: '',
                        thumbnail: '',
                        image: '',
                        filename: '',
                        data: {
                            orientation: 'landscape',
                            fields: []
                        }
                    };
                    let certificates = _this.certificates;
                    certificates.push(newCertificate);
                    _this.$set(_this, 'certificates', certificates);
                    _this.$set(_this, 'currentCertificate', certificates.length - 1);

                },
                uploadFieldImage: function (index) {
                    let _this = this;
                    let custom_uploader = wp.media({
                        title: "Select image",
                        button: {
                            text: "Attach"
                        },
                        multiple: true
                    }).on("select", function () {
                        let attachment = custom_uploader.state().get("selection").first().toJSON();
                        if (typeof _this.certificates[_this.currentCertificate].data.fields[index] !== 'undefined') {
                            _this.$set(_this.certificates[_this.currentCertificate].data.fields[index], 'imageId', attachment.id);
                            _this.$set(_this.certificates[_this.currentCertificate].data.fields[index], 'content', attachment.url);
                        }
                    }).open();
                },
                uploadImage: function () {
                    let _this = this;
                    let custom_uploader = wp.media({
                        title: "Select image",
                        button: {
                            text: "Attach"
                        },
                        multiple: true
                    }).on("select", function () {
                        let attachment = custom_uploader.state().get("selection").first().toJSON();
                        _this.certificates[_this.currentCertificate].thumbnail_id = attachment.id;
                        _this.certificates[_this.currentCertificate].thumbnail = attachment.url;
                        _this.certificates[_this.currentCertificate].image = attachment.url;
                        _this.certificates[_this.currentCertificate].filename = attachment.filename;

                    }).open();
                },
                deleteImage: function () {
                    let _this = this;
                    _this.certificates[_this.currentCertificate].thumbnail_id = '';
                    _this.certificates[_this.currentCertificate].thumbnail = '';
                    _this.certificates[_this.currentCertificate].image = '';
                    _this.certificates[_this.currentCertificate].filename = '';
                },
                addField: function (type) {
                    let _this = this;
                    let content = '';
                    if (typeof _this.fields[type] !== 'undefined') {
                        content = _this.fields[type].value;
                    }
                    let x = 375;
                    if (typeof _this.certificates[_this.currentCertificate] !== 'undefined' && typeof _this.certificates[_this.currentCertificate].data.orientation !== 'undefined') {
                        let orientation = _this.certificates[_this.currentCertificate].data.orientation;
                        if(orientation === 'portrait'){
                            x = 225;
                        }
                    }
                    let height = 50;
                    let styles = {
                        'fontSize': '14px',
                        'fontFamily': 'OpenSans',
                        'color': {
                            'hex': '#000'
                        },
                        'textAlign': 'left',
                        'fontStyle': 'normal',
                        'fontWeight': '400',
                    };
                    if (type === 'image') {
                        height = 150;
                    }
                    let field = {
                        'type': type,
                        'content': content,
                        'x': x,
                        'y': 0,
                        'w': 150,
                        'h': height,
                        'styles': styles,
                        'classes': 'top-align',
                    };

                    if (typeof _this.certificates[_this.currentCertificate] !== 'undefined') {
                        if(typeof _this.certificates[_this.currentCertificate].data.fields !== 'undefined'){
                            _this.certificates[_this.currentCertificate].data.fields.push(field);
                        }
                        else {
                            _this.$set(_this.certificates[_this.currentCertificate].data, 'fields', [field])
                        }

                    }
                },
                deleteCertificate: function(index) {
                    let _this = this;
                    let certificates = _this.certificates;
                    if (typeof certificates[index] !== 'undefined') {
                        if(typeof certificates[index].id !== 'undefined'){
                            let url = stm_lms_ajaxurl + '?action=stm_delete_certificate&nonce=' + stm_lms_nonces['stm_delete_certificate'] + '&certificate_id=' + certificates[index].id;
                            _this.$http.get(url).then(function (res) {
                                certificates.splice(index, 1);
                                _this.$set(_this, 'certificates', certificates);
                            });
                        }
                    }
                },
                deleteField: function (index) {
                    let _this = this;
                    let fields = _this.certificates[_this.currentCertificate].data.fields;
                    if (typeof fields[index] !== 'undefined') {
                        fields.splice(index, 1);
                        _this.$set(_this.certificates[_this.currentCertificate].data, 'fields', fields);
                    }
                },
                saveCertificate: function () {
                    let _this = this;
                    _this.loading = true;
                    if (typeof _this.certificates[_this.currentCertificate] !== 'undefined') {
                        let data = {
                            certificate: _this.certificates[_this.currentCertificate],
                            action: 'stm_save_certificate',
                            nonce: stm_lms_nonces['stm_save_certificate'],
                        };

                        _this.$http.post(stm_lms_ajaxurl, data, {emulateJSON: true}).then(function (r) {
                            if(typeof r.body.id !== 'undefined'){
                                _this.$set(_this.certificates[_this.currentCertificate], 'id', r.body.id);
                            }
                            _this.loading = false;
                            _this.saved = true;
                            setTimeout(function () {
                                _this.saved = false;
                            }, 1000);
                        });
                    }
                },
                onResize: function (left, top, width, height) {
                    let _this = this;
                    if (typeof _this.certificates[_this.currentCertificate].data.fields[_this.activeField] !== 'undefined') {
                        _this.certificates[_this.currentCertificate].data.fields[_this.activeField].x = left;
                        _this.certificates[_this.currentCertificate].data.fields[_this.activeField].y = top;
                        _this.certificates[_this.currentCertificate].data.fields[_this.activeField].w = width;
                        _this.certificates[_this.currentCertificate].data.fields[_this.activeField].h = height;
                    }
                },
                onDrag: function (left, top) {
                    let _this = this;
                    if (typeof _this.certificates[_this.currentCertificate].data.fields[_this.activeField] !== 'undefined') {
                        let width = 600;
                        let classes = '';
                        if(_this.certificates[_this.currentCertificate].data.fields[_this.activeField]['orientation'] === 'landscape'){
                            width = 900;
                        }
                        if((left + 280) > width){
                            classes = 'right-align'
                        }
                        if(top < 120){
                            classes += ' top-align'
                        }
                        _this.$set(_this.certificates[_this.currentCertificate].data.fields[_this.activeField], 'x', left);
                        _this.$set(_this.certificates[_this.currentCertificate].data.fields[_this.activeField], 'y', top);
                        _this.$set(_this.certificates[_this.currentCertificate].data.fields[_this.activeField], 'classes', classes);
                    }
                },
            }
        });

    });
})(jQuery);