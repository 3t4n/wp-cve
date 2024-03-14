<?php
namespace Shop_Ready\system\base\dashboard;

final class Dashboard
{

    protected $config = null;
    public function register()
    {

        $this->config = apply_filters('shop_ready_dashboard_config', shop_ready_dashboard_config());
        add_action('admin_footer', [$this, 'dashboard_js_code']); // For back-end
        add_action('woo_ready_tab_item', [$this, 'add_tab_menu'], 10);
        add_filter('woo_ready_tab_content', [$this, 'add_tab_content'], 10);
    }

    function add_tab_menu()
    {

        $dashboard_tab = $this->config->get('dashboard_tab');
        $current_active_menu = sanitize_text_field(isset($_REQUEST['nav']) ? sanitize_text_field($_REQUEST['nav']) : '');
        $default_id = 'woo-ready-dash-tab-content-default';
        $flag = false;

        if (is_array($dashboard_tab)) {

            foreach ($dashboard_tab as $item) {

                $active = 'no';

                if ($flag == false && $current_active_menu == $item['menu_id']) {
                    $flag = true;
                    $active = $default_id;

                } elseif ($current_active_menu == '' && isset($item['active']) && $item['active'] == true) {
                    $active = $default_id;
                }

                echo wp_kses_post(
                    sprintf(
                        '<button class="woo-ready-dash-tab-links woo-ready-dash-link %s" data-navid="%s" id="%s">%s</button>',
                        esc_attr($item['attr_class']),
                        esc_attr($item['menu_id']),
                        esc_attr($active),
                        esc_attr($item['menu_title'])

                    )
                );

            }
        }

    }

    function add_tab_content()
    {

        $dashboard_tab = $this->config->get('dashboard_tab');
        if (is_array($dashboard_tab)) {

            foreach ($dashboard_tab as $item) {

                echo wp_kses_post(sprintf('<div id="%s" class="woo-ready-dash-tab-content">', $item['menu_id']));

                if (file_exists($item['content_view_path'])) {
                    include($item['content_view_path']);
                }

                echo wp_kses_post('</div>');


            } // end foreach 
        } // endif

    }

    public function dashboard_js_code($handle)
    {

        $current_page = get_current_screen();

        if (!isset($current_page->base)) {
            return;
        }

        if ($current_page->base != 'toplevel_page_' . SHOP_READY_SETTING_PATH) {
            return;
        }

        ?>
        <script type="text/javascript">
            (function () {
                var buttons = document.querySelectorAll('.woo-ready-menu-tab .woo-ready-dash-tab-links');
                [].forEach.call(buttons, function (nav) {
                    nav.addEventListener('click', shop_ready_open_nav, false);
                    nav.navigation = nav.dataset.navid;
                    nav.default = nav.dataset.default;
                });
                if (document.getElementById('woo-ready-dash-tab-content-default')) {
                    document.getElementById('woo-ready-dash-tab-content-default').click();
                }
                // offcanvas
                var offcanvas = document.querySelectorAll('.woo-ready-offcanvas');
                [].forEach.call(offcanvas, function (woo_canvas) {
                    woo_canvas.addEventListener('click', shop_ready_offcanvas_push, false);
                });
                // switch enable disable
                var enable_all = document.querySelectorAll('.woo-ready-enable-all-switch,.woo-ready-disable-all-switch');
                [].forEach.call(enable_all, function (canvas_swither) {
                    canvas_swither.addEventListener('click', shop_ready_enable_all_switch, false);
                });
                // search 
                var search_all = document.querySelectorAll('input.woo-ready-element-search');
                [].forEach.call(search_all, function (search_fld) {
                    search_fld.addEventListener('input', shop_ready_element_search_action, false);
                });
                // end select option
                var select_tags = document.querySelectorAll('select.woo-ready-selectbox');
                [].forEach.call(select_tags, function (select_tag) {
                    var title = select_tag.dataset.title;
                    var dynamic_select_tpl = new BVSelect({
                        selector: "#" + select_tag.id,
                        width: "98%",
                        searchbox: true,
                        offset: false,
                        placeholder: "Select " + title + ' template',
                        search_placeholder: "Search...",
                        search_autofocus: true,
                        breakpoint: 450
                    });
                });
                // Template Swicher 
                var templates_switcher = document.querySelectorAll('.woo-ready-templates-swicher-wrp input');
                [].forEach.call(templates_switcher, function (_fld) {
                    var checked = _fld.checked;
                    var targetee = document.querySelector('div[data-targetee=' + _fld.dataset.target + ']');
                    if (checked) {
                        targetee.style.display = '';
                    } else {
                        targetee.style.display = 'none';
                        if (_fld.dataset.target == 'single') {
                            var variable_tpl = document.querySelector('div[data-target-row=variable_single]');
                            var grouped_tpl = document.querySelector('div[data-target-row=grouped_single]');
                            variable_tpl.style.display = 'none';
                            grouped_tpl.style.display = 'none';
                        }

                    }

                    _fld.addEventListener('click', shop_ready__template_swicher_click_action, false);

                });

                // Presets
                var presets_switcher = document.querySelectorAll(
                    '.shop-ready-preset-swicher-wrp input.shop-ready-preset-checkbox');
                [].forEach.call(presets_switcher, function (_fld) {

                    var checked = _fld.checked;
                    var targetee = document.querySelector('div[data-presets=' + _fld.dataset.ptarget + ']');
                    var preset_tpl = document.querySelector('.shop-ready-preset-option-preset-selector.' + _fld.dataset
                        .ptarget);

                    if (checked) {
                        targetee.style.display = 'none';
                        preset_tpl.style.display = 'flex';
                    } else {
                        targetee.style.display = '';
                        preset_tpl.style.display = 'none';
                    }

                    _fld.addEventListener('click', shop_ready_preset_swicher_click_action, false);
                });

                function shop_ready_preset_swicher_click_action(event) {

                    var checked = event.target.checked;
                    var targetee_row = document.querySelector('div[data-target-row=' + this.dataset.ptarget + ']');

                    var targetee = document.querySelector('div[data-presets=' + this.dataset.ptarget + ']');
                    var template_switch_target = document.querySelector('.woo-ready-templates-swicher-wrp input[data-target=' +
                        this.dataset.ptarget + ']');
                    var preset_tpl = document.querySelector('.shop-ready-preset-option-preset-selector.' + this.dataset
                        .ptarget);
                    var temp_water_mark_color = 'rgba(255, 251, 251, 0.08)';
                    if (checked) {

                        if (template_switch_target.checked) {
                            template_switch_target.click();
                        }
                        document.documentElement.style.setProperty('--sr-dashboard-water-mark-color', '#000');

                        targetee.style.display = 'none';
                        preset_tpl.style.display = 'flex';
                        template_switch_target.checked = false;
                    } else {

                        document.documentElement.style.setProperty('--sr-dashboard-water-mark-color', temp_water_mark_color);
                        targetee.style.display = '';
                        preset_tpl.style.display = 'none';
                    }
                }
                // Preset End
            })();

            function shop_ready__template_swicher_click_action(event) {

                var checked = event.target.checked;
                var targetee = document.querySelector('div[data-targetee=' + this.dataset.target + ']');

                var variable_tpl = document.querySelector('div[data-target-row=variable_single]');
                var grouped_tpl = document.querySelector('div[data-target-row=grouped_single]');

                if (checked) {

                    targetee.style.display = '';

                    if (this.dataset.target == 'single') {

                        variable_tpl.style.display = '';
                        grouped_tpl.style.display = '';

                    }

                } else {

                    targetee.style.display = 'none';

                    if (this.dataset.target == 'single') {

                        variable_tpl.style.display = 'none';
                        grouped_tpl.style.display = 'none';

                    }
                }
            }



            // search element
            function shop_ready_element_search_action(event) {
                var search_text = event.target.value.toUpperCase();

                var targetee = document.querySelectorAll('div[data-targetee=' + this.dataset.target + '] strong');
                [].forEach.call(targetee, function (div) {

                    var txtValue = div.innerText;

                    if (txtValue.toUpperCase().indexOf(search_text) > -1) {
                        // show

                        div.closest('.woo-ready-col').style.display = "";
                    } else {

                        div.closest('.woo-ready-col').style.display = "none";
                    }

                });

            }

            function shop_ready_enable_all_switch(event) {
                // notification
                var notice = document.getElementById("woo-ready-admin-notification");

                if (this.classList.contains('woo-ready-disable-all-switch')) {

                    var targetee = document.querySelectorAll('div[data-targetee=' + this.dataset.target + '] ' + 'input');
                    [].forEach.call(targetee, function (input) {

                        input.checked = false;
                    });

                    notice.innerText = '<?php echo esc_html__("Disable All swicth", 'shopready-elementor-addon'); ?>';

                } else {

                    var targetee = document.querySelectorAll('div[data-targetee=' + this.dataset.target + '] ' + 'input');
                    [].forEach.call(targetee, function (input) {

                        if (input.getAttribute('readonly') == null) {
                            input.checked = true;
                        }


                    });
                    notice.innerText = '<?php echo esc_html__('Enable All switch', 'shopready-elementor-addon'); ?>';
                }

                notice.className = "show";
                setTimeout(function () {
                    notice.className = notice.className.replace("show", "");
                }, 3000);

            }



            function shop_ready_offcanvas_push(event) {

                var element = document.querySelector('.woo-ready-menu-tab')
                var element_content = document.querySelector('.woo-ready-tab-content-container')
                var element_expend = document.querySelectorAll('.woo-ready-tab-content-container form .woo-ready-title')
                var hidden = element.getAttribute('hidden');

                if (hidden) {
                    element.classList.remove('woo-ready-sidebar-nav');
                    element_content.classList.remove('shop-ready-sidebar-nav');

                    element.removeAttribute('hidden', 'false');
                    element_content.removeAttribute('style');

                    // var i;
                    // for (i = 0; i < element_expend.length; i++) {
                    //     element_expend[i].classList.remove('shop-ready-nav-expend');
                    // }

                } else {
                    element.classList.add('woo-ready-sidebar-nav');
                    element_content.classList.add('shop-ready-sidebar-nav');

                    element.setAttribute('hidden', 'true');
                    element_content.style.width = "100%";
                    var t;
                    // for (t = 0; t < element_expend.length; t++) {
                    //     element_expend[t].classList.add('shop-ready-nav-expend');
                    // }

                }

            }

            function shop_ready_open_nav(evt) {

                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("woo-ready-dash-tab-content");
                for (i = 0; i < tabcontent.length; i++) {

                    tabcontent[i].style.display = 'none';
                }

                tablinks = document.getElementsByClassName('woo-ready-dash-tab-links');
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(' active', '');
                }

                document.getElementById(evt.currentTarget.navigation).style.display = 'block';
                evt.currentTarget.className += ' active';

            }
        </script>
        <?php
    }




}