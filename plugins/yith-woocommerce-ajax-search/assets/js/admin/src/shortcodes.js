const YWCAS_Admin_Shortcodes = () => {
    const security = ywcas_admin_params.shortcodeNonce;
    let target_deps = [];
    let target_deps_id = [];
    const init = () => {
        jQuery(document).on('click', '.yith-plugin-fw__action-button--edit-action', editShortcode);
        jQuery(document).on('click', '.ywcas-save-shortcode', submitForm);
        jQuery(document).on('click', '.ywcas-add-shortcode', addNewShortcode);
        jQuery(document).on('click', '.yith-plugin-fw__action-button--trash-action', deleteShortcode);
        jQuery(document).on('click', '.yith-plugin-fw__action-button--duplicate-action', cloneShortcode);
        jQuery(document).on('change', '.ywcas-shortcode-field', toggleTab);

        initFields();
        handleFieldsChange();
    };

    const initFields = () => {
        const fields = jQuery(document).find('[data-ywcas-deps]');
        fields.each(function () {
            let t = jQuery(this);
            handleField(t);
        });
    }

    const toggleTab = (event)=>{
        const field = jQuery(event.target),
            value = event.target.value,
            tabs = field.parents('.ywcas-shortcode__options__form').find('.yith-plugin-fw__tabs'),
            tab =  tabs.find('li.submit-button');
        if( 'classic' !== value) {
            tab.hide();
        }else{
            tab.show();
        }
    }
    const handleField = (field) => {
        let parent = field.closest('.yith-plugin-fw__panel__option__content'),
            deps = field.data('ywcas-deps'),
            show = true;

        jQuery.each(deps, function (i, dep) {
            let target_dep = jQuery('#' + dep.id),
                compare =
                    typeof dep.compare === 'undefined' ? '==' : dep.compare,
                property =
                    typeof dep.property === 'undefined' ? false : dep.property,
                current_value;

            // it's a radio button.
            if (target_dep.hasClass('yith-plugin-fw-radio')) {
                current_value = target_dep
                    .find('input[type="radio"]')
                    .filter(':checked')
                    .val();
            } else if (target_dep.hasClass('yith-plugin-fw-checkbox-array')) {
                const checked = target_dep
                    .find('input[type="checkbox"]')
                    .filter(':checked')

                const values = [];
                jQuery.each(checked, function (i, check) {
                    values.push(jQuery(check).val());
                });
                current_value = values;
            } else if (
                target_dep.hasClass('yith-plugin-fw-select') ||
                target_dep.hasClass('yith-post-search') ||
                target_dep.hasClass('wc-enhanced-select')
            ) {
                current_value = target_dep.val();
            } else if (
                target_dep.hasClass('yith-plugin-fw-onoff-container')
            ) {
                current_value = target_dep
                    .find('input[type="checkbox"]')
                    .is(':checked')
                    ? 'yes'
                    : 'no';
            } else {
                current_value = target_dep.is(':checked') ? 'yes' : 'no';
            }

            if (target_deps_id.indexOf(dep.id) < 0) {
                target_deps.push(target_dep);
                target_deps_id.push(dep.id);
            }
            if (show) {
                let value = dep.value.split(',');
                let isArray = Array.isArray(current_value);
                switch (compare) {
                    case '==':
                    case '===':
                        if (isArray) {
                            const filteredArray = value.filter(val => current_value.includes(val));
                            show = filteredArray.length > 0;
                        } else {
                            show = value.indexOf(current_value) >= 0;
                        }
                        break;
                    case '!=':
                    case '!==':
                        if (isArray) {
                            const filteredArray = value.filter(val => current_value.includes(val));
                            show = filteredArray.length === 0;
                        } else {
                            show = value.indexOf(current_value) < 0;
                        }
                        break;
                }
            }
        });

        if (show) {
            parent.show();
        } else {
            parent.hide();
        }
    }

    const handleFieldsChange = () => {
        jQuery.each(target_deps, function (i, field) {
            field.on('change', function () {
                initFields();
            });
        });
    }
    const editShortcode = (event) => {
        event.preventDefault();
        const row = jQuery(event.target).parents('.ywcas-row');
        const options = row.find('.ywcas-edit');
        if (row.is('.ywcas-row-opened')) {
            row.removeClass('ywcas-row-opened');
            options.slideUp();
        } else {
            row.addClass('ywcas-row-opened');
            options.slideDown();
        }
    };

    const getNewSlug = () => {
        const shortcodes = document.querySelectorAll('.ywcas-edit');
        const suff = 'presets-';
        let slug = '';
        let i = 1;
        let exists = true;
        do {
            slug = suff + (shortcodes.length + i++);
            exists = slugExists(slug);
        } while (exists);

        return slug;
    };

    const slugExists = (slug) => {
        const shortcodes = document.querySelectorAll('.ywcas-edit');
        let exists = false;
        shortcodes.forEach(s => {
            const currentSlug = s.dataset.target;
            if (currentSlug === slug) {
                exists = true;
                return;
            }
        });
        return exists;
    };

    const getSlug = (name) => {
        return name.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
    };

    const submitForm = (event) => {
        event.preventDefault();
        event.stopPropagation();
        const form = jQuery(event.target.closest('form'));
        const slug = form.data('preset');

        const formData = new FormData();
        const block_params = {
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            },
            ignoreIfBlocked: true
        };

        jQuery.each(form.serializeArray(), function (i, field) {
            formData.append(field.name, field.value);
        });
        formData.append('slug', slug);
        formData.append('security', security);
        formData.append('action', 'yith_wcas_save_shortcode');

        jQuery.ajax({
            url: ywcas_admin_params.ajaxurl,
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            type: 'POST',
            beforeSend:function (){
                form.block(block_params);
            },
            success: function (response) {
                if (response?.data?.content) {
                    jQuery(document).find('.ywcas-shortcodes-list').replaceWith(jQuery(response.data.content))
                    jQuery(document).trigger('yith-plugin-fw-tabs-init');
                    jQuery(document).trigger('yith_fields_init');
                    jQuery(document).trigger('yith-add-box-button-toggle');
                    jQuery(document).trigger('yith-plugin-fw-panel-init-deps');
                    target_deps_id = [];
                    target_deps = [];
                    initFields();
                    handleFieldsChange();
                    window.onbeforeunload = null;
                }
                form.unblock();
            },
        });
    };

    const addNewShortcode = (e) => {
        e.preventDefault();
        e.stopPropagation();
        const newSlug = getNewSlug();
        jQuery.ajax({
            url: ywcas_admin_params.ajaxurl,
            data: {
                security,
                action: 'yith_wcas_add_new_shortcode',
                slug: newSlug
            },
            type: 'POST',
            success: function (response) {
                if (response.data.content) {
                    jQuery(document).find('.ywcas-body').append(response.data.content);
                    jQuery(document).trigger('yith-plugin-fw-tabs-init');
                    jQuery(document).trigger('yith_fields_init');
                    jQuery(document).trigger('yith-plugin-fw-panel-init-deps');
                    initFields();
                    handleFieldsChange();
                }
            }
        });
    };

    const deleteShortcode = (event) => {
        event.preventDefault();
        event.stopPropagation();
        const container = event.target.closest('.ywcas-row');
        const form = jQuery(container).find('form');
        const slug = form.data('preset');

        yith.ui.confirm({
            title: ywcas_admin_params.shortcode_message_alert.title,
            message: ywcas_admin_params.shortcode_message_alert.desc,
            confirmButton: ywcas_admin_params.shortcode_message_alert.confirmButton,
            closeAfterConfirm: true,
            classes: {
                wrap: 'ywcas-warning-popup'
            },
            onConfirm: function onConfirm() {
                jQuery.ajax({
                    url: ywcas_admin_params.ajaxurl,
                    data: {
                        action: 'yith_wcas_delete_shortcode',
                        security,
                        slug: slug
                    },
                    type: 'POST',
                    success: function (response) {
                        if (response?.success) {
                            container.remove();
                        }
                    }
                });
            }
        });
    }

    const cloneShortcode = (event) => {
        event.preventDefault();
        event.stopImmediatePropagation();
        const container = event.target.closest('.ywcas-row');
        const form = jQuery(container).find('form');
        const slug = form.data('preset');
        const newSlug = getNewSlug(slug)
        jQuery.ajax({
            url: ywcas_admin_params.ajaxurl,
            data: {
                security,
                action: 'yith_wcas_clone_shortcode',
                newSlug: newSlug,
                slug: slug
            },
            type: 'POST',
            success: function (response) {
                if (response.data.content) {
                    const target = jQuery(response.data.content).find('.ywcas-edit').data('target');
                    jQuery(document).find('.ywcas-body').append(response.data.content);
                    document.querySelector('#' + target).scrollIntoView({behavior: 'smooth'});
                    jQuery(document).trigger('yith-plugin-fw-tabs-init');
                    jQuery(document).trigger('yith_fields_init');
                    jQuery(document).trigger('yith-plugin-fw-panel-init-deps');
                    initFields();
                    handleFieldsChange();
                }

            }
        });
    }

    init();
};

YWCAS_Admin_Shortcodes();