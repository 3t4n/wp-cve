(function () {
    'use strict';
    const paged = {
        product: 1, post: 1
    };

    const maxPage = {
        product: 1, post: 1,
    }
    const {html} = emailBuilder.utils;
    const {TextInput, FieldWrapper, Select, RadioGroup} = emailBuilder.ui;
    let ruleCountries = [];
    let ruleCategories = [];
    let ruleMaxOrder = '';
    let ruleMinOrder = '';
    let purchaseCode = '';
    let emailTesting = '';
    let addedToCartXMinutes = 15;
    let afterOrderStatusPending = 'none';
    let afterOrderStatusFailed = 'none';
    let generalSetting = {
        "automatically": ""
    };
    let setUpPurchaseCode = {
        'email': '',
        'website': '',
        'captcha': ''
    };

    function resetVariable() {
        ruleCountries = [];
        ruleCategories = [];
        ruleMaxOrder = '';
        ruleMinOrder = '';
        purchaseCode = '';
        emailTesting = '';
        addedToCartXMinutes = 15;
        afterOrderStatusPending = 'none';
        afterOrderStatusFailed = 'none';
        generalSetting = {
            "automatically": ""
        };
    }

    function wpStyle() {
        const wpBodyContentEl = document.querySelector('#wpbody-content');
        const wpContentEl = document.querySelector('#wpcontent');
        const wpwrapEl = document.querySelector('#wpwrap');

        Array.from(wpBodyContentEl.children).forEach(el => {
            if (!el.classList.contains('wiloke-email-builder')) {
                el.remove();
            }
            wpContentEl.style.paddingLeft = '0px';
        });

        wpwrapEl.classList.add('bgc:color-gray1');
    }

    wpStyle();

    const modal = emailBuilder.createModal({
        headerText: WILMT_GLOBAL.headerTextModal, children: (data) => html`
            <div>We found <b>${data.titles}</b> campaigns are running on the same page and it will <b>CAUSE CONFLICT
                PROBLEM</b> with
                this campaign. Do you still disable the previous Campaigns?
            </div>
        `, onOk: ({data, onClose}) => {
            jQuery.ajax({
                type: 'POST', url: ajaxurl, data: {
                    action: "wilETP_updateForceActive", ids: data.ids
                }, success: function (response) {
                    if (response.status === 'success') {
                        if (data.actionStatusChange) {
                            jQuery.ajax({
                                type: 'POST', url: ajaxurl, data: {
                                    action: "wilETP_updateStatusTemplate", beId: data.template.BeId, status: data.status
                                }, success: function (response) {
                                    emailBuilder.disableTemplates(data.feIds.split(","));
                                    onClose();
                                }, error: function (jqXHR, error) {
                                    console.log(error);
                                },
                            })
                        } else {
                            if (data.actionTypeCreate) {
                                jQuery.ajax({
                                    type: 'POST',
                                    url: ajaxurl + "?action=wilETP_createTemplate",
                                    dataType: "json",
                                    contentType: "application/json;charset=utf-8",
                                    data: JSON.stringify({
                                        action: "wilETP_createTemplate", data: data.dataTemplate, html: data.dataHtml
                                    }),
                                    success: function (response) {
                                        if (response.status === 'success') {
                                            data.done(response.data);
                                            data.goBack();
                                            emailBuilder.disableTemplates(data.feIds.split(","));
                                            onClose();
                                        } else {
                                            throw new Error(response.message);
                                        }
                                    },
                                    error: function (jqXHR, error) {
                                        console.log(error);
                                    },
                                })
                            } else {
                                jQuery.ajax({
                                    type: 'POST',
                                    url: ajaxurl + "?action=wilETP_updateTemplate",
                                    dataType: "json",
                                    contentType: "application/json;charset=utf-8",
                                    data: JSON.stringify({
                                        action: "wilETP_updateTemplate", data: data.dataTemplate, html: data.dataHtml
                                    }),
                                    success: function (response) {
                                        if (response.status === 'success') {
                                            data.done(response.data);
                                            data.goBack();
                                            emailBuilder.disableTemplates(data.feIds.split(","));
                                            onClose();
                                        } else {
                                            throw new Error(response.message);
                                        }
                                    },
                                    error: function (jqXHR, error) {
                                        console.log(error);
                                    },
                                })
                            }
                        }
                    } else {
                        throw new Error(response.message);
                    }
                }, error: function (jqXHR, error) {
                    console.log(error);
                },
            })
        }, onCancel: ({data}) => {
            if (data.onCancel) {
                data.onCancel();
            }
        }
    });

    const emailTestingModal = emailBuilder.createModal({
        headerText: WILMT_GLOBAL.headerTextEmailTestingModal,
        okText: 'Send',
        children: (data) => {
            if (data.successMessage) {
                return data.successMessage;
            }
            let error = "";
            if (data.errorMessage === 'error-config-mail') {
                error = html`
                    <div> ${WILMT_GLOBAL.messageErrorMail}<a href="${WILMT_GLOBAL.linkDocSMTP}" target="_blank"
                                                             style=${{textDecoration: 'underline'}}>SMTP</a> to resolve
                        the issue.
                    </div>`
            } else {
                error = data.errorMessage;
            }
            return html`
                <div>
                    <${FieldWrapper} horizontal error=${error} label=${WILMT_GLOBAL.labelTestingModal}>
                        <${TextInput} defaultValue=${data.emailTesting} onChange=${(value) =>
                                (emailTesting = value)}/>
                    </${FieldWrapper}>
                </div>
            `;
        },
        onOk: ({data, onClose}) => {
            const templateTransformer = emailBuilder.createTemplateTransformer(
                data.templateDetail
            );
            const newTemplateDetail = templateTransformer({
                variables: {...WILMT_GLOBAL.variables.account, ...WILMT_GLOBAL.variables.orders}
            });
            jQuery.ajax({
                type: 'POST', url: ajaxurl, data: {
                    action: "wilETP_testingSendEmail",
                    email: emailTesting,
                    emailSubject: newTemplateDetail.emailSubject,
                    html: emailBuilder.getHtml(newTemplateDetail)
                }, success: function (response) {
                    if (response.status === 'success') {
                        emailTestingModal.dispatch({
                            successMessage: response.message
                        });
                        emailTestingModal.onLoading(false);
                        setTimeout(function () {
                            if (response.data.isReview) {
                                reviewModal.onOpen();
                            }
                        }, 2000)
                    } else {
                        throw new Error(response.message);
                    }
                }, error: function (jqXHR, error) {
                    emailTestingModal.dispatch({
                        errorMessage: jqXHR.responseJSON.message
                    });
                    emailTestingModal.onLoading(false);
                },
            })
        }
    });

    const ruleUpgradePlanModal = emailBuilder.createModal({
        headerText: WILMT_GLOBAL.upgradePlanModal,
        children: html`
            <div>${WILMT_GLOBAL.notionUpgradePlanModal}
            </div>
        `,
        onOk: ({onClose, data}) => {
            onClose()
            if (data && data.onCancel) {
                data.onCancel();
            }
        },
        onCancel: ({data}) => {
            if (data && data.onCancel) {
                data.onCancel();
            }
        }
    });

    const ruleComingSoonModal = emailBuilder.createModal({
        headerText: WILMT_GLOBAL.comingSoonModal,
        children: html`
            <div>${WILMT_GLOBAL.notionComingSoonModal}
            </div>
        `,
        onOk: ({onClose, data}) => {
            onClose()
            if (data && data.onCancel) {
                data.onCancel();
            }
        },
        onCancel: ({data}) => {
            if (data && data.onCancel) {
                data.onCancel();
            }
        }
    });

    const ruleModal = emailBuilder.createModal({
        headerText: WILMT_GLOBAL.headerTextRuleModal,
        children: (data) => {
            return html`
                <div>
                    <${FieldWrapper} horizontal label=${WILMT_GLOBAL.labelCountries}>
                        <${Select}
                                defaultValue=${data.ruleCountries}
                                options=${WILMT_GLOBAL.countries}
                                mode="multiple"
                                onChange=${(value) => {
                                    ruleCountries = value;
                                }}
                        />
                    </${FieldWrapper}>
                    <${FieldWrapper} horizontal label=${WILMT_GLOBAL.labelCategories}>
                        <${Select}
                                defaultValue=${data.ruleCategories}
                                options=${WILMT_GLOBAL.categories}
                                mode="multiple"
                                onChange=${(value) => {
                                    ruleCategories = value;
                                }}
                        />
                    </${FieldWrapper}>
                    <${FieldWrapper} horizontal label=${WILMT_GLOBAL.labelMaxOrder}>
                        <${TextInput} defaultValue=${data.ruleMaxOrder} type="text" placeholder="Eg: 30"
                                      onChange=${(value) => {
                                          ruleMaxOrder = value;
                                      }}/>
                    </${FieldWrapper}>
                    <${FieldWrapper} horizontal label=${WILMT_GLOBAL.labelMinOrder}>
                        <${TextInput} defaultValue=${data.ruleMinOrder} type="text" placeholder="Eg: 30"
                                      onChange=${(value) => {
                                          ruleMinOrder = value;
                                      }}/>
                    </${FieldWrapper}>
                    ${data.templateDetail.emailType.value === 'cart_abandonment' && html`
                        <${FieldWrapper} horizontal label=${WILMT_GLOBAL.labelAddedToCartXMinutes}>
                            <${TextInput} defaultValue=${data.addedToCartXMinutes} type="text" placeholder="Eg: 15'"
                                          onChange=${(value) => {
                                              addedToCartXMinutes = value;
                                          }}/>
                        </${FieldWrapper}>
                        <${FieldWrapper} horizontal label=${WILMT_GLOBAL.labelAfterOrderStatusPending}>
                            <${RadioGroup}
                                    value=${afterOrderStatusPending}
                                    options=${[
                                        {label: 'None', value: 'none'},
                                        {label: 'Active', value: 'active'},
                                        {label: 'Deactive', value: 'deactive'},
                                    ]}
                                    onChange=${(value) => {
                                        afterOrderStatusPending = value;
                                    }}
                            />
                        </${FieldWrapper}>
                        <${FieldWrapper} horizontal label=${WILMT_GLOBAL.labelAfterOrderStatusFailed}>
                            <${RadioGroup}
                                    value=${afterOrderStatusFailed}
                                    options=${[
                                        {label: 'None', value: 'none'},
                                        {label: 'Active', value: 'active'},
                                        {label: 'Deactive', value: 'deactive'},
                                    ]}
                                    onChange=${(value) => {
                                        afterOrderStatusFailed = value;
                                    }}
                            />
                        </${FieldWrapper}>
                    `}
                </div>
            `
        },
        onOk: ({onClose}) => {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl + "?action=wilETP_checkPackage",
                dataType: "json",
                contentType: "application/json;charset=utf-8",
                data: JSON.stringify({
                    action: "wilETP_checkPackage"
                }),
                success: function (response) {
                    if (response.status === 'success') {
                        emailBuilder.setTemplateDetailProperty('rules', {
                            ruleCountries,
                            ruleCategories,
                            ruleMaxOrder,
                            ruleMinOrder,
                            addedToCartXMinutes,
                            afterOrderStatusPending,
                            afterOrderStatusFailed
                        })
                        onClose();
                    } else {
                        throw new Error('error');
                    }
                },
                error: function (jqXHR, error) {
                    ruleUpgradePlanModal.onOpen();
                },
            })
        }
    });

    const reviewPurchaseCodeModal = emailBuilder.createModal({
        children: (data)=>{
            let linkReview = data.link;
            return html`
            <div class="ta:center">
                <div class="mb:10px"><h2>${WILMT_GLOBAL.textPCMReview}</h2></div>
                <div class="mb:10px">${WILMT_GLOBAL.descPCMReview}</div>
                <div class="d:flex ai:center jc:center">
                    <button class="bd:0 bxsh:none bgc:color-gray1 c:color-gray9 p:0_20px h:36px d:flex jc:center ai:center ff:font-secondary fw:500 fz:12px bdrs:6px cur:pointer mr:8px">
                        <a target="_blank" href="${WILMT_GLOBAL.linkFeatures}">${WILMT_GLOBAL.buttonFeatures}</a>
                    </button>
                    <button class="bgi:linear-gradient(60deg,color-primary,color-secondary) bd:0 bxsh:none c:color-light-freeze p:0_20px h:36px d:flex jc:center ai:center ff:font-secondary fw:500 fz:12px bdrs:6px cur:pointer">
                        <span><a target="_blank"
                                 href="${linkReview}">${WILMT_GLOBAL.buttonReview}</a></span></button>
                </div>
            </div>
        `;
        },
        footerDisabled: true
    });

    const generalSettingsModal = emailBuilder.createModal({
        headerText: WILMT_GLOBAL.generalSettings,
        children: html`
            <div>
                <${FieldWrapper} horizontal label=${WILMT_GLOBAL.AutomaticallyLabel}>
                    <${RadioGroup}
                            value=${WILMT_GLOBAL.automatically}
                            options=${[
                                {label: 'Active', value: 'active'},
                                {label: 'Deactive', value: 'deactive'},
                            ]}
                            onChange=${(value) => {
                                generalSetting.automatically = value;
                            }}
                    />
                </${FieldWrapper}>
            </div>
        `,
        onOk: ({onClose, data}) => {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: "wilETP_generalSettings",
                    generalSetting
                },
                success: function (response) {
                    if (response.status === 'success') {
                        onClose();
                        window.location.reload();
                    } else {
                        throw new Error('error');
                    }
                },
                error: function (jqXHR, error) {
                    onClose();
                },
            })
            if (data && data.onCancel) {
                data.onCancel();
            }
        },
        onCancel: ({data}) => {
            if (data && data.onCancel) {
                data.onCancel();
            }
        }
    });

    const reviewModal = emailBuilder.createModal({
        headerText: '',
        children: html`
            <div class="ta:center">
                <div class="mb:10px">${WILMT_GLOBAL.textReview}</div>
                <div class="d:flex ai:center jc:center">
                    <button class="bd:0 bxsh:none bgc:color-gray1 c:color-gray9 p:0_20px h:36px d:flex jc:center ai:center ff:font-secondary fw:500 fz:12px bdrs:6px cur:pointer mr:8px">
                        <a target="_blank" href="${WILMT_GLOBAL.linkFeatures}">${WILMT_GLOBAL.buttonFeatures}</a>
                    </button>
                    <button class="bgi:linear-gradient(60deg,color-primary,color-secondary) bd:0 bxsh:none c:color-light-freeze p:0_20px h:36px d:flex jc:center ai:center ff:font-secondary fw:500 fz:12px bdrs:6px cur:pointer">
                        <span><a target="_blank"
                                 href="${WILMT_GLOBAL.linkReview}">${WILMT_GLOBAL.buttonReview}</a></span></button>
                </div>
            </div>
        `,
        footerDisabled: true
    });

    const purchaseCodeModal = emailBuilder.createModal({
        headerText: WILMT_GLOBAL.headerTextPurchase,
        children: (data) => {
            let label = html`Enter your <a href="${WILMT_GLOBAL.docPlugin}" target="_blank"
                                           style=${{textDecoration: 'underline'}}>Purchase
                code</a> here and click Submit button`;
            return html`
                <div>
                    <${FieldWrapper} horizontal label=${label}>
                        <${TextInput} defaultValue=${data.ruleMaxOrder} type="text"
                                      placeholder=${WILMT_GLOBAL.enterPurchaseCode}
                                      onChange=${(data) => {
                                          purchaseCode = data;
                                      }}/>
                    </${FieldWrapper}>
                </div>
            `;
        }, onOk: ({onClose}) => {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: "wilETP_verifyPurchaseCode",
                    purchaseCode
                },
                success: function (response) {
                    if (response.status === 'success') {
                        reviewPurchaseCodeModal.onOpen({
                            link: response.data.link
                        });
                        setTimeout(function () {
                            window.location.reload();
                        }, 10000)
                    }
                },
                error: function (jqXHR, error) {
                    alert(jqXHR.responseJSON.message);
                    onClose();
                },
            })
        }
    });


    function fetchAPI({
                          type = 'POST',
                          url = ajaxurl,
                          data
                      }, actions, cache = undefined, cacheId = undefined, forceRequest = false) {
        actions.request();
        if (cache && cache.get(cacheId)) {
            actions.success(cache.get(cacheId));
            if (!forceRequest) {
                return;
            }
        }
        jQuery.ajax({
            type, url, data, success: function (response) {
                if (response.status === 'success') {
                    actions.success(response.data);
                    cache && cache.set(cacheId, response.data);
                } else {
                    throw new Error(response.message);
                }
            }, error: function (jqXHR, error, errorThrown) {
                actions.failure(error.message);
                alert(error.message);
            },
        })
    }

    function handleImage(callback) {
        let file_frame = wp.media.frames.file_frame = wp.media({
            title: WILMT_GLOBAL.titleUpload, library: {
                type: 'image'
            }, button: {
                text: 'Select'
            }, multiple: false
        });
        file_frame.on('select', function () {
            let attachment = file_frame.state().get('selection').first().toJSON();
            callback(attachment.url);
        });
        file_frame.open();
    }

    emailBuilder
        .setConfig({
            logo: WILMT_GLOBAL.logo,
            role: "admin",
            customerTemplatesSelector: "#customer-templates-root",
            productOptions: WILMT_GLOBAL.productOptions,
            emailTypes: WILMT_GLOBAL.emailTypes,
            currency: {
                active: WILMT_GLOBAL.currency.active,
                position: WILMT_GLOBAL.currency.position,
                symbol: WILMT_GLOBAL.currency.symbol
            },
            package: WILMT_GLOBAL.package,
            brandContentSection: WILMT_GLOBAL.brandContentSection
        })
        .addBuilderHeaderButtons({
            right: [
                {
                    icon: "far fa-send-back", label: "Rules", onClick: ({templateDetail}) => {
                        ruleModal.onOpen({
                            ruleCountries,
                            ruleCategories,
                            ruleMaxOrder,
                            ruleMinOrder,
                            addedToCartXMinutes,
                            templateDetail
                        });
                    }
                },
                {
                    icon: "far fa-envelope",
                    label: "Testing",
                    onClick: ({templateDetail}) => {
                        emailTestingModal.onOpen({
                            templateDetail,
                            emailTesting
                        });
                    }
                }
            ]
        })
        .on("getTemplates", async ({cache, actions}) => {
            const cacheId = "templates";
            fetchAPI({
                type: "POST", data: {
                    action: "wilETP_getTemplates"
                }
            }, actions, cache, cacheId);
        })
        .on("getCustomerTemplates", async ({cache, actions}) => {
            fetchAPI({
                type: "POST", data: {
                    action: "wilETP_getCustomerTemplates"
                }
            }, actions);
        })
        .on("imageClick", ({done}) => {
            handleImage(done);
        })
        .on("getCategories", async ({cache, actions}) => {
            let cacheId = 'categories';
            fetchAPI({
                type: "POST", data: {
                    action: "wilETP_getCategories"
                }
            }, actions, cache, cacheId);
        })
        .on("getTemplateDetail", async ({actionType, template, cache, actions}) => {
            resetVariable();
            if (template.emailType.type === 'order') {
                emailBuilder.setPlaceholderVariables(WILMT_GLOBAL.variables.orders);
            } else {
                emailBuilder.setPlaceholderVariables(WILMT_GLOBAL.variables.account);
            }
            fetchAPI({
                type: "POST", data: {
                    action: "wilETP_getTemplateDetail", template: template
                }
            }, {
                request: actions.request,
                success: (data) => {
                    actions.success(data);
                    if (data.rules) {
                        ruleCountries = data.rules.ruleCountries;
                        ruleCategories = data.rules.ruleCategories;
                        ruleMaxOrder = data.rules.ruleMaxOrder;
                        ruleMinOrder = data.rules.ruleMinOrder;
                        addedToCartXMinutes = data.rules.addedToCartXMinutes;
                        afterOrderStatusPending = data.rules.afterOrderStatusPending;
                        afterOrderStatusFailed = data.rules.afterOrderStatusFailed;
                    }
                },
                failure: actions.failure,
            }, cache, template.id, true);
        })
        .on("getSections", async ({categoryId, cache, actions}) => {
            fetchAPI({
                type: "POST", data: {
                    action: "wilETP_getSection", categoryId: categoryId
                }
            }, actions, cache, categoryId);
        })
        .on("duplicateTemplate", ({template, done}) => {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl + "?action=wilETP_duplicateTemplate",
                dataType: "json",
                contentType: "application/json;charset=utf-8",
                data: JSON.stringify({
                    action: "wilETP_duplicateTemplate", data: template
                }),
                success: function (response) {
                    if (response.status === 'success') {
                        done(response.data);
                    } else {
                        throw new Error(response.message);
                    }
                },
                error: function (jqXHR, error) {
                    actions.failure(error.message);
                },
            })
        })
        .on("deleteTemplate", ({template, done}) => {
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl + "?action=wilETP_deleteTemplate",
                dataType: "json",
                contentType: "application/json;charset=utf-8",
                data: JSON.stringify({
                    action: "wilETP_deleteTemplate", data: template
                }),
                success: function (response) {
                    if (response.status === 'success') {
                        done();
                    } else {
                        throw new Error(response.message);
                    }
                },
                error: function (jqXHR, error) {
                    console.log(error)
                },
            })
        })
        .on("exportTemplate", async ({template, done}) => {
            emailBuilder.exportTemplate(template);
            done();
        })
        .on("getProducts", async ({search, actions}) => {
            paged.product = 1;
            actions.request();
            jQuery.ajax({
                type: 'POST', url: ajaxurl, data: {
                    action: "wilETP_getProducts", search: search, paged: paged.product
                }, success: function (response) {
                    if (response.status === 'success') {
                        actions.success(response.data.items);
                        maxPage.product = response.data.maxPages
                    } else {
                        throw new Error(response.message);
                    }
                }, error: function (jqXHR, error) {
                    actions.failure(error.message);
                },
            })
        })
        .on("loadMoreProducts", async ({search, actions}) => {
            if (paged.product < maxPage.product) {
                paged.product++;
                actions.request();
                jQuery.ajax({
                    type: 'POST', url: ajaxurl, data: {
                        action: "wilETP_loadMoreProducts", search: search, page: paged.product
                    }, success: function (response) {
                        if (response.status === 'success') {
                            actions.success(response.data.items);
                            maxPage.product = response.data.maxPages
                        } else {
                            throw new Error(response.message);
                        }
                    }, error: function (jqXHR, error, errorThrown) {
                        actions.failure(error.message);
                    },
                })
            }
        })
        .on("getPosts", async ({search, actions}) => {
            paged.post = 1;
            actions.request();
            jQuery.ajax({
                type: 'POST', url: ajaxurl, data: {
                    action: "wilETP_getPosts", search: search, paged: paged.post
                }, success: function (response) {
                    if (response.status === 'success') {
                        actions.success(response.data.items);
                        maxPage.post = response.data.maxPages
                    } else {
                        throw new Error(response.message);
                    }
                }, error: function (jqXHR, error) {
                    actions.failure(error.message);
                },
            })
        })
        .on("loadMorePosts", async ({search, actions}) => {
            if (paged.post < maxPage.post) {
                paged.post++;
                actions.request();
                jQuery.ajax({
                    type: 'POST', url: ajaxurl, data: {
                        action: "wilETP_loadMorePosts", search: search, page: paged.post
                    }, success: function (response) {
                        if (response.status === 'success') {
                            actions.success(response.data.items);
                            maxPage.post = response.data.maxPages
                        } else {
                            throw new Error(response.message);
                        }
                    }, error: function (jqXHR, error) {
                        actions.failure(error.message);
                    },
                })
            }
        })
        .on("templateStatusChange", ({template, status, onCancel}) => {
            if (status === 'enabled') {
                let request = jQuery.ajax({
                    type: 'POST', url: ajaxurl, data: {
                        action: "wilETP_checkEmailTypeExist", emailType: template.emailType
                    }, success: function (response) {
                        if (response.status === 'success') {
                            if (response.data.isExist) {
                                const id = setTimeout(() => {
                                    modal.onOpen({
                                        ids: response.data.ids,
                                        feIds: response.data.feIds,
                                        titles: response.data.titles,
                                        actionTypeCreate: false,
                                        actionStatusChange: true,
                                        template,
                                        status,
                                        onCancel
                                    });
                                    clearTimeout(id);
                                }, 400);
                            } else {
                                jQuery.ajax({
                                    type: 'POST', url: ajaxurl, data: {
                                        action: "wilETP_updateStatusTemplate", beId: template.BeId, status: status
                                    }, success: function (response) {
                                    }, error: function (jqXHR, error) {
                                        throw new Error(response.message);
                                    },
                                })
                            }
                        } else {
                            throw new Error(response.message);
                        }
                    }, error: function (jqXHR, error) {
                        console.log(jqXHR)
                    },
                })
                if (jQuery.active > 1) {
                    request.abort();
                }
            } else {
                let request = jQuery.ajax({
                    type: 'POST', url: ajaxurl, data: {
                        action: "wilETP_updateStatusTemplate", beId: template.BeId, status: status
                    }, success: function (response) {
                    }, error: function (jqXHR, error) {
                        request.abort();
                    },
                })
                if (jQuery.active > 1) {
                    request.abort();
                }
            }
        })
        .on("saveTemplate", ({actionType, templateDetail, html, needUpgrade, onCancel, done, goBack}) => {
            if (templateDetail.emailType.value === 'cart_abandonment' && WILMT_GLOBAL.package.type === 'free') {
                ruleUpgradePlanModal.onOpen({
                    done,
                    goBack,
                    onCancel
                });
            } else {
                if (needUpgrade) {
                    ruleUpgradePlanModal.onOpen({
                        done,
                        goBack,
                        onCancel
                    });
                } else {
                    jQuery.ajax({
                        type: 'POST', url: ajaxurl, data: {
                            action: "wilETP_checkEmailTypeExist",
                            emailType: templateDetail.emailType,
                            id: templateDetail.BeId ?? 0,
                        }, success: function (response) {
                            if (response.status === 'success') {
                                if (response.data.isExist) {
                                    modal.onOpen({
                                        ids: response.data.ids,
                                        feIds: response.data.feIds,
                                        titles: response.data.titles,
                                        dataTemplate: templateDetail,
                                        dataHtml: html,
                                        actionTypeCreate: actionType === 'create',
                                        actionStatusChange: false,
                                        done,
                                        goBack,
                                        onCancel
                                    });
                                } else {
                                    if (actionType === 'create') {
                                        jQuery.ajax({
                                            type: 'POST',
                                            url: ajaxurl + "?action=wilETP_createTemplate",
                                            dataType: "json",
                                            contentType: "application/json;charset=utf-8",
                                            data: JSON.stringify({
                                                action: "wilETP_createTemplate", data: templateDetail, html: html
                                            }),
                                            success: function (response) {
                                                if (response.status === 'success') {
                                                    done(response.data);
                                                    goBack();
                                                } else {
                                                    throw new Error(response.message);
                                                }
                                            },
                                            error: function (jqXHR, error) {
                                                console.log(error);
                                            },
                                        })
                                    } else {
                                        jQuery.ajax({
                                            type: 'POST',
                                            url: ajaxurl + "?action=wilETP_updateTemplate",
                                            dataType: "json",
                                            contentType: "application/json;charset=utf-8",
                                            data: JSON.stringify({
                                                action: "wilETP_updateTemplate", data: templateDetail, html: html
                                            }),
                                            success: function (response) {
                                                if (response.status === 'success') {
                                                    done(response.data);
                                                    goBack();
                                                } else {
                                                    throw new Error(response.message);
                                                }
                                            },
                                            error: function (jqXHR, error) {
                                                console.log(error);
                                            },
                                        })
                                    }
                                }
                            } else {
                                throw new Error(response.message);
                            }
                        }, error: function (jqXHR, error) {
                            console.log(error);
                        },
                    })
                }
            }
        });
    let headerButtons = [
        {
            icon: 'far fa-user-cog',
            label: 'General Setting',
            onClick: () => {
                generalSettingsModal.onOpen()
            },
        }
    ];
    if (WILMT_GLOBAL.package.type === 'free') {
        headerButtons.push({
            icon: 'far fa-crown',
            label: 'Upgrade',
            onClick: () => {
                purchaseCodeModal.onOpen({})
            },
        });
    }
    emailBuilder.setHeaderContent({
        title: 'My Template',
        description: WILMT_GLOBAL.introMyTemplate,
    })
    emailBuilder.addHeaderButtons(headerButtons)
    emailBuilder.setContentWithoutTemplates({
        title: WILMT_GLOBAL.pluginName,
        description: WILMT_GLOBAL.intro,
        buttons: headerButtons
    })
})(jQuery);