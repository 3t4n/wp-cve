
window.addEventListener('DOMContentLoaded', (event) => {

    // Dashboard Template add
    var dash_new_tempplates = document.querySelectorAll(".shop-ready-dash-add-new-template");

    for (let i = 0; i < dash_new_tempplates.length; i++) {

        dash_new_tempplates[i].addEventListener("click", function () {

            if (typeof dash_new_tempplates[i].dataset.target !== 'undefined') {
                dash_new_tempplates[i].classList.add('loading');
                shop_ready_dashboard_template_init(dash_new_tempplates[i]);
            }

        });

    }

    var dashboard_modal_close = document.getElementsByClassName('shop-ready-dashboard-modal-close');

    for (let k = 0; k < dashboard_modal_close.length; k++) {

        dashboard_modal_close[k].addEventListener("click", function () {
            document.getElementById('shop-ready-dashboard-content-popup').classList.remove('active');

        });

    }

    // Dashboard Template Edit
    var dash_edit_tempplates = document.querySelectorAll(".shop-ready-dash-edit-template");


    for (let i = 0; i < dash_edit_tempplates.length; i++) {

        dash_edit_tempplates[i].addEventListener("click", function () {

            if (typeof dash_edit_tempplates[i].dataset.target !== 'undefined') {
                dash_edit_tempplates[i].classList.add('loading');
                shop_ready_dashboard_edit_template_init(dash_edit_tempplates[i]);
            }


        });

    }

    var dash_edit_link = document.querySelectorAll(".shop-ready-tmp-edit-popup-js");
    var edit_option_content_area = document.getElementById('shop-ready-dashboard-modal-area');

    for (let e = 0; e < dash_edit_link.length; e++) {
        dash_edit_link[e].addEventListener("click", function (et) {
            et.preventDefault();

            var shop_edit_link = et.target.href;

            if (typeof shop_edit_link !== '') {

                edit_option_content_area.innerHTML = "<iframe src=" + shop_edit_link + "></iframe>";
                document.getElementById('shop-ready-dashboard-content-popup').classList.add('active');
            }

        });
    }

    /* :::::::::::: Product Edit Template ::::::::::::::: */
    function shop_ready_dashboard_edit_template_init(template) {

        var _local_template = template.dataset.target;
        var _tpl_id = template.dataset.tpl_id;
        var content_area = document.getElementById('shop-ready-dashboard-modal-area');
        var data_args = {
            action: 'shop_ready_dash_template_edit_content',
            sr_ds_template: _local_template,
            sr_edit_id: _tpl_id
        };

        shop_ready_dsh_tpl_fetch_data(data_args)
            .then(data => {
                var json_data = JSON.parse(data);
                content_area.innerHTML = json_data.html;
                template.classList.remove('loading');
                document.getElementById('shop-ready-dashboard-content-popup').classList.add('active');

            });

    }

    // end popup

    /* :::::::::::: Product New Template ::::::::::::::: */
    function shop_ready_dashboard_template_init(template) {

        var _local_template = template.dataset.target;
        var edit_button = template.previousElementSibling;
        var content_area = document.getElementById('shop-ready-dashboard-modal-area');
        var data_args = {
            action: 'shop_ready_dash_template_content',
            sr_ds_template: _local_template
        };

        shop_ready_dsh_tpl_fetch_data(data_args)
            .then(data => {

                var json_data = JSON.parse(data);
                content_area.innerHTML = json_data.html;
                // POpUp Open
                template.classList.remove('loading');

                document.getElementById('shop-ready-dashboard-content-popup').classList.add('active');

                var select_option = document.querySelector('[name="shop_ready_templates[' + _local_template + ']"]');
                select_option.nextElementSibling.firstChild.firstChild.innerText = json_data.title;

                select_option[select_option.options.length] = new Option(json_data.title, json_data.id, false, true);

                // Update Edit button 

                if (typeof (edit_button) != 'undefined' && edit_button != null) {
                    edit_button.dataset.tpl_id = json_data.id;
                }


            });

    }

    /* Ajax fetch */
    async function shop_ready_dsh_tpl_fetch_data(data = {}) {

        const response = await fetch(wp.ajax.settings.url, {
            method: 'POST',
            cache: 'no-cache',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)

        });

        return response.text();
    }

    // Template Clear 

    var template_ajax_wrapper = document.querySelector('.shopready-template-ajax-message');
    var template_clear_btn = document.querySelector('.shop-ready-clear-template-data a');

    template_clear_btn.addEventListener('click', function () {

        let text = "Would you like to delete template current settings";
        if (confirm(text) == true) {

            var data_args = {
                action: 'shopready_template_option_delete'
            };

            shop_ready_dsh_tpl_fetch_data(data_args)
                .then(data => {


                    var message = '';
                    var border_color = 'updated';
                    if (data == 'success') {
                        message = 'Data removed';

                    } else {
                        message = 'Template Settings remove fail';
                        border_color = 'updated fail';
                    }
                    var template = `<div id="message" class="${border_color} woocommerce-message woocommerce-message--success">
                    <p> ${message} </p>          
                </div>`;

                    template_ajax_wrapper.innerHTML = template;

                    setTimeout(function () {
                        template_ajax_wrapper.innerHTML = '';
                        window.top.location = window.top.location
                    }, 3000);


                });
        }


    });

});
