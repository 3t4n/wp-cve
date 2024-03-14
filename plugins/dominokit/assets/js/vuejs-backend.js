(function (dominokit, updOption, dominokitPro) {
    'use strict';

    Vue.config.devtools = true;

    let Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        icon: 'success',
        showConfirmButton: false,
        timerProgressBar: true,
        timer: 3000,
        customClass: {
            title: 'woo-swal2-title',
            popup: 'woo-swal2-popup'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    new Vue({
        el: '#admin-ui-option',
        data: {
            options: dominokit.options,
            products: dominokit.products,
            local: (dominokit.local === 'en_US') ? 'top-end' : 'top-start',
            isActivated: 0,
            checkbox: false,
            wooCart: false,
            wooSingle: false,
            wooHidePrice: false,
            wooReplacePrice: false,
            wooShamsi: false,
            wooDatepicker: false,
            txtBtnCart: '',
            urlBtnCart: '',
            urlBtnCartShop: '',
            btnHidePrice: '',
            btnHidePriceUrl: '',
            txtReplacePrice: '',
            isPro: false,
            showMessagePro: false,
            isLicense: false,
            licenseUrl: false
        },
        created: function () {
            if (typeof updOption.woo_unavailable_products !== 'undefined') {
                this.checkbox = (updOption.woo_unavailable_products === 'true');
            }

            if (typeof updOption.cart_button_product_txt !== 'undefined') {
                if (updOption.cart_button_product_txt === '') {
                    this.wooCart = false;
                } else {
                    this.txtBtnCart = updOption.cart_button_product_txt;
                    this.wooCart = true;
                }
            }

            if (typeof updOption.cart_button_product_url !== 'undefined') {
                if (updOption.cart_button_product_url === '') {
                    this.wooSingle = false;
                } else {
                    this.urlBtnCart = updOption.cart_button_product_url;
                    this.wooSingle = true;
                }
            }

            if (typeof updOption.product_hide_price.enabled_hide_price !== 'undefined') {
                if (updOption.product_hide_price.enabled_hide_price === false) {
                    this.wooHidePrice = false;
                } else {
                    if (updOption.product_hide_price.text_hide_price !== '') {
                        this.btnHidePrice = updOption.product_hide_price.text_hide_price;
                    }

                    if (updOption.product_hide_price.url_hide_price !== '') {
                        this.btnHidePriceUrl = updOption.product_hide_price.url_hide_price;
                    }

                    this.wooHidePrice = true;
                }
            }

            if (typeof updOption.product_replace_price !== 'undefined') {
                if (updOption.product_replace_price === '') {
                    this.wooReplacePrice = false;
                } else {
                    this.txtReplacePrice = updOption.product_replace_price;
                    this.wooReplacePrice = true;
                }
            }

            if (typeof updOption.enabled_wooShamsi !== 'undefined') {
                this.wooShamsi = (updOption.enabled_wooShamsi === 'true');
            }

            if (typeof updOption.enabled_wooDatepicker !== 'undefined') {
                this.wooDatepicker = (updOption.enabled_wooDatepicker === 'true');
            }

            if (typeof dominokitPro !== 'undefined') {
                this.isPro = dominokitPro.pro.activated;
                this.isLicense = dominokitPro.license.activated;
                this.licenseUrl = dominokitPro.license.url;
            }

            if (this.btnHidePriceUrl === '' || this.btnHidePrice === '') {
                this.wooHidePrice = false;
            }

        },
        methods: {
            toggleCheckbox() {
                this.checkbox = !this.checkbox;
                let $this = this;

                let formData = new FormData();
                formData.append('action', 'dominokit_option_admin_action');
                formData.append('unavailable_products', this.checkbox);

                axios({
                    url: dominokit.ajax_url,
                    method: 'POST',
                    data: formData,
                }).then(function (res) {
                    Toast.fire({
                        title: res.data.message,
                        icon: 'success',
                        position: $this.local
                    })
                }).catch(function (err) {
                    Toast.fire({
                        icon: 'error',
                        title: err,
                        position: $this.local
                    });
                });
            },
            toggleWooCart() {
                this.wooCart = !this.wooCart;
                let $this = this;

                if (this.wooCart === false) {

                    let formData = new FormData();
                    formData.append('action', 'dominokit_toggleWooCart_remove_action');
                    formData.append('cart_button_remove', this.wooCart);

                    axios({
                        url: dominokit.ajax_url,
                        method: 'POST',
                        data: formData,
                    }).then(function (res) {
                        Toast.fire({
                            title: res.data.message,
                            icon: 'success',
                            position: $this.local
                        })
                    }).catch(function (err) {
                        Toast.fire({
                            icon: 'error',
                            title: err,
                            position: $this.local
                        });
                    });
                }
            },
            toggleWooCartTxt() {
                let $this = this;

                let formData = new FormData();
                formData.append('action', 'dominokit_toggleWooCart_admin_action');
                formData.append('cart_button_product_txt', this.txtBtnCart);

                axios({
                    url: dominokit.ajax_url,
                    method: 'POST',
                    data: formData,
                }).then(function (res) {
                    if (res.data.result !== false) {
                        Toast.fire({
                            title: res.data.message,
                            icon: 'success',
                            position: $this.local
                        });
                    } else {
                        Toast.fire({
                            title: res.data.error,
                            icon: 'error',
                            position: $this.local
                        });
                    }
                }).catch(function (err) {
                    Toast.fire({
                        icon: 'error',
                        title: err,
                    });
                });
            },
            toggleWooSingle() {
                if (!this.isPro) {
                    Toast.fire({
                        icon: 'error',
                        title: dominokit.isPro.message,
                        position: this.local
                    });
                    return;
                }

                this.wooSingle = !this.wooSingle;
                let $this = this;

                if (this.wooSingle === false) {

                    let formData = new FormData();
                    formData.append('action', 'dominokit_toggleWooCartUrl_remove_action');
                    formData.append('cart_button_url_remove', this.wooSingle);

                    axios({
                        url: dominokit.ajax_url,
                        method: 'POST',
                        data: formData,
                    }).then(function (res) {
                        Toast.fire({
                            title: res.data.message,
                            icon: 'success',
                            position: $this.local
                        })
                    }).catch(function (err) {
                        Toast.fire({
                            icon: 'error',
                            title: err,
                            position: $this.local
                        });
                    });
                }
            },
            toggleWooSingleUrl(e, option) {
                let $this = this;

                let char = e.target.value;

                if (/^(https?):\/\/[^\s$.?#].[^\s]*$/.test(char)) {

                    let formData = new FormData();
                    formData.append('action', 'dominokit_toggleWooSingleUrl_admin_action');
                    formData.append('cart_button_product_url', this.urlBtnCart);

                    axios({
                        url: dominokit.ajax_url,
                        method: 'POST',
                        data: formData,
                    }).then(function (res) {
                        Toast.fire({
                            title: res.data.message,
                            icon: 'success',
                            position: $this.local
                        })
                    }).catch(function (err) {
                        Toast.fire({
                            icon: 'error',
                            title: err,
                            position: $this.local
                        });
                    });

                } else {
                    e.preventDefault();
                    Toast.fire({
                        icon: 'error',
                        title: option.errorTxt,
                        position: $this.local
                    });
                }
            },
            toggleWooHidePrice() {
                if (!this.isPro) {
                    Toast.fire({
                        icon: 'error',
                        title: dominokit.isPro.message,
                        position: this.local
                    });
                    return;
                }

                this.wooHidePrice = !this.wooHidePrice;
                let $this = this;

                let formData = new FormData();

                if (this.wooHidePrice !== false) {
                    formData.append('action', 'dominokit_price_hide_action');
                    formData.append('price_hide_enabled', this.wooHidePrice);
                } else {
                    formData.append('action', 'dominokit_price_hide_remove_action');
                }

                axios({
                    url: dominokit.ajax_url,
                    method: 'POST',
                    data: formData,
                }).then(function (res) {
                    Toast.fire({
                        title: res.data.message,
                        icon: 'success',
                        position: $this.local
                    })
                }).catch(function (err) {
                    Toast.fire({
                        icon: 'error',
                        title: err,
                        position: $this.local
                    });
                });
            },
            toggleWooPriceTxt() {
                let $this = this;

                let formData = new FormData();
                formData.append('action', 'dominokit_price_hide_text_action');
                formData.append('btn_hide_price_txt', this.btnHidePrice);

                axios({
                    url: dominokit.ajax_url,
                    method: 'POST',
                    data: formData,
                }).then(function (res) {
                    Toast.fire({
                        title: res.data.message,
                        icon: 'success',
                        position: $this.local
                    })
                }).catch(function (err) {
                    Toast.fire({
                        icon: 'error',
                        title: err,
                        position: $this.local
                    });
                });
            },
            toggleWooPriceUrl(e, option) {
                let $this = this;
                let char = e.target.value;

                if (/^(https?):\/\/[^\s$.?#].[^\s]*$/.test(char)) {

                    let formData = new FormData();
                    formData.append('action', 'dominokit_toggleWooPriceUrl_admin_action');
                    formData.append('btn_hide_price_url', this.btnHidePriceUrl);

                    axios({
                        url: dominokit.ajax_url,
                        method: 'POST',
                        data: formData,
                    }).then(function (res) {
                        Toast.fire({
                            title: res.data.message,
                            icon: 'success',
                            position: $this.local
                        })
                    }).catch(function (err) {
                        Toast.fire({
                            icon: 'error',
                            title: err,
                            position: $this.local
                        });
                    });

                } else {
                    e.preventDefault();
                    Toast.fire({
                        icon: 'error',
                        title: option.errorTxt,
                        position: $this.local
                    });
                }
            },
            toggleWooReplacePrice() {
                if (!this.isPro) {
                    Toast.fire({
                        icon: 'error',
                        title: dominokit.isPro.message,
                        position: this.local
                    });
                    return;
                }

                this.wooReplacePrice = !this.wooReplacePrice;
                let $this = this;

                if (this.wooReplacePrice === false) {
                    let formData = new FormData();
                    formData.append('action', 'dominokit_remove_replace_text_zero_action');

                    axios({
                        url: dominokit.ajax_url,
                        method: 'POST',
                        data: formData,
                    }).then(function (res) {
                        Toast.fire({
                            title: res.data.message,
                            icon: 'success',
                            position: $this.local
                        });
                    }).catch(function (err) {
                        Toast.fire({
                            icon: 'error',
                            title: err,
                            position: $this.local
                        });
                    });
                }
            },
            toggleWooReplaceTxt() {
                let $this = this;

                let formData = new FormData();
                formData.append('action', 'dominokit_replace_text_zero_action');
                formData.append('price_replace_text', this.txtReplacePrice);

                axios({
                    url: dominokit.ajax_url,
                    method: 'POST',
                    data: formData,
                }).then(function (res) {
                    Toast.fire({
                        title: res.data.message,
                        icon: 'success',
                        position: $this.local
                    });
                }).catch(function (err) {
                    Toast.fire({
                        icon: 'error',
                        title: err,
                        position: $this.local
                    });
                });
            },
            toggleShamsi() {
                this.wooShamsi = !this.wooShamsi;
                let $this = this;

                let formData = new FormData();
                formData.append('action', 'dominokit_shamsi_enabled_action');
                formData.append('opt_wooShamsi', this.wooShamsi);

                axios({
                    url: dominokit.ajax_url,
                    method: 'POST',
                    data: formData,
                }).then(function (res) {
                    Toast.fire({
                        title: res.data.message,
                        icon: 'success',
                        position: $this.local
                    })
                }).catch(function (err) {
                    Toast.fire({
                        icon: 'error',
                        title: err,
                        position: $this.local
                    });
                });
            },
            toggleDatepicker() {
                this.wooDatepicker = !this.wooDatepicker;
                let $this = this;

                let formData = new FormData();
                formData.append('action', 'dominokit_datepicker_enabled_action');
                formData.append('opt_wooDatepicker', this.wooDatepicker);

                axios({
                    url: dominokit.ajax_url,
                    method: 'POST',
                    data: formData,
                }).then(function (res) {
                    Toast.fire({
                        title: res.data.message,
                        icon: 'success',
                        position: $this.local
                    })
                }).catch(function (err) {
                    Toast.fire({
                        icon: 'error',
                        title: err,
                        position: $this.local
                    });
                });
            },
            isEnglish(e, option) {
                let char = String.fromCharCode(e.keyCode);
                if (/^[A-Za-z0-9]+$/.test(char)) {
                    return true;
                } else {
                    e.preventDefault();
                    Toast.fire({
                        icon: 'error',
                        title: option.errorTxt,
                    });
                }
            },
            switchPro() {
                this.isActivated = 3;
            }
        }
    });
})(dominokit, updOption, window.dominokitPro)