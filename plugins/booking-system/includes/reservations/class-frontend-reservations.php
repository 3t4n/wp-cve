<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.2
* File                    : includes/reservations/class-reservations-extras.php
* File Version            : 1.1.1
* Created / Last Modified : 04 December 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Front end reservations PHP class.
*/

if (!class_exists('DOPBSPFrontEndReservations')){
    class DOPBSPFrontEndReservations extends DOPBSPFrontEnd{
        /*
         * Constructor.
         */
        function __construct(){
        }

        /*
         * Get reservation data.
         */
        function get(){
            global $DOPBSP;

            return array('data' => array(),
                         'text' => array('addressShippingCopy' => $DOPBSP->text('RESERVATIONS_RESERVATION_ADDRESS_SHIPPING_COPY'),
                                         'buttonApprove' => $DOPBSP->text('RESERVATIONS_RESERVATION_APPROVE'),
                                         'buttonCancel' => $DOPBSP->text('RESERVATIONS_RESERVATION_CANCEL'),
                                         'buttonClose' => $DOPBSP->text('RESERVATIONS_RESERVATION_CLOSE'),
                                         'buttonDelete' => $DOPBSP->text('RESERVATIONS_RESERVATION_DELETE'),
                                         'buttonReject' => $DOPBSP->text('RESERVATIONS_RESERVATION_REJECT'),
                                         'dateCreated' => $DOPBSP->text('RESERVATIONS_RESERVATION_DATE_CREATED'),
                                         'id' => $DOPBSP->text('RESERVATIONS_RESERVATION_ID'),
                                         'instructions' => $DOPBSP->text('RESERVATIONS_RESERVATION_INSTRUCTIONS'),
                                         'language' => $DOPBSP->text('RESERVATIONS_RESERVATION_LANGUAGE'),
                                         'noAddressBilling' => $DOPBSP->text('RESERVATIONS_RESERVATION_NO_ADDRESS_BILLING'),
                                         'noAddressShipping' => $DOPBSP->text('RESERVATIONS_RESERVATION_NO_ADDRESS_SHIPPING'),
                                         'noExtras' => $DOPBSP->text('RESERVATIONS_RESERVATION_NO_EXTRAS'),
                                         'noDiscount' => $DOPBSP->text('RESERVATIONS_RESERVATION_NO_DISCOUNT'),
                                         'noCoupon' => $DOPBSP->text('RESERVATIONS_RESERVATION_NO_COUPON'),
                                         'noFees' => $DOPBSP->text('RESERVATIONS_RESERVATION_NO_FEES'),
                                         'noForm' => $DOPBSP->text('RESERVATIONS_RESERVATION_NO_FORM'),
                                         'noFormField' => $DOPBSP->text('RESERVATIONS_RESERVATION_NO_FORM_FIELD'),
                                         'price' => $DOPBSP->text('RESERVATIONS_RESERVATION_FRONT_END_PRICE'),
                                         'priceChange' => $DOPBSP->text('RESERVATIONS_RESERVATION_PAYMENT_PRICE_CHANGE'),
                                         'priceTotal' => $DOPBSP->text('RESERVATIONS_RESERVATION_FRONT_END_TOTAL_PRICE'),
                                         'selectDays' => $DOPBSP->text('RESERVATIONS_RESERVATION_FRONT_END_SELECT_DAYS'),
                                         'selectHours' => $DOPBSP->text('RESERVATIONS_RESERVATION_FRONT_END_SELECT_HOURS'),
                                         'status' => $DOPBSP->text('RESERVATIONS_RESERVATION_STATUS'),
                                         'statusApproved' => $DOPBSP->text('RESERVATIONS_RESERVATION_STATUS_APPROVED'),
                                         'statusCanceled' => $DOPBSP->text('RESERVATIONS_RESERVATION_STATUS_CANCELED'),
                                         'statusExpired' => $DOPBSP->text('RESERVATIONS_RESERVATION_STATUS_EXPIRED'),
                                         'statusPending' => $DOPBSP->text('RESERVATIONS_RESERVATION_STATUS_PENDING'),
                                         'statusRejected' => $DOPBSP->text('RESERVATIONS_RESERVATION_STATUS_REJECTED'),
                                         'title' => $DOPBSP->text('RESERVATIONS_RESERVATION_FRONT_END_TITLE'),
                                         'titleDetails' => $DOPBSP->text('RESERVATIONS_RESERVATION_DETAILS_TITLE')));
        }

        /*
         * Book a reservation.
         *
         * @post calendar_id (integer): calendar ID
         * @post language (string): selected language
         * @post currency (string): selected currency sign
         * @post currency_code (string): selected currency code
         * @post cart_data (array): list of reservations
         * @post form (object): form data
         * @post address_billing_data (object): billing address data
         * @post address_shipping_data (object): shipping address data
         * @post payment_method (string): selected payment method
         * @post form_addon_data (object): form addon data
         * @post card_data (object): card data
         * @post token (string): payment token (different for payment gateways)
         * @post page_url (string): page url were the calendar is
         */
        function book(){
            global $DOT;
            global $DOPBSP;

            // HOOK (dopbsp_action_book_before) *************************************** Add action before booking request.
            do_action('dopbsp_action_book_before');

            $calendar_id = $DOT->post('calendar_id',
                                      'int');
            $language = $DOT->post('language');
            $currency = $DOT->post('currency');
            $currency_code = $DOT->post('currency_code');
            $cart = $DOT->post('cart_data');
            $form = $DOT->post('form');
            $address_billing = $DOT->post('address_billing_data');
            $address_shipping = $DOT->post('address_shipping_data');
            $payment_method = $DOT->post('payment_method');
            $token = $DOT->post('token');
            $ip = $_SERVER['REMOTE_ADDR'];

            // Sync with iCal
            $DOPBSP->classes->backend_calendar_schedule->sync($calendar_id,
                                                              true);

            /*
             * Verify reservations.
             */
            $settings_payment = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                           'payment');

            for ($i = 0; $i<count($cart); $i++){
                $reservation = $cart[$i];
                $reservation['ip'] = $ip;

                if (($payment_method != 'default'
                                && $payment_method != 'none')
                        || $settings_payment->arrival_with_approval_enabled == 'true'){
                    /*
                     * Verify reservations availability.
                     */
                    if ($reservation['start_hour'] == ''){
                        if (!$DOPBSP->classes->backend_calendar_schedule->validateDays($calendar_id,
                                                                                       $reservation['check_in'],
                                                                                       $reservation['check_out'],
                                                                                       $reservation['no_items'])){
                            echo 'unavailable';
                            die();
                        }
                    }
                    else{
                        if (!$DOPBSP->classes->backend_calendar_schedule->validateHours($calendar_id,
                                                                                        $reservation['check_in'],
                                                                                        $reservation['start_hour'],
                                                                                        $reservation['end_hour'],
                                                                                        $reservation['no_items'])){
                            echo 'unavailable';
                            die();
                        }
                    }

                    /*
                     * Verify coupon.
                     */
                    // $coupon = json_decode($reservation['coupon']);
                    $coupon = $reservation['coupon'];

                    if ($coupon['id'] != 0){
                        if (!$DOPBSP->classes->backend_coupon->validate($coupon['id'])){
                            echo 'unavailable-coupon';
                            die();
                        }
                    }
                }

                /*
                 * Validate reservation.
                 */
                if (!$this->validate($calendar_id,
                                     $reservation)){
                    echo 'security';
                    die();
                }
            }

            /*
             * Set token.
             */
            if ($payment_method != 'default'
                    && $payment_method != 'none'){
                $token = $token == ''
                        ? $DOPBSP->classes->prototypes->getRandomString(32)
                        : $token;
            }
            else{
                $token = '';
            }
            $DOPBSP->vars->payment_token = $token;

            /*
             * Add reservations.
             */
            for ($i = 0; $i<count($cart); $i++){
                $reservation = $cart[$i];
                $reservation['ip'] = $ip;

                $reservation_id = $DOPBSP->classes->backend_reservation->add($calendar_id,
                                                                             $language,
                                                                             $currency,
                                                                             $currency_code,
                                                                             $reservation,
                                                                             $form,
                                                                             $address_billing,
                                                                             $address_shipping,
                                                                             $payment_method,
                                                                             $token);

                if ($payment_method == 'default'
                        || $payment_method == 'none'){
                    if ($settings_payment->arrival_with_approval_enabled == 'true'){
                        $DOPBSP->classes->backend_reservation_notifications->send($reservation_id,
                                                                                  'book_with_approval_admin');
                        $DOPBSP->classes->backend_reservation_notifications->send($reservation_id,
                                                                                  'book_with_approval_user');
                    }
                    else{
                        $DOPBSP->classes->backend_reservation_notifications->send($reservation_id,
                                                                                  'book_admin');
                        $DOPBSP->classes->backend_reservation_notifications->send($reservation_id,
                                                                                  'book_user');
                    }
                }
            }

            // HOOK (dopbsp_action_book_payment) *************************************** Add action for payment gateways.
            do_action('dopbsp_action_book_payment');

            // HOOK (dopbsp_action_book_after) *************************************** Add action after booking request.
            do_action('dopbsp_action_book_after');

            die();
        }

        /*
         * Sync a reservation.
         *
         * @post calendar_id (integer): calendar ID
         * @post language (string): selected language
         * @post currency (string): selected currency sign
         * @post currency_code (string): selected currency code
         * @post cart_data (array): list of reservations
         * @post form (object): form data
         * @post address_billing_data (object): billing address data
         * @post address_shipping_data (object): shipping address data
         * @post payment_method (string): selected payment method
         * @post form_addon_data (object): form addon data
         * @post card_data (object): card data
         * @post token (string): payment token (different for payment gateways)
         */
        function sync($calendar_id,
                      $language,
                      $currency,
                      $currency_code,
                      $cart,
                      $form,
                      $address_billing,
                      $address_shipping,
                      $payment_method,
                      $token,
                      $source = 'pinpoint'){
            global $DOPBSP;
            global $wpdb;

            $ip = $_SERVER['REMOTE_ADDR'];

            /*
             * Verify reservations.
             */
            $settings_payment = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                           'payment');

            /*
             * Set token.
             */
            if ($payment_method != 'default'
                    && $payment_method != 'none'){
                $token = $token == ''
                        ? $DOPBSP->classes->prototypes->getRandomString(32)
                        : $token;
            }
            else{
                $token = '';
            }
            $DOPBSP->vars->payment_token = $token;

            /*
             * Add reservations.
             */
            for ($i = 0; $i<count($cart); $i++){
                $reservation = $cart[$i];
                $reservation['ip'] = $ip;

                // check reservation
                if ($source != 'airbnb'){
                    $control_data = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->reservations.' WHERE uid="%s" AND calendar_id=%d AND (status="approved" OR status="expired")',
                                                                  $reservation['uid'],
                                                                  $calendar_id));
                }
                else{
                    $control_data = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->reservations.' WHERE check_in="%s" AND check_out="%s" AND start_hour="%s" AND end_hour="%s" AND calendar_id=%d AND (status="approved" OR status="expired")',
                                                                  $reservation['check_in'],
                                                                  $reservation['check_out'],
                                                                  $reservation['start_hour'],
                                                                  $reservation['end_hour'],
                                                                  $calendar_id));
                }

                if ($wpdb->num_rows<1){
                    $DOPBSP->classes->backend_reservation->add($calendar_id,
                                                               $language,
                                                               $currency,
                                                               $currency_code,
                                                               $reservation,
                                                               $form,
                                                               $address_billing,
                                                               $address_shipping,
                                                               $payment_method,
                                                               $token,
                                                               '',
                                                               'approved',
                                                               $source);
                }
            }
        }

        function validate($calendar_id,
                          $reservation){
            /*
             * Validate price.
             */
            $price = $reservation['no_items']
                    *($reservation['start_hour'] == ''
                            ? $this->getPriceDays($calendar_id,
                                                  $reservation)
                            : $this->getPriceHours($calendar_id,
                                                   $reservation));

            if (floor(floatval($reservation['price'])) !== floor($price) && floor($price) != 0){
                return false;
            }
            
            /*
             * Validate extras.
             */
            $price_extras = $this->validateExtras($calendar_id,
                                                  $reservation);

            if ($price_extras === false
                    || floor(floatval($reservation['extras_price'])) !== floor($price_extras)){
                return false;
            }

            /*
             * Validate discount.
             */
            $price_discount = $this->validateDiscount($calendar_id,
                                                      $reservation);

            if ($price_discount === false
                    || floor(floatval($reservation['discount_price'])) !== floor($price_discount)){
                return false;
            }

            /*
             * Validate fees.
             */
            $price_fees = $this->validateFees($calendar_id,
                                              $reservation);

            if ($price_fees === false
                    || floor(floatval($reservation['fees_price'])) !== floor($price_fees)){
                return false;
            }

            /*
             * Validate coupon.
             */
            $price_coupon = $this->validateCoupon($calendar_id,
                                                  $reservation);

            if ($price_coupon === false
                    || floor(floatval($reservation['coupon_price'])) !== floor($price_coupon)){
                return false;
            }

            /*
             * Validate total price.
             */
            $price_total = $price+$price_extras+$price_discount+$price_fees+$price_coupon;
            $decimals = 2;
            $expo = pow(10,
                        $decimals);
            $price_total = intval($price_total*$expo)/$expo;

            if (floor(floatval($reservation['price_total'])) !== floor($price_total) && floor($price_total) != 0){
                return false;
            }

            /*
             * Validate deposit price.
             */
            $price_deposit = $this->validateDeposit($calendar_id,
                                                    $reservation);

            if (($price_deposit === false
                            || floor(floatval($reservation['deposit_price'])) !== floor($price_deposit))
                    && (float)$reservation['deposit_price']>0){
                return false;
            }

            return true;
        }

        function getPriceDays($calendar_id,
                              $reservation){
            global $DOT;
            global $wpdb;
            global $DOPBSP;

            /*
             * Get data.
             */
            $check_in = $reservation['check_in'];
            $check_out = $reservation['check_out'];
            $price = 0;

            $check_out = $check_out == ''
                    ? $check_in
                    : $check_out;

            $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                            'calendar');

            $selected_days = $DOT->models->days->get($check_in,
                                                     $check_out);

            // Default Availability
            $calendar = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->calendars.' WHERE id=%d',
                                                      $calendar_id));

            $calendar->default_availability != ''
                    ? $default_availability = json_decode($calendar->default_availability)
                    : null;

            for ($i = 0; $i<count($selected_days)-($settings_calendar->days_morning_check_out == 'true'
                    ? 1
                    : 0); $i++){
                $day = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->days.' WHERE calendar_id=%d AND day="%s"',
                                                     $calendar_id,
                                                     $selected_days[$i]));

                $day_data = $wpdb->num_rows<1
                        ? $default_availability
                        : json_decode($day->data);

                if (($day_data->status == 'available'
                                || $day_data->status == 'special')
                        && ($day_data->bind == 0
                                || $day_data->bind == 1)){
                    $price_day = $day_data->price;
                    $promo_day = $day_data->promo;

                    if ($price_day != 0){
                        $price += $promo_day == 0 || $promo_day == ''
                                ? $price_day
                                : $promo_day;
                    }
                }
            }

            return strval($price) == '-0'
                    ? 0
                    : $price;
        }

        function getPriceHours($calendar_id,
                               $reservation){
            global $wpdb;
            global $DOPBSP;

            /*
             * Get data.
             */
            $day = $reservation['check_in'];
            $start_hour = $reservation['start_hour'];
            $end_hour = $reservation['end_hour'];
            $price = 0;

            $end_hour = $end_hour == ''
                    ? $start_hour
                    : $end_hour;

            $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                            'calendar');

            // Default Availability
            $calendar = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->calendars.' WHERE id=%d',
                                                      $calendar_id));

            $default_availability = json_decode($calendar->default_availability);
            //Custom Availability
            $day = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->days.' WHERE calendar_id=%d AND day="%s"',
                                                 $calendar_id,
                                                 $day));

            if ($wpdb->num_rows<1){
                $day = $default_availability;
                $day->data = json_encode($default_availability);
            }

            $day_data = json_decode($day->data);

            $i = 0;

            foreach ($day_data->hours as $key => $hour){
                $i++;

                if ($settings_calendar->hours_interval_autobreak_enabled == 'true'
                        && $i%2 == 0){
                    continue;
                }

                if ($start_hour<=$key
                        && ((($settings_calendar->hours_add_last_hour_to_total_price == 'false'
                                                || $settings_calendar->hours_interval_enabled == 'true')
                                        && $key<$end_hour) ||
                                ($settings_calendar->hours_add_last_hour_to_total_price == 'true'
                                        && $settings_calendar->hours_interval_enabled == 'false'
                                        && $key<=$end_hour) ||
                                ($start_hour == $end_hour
                                        && $key<=$end_hour))
                        && ($hour->status == 'available'
                                || $hour->status == 'special')
                        && ($hour->bind == 0
                                || $hour->bind == 1)){
                    $price_hour = $hour->price;
                    $promo_hour = $hour->promo;

                    if ($price_hour != 0){
                        $price += $promo_hour == 0 || $promo_hour == ''
                                ? $price_hour
                                : $promo_hour;
                    }
                }
            }

            return strval($price) == '-0'
                    ? 0
                    : $price;
        }

        function validateExtras($calendar_id,
                                $reservation){
            global $DOPBSP;
            global $DOT;
            global $wpdb;

            /*
             * Get data.
             */
            $extras = $reservation['extras'];
            $price_reservation = $reservation['price'];
            $check_in = $reservation['check_in'];
            $check_out = $reservation['check_out'];
            $start_hour = $reservation['start_hour'];
            $end_hour = $reservation['end_hour'];
            $no_items = $reservation['no_items'];
            $price = 0;

            /*
             * Verify days/hours.
             */
            $check_out = $check_out == ''
                    ? $check_in
                    : $check_out;
            $end_hour = $end_hour == ''
                    ? $start_hour
                    : $end_hour;

            /*
             * Get data.
             */
            $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                            'calendar');

            /*
             * Get extras data.
             */
            $extras_ids = array();
            $extras_ids_type = array();

            foreach ($extras as $extra){
                array_push($extras_ids,
                           $extra['id']);
                array_push($extras_ids_type,
                           '%d');
            }

            $extras_data = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->extras_groups_items.' WHERE id in ('.implode(',',
                                                                                                                                            $extras_ids_type).') ORDER BY position',
                                                             $extras_ids));

            /*
             * Verify data.
             */
            if ($start_hour == ''){
                $selected_days = $DOT->models->days->get($check_in,
                                                         $check_out);
                $time_lapse = count($selected_days)-($settings_calendar->days_morning_check_out == 'true'
                                ? 1
                                : 0);
            }
            else{
                $time_lapse = $DOT->prototypes->hours($start_hour,
                                                      $end_hour);
            }

            foreach ($extras as $extra){
                foreach ($extras_data as $extra_data){
                    if ($extra['id'] == $extra_data->id){
                        /*
                         * Calculate price.
                         */
                        $price_extra = ($extra_data->operation == '-'
                                        ? -1
                                        : 1)
                                *($extra_data->price_by == 'once'
                                        ? 1
                                        : $time_lapse)
                                *$extra_data->price
                                *($extra_data->price_type == 'fixed'
                                        ? ($extra['no_items_multiply'] == 'true'
                                                ? $no_items
                                                : 1)
                                        : floatval($price_reservation))
                                /($extra_data->price_type == 'fixed'
                                        ? 1
                                        : 100);

                        /*
                         * Verify data.
                         */
                        if ($extra['group_id'] != $extra_data->group_id
                                || $extra['operation'] != $extra_data->operation
                                || $extra['price'] != $extra_data->price
                                || $extra['price_by'] != $extra_data->price_by
                                || $extra['price_type'] != $extra_data->price_type
                                || $extra['price_total'] != $price_extra){
                            return false;
                        }

                        $price += $price_extra;
                    }
                }
            }

            return strval($price) == '-0'
                    ? 0
                    : $price;
        }

        function validateDiscount($calendar_id,
                                  $reservation){
            global $DOPBSP;
            global $DOT;

            /*
             * Get data.
             */
            $discount = $reservation['discount'];
            $price_extras = $reservation['extras_price'];
            $price_reservation = $reservation['price'];
            $check_in = $reservation['check_in'];
            $check_out = $reservation['check_out'];
            $start_hour = $reservation['start_hour'];
            $end_hour = $reservation['end_hour'];
            $no_items = $reservation['no_items'];

            /*
             * Verify days/hours.
             */
            $check_out = $check_out == ''
                    ? $check_in
                    : $check_out;
            $end_hour = $end_hour == ''
                    ? $start_hour
                    : $end_hour;

            /*
             * Get data.
             */
            $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                            'calendar');

            /*
             * Get discount data.
             */
            $discount_data = $this->validateDiscountGet($calendar_id,
                                                        $reservation);

            /*
             * Verify data.
             */
            if ($start_hour == ''){
                $selected_days = $DOT->models->days->get($check_in,
                                                         $check_out);
                $time_lapse = count($selected_days)-($settings_calendar->days_morning_check_out == 'true'
                                ? 1
                                : 0);
            }
            else{
                $time_lapse = $DOT->prototypes->hours($start_hour,
                                                      $end_hour);
            }

            /*
             * Verify data.
             */
            if ($discount['rule_id'] != $discount_data->rule_id
                    || $discount['operation'] != $discount_data->operation
                    || $discount['price'] != $discount_data->price
                    || $discount['price_by'] != $discount_data->price_by
                    || $discount['price_type'] != $discount_data->price_type
                    || $discount['start_date'] != $discount_data->start_date
                    || $discount['end_date'] != $discount_data->end_date
                    || $discount['start_hour'] != $discount_data->start_hour
                    || $discount['end_hour'] != $discount_data->end_hour){
                return false;
            }

            /*
             * Calculate price.
             */
            $price = ($discount_data->operation === '-'
                            ? -1
                            : 1)
                    *($discount_data->price_by === 'once'
                            ? 1
                            : $time_lapse)
                    *$discount_data->price
                    *($discount_data->price_type === 'fixed'
                            ? $no_items
                            : ($price_reservation+($discount_data->extras
                                            ? floatval($price_extras)
                                            : 0)))
                    /($discount_data->price_type === 'fixed'
                            ? 1
                            : 100);

            return strval($price) == '-0'
                    ? 0
                    : $price;
        }

        function validateDiscountGet($calendar_id,
                                     $reservation){
            global $DOPBSP;
            global $DOT;

            /*
             * Get data.
             */
            $check_in = $reservation['check_in'];
            $check_out = $reservation['check_out'];
            $start_hour = $reservation['start_hour'];
            $end_hour = $reservation['end_hour'];

            /*
             * Verify days/hours.
             */
            $check_out = $check_out == ''
                    ? $check_in
                    : $check_out;
            $end_hour = $end_hour == ''
                    ? $start_hour
                    : $end_hour;

            /*
             * Initialize discount.
             */
            $discount_data = new stdClass;
            $discount_data->id = 0;
            $discount_data->rule_id = 0;
            $discount_data->operation = '-';
            $discount_data->price = 0;
            $discount_data->price_type = 'percent';
            $discount_data->price_by = 'once';
            $discount_data->start_date = '';
            $discount_data->end_date = '';
            $discount_data->start_hour = '';
            $discount_data->end_hour = '';
            $discount_data->translation = '';
            $discount_data->extras = false;

            /*
             * Get data.
             */
            $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                            'calendar');

            /*
             * Get discounts data.
             */
            $discounts = $DOPBSP->classes->frontend_discounts->get($settings_calendar->discount);
            $discount_data->extras = $discounts['data']['extras'];
            $discounts = $discounts['data']['discount'];

            /*
             * Verify data.
             */
            if ($start_hour == ''){
                $selected_days = $DOT->models->days->get($check_in,
                                                         $check_out);
                $time_lapse = count($selected_days)-($settings_calendar->days_morning_check_out == 'true'
                                ? 1
                                : 0);
            }
            else{
                $time_lapse = $DOT->prototypes->hours($start_hour,
                                                      $end_hour);
            }

            //            if ($settings_calendar->hours_enabled == 'true'
            //                    && $settings_calendar->hours_interval_enabled == 'true'){
            //                $time_lapse = $time_lapse%2 === 0
            //                        ? intval($time_lapse/2)
            //                        : intval($time_lapse/2)+1;
            //            }

            for ($i = 0; $i<count($discounts); $i++){
                if (($discounts[$i]->start_time_lapse == ''
                                || $discounts[$i]->start_time_lapse == 0
                                || floatval($discounts[$i]->start_time_lapse)<=$time_lapse)
                        && ($discounts[$i]->end_time_lapse == ''
                                || $discounts[$i]->end_time_lapse == 0
                                || floatval($discounts[$i]->end_time_lapse)>=$time_lapse)){
                    $discount_data->id = $discounts[$i]->id;
                    $discount_data->operation = $discounts[$i]->operation;
                    $discount_data->price = $discounts[$i]->price;
                    $discount_data->price_by = $discounts[$i]->price_by;
                    $discount_data->price_type = $discounts[$i]->price_type;
                    $discount_data->translation = $discounts[$i]->translation;

                    for ($j = 0; $j<count($discounts[$i]->rules); $j++){
                        $rule = $discounts[$i]->rules[$j];

                        if (($rule->start_date == ''
                                        || $rule->start_date<=$check_in)
                                && ($rule->end_date == ''
                                        || $rule->end_date>=$check_out)){
                            if ($settings_calendar->hours_enabled == 'true'){
                                if (($rule->start_hour == ''
                                                || $rule->start_hour<=$start_hour)
                                        && ($rule->end_hour == ''
                                                || $rule->end_hour>=$end_hour)){
                                    $rule_found = true;
                                }
                            }
                            else{
                                $rule_found = true;
                            }
                        }

                        if ($rule_found){
                            $discount_data->rule_id = $rule->id;
                            $discount_data->operation = $rule->operation;
                            $discount_data->price = $rule->price;
                            $discount_data->price_by = $rule->price_by;
                            $discount_data->price_type = $rule->price_type;
                            $discount_data->start_date = $rule->start_date;
                            $discount_data->end_date = $rule->end_date;
                            break;
                        }
                    }

                    if (count($discounts[$i]->rules) == 0
                            && $discounts[$i]->price != 0
                            || $rule_found){
                        break;
                    }
                }
            }

            return $discount_data;
        }

        function validateFees($calendar_id,
                              $reservation){
            global $DOPBSP;
            global $DOT;

            /*
             * Get data.
             */
            $fees = $reservation['fees'];
            $price_extras = $reservation['extras_price'];
            $price_discount = $reservation['discount_price'];
            $price_reservation = $reservation['price'];
            $check_in = $reservation['check_in'];
            $check_out = $reservation['check_out'];
            $start_hour = $reservation['start_hour'];
            $end_hour = $reservation['end_hour'];
            $no_items = $reservation['no_items'];
            $price = 0;

            /*
             * Verify days/hours.
             */
            $check_out = $check_out == ''
                    ? $check_in
                    : $check_out;
            $end_hour = $end_hour == ''
                    ? $start_hour
                    : $end_hour;

            /*
             * Get data.
             */
            $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                            'calendar');

            /*
             * Get discount data.
             */
            $fees_data = $DOPBSP->classes->frontend_fees->get($settings_calendar->fees);

            /*
             * Verify data.
             */
            if ($start_hour == ''){
                $selected_days = $DOT->models->days->get($check_in,
                                                         $check_out);
                $time_lapse = count($selected_days)-($settings_calendar->days_morning_check_out == 'true'
                                ? 1
                                : 0);
            }
            else{
                $time_lapse = $DOT->prototypes->hours($start_hour,
                                                      $end_hour)+($settings_calendar->hours_add_last_hour_to_total_price
                                ? 1
                                : 0);
            }

            foreach ($fees as $fee){
                foreach ($fees_data['data']['fees'] as $fee_data){
                    if ($fee['id'] == $fee_data->id){
                        /*
                         * Calculate price.
                         */
                        $price_fee = ($fee_data->operation == '-'
                                        ? -1
                                        : 1)
                                *($fee_data->price_by == 'once'
                                        ? 1
                                        : $time_lapse)
                                *$fee_data->price
                                *($fee_data->price_type == 'fixed'
                                        ? $no_items
                                        : (floatval($price_reservation)+floatval($price_discount)+($fee_data->extras === 'true'
                                                        ? floatval($price_extras)
                                                        : 0)))
                                /($fee_data->price_type == 'fixed'
                                        ? 1
                                        : 100);

                        /*
                         * Verify data.
                         */
                        if ($fee['group_id'] != $fee_data->group_id
                                || $fee['operation'] != $fee_data->operation
                                || $fee['price'] != $fee_data->price
                                || $fee['price_by'] != $fee_data->price_by
                                || $fee['price_type'] != $fee_data->price_type
                                || $fee['included'] != $fee_data->included){
                            return false;
                        }

                        $price += $fee_data->included == 'true'
                                ? 0
                                : $price_fee;
                    }
                }
            }

            return strval($price) == '-0'
                    ? 0
                    : $price;
        }

        function validateCoupon($calendar_id,
                                $reservation){
            global $DOPBSP;
            global $DOT;

            /*
             * Get data.
             */
            $coupon = $reservation['coupon'];
            $price_extras = $reservation['extras_price'];
            $price_discount = $reservation['discount_price'];
            $price_fees = $reservation['discount_fees'];
            $price_reservation = $reservation['price'];
            $check_in = $reservation['check_in'];
            $check_out = $reservation['check_out'];
            $start_hour = $reservation['start_hour'];
            $end_hour = $reservation['end_hour'];
            $no_items = $reservation['no_items'];
            $price = 0;

            /*
             * Verify days/hours.
             */
            $check_out = $check_out == ''
                    ? $check_in
                    : $check_out;
            $end_hour = $end_hour == ''
                    ? $start_hour
                    : $end_hour;

            /*
             * Get data.
             */
            $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                            'calendar');

            /*
             * Get discount data.
             */
            $coupon_data = $DOPBSP->classes->frontend_coupons->get($coupon['id']);
            $coupon_data = $coupon_data['data']['coupon'];

            /*
             * Verify data.
             */
            if ($start_hour == ''){
                $selected_days = $DOT->models->days->get($check_in,
                                                         $check_out);
                $time_lapse = count($selected_days)-($settings_calendar->days_morning_check_out == 'true'
                                ? 1
                                : 0);
            }
            else{
                $time_lapse = $DOT->prototypes->hours($start_hour,
                                                      $end_hour);
            }

            if ($coupon['id'] == $coupon_data->id){
                /*
                 * Calculate price.
                 */
                $price_coupon = ($coupon_data->operation == '-'
                                ? -1
                                : 1)
                        *($coupon_data->price_by == 'once'
                                ? 1
                                : $time_lapse)
                        *$coupon_data->price
                        *($coupon_data->price_type == 'fixed'
                                ? 1
                                : (floatval($price_reservation)+floatval($price_discount)+floatval($price_extras)+floatval($price_fees)))
                        /($coupon_data->price_type == 'fixed'
                                ? 1
                                : 100);

                /*
                 * Verify data.
                 */
                if ($coupon['operation'] != $coupon_data->operation
                        || $coupon['price'] != $coupon_data->price
                        || $coupon['price_by'] != $coupon_data->price_by
                        || $coupon['price_type'] != $coupon_data->price_type){
                    return false;
                }

                $price += $price_coupon;
            }

            return strval($price) == '-0'
                    ? 0
                    : $price;
        }

        function validateDeposit($calendar_id,
                                 $reservation){
            global $DOPBSP;
            global $DOT;

            /*
             * Get data.
             */
            $deposit = $reservation['deposit'];
            $price_total = $reservation['price_total'];

            /*
             * Get data.
             */
            $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                            'calendar');

            $price = floatval($settings_calendar->deposit)
                    *($settings_calendar->deposit_type === 'fixed'
                            ? 1
                            : floatval($price_total))
                    /($settings_calendar->deposit_type === 'fixed'
                            ? 1
                            : 100);

            return strval($price) == '-0'
                    ? 0
                    : $price;
        }
    }
}