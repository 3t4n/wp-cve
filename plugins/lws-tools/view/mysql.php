<h2 class="lws_tk_mysql_title_text"> <?php esc_html_e('MySQL Reports', 'lws-tools'); ?>
</h2>

<table class="lws_tk_mysqltable" id="lws_tk_mysqltable" style="width:100%">
    <thead class="lws_tk_tab_line_mysql">
        <td class="lws_tk_tdmysql_tr"><?php esc_html_e('Name', 'lws-tools'); ?>
        </td>
        <td class="lws_tk_tdmysql_tr"><?php esc_html_e('Size', 'lws-tools'); ?>
        </td>
        <td class="lws_tk_tdmysql_tr"><?php esc_html_e('Charset', 'lws-tools'); ?>
        </td>
        <td class="lws_tk_tdmysql_tr"><?php esc_html_e('Engine', 'lws-tools'); ?>
        </td>
        <td class="lws_tk_tdmysql_tr"><?php esc_html_e('Table created on', 'lws-tools'); ?>
        </td>
    </thead>
    <tbody>
        <?php foreach ($list_tables as $table) : ?>
        <tr class="lws_tk_tr_mysql">
            <td class="lws_tk_tdmysql" style="width:30%">
                <?php echo(wordwrap(esc_html($table['name'], 40, "<br>", true))); ?>
            </td>
            <td class="lws_tk_tdmysql" style="width:15%">
                <?php echo(esc_html($table['size'])); ?>
            </td>
            <td class="lws_tk_tdmysql" style="width:20%">
                <?php echo(esc_html($table['charset'])); ?>
            </td>
            <td class="lws_tk_tdmysql" style="width:15%">
                <?php echo(esc_html($table['engine'])); ?>
            </td>
            <td class="lws_tk_tdmysql" style="padding-right:0px">
                <?php echo(esc_html(explode(' ', $table['created'])[0])); ?>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php if (is_plugin_active('wps-bidouille/wps-bidouille.php')) : ?>
<div class="error">
    <?php esc_html_e('You are using WPS Bidouille. Due to conflicts, you cannot use thoses functions while it is activated.', 'lws-tools'); ?>
</div>
<script>
    jQuery(document).ready(function() {
        jQuery('#lws_tk_repair').prop('disabled', true);
        jQuery('#lws_tk_repair').removeClass('lws_tk_general_install_button');
        jQuery('#lws_tk_repair').addClass('lws_tk_general_install_button_mysql');

        jQuery('#lws_tk_optimise').removeClass('lws_tk_general_install_button');
        jQuery('#lws_tk_optimise').addClass('lws_tk_general_install_button_mysql');
        jQuery('#lws_tk_optimise').prop('disabled', true);
    });
</script>
<?php endif ?>

<h2 class="lws_tk_title_repair"><?php esc_html_e('Repair or Optimize the database', 'lws-tools'); ?></h2>
<div class="lws_tk_div_repair_sql">
    <div>
        <?php $aff = array('strong' => array()); ?>
        <p class="lws_tk_p_repair_sql">
            <?php esc_html_e('If your database is not working properly, you may want to repair or optimise it.', 'lws-tools'); ?>
        </p>
    </div>
    <div style="flex:35%; padding-left:30px">
        <button class="lws_tk_general_install_button lws_tk_mysql_repair" id="lws_tk_repair" onclick="">
            <span class="" name="update">
                <img class="lws_tk_image_button" width="20px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/reparer.svg')?>">
                <?php esc_html_e('Repair', 'lws-tools'); ?>
            </span>
            <span class="hidden" name="loading">
                <img class="lws_tk_image_button" width="20px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                <span id="loading_1"><?php esc_html_e("Repairing...", "lws-tools");?></span>
            </span>
            <span class="hidden" name="validated">
                <img class="lws_tk_image_button" width="18px" height="18px" style="vertical-align:sub"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                <?php esc_html_e('Done', 'lws-tools'); ?>
                &nbsp;
            </span>
        </button>

        <button class="lws_tk_general_install_button lws_tk_mysql_repair" id="lws_tk_optimise" onclick="">
            <span class="" name="update">
                <img class="lws_tk_image_button" width="20px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/optimiser.svg')?>">
                <?php esc_html_e('Repair & Optimise', 'lws-tools'); ?>
            </span>
            <span class="hidden" name="loading">
                <img class="lws_tk_image_button" width="15px" height="15px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>">
                <span id="loading_1"><?php esc_html_e("Optimizing...", "lws-tools");?></span>
            </span>
            <span class="hidden" name="validated">
                <img class="lws_tk_image_button" width="18px" height="18px" style="vertical-align:sub"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                <?php esc_html_e('Done', 'lws-tools'); ?>
                &nbsp;
            </span>
        </button>

        <button class="lws_tk_general_install_button hidden lws_tk_mysql_repair" id="lws_tk_close_iframe_button">
            <span class="" name="update"><?php esc_html_e('Close', 'lws-tools'); ?></span>
        </button>
    </div>
</div>

<div class="lws_tk_iframe" id="result"></div>

<script>
    jQuery('#lws_tk_repair').on('click', function() {
        let button = this;
        let button_id = this.id;
        jQuery('#lws_tk_close_iframe_button').addClass('hidden');
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button_tools');
        button.setAttribute('disabled', true);
        jQuery('#result').html("");
        var data = {
            action: "lwstools_repairdb",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_repair_only_db')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            jQuery('#result').html("<iframe width='100%' height='600px' sandbox src='" +
                response +
                "<?php echo("?random='" . time(). "'");?> ></iframe>"
            );
            var _theframe = document.getElementById("result").children[0];
            _theframe.contentWindow.location.href = _theframe.src;
            var data = {
                action: "lwstools_deactivate_repair",
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_deactivate_repair_option')); ?>',
            };
            setTimeout(function() {
                jQuery.post(ajaxurl, data);
            }, 2000);
            var button = jQuery('#' + button_id);
            button.children()[0].classList.add('hidden');
            button.children()[2].classList.remove('hidden');
            button.children()[1].classList.add('hidden');
            button.addClass('lws_tk_validated_button_tools');
            jQuery('#lws_tk_close_iframe_button').removeClass('hidden');
            button.prop('disabled', false);
        });
    });

    jQuery('#lws_tk_optimise').on('click', function() {
        let button = this;
        let button_id = this.id;
        jQuery('#lws_tk_close_iframe_button').addClass('hidden');
        button.children[0].classList.add('hidden');
        button.children[2].classList.add('hidden');
        button.children[1].classList.remove('hidden');
        button.classList.remove('lws_tk_validated_button_tools');
        button.setAttribute('disabled', true);
        jQuery('#result').html("");
        var data = {
            action: "lwstools_optidb",
            _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_optimize_all_db')); ?>',
        };
        jQuery.post(ajaxurl, data, function(response) {
            setTimeout(function() {
                jQuery('#result').html("<iframe width='100%' height='600px' sandbox src='" +
                    response +
                    "<?php echo("?random=" . time());?>'></iframe>"
                );
                var _theframe = document.getElementById("result").children[0];
                _theframe.contentWindow.location.href = _theframe.src;

                var button = jQuery('#' + button_id);
                button.children()[0].classList.add('hidden');
                button.children()[2].classList.remove('hidden');
                button.children()[1].classList.add('hidden');
                button.addClass('lws_tk_validated_button_tools');
                jQuery('#lws_tk_close_iframe_button').removeClass('hidden');
                button.prop('disabled', false);
            }, 2000);
            setTimeout(function() {
                var data = {
                    action: "lwstools_deactivate_repair",
                    _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('tools_deactivate_repair_option')); ?>',
                };
                jQuery.post(ajaxurl, data);
            }, 5000);
        });
    });

    jQuery('#lws_tk_close_iframe_button').on('click', function() {
        jQuery('#result').html('');
        jQuery('#lws_tk_repair').children()[0].classList.remove('hidden');
        jQuery('#lws_tk_repair').children()[2].classList.add('hidden');

        jQuery('#lws_tk_optimise').children()[0].classList.remove('hidden');
        jQuery('#lws_tk_optimise').children()[2].classList.add('hidden');

        jQuery('#lws_tk_repair').removeClass('lws_tk_validated_button_tools');
        jQuery('#lws_tk_optimise').removeClass('lws_tk_validated_button_tools');
        this.classList.add('hidden');
    });
</script>

<script>
    jQuery(document).ready(function() {
        var table = jQuery('#lws_tk_mysqltable').DataTable({
            scrollY: "600px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: true,
            searching: false,
            info: false,
        });
    });
</script>