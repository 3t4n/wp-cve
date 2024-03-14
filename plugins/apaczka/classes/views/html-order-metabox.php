<?php
/**
 * @var WC_ORDER $order
 */ ?>


<div id="apaczka_panel_<?php echo $id; ?>"
     class="panel woocommerce_options_panel apaczka_panel">
    <div class="options_group">

        <?php

        // Default package_width.
        $key = '_apaczka['.$id.'][default_package_width]';
        $value = '';
        if (isset($apaczka['package_width'])) {
	        $value = $apaczka['package_width'];
        }

        woocommerce_wp_hidden_input([
            'id' => $key,
            'value' => $value,
            'class' => 'default_package_width'
        ]);

        // Default package_depth.
        $key = '_apaczka['.$id.'][default_package_depth]';
        $value = '';
        if (isset($apaczka['package_depth'])) {
	        $value = $apaczka['package_depth'];
        }

        woocommerce_wp_hidden_input([
	        'id' => $key,
	        'value' => $value,
	        'class' => 'default_package_depth'
        ]);

        // Default package_height.
        $key = '_apaczka['.$id.'][default_package_height]';
        $value = '';
        if (isset($apaczka['package_height'])) {
	        $value = $apaczka['package_height'];
        }

        woocommerce_wp_hidden_input([
	        'id' => $key,
	        'value' => $value,
	        'class' => 'default_package_height'
        ]);

        $key = '_apaczka['.$id.'][service]';

        $value = '';
        if (isset($apaczka['service'])) {
            $value = $apaczka['service'];
        }

        $custom_attributes = [];

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        if (!empty($apaczka['service'])) {
            $value = $apaczka['service'];
        }

        woocommerce_wp_select([
            'id' => $key,
            'label' => __('Usługa ', 'apaczka'),
            'desc_tip' => false,
            'type' => 'number',
            'options' => $services,
            'value' => $value,
            'custom_attributes' => $custom_attributes,
            'class' => 'apaczka_service_select',
        ]);
        ?>

        <div class="shipx-input-wrapper">

            <?php
            $key = '_apaczka['.$id.'][parcel_machine_from]';
            $value = '';
            if (isset($apaczka['parcel_machine_from'])) {
                $value = $apaczka['parcel_machine_from'];
            }
            

            $custom_attributes = [];

            if ($package_send) {
                $custom_attributes['disabled'] = 'disabled';
            }

            woocommerce_wp_text_input([
                'id' => $key,
                'label' => __('Paczkomat nadawczy', 'apaczka'),
                'desc_tip' => false,
                'value' => $value,
                'custom_attributes' => $custom_attributes,
                'class' => 'settings-geowidget',
            ]);
            ?>


            <?php
            $key = '_apaczka['.$id.'][parcel_machine_to]';
            $value = $order->get_meta('_parcel_machine_id');
            if (empty($value)) {
                $value = '';
            }

            if (isset($apaczka['parcel_machine_to'])) {
                $value = $apaczka['parcel_machine_to'];
            }

            $custom_attributes = [];

            if ($package_send) {
                $custom_attributes['disabled'] = 'disabled';
            }

            woocommerce_wp_text_input([
                'id' => $key,
                'label' => __('Paczkomat docelowy', 'apaczka'),
                'desc_tip' => false,
                'value' => $value,
                'custom_attributes' => $custom_attributes,
                'class' => 'settings-geowidget',
            ]);
            ?>


            <?php
            $key = '_apaczka['.$id.'][parcel_dimensions_template]';
            $parcel_dimensions_template_value = '';
            if (isset($apaczka['parcel_dimensions_template'])) {
	            $parcel_dimensions_template_value = $apaczka['parcel_dimensions_template'];
            }

            $custom_attributes = [];

            if ($package_send) {
                $custom_attributes['disabled'] = 'disabled';
            }

            woocommerce_wp_select([
                'id' => $key,
                'label' => __('Szablon rozmiaru', 'apaczka'),
                'desc_tip' => false,
                'type' => 'number',
                'options' => [
                    'a' => $parcel_machine_parcel_sizes['a']['label'],
                    'b' => $parcel_machine_parcel_sizes['b']['label'],
                    'c' => $parcel_machine_parcel_sizes['c']['label'],
                ],
                'value' => $parcel_dimensions_template_value,
                'custom_attributes' => $custom_attributes,
                'class' => 'parcel_dimensions_template',
            ]);
            ?>

        </div>


        <h4><?php _e('Wymiary [cm]', 'apaczka'); ?></h4>

        <?php
        $key = '_apaczka['.$id.'][package_width]';
        $value = '';
        if (isset($apaczka['package_width'])) {
            $value = $apaczka['package_width'];
        }

        $custom_attributes = [
            'step' => '1',
            'min' => '0',
        ];

        if ('PACZKOMAT' === $apaczka['service']) {
	        $custom_attributes['readonly'] = 'readonly';

	        if ( empty( $parcel_dimensions_template_value ) && isset( $parcel_machine_parcel_sizes['a']['width'] ) ) {
	            $value = $parcel_machine_parcel_sizes['a']['width'];
            }
        }

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_text_input([
            'id' => $key,
            'label' => __('Długość ', 'apaczka'),
            'desc_tip' => false,
            'type' => 'number',
            'custom_attributes' => $custom_attributes,
            'data_type' => 'number',
            'value' => $value,
            'class' => 'package_width'
        ]);

        ?>

        <?php
        $key = '_apaczka['.$id.'][package_depth]';
        $value = '';
        if (isset($apaczka['package_depth'])) {
            $value = $apaczka['package_depth'];
        }

        $custom_attributes = [
            'step' => '1',
            'min' => '0',
        ];

        if ('PACZKOMAT' === $apaczka['service']) {
	        $custom_attributes['readonly'] = 'readonly';

	        if ( empty( $parcel_dimensions_template_value ) && isset( $parcel_machine_parcel_sizes['a']['depth'] ) ) {
		        $value = $parcel_machine_parcel_sizes['a']['depth'];
	        }
        }

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_text_input([
            'id' => $key,
            'label' => __('Szerokość ', 'apaczka'),
            'desc_tip' => false,
            'type' => 'number',
            'custom_attributes' => $custom_attributes,
            'data_type' => 'number',
            'value' => $value,
	        'class' => 'package_depth'
        ]);

        ?>

        <?php
        $key = '_apaczka['.$id.'][package_height]';
        $value = '';
        if (isset($apaczka['package_height'])) {
            $value = $apaczka['package_height'];
        }

        $custom_attributes = [
            'step' => '1',
            'min' => '0',
        ];

        if ('PACZKOMAT' === $apaczka['service']) {
	        $custom_attributes['readonly'] = 'readonly';

	        if ( empty( $parcel_dimensions_template_value ) && isset( $parcel_machine_parcel_sizes['a']['height'] ) ) {
		        $value = $parcel_machine_parcel_sizes['a']['height'];
	        }
        }

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_text_input([
            'id' => $key,
            'label' => __('Wysokość ', 'apaczka'),
            'desc_tip' => false,
            'type' => 'number',
            'custom_attributes' => $custom_attributes,
            'data_type' => 'number',
            'value' => $value,
	        'class' => 'package_height'
        ]);

        ?>

        <h4><?php _e('Waga paczki [kg]', 'apaczka'); ?></h4>

        <?php
        $key = '_apaczka['.$id.'][package_weight]';
        $value = '';
        if (isset($apaczka['package_weight'])) {
            $value = $apaczka['package_weight'];
        }

        $custom_attributes = [
            'step' => '1',
            'min' => '0',
            'max' => '30',
            'step' => 'any',
        ];

        if ('PACZKOMAT' === $apaczka['service']) {
            $size_key = ! empty( $parcel_dimensions_template_value ) ? $parcel_dimensions_template_value : 'a';
	        $custom_attributes['max'] = $parcel_machine_parcel_sizes[$size_key]['max_weight'];
        }

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_text_input([
            'id' => $key,
            'label' => __('Waga ', 'apaczka'),
            'desc_tip' => false,
            'type' => 'number',
            'custom_attributes' => $custom_attributes,
            'data_type' => 'number',
            'value' => $value,
            'class' => 'package_weight'
        ]);

        ?>

        <h4><?php _e('Zawartość', 'apaczka'); ?></h4>

        <?php
        $key = '_apaczka['.$id.'][package_contents]';
        $value = '';
        if (isset($apaczka['package_contents'])) {
            $value = $apaczka['package_contents'];
        }

        $custom_attributes = [];

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_text_input([
            'id' => $key,
            'label' => __('Zawartość ', 'apaczka'),
            'desc_tip' => false,
            'type' => 'text',
            'data_type' => 'text',
            'value' => $value,
            'custom_attributes' => $custom_attributes,
        ]);

        ?>

        <h4><?php _e('Pobranie', 'apaczka'); ?></h4>

        <?php
        $key = '_apaczka['.$id.'][cod_amount]';
        $value = '';
        if (isset($apaczka['cod_amount'])) {
            $value = $apaczka['cod_amount'];
        }

        $custom_attributes = [
            'step' => '1',
            'min' => '0',
            'step' => 'any',
        ];

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_text_input([
            'id' => $key,
            'label' => __('Kwota pobrania', 'apaczka'),
            'desc_tip' => false,
            'type' => 'number',
            'custom_attributes' => $custom_attributes,
            'data_type' => 'number',
            'value' => $value,
        ]);

        ?>

        <h4><?php _e('Ubezpieczenie', 'apaczka'); ?></h4>

        <?php
        $key = '_apaczka['.$id.'][insurance]';
        $value = '';
        if (isset($apaczka['insurance'])) {
            $value = $apaczka['insurance'];
        }

        $custom_attributes = [];

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_select([
            'id' => $key,
            'label' => __('Ubezpieczenie', 'apaczka'),
            'desc_tip' => false,
            'type' => 'number',
            'options' => [
                'yes' => __('Tak', 'apaczka'),
                'no' => __('Nie', 'apaczka'),
            ],
            'value' => $value,
            'custom_attributes' => $custom_attributes,
        ]);

        ?>

        <h4><?php _e('Odbiór', 'apaczka'); ?></h4>

        <?php
        $key = '_apaczka['.$id.'][pickup_date]';
        $value = date_i18n('Y-m-d', current_time('timestamp'));
        if (isset($apaczka['pickup_date']) && $apaczka['pickup_date'] != '') {
            $value = $apaczka['pickup_date'];
        }

        $custom_attributes = [
            'pattern' => '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])',
            'maxlength' => '10',
        ];

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_text_input([
            'id' => $key,
            'label' => __('Data odbioru', 'apaczka'),
            'desc_tip' => false,
            'class' => 'date-picker',
            'type' => 'text',
            'custom_attributes' => $custom_attributes,
            'data_type' => 'number',
            'value' => $value,
        ]);
        ?>

        <?php
        $key = '_apaczka['.$id.'][pickup_hour_from]';
        $value = '';
        if (isset($apaczka['pickup_hour_from'])) {
            $value = $apaczka['pickup_hour_from'];
        }

        $custom_attributes = [
        ];

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_select([
            'id' => $key,
            'label' => __('Od godziny', 'apaczka'),
            'desc_tip' => false,
            'type' => 'number',
            'custom_attributes' => $custom_attributes,
            'options' => $options_hours,
            'value' => $value,
        ]);
        ?>

        <?php
        $key = '_apaczka['.$id.'][pickup_hour_to]';
        $value = '';
        if (isset($apaczka['pickup_hour_to'])) {
            $value = $apaczka['pickup_hour_to'];
        }

        $custom_attributes = [
            'min' => '0',
            'max' => '24',
        ];

        if ($package_send) {
            $custom_attributes['disabled'] = 'disabled';
        }

        woocommerce_wp_select([
            'id' => $key,
            'label' => __('Do godziny', 'apaczka'),
            'desc_tip' => false,
            'custom_attributes' => $custom_attributes,
            'options' => $options_hours,
            'value' => $value,
        ]);
        ?>

        <h4><?php _e('Opcje dodatkowe', 'apaczka'); ?></h4>

        <div class="apaczka_przes_niet_checkbox_wrap">
            <?php
            $key = '_apaczka['.$id.'][przes_nietyp]';
            $value = '';

            if ($package_send) {
                $custom_attributes['disabled'] = 'disabled';
            }

            woocommerce_wp_checkbox([
                'id' => $key,
                'label' => __('Przesyłka niestandardowa', 'apaczka'),
                'custom_attributes' => $custom_attributes,
                'class' => 'apaczka_przes_niet_checkbox',
            ]);
            ?>
        </div>


        <div class="apaczka_zwrot_dok_checkbox_wrap">
            <?php
            $key = '_apaczka['.$id.'][zwrot_dok]';
            $value = '';

            if ($package_send) {
                $custom_attributes['disabled'] = 'disabled';
            }

            woocommerce_wp_checkbox([
                'id' => $key,
                'label' => __('Zwrot dokumentów', 'apaczka'),
                'custom_attributes' => $custom_attributes,
                'class' => 'apaczka_zwrot_dok_checkbox',
            ]);
            ?>
        </div>

        <div class="apaczka_dost_sob_checkbox_wrap">
            <?php
            $key = '_apaczka['.$id.'][dost_sob]';
            $value = '';

            if ($package_send) {
                $custom_attributes['disabled'] = 'disabled';
            }


            woocommerce_wp_checkbox([
                'id' => $key,
                'label' => __('Dostarczenie w sobotę', 'apaczka'),
                'custom_attributes' => $custom_attributes,
                'class' => 'apaczka_dost_sob_checkbox',
            ]);
            ?>
        </div>

        <hr/>

        <?php if ($package_send == false) : ?>
            <button class="button-primary apaczka_send"
                    data-apaczka-id="<?php echo $id; ?>"><?php _e('Nadaj z zamówieniem kuriera',
                    'apaczka'); ?></button>
            <span style="float:none;" class="spinner spinner_courier"></span>
            <div style="padding-top:5px"></div>
            <button class="button-primary apaczka_send_pickup_self"
                    data-apaczka-id="<?php echo $id; ?>"><?php _e('Nadaj bez zamówienia kuriera',
                    'apaczka'); ?></button>
            <span style="float:none;" class="spinner spinner_self"></span>
            <?php if (isset($apaczka['error_messages'])
                && $apaczka['error_messages'] != ''
            ) : ?>
                <hr/>
                <div class="apaczka_error ">
                    <?php _e('Wystąpiły błędy:',
                        'apaczka'); ?><?php echo $apaczka['error_messages']; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>



        <?php if ($package_send == true) : ?>
            <?php _e('Numer nadania:', 'apaczka'); ?>
            <strong><?php echo $apaczka['apaczka_order']['id']; ?></strong><br/>
            <?php _e('List przewozowy:', 'apaczka'); ?> <a target="_blank"
                                                           href="<?php echo $url_waybill; ?>"><strong><?php echo $apaczka['apaczka_order']['orderNumber']; ?></strong></a>
            <br/>
        <?php endif; ?>
        <?php /* ?>
		<pre>
			<?php print_r( $apaczka ); ?>
		</pre>
<?php /* */ ?>
    </div>
</div>
<script type="text/javascript">
    jQuery(".apaczka_send").click(function () {
        //console.log('Nadaj');

        if (!jQuery(this).closest("form")[0].checkValidity()) {
            jQuery(this).closest("form")[0].reportValidity();
            //console.log('not valid');
            return false;
        }

        jQuery(this).attr('disabled', true);
        jQuery(this).parent().find(".spinner_self").hide();
        jQuery(this).parent().find(".spinner").addClass('is-active');

        var apaczka_id = jQuery(this).attr('data-apaczka-id');

        //console.log(apaczka_id);
        //console.log(jQuery('select[name="_apaczka[' + apaczka_id + '][service]"').val());

        var data = {
            action: 'apaczka',
            apaczka_action: 'create_package',
            security: apaczka_ajax_nonce,
            order_id: <?php echo $order_id; ?>,
            id: apaczka_id,
            service: jQuery('select[name="_apaczka[' + apaczka_id + '][service]"').val(),
            package_width: jQuery('input[name="_apaczka[' + apaczka_id + '][package_width]"').val(),
            package_depth: jQuery('input[name="_apaczka[' + apaczka_id + '][package_depth]"').val(),
            package_height: jQuery('input[name="_apaczka[' + apaczka_id + '][package_height]"').val(),
            package_weight: jQuery('input[name="_apaczka[' + apaczka_id + '][package_weight]"').val(),
            package_contents: jQuery('input[name="_apaczka[' + apaczka_id + '][package_contents]"').val(),
            cod_amount: jQuery('input[name="_apaczka[' + apaczka_id + '][cod_amount]"').val(),
            insurance: jQuery('select[name="_apaczka[' + apaczka_id + '][insurance]"').val(),
            pickup_date: jQuery('input[name="_apaczka[' + apaczka_id + '][pickup_date]"').val(),
            pickup_hour_from: jQuery('select[name="_apaczka[' + apaczka_id + '][pickup_hour_from]"').val(),
            pickup_hour_to: jQuery('select[name="_apaczka[' + apaczka_id + '][pickup_hour_to]"').val(),
            przes_nietyp: jQuery('input[name="_apaczka[' + apaczka_id + '][przes_nietyp]"').is(':checked'),
            dost_sob: jQuery('input[name="_apaczka[' + apaczka_id + '][dost_sob]"').is(':checked'),
            zwrot_dok: jQuery('input[name="_apaczka[' + apaczka_id + '][zwrot_dok]"').is(':checked'),
            parcel_machine_to: jQuery('select[name="_apaczka[' + apaczka_id + '][parcel_machine_to]"').val(),
            parcel_machine_from: jQuery('select[name="_apaczka[' + apaczka_id + '][parcel_machine_from]"').val(),        };

        jQuery.post(ajaxurl, data, function (response) {
            //console.log(response);
            if (response != 0) {
                response = JSON.parse(response);
                //console.log(response);
                if (response.status == 'ok') {
                    jQuery("#apaczka_panel_<?php echo $id; ?>").replaceWith(response.content);
                    return false;
                }
                else {
                    //console.log(response);
                    jQuery('#apaczka_error').html(response.message);
                }
            }
            else {
                //console.log('Invalid response.');
                jQuery('#apaczka_error').html('Invalid response.');
            }
            jQuery(this).parent().find(".spinner").removeClass('is-active');
            jQuery(this).parent().find(".spinner_self").show();
            jQuery('#easypack_send_parcels').attr('disabled', false);
        });

        return false;
    });


    jQuery(".apaczka_send_pickup_self").click(function () {
        //console.log('Nadaj');

        if (!jQuery(this).closest("form")[0].checkValidity()) {
            jQuery(this).closest("form")[0].reportValidity();
            //console.log('not valid');
            return false;
        }

        jQuery(this).attr('disabled', true);
        jQuery(this).parent().find(".spinner_courier").hide();
        jQuery(this).parent().find(".spinner").addClass('is-active');

        var apaczka_id = jQuery(this).attr('data-apaczka-id');

        //console.log(apaczka_id);
        //console.log(jQuery('select[name="_apaczka[' + apaczka_id + '][service]"').val());

        var data = {
            action: 'apaczka',
            apaczka_action: 'create_package_pickup_self',
            security: apaczka_ajax_nonce,
            order_id: <?php echo $order_id; ?>,
            id: apaczka_id,
            service: jQuery('select[name="_apaczka[' + apaczka_id + '][service]"').val(),
			parcel_dimensions_template: jQuery('select[name="_apaczka[' + apaczka_id + '][parcel_dimensions_template]"').val(),
            package_width: jQuery('input[name="_apaczka[' + apaczka_id + '][package_width]"').val(),
            package_depth: jQuery('input[name="_apaczka[' + apaczka_id + '][package_depth]"').val(),
            package_height: jQuery('input[name="_apaczka[' + apaczka_id + '][package_height]"').val(),
            package_weight: jQuery('input[name="_apaczka[' + apaczka_id + '][package_weight]"').val(),
            package_contents: jQuery('input[name="_apaczka[' + apaczka_id + '][package_contents]"').val(),
            cod_amount: jQuery('input[name="_apaczka[' + apaczka_id + '][cod_amount]"').val(),
            insurance: jQuery('select[name="_apaczka[' + apaczka_id + '][insurance]"').val(),
            pickup_date: jQuery('input[name="_apaczka[' + apaczka_id + '][pickup_date]"').val(),
            pickup_hour_from: jQuery('select[name="_apaczka[' + apaczka_id + '][pickup_hour_from]"').val(),
            pickup_hour_to: jQuery('select[name="_apaczka[' + apaczka_id + '][pickup_hour_to]"').val(),
            przes_nietyp: jQuery('input[name="_apaczka[' + apaczka_id + '][przes_nietyp]"').is(':checked'),
            dost_sob: jQuery('input[name="_apaczka[' + apaczka_id + '][dost_sob]"').is(':checked'),
            zwrot_dok: jQuery('input[name="_apaczka[' + apaczka_id + '][zwrot_dok]"').is(':checked'),
            parcel_machine_to: jQuery('input[name="_apaczka[' + apaczka_id + '][parcel_machine_to]"').val(),
            parcel_machine_from: jQuery('input[name="_apaczka[' + apaczka_id + '][parcel_machine_from]"').val(),
        };

        jQuery.post(ajaxurl, data, function (response) {
            //console.log(response);
            if (response != 0) {
                response = JSON.parse(response);
                //console.log(response);
                if (response.status == 'ok') {
                    jQuery("#apaczka_panel_<?php echo $id; ?>").replaceWith(response.content);
                    return false;
                }
                else {
                    //console.log(response);
                    jQuery('#apaczka_error').html(response.message);
                }
            }
            else {
                //console.log('Invalid response.');
                jQuery('#apaczka_error').html('Invalid response.');
            }

            jQuery(this).parent().find(".spinner").removeClass('is-active');
            jQuery(this).parent().find(".spinner_courier").show();
            jQuery('#easypack_send_parcels').attr('disabled', false);
        });

        return false;
    });

    var apaczka_service_select = jQuery(".apaczka_service_select");

    apaczka_service_select.change(function () {
        if ('DHLSTD' === this.value) {
            jQuery('.apaczka_dost_sob_checkbox').prop("disabled", false);
            jQuery('.apaczka_dost_sob_checkbox_wrap p label').removeClass('apaczka_disabled');
        } else {
            jQuery('.apaczka_dost_sob_checkbox').prop("disabled", true);
            jQuery('.apaczka_dost_sob_checkbox').attr('checked', false);
            jQuery('.apaczka_dost_sob_checkbox_wrap p label').addClass('apaczka_disabled');
        }


        if ('POCZTA_POLSKA_E24' === this.value
            || 'TNT' === this.value
            || 'TNT_Z' === this.value
        ) {
            jQuery('.apaczka_send_pickup_self').prop("disabled", true);
        } else {
            jQuery('.apaczka_send_pickup_self').prop("disabled", false);
        }


        if ('POCZTA_POLSKA_E24' === this.value
            || 'KEX_EXPRESS' === this.value
            || 'TNT' === this.value
            || 'TNT_Z' === this.value
            || 'KEX_EXPRESS' === this.value
            || 'KEX_EXPRESS' === this.value
            || 'UPS_Z_STANDARD' === this.value
            || 'UPS_Z_STANDARD' === this.value
            || 'DPD_CLASSIC_FOREIGN' === this.value
            || 'APACZKA_DE' === this.value
            || 'PACZKOMAT' === this.value

        ) {
            jQuery('.apaczka_zwrot_dok_checkbox').prop("disabled", true);
            jQuery('.apaczka_zwrot_dok_checkbox').attr('checked', false);
            jQuery('.apaczka_zwrot_dok_checkbox_wrap p label').addClass('apaczka_disabled');
        } else {
            jQuery('.apaczka_zwrot_dok_checkbox').prop("disabled", false);
            jQuery('.apaczka_zwrot_dok_checkbox_wrap p label').removeClass('apaczka_disabled');
        }

        if ('APACZKA_DE' === this.value

        ) {
            jQuery('.apaczka_przes_niet_checkbox').prop("disabled", true);
            jQuery('.apaczka_przes_niet_checkbox').attr('checked', false);
            jQuery('.apaczka_przes_niet_checkbox_wrap p label').addClass('apaczka_disabled');
        } else {
            jQuery('.apaczka_przes_niet_checkbox').prop("disabled", false);
            jQuery('.apaczka_przes_niet_checkbox_wrap p label').removeClass('apaczka_disabled');
        }

        if ('PACZKOMAT' === this.value) {
            jQuery('.shipx-input-wrapper').show();
            jQuery('.apaczka_zwrot_dok_checkbox').prop("disabled", true);
            jQuery('.apaczka_zwrot_dok_checkbox_wrap p label').addClass('apaczka_disabled');
            jQuery('.apaczka_przes_niet_checkbox').prop("disabled", true);
            jQuery('.apaczka_przes_niet_checkbox_wrap p label').addClass('apaczka_disabled');
            jQuery('.apaczka_send').addClass('apaczka_disabled');
            jQuery('.apaczka_send').prop("disabled", true);
            jQuery('.apaczka_send_pickup_self').text("<?php _e('Nadaj w Paczkomacie', 'apaczka') ?>");


        } else {
            jQuery('.shipx-input-wrapper').hide();
            jQuery('.apaczka_zwrot_dok_checkbox').prop("disabled", false);
            jQuery('.apaczka_zwrot_dok_checkbox_wrap p label').removeClass('apaczka_disabled');
            jQuery('.apaczka_przes_niet_checkbox').prop("disabled", false);
            jQuery('.apaczka_przes_niet_checkbox_wrap p label').removeClass('apaczka_disabled');
            jQuery('.apaczka_send').removeClass('apaczka_disabled');
            jQuery('.apaczka_send').prop("disabled", false);
            jQuery('.apaczka_send_pickup_self').text("<?php _e('Nadaj bez zamówienia kuriera', 'apaczka') ?>");
        }

    });

    jQuery(document).ready(function () {
        var apaczka_service_select = jQuery(".apaczka_service_select");

        if (apaczka_service_select.val() !== 'DHLSTD') {
            jQuery('.apaczka_dost_sob_checkbox').prop("disabled", true);
            jQuery('.apaczka_dost_sob_checkbox').attr('checked', false);
            jQuery('.apaczka_dost_sob_checkbox_wrap p label').addClass('apaczka_disabled');
        }


        if ('POCZTA_POLSKA_E24' === apaczka_service_select.val()
            || 'KEX_EXPRESS' === apaczka_service_select.val()
            || 'TNT' === apaczka_service_select.val()
            || 'TNT_Z' === apaczka_service_select.val()
            || 'KEX_EXPRESS' === apaczka_service_select.val()
            || 'KEX_EXPRESS' === apaczka_service_select.val()
            || 'UPS_Z_STANDARD' === apaczka_service_select.val()
            || 'UPS_Z_STANDARD' === apaczka_service_select.val()
            || 'DPD_CLASSIC_FOREIGN' === apaczka_service_select.val()
            || 'APACZKA_DE' === apaczka_service_select.val()
        ) {
            jQuery('.apaczka_zwrot_dok_checkbox').prop("disabled", true);
            jQuery('.apaczka_zwrot_dok_checkbox').attr('checked', false);
            jQuery('.apaczka_zwrot_dok_checkbox_wrap p label').addClass('apaczka_disabled');
        } else {
            jQuery('.apaczka_zwrot_dok_checkbox').prop("disabled", false);
            jQuery('.apaczka_zwrot_dok_checkbox_wrap p label').removeClass('apaczka_disabled');
        }


        if ('POCZTA_POLSKA_E24' === apaczka_service_select.val()
            || 'TNT' === apaczka_service_select.val()
            || 'TNT_Z' === apaczka_service_select.val()
        ) {
            jQuery('.apaczka_send_pickup_self').prop("disabled", true);
        } else {
            jQuery('.apaczka_send_pickup_self').prop("disabled", false);
        }

        if ('PACZKOMAT' === apaczka_service_select.val()) {
            jQuery('.shipx-input-wrapper').show();
            jQuery('.apaczka_zwrot_dok_checkbox').prop("disabled", true);
            jQuery('.apaczka_zwrot_dok_checkbox_wrap p label').addClass('apaczka_disabled');
            jQuery('.apaczka_przes_niet_checkbox').prop("disabled", true);
            jQuery('.apaczka_przes_niet_checkbox_wrap p label').addClass('apaczka_disabled');
            jQuery('.apaczka_send').addClass('apaczka_disabled');
            jQuery('.apaczka_send').prop("disabled", true);
            jQuery('.apaczka_send_pickup_self').text("<?php _e('Nadaj w Paczkomacie', 'apaczka') ?>");
        } else {
            jQuery('.shipx-input-wrapper').hide();
        }

    });

</script>