<?php

/*
 * Title                   : Pinpoint Booking System
 * File                    : application/models/reservations/model-reservations-xls.php
 * Author                  : Pinpoint World
 * Copyright               : © 2021 Pinpoint World
 * Website                 : https://pinpoint.world
 * Description             : Reservations XLS model PHP class.
 */

if (!class_exists('DOTModelReservationsXls')){
    class DOTModelReservationsXls{
        /*
         * Constructor
         *
         * @usage
         *      The constructor is called when a class instance is created.
         *
         * @params
         *      -
         *
         * @post
         *      -
         *
         * @get
         *      -
         *
         * @sessions
         *      -
         *
         * @cookies
         *      -
         *
         * @constants
         *      -
         *
         * @globals
         *      -
         *
         * @functions
         *      -
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      -
         *
         * @return_details
         *      -
         *
         * @dv
         *      -
         *
         * @tests
         *      -
         */
        function __construct(){
        }

        /*
         * Get reservation XLS data.
         *
         * @usage
         *      In FILE search for function call: $this->data
         *      In FILE search for function call in hooks: array(&$this, 'data')
         *      In PROJECT search for function call: $DOT->models->reservations_xls->data
         *
         * @params
         *      labels (array): labels list
         *      reservation (object): reservation data
         *
         * @post
         *      -
         *
         * @get
         *      -
         *
         * @sessions
         *      -
         *
         * @cookies
         *      -
         *
         * @constants
         *      -
         *
         * @globals
         *      DOT (object): DOT framework main class variable
         *
         * @functions
         *      this : price() // Format price type value.
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      "labels" is updated by reference.
         *
         *      Reservation data.
         *
         * @return_details
         *      -
         *
         * @dv
         *      -
         *
         * @tests
         *      -
         */
        /**
         * @param array $labels
         * @param object $reservation
         */
        function data(&$labels,
                      $reservation){
            global $DOPBSP;
            global $wpdb;

            $data = array();

            /*
             * Get calendar data.
             */
            $calendar = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->calendars.' WHERE id=%d',
                                                      $reservation->calendar_id));
            $calendar_settings = $DOPBSP->classes->backend_settings->values($reservation->calendar_id,
                                                                            'calendar');

            /*
             * Set reservation ID.
             */
            $data['id'] = $reservation->id;
            $labels['id']->usage++;

            /*
             * Set reservation status.
             */
            switch ($reservation->status){
                case 'pending':
                    $status = 'RESERVATIONS_RESERVATION_STATUS_PENDING';
                    break;
                case 'approved':
                    $status = 'RESERVATIONS_RESERVATION_STATUS_APPROVED';
                    break;
                case 'rejected':
                    $status = 'RESERVATIONS_RESERVATION_STATUS_REJECTED';
                    break;
                case 'canceled':
                    $status = 'RESERVATIONS_RESERVATION_STATUS_CANCELED';
                    break;
                default:
                    $status = 'RESERVATIONS_RESERVATION_STATUS_EXPIRED';
            }
            $data['status'] = $DOPBSP->text($status);
            $labels['status']->usage++;

            /*
             * Set reservation calendar ID.
             */
            $data['calendar_id'] = $reservation->id;
            $labels['calendar_id']->usage++;

            /*
             * Set reservation calendar name.
             */
            $data['calendar_name'] = utf8_decode($calendar->name);
            $labels['calendar_name']->usage++;

            /*
             * Set reservation check in.
             */
            $data['check_in'] = $reservation->check_in;
            $labels['check_in']->usage++;

            /*
             * Set reservation check out.
             */
            if ($reservation->check_out != ''){
                $data['check_out'] = $reservation->check_out;
                $labels['check_out']->usage++;
            }

            /*
             * Set reservation start hour.
             */
            if ($reservation->start_hour != ''){
                $data['start_hour'] = $reservation->start_hour;
                $labels['start_hour']->usage++;
            }

            /*
             * Set reservation end hour.
             */
            if ($reservation->end_hour != ''){
                $data['end_hour'] = $reservation->end_hour;
                $labels['end_hour']->usage++;
            }

            /*
             * Set reservation no items.
             */
            if ($calendar_settings->sidebar_no_items_enabled == 'true'){
                $data['no_items'] = $reservation->no_items;
                $labels['no_items']->usage++;
            }

            /*
             * Set reservation currency.
             */
            if ($reservation->price>0
                    || $reservation->price_total>=0
                    || $reservation->extras_price != 0
                    || $reservation->discount_price != 0
                    || $reservation->fees_price != 0
                    || $reservation->coupon_price != 0){
                $data['currency_code'] = $reservation->currency_code;
                $labels['currency_code']->usage++;
            }

            /*
             * Set reservation price.
             */
            if ($reservation->price>0){
                $data['price'] = $this->price($reservation->price);
                $labels['price']->usage++;
            }

            /*
             * Set reservation total price.
             */
            if ($reservation->price_total>=0){
                $data['price_total'] = $this->price($reservation->price_total);
                $labels['price_total']->usage++;
            }

            /*
             * Set reservation deposit & deposit left.
             */
            if ($reservation->deposit_price>0){
                $data['deposit_price'] = $this->price($reservation->deposit_price);
                $labels['deposit_price']->usage++;

                /*
                 * Set reservation deposit left.
                 */
                $data['deposit_price_left'] = $this->price($reservation->price_total-$reservation->deposit_price);
                $labels['deposit_price_left']->usage++;
            }

            /*
             * Set reservation payment method & transaction ID.
             */
            switch ($reservation->payment_method){
                case 'none':
                    $data['payment_method'] = $DOPBSP->text('ORDER_PAYMENT_METHOD_NONE');
                    break;
                case 'default':
                    $data['payment_method'] = $DOPBSP->text('ORDER_PAYMENT_METHOD_ARRIVAL');
                    break;
                case 'woocommerce':
                    $data['payment_method'] = $DOPBSP->text('ORDER_PAYMENT_METHOD_WOOCOMMERCE');
                    $data['transaction_id'] = $reservation->transaction_id;
                    $labels['transaction_id']->usage++;
                    break;
                default:
                    $data['payment_method'] = $DOPBSP->text('SETTINGS_PAYMENT_GATEWAYS_'.strtoupper($reservation->payment_method));
                    $data['transaction_id'] = $reservation->transaction_id;
                    $labels['transaction_id']->usage++;
            }
            $labels['payment_method']->usage++;

            /*
             * Set reservation extras.
             */
            $extras = $reservation->extras != ''
                    ? json_decode(utf8_decode($reservation->extras))
                    : array();

            foreach ($extras as $item){
                $key = 'extra_'.$item->group_id;
                isset($data[$key])
                        ? $data[$key] .= $item->translation
                        : $data[$key] = $item->translation;
                $labels[$key]->usage++;
            }

            if ($reservation->extras_price != 0){
                $data['extras_price'] = $this->price($reservation->extras_price);
                $labels['extras_price']->usage++;
            }

            /*
             * Set reservation discount.
             */
            if ($reservation->discount_price != 0){
                $discount = json_decode(utf8_decode($reservation->discount));
                $data['discount'] = $discount->translation;
                $labels['discount']->usage++;

                $data['discount_price'] = $this->price($reservation->discount_price);
                $labels['discount_price']->usage++;
            }

            /*
             * Set reservation fees.
             */
            $fees = $reservation->fees != ''
                    ? json_decode($reservation->fees)
                    : array();

            foreach ($fees as $item){
                $key = 'fee_'.$item->id;
                $data[$key] = '';

                if ($item->price_type != 'fixed'
                        || $item->price_by != 'once'){
                    $data[$key] .= $item->operation.' '
                            .($item->price_type == 'fixed'
                                    ? $item->price
                                    : $item->price.'%');

                    $item->price_by != 'once'
                            ? $data[$key] .= '/'.($settings_calendar->hours_enabled == 'true'
                                    ? $DOPBSP->text('FEES_FRONT_END_BY_HOUR')
                                    : $DOPBSP->text('FEES_FRONT_END_BY_DAY'))
                            : null;
                }
                $data[$key] .= $item->included == 'true'
                        ? ' '.$DOPBSP->text('FEES_FRONT_END_INCLUDED')
                        : '';

                $labels[$key]->usage++;
            }

            if ($reservation->fees_price != 0){
                $data['fees_price'] = $this->price($reservation->fees_price);
                $labels['fees_price']->usage++;
            }

            /*
             * Set reservation coupon.
             */
            if ($reservation->coupon_price != 0){
                $coupon = json_decode(utf8_decode($reservation->coupon));
                $data['coupon'] = $coupon->translation;
                $labels['coupon']->usage++;

                $data['coupon_price'] = $this->price($reservation->coupon_price);
                $labels['coupon_price']->usage++;
            }

            /*
             * Set reservation email & form.
             */
            $data['email'] = $reservation->email;
            $labels['email']->usage++;

            $form = $reservation->form != ''
                    ? json_decode(utf8_decode($reservation->form))
                    : array();

            foreach ($form as $item){
                if ($item->is_email == 'false'){
                    $key = 'form_'.$item->id;
                    $data[$key] = $item->value;
                    $labels[$key]->usage++;
                }
            }

            /*
             * Set billing address.
             */
            $fields = array('first_name',
                            'last_name',
                            'company',
                            'email',
                            'phone',
                            'country',
                            'address_first',
                            'address_second',
                            'city',
                            'state',
                            'zip_code');

            if ($reservation->address_billing != ''){
                $address = json_decode(utf8_decode($reservation->address_billing));

                foreach ($fields as $field){
                    if ($address->{$field} != ''){
                        $key = 'ba_'.$field;
                        $data[$key] = $address->{$field};
                        $labels[$key]->usage++;
                    }
                }
            }

            /*
             * Set shipping address.
             */
            if ($reservation->address_shipping != ''
                    && $reservation->address_shipping != 'billing_address'){
                $address = json_decode(utf8_decode($reservation->address_shipping));

                foreach ($fields as $field){
                    if ($address->{$field} != ''){
                        $key = 'sa_'.$field;
                        $data[$key] = $address->{$field};
                        $labels[$key]->usage++;
                    }
                }
            }

            /*
             * Set reservation date created.
             */
            $data['date_created'] = str_replace(' ',
                                                ' | ',
                                                $reservation->date_created);
            $labels['date_created']->usage++;

            return $data;
        }

        /*
         * Get reservations XLS labels.
         *
         * @usage
         *      In FILE search for function call: $this->labels
         *      In FILE search for function call in hooks: array(&$this, 'labels')
         *      In PROJECT search for function call: $DOT->models->reservations_xls->labels
         *
         * @params
         *      labels (array): labels list
         *      reservations (array): reservations list
         *
         * @post
         *      -
         *
         * @get
         *      -
         *
         * @sessions
         *      -
         *
         * @cookies
         *      -
         *
         * @constants
         *      -
         *
         * @globals
         *      DOT (object): DOT framework main class variable
         *
         * @functions
         *      -
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      "labels" is updated by reference.
         *
         * @return_details
         *      -
         *
         * @dv
         *      -
         *
         * @tests
         *      -
         */
        /**
         * @param array $labels
         * @param array $reservations
         */
        function labels(&$labels,
                        $reservations){
            global $DOPBSP;

            /*
             * Set reservation ID.
             */
            $labels['id'] = new stdClass;
            $labels['id']->label = 'ID';
            $labels['id']->usage = 0;

            /*
             * Set reservation status.
             */
            $labels['status'] = new stdClass;
            $labels['status']->label = $DOPBSP->text('RESERVATIONS_RESERVATION_STATUS');
            $labels['status']->usage = 0;

            /*
             * Set reservation calendar ID.
             */
            $labels['calendar_id'] = new stdClass;
            $labels['calendar_id']->label = $DOPBSP->text('RESERVATIONS_RESERVATION_CALENDAR_ID');
            $labels['calendar_id']->usage = 0;

            /*
             * Set reservation calendar name.
             */
            $labels['calendar_name'] = new stdClass;
            $labels['calendar_name']->label = $DOPBSP->text('RESERVATIONS_RESERVATION_CALENDAR_NAME');
            $labels['calendar_name']->usage = 0;

            /*
             * Set reservation check in.
             */
            $labels['check_in'] = new stdClass;
            $labels['check_in']->label = $DOPBSP->text('SEARCH_FRONT_END_CHECK_IN');
            $labels['check_in']->usage = 0;

            /*
             * Set reservation check out.
             */
            $labels['check_out'] = new stdClass;
            $labels['check_out']->label = $DOPBSP->text('SEARCH_FRONT_END_CHECK_OUT');
            $labels['check_out']->usage = 0;

            /*
             * Set reservation start hour.
             */
            $labels['start_hour'] = new stdClass;
            $labels['start_hour']->label = $DOPBSP->text('SEARCH_FRONT_END_START_HOUR');
            $labels['start_hour']->usage = 0;

            /*
             * Set reservation end hour.
             */
            $labels['end_hour'] = new stdClass;
            $labels['end_hour']->label = $DOPBSP->text('SEARCH_FRONT_END_END_HOUR');
            $labels['end_hour']->usage = 0;

            /*
             * Set reservation no items.
             */
            $labels['no_items'] = new stdClass;
            $labels['no_items']->label = $DOPBSP->text('SEARCH_FRONT_END_NO_ITEMS');
            $labels['no_items']->usage = 0;

            /*
             * Set reservation currency.
             */
            $labels['currency_code'] = new stdClass;
            $labels['currency_code']->label = $DOPBSP->text('SETTINGS_CALENDAR_CURRENCY');
            $labels['currency_code']->usage = 0;

            /*
             * Set reservation price.
             */
            $labels['price'] = new stdClass;
            $labels['price']->label = $DOPBSP->text('RESERVATIONS_RESERVATION_FRONT_END_PRICE');
            $labels['price']->usage = 0;

            /*
             * Set reservation total price.
             */
            $labels['price_total'] = new stdClass;
            $labels['price_total']->label = $DOPBSP->text('RESERVATIONS_RESERVATION_FRONT_END_TOTAL_PRICE');
            $labels['price_total']->usage = 0;

            /*
             * Set reservation deposit.
             */
            $labels['deposit_price'] = new stdClass;
            $labels['deposit_price']->label = $DOPBSP->text('RESERVATIONS_RESERVATION_FRONT_END_DEPOSIT');
            $labels['deposit_price']->usage = 0;

            /*
             * Set reservation deposit left.
             */
            $labels['deposit_price_left'] = new stdClass;
            $labels['deposit_price_left']->label = $DOPBSP->text('RESERVATIONS_RESERVATION_FRONT_END_DEPOSIT_LEFT');
            $labels['deposit_price_left']->usage = 0;

            /*
             * Set reservation payment method.
             */
            $labels['payment_method'] = new stdClass;
            $labels['payment_method']->label = $DOPBSP->text('ORDER_PAYMENT_METHOD');
            $labels['payment_method']->usage = 0;

            /*
             * Set reservation transaction ID.
             */
            $labels['transaction_id'] = new stdClass;
            $labels['transaction_id']->label = $DOPBSP->text('ORDER_PAYMENT_METHOD_TRANSACTION_ID');
            $labels['transaction_id']->usage = 0;

            /*
             * Set reservation extras.
             */
            foreach ($reservations as $reservation){
                $extras = $reservation->extras != ''
                        ? json_decode(utf8_decode($reservation->extras))
                        : array();

                foreach ($extras as $item){
                    $key = 'extra_'.$item->group_id;
                    $labels[$key] = new stdClass;
                    $labels[$key]->label = $item->group_translation;
                    $labels[$key]->usage = 0;
                }
            }

            $labels['extras_price'] = new stdClass;
            $labels['extras_price']->label = $DOPBSP->text('EXTRAS_FRONT_END_TITLE').' ('.strtolower($DOPBSP->text('RESERVATIONS_RESERVATION_PAYMENT_PRICE_CHANGE')).')';
            $labels['extras_price']->usage = 0;

            /*
             * Set reservation discount.
             */
            $labels['discount'] = new stdClass;
            $labels['discount']->label = $DOPBSP->text('DISCOUNTS_FRONT_END_TITLE');
            $labels['discount']->usage = 0;

            $labels['discount_price'] = new stdClass;
            $labels['discount_price']->label = $DOPBSP->text('DISCOUNTS_FRONT_END_TITLE').' ('.strtolower($DOPBSP->text('RESERVATIONS_RESERVATION_PAYMENT_PRICE_CHANGE')).')';
            $labels['discount_price']->usage = 0;

            /*
             * Set reservation fees.
             */
            foreach ($reservations as $reservation){
                $fees = $reservation->fees != ''
                        ? json_decode(utf8_decode($reservation->fees))
                        : array();

                foreach ($fees as $item){
                    $key = 'fee_'.$item->id;
                    $labels[$key] = new stdClass;
                    $labels[$key]->label = $item->translation;
                    $labels[$key]->usage = 0;
                }
            }

            $labels['fees_price'] = new stdClass;
            $labels['fees_price']->label = $DOPBSP->text('FEES_FRONT_END_TITLE').' ('.strtolower($DOPBSP->text('RESERVATIONS_RESERVATION_PAYMENT_PRICE_CHANGE')).')';
            $labels['fees_price']->usage = 0;

            /*
             * Set reservation coupon.
             */
            $labels['coupon'] = new stdClass;
            $labels['coupon']->label = $DOPBSP->text('COUPONS_FRONT_END_TITLE');
            $labels['coupon']->usage = 0;

            $labels['coupon_price'] = new stdClass;
            $labels['coupon_price']->label = $DOPBSP->text('COUPONS_FRONT_END_TITLE').' ('.strtolower($DOPBSP->text('RESERVATIONS_RESERVATION_PAYMENT_PRICE_CHANGE')).')';
            $labels['coupon_price']->usage = 0;

            /*
             * Set reservation email & form.
             */
            $labels['email'] = new stdClass;
            $labels['email']->label = $DOPBSP->text('ORDER_ADDRESS_EMAIL');
            $labels['email']->usage = 0;

            foreach ($reservations as $reservation){
                $form = $reservation->form != ''
                        ? json_decode(utf8_decode($reservation->form))
                        : array();

                foreach ($form as $item){
                    if ($item->is_email == 'false'){
                        $key = 'form_'.$item->id;
                        $labels[$key] = new stdClass;
                        $labels[$key]->label = $item->translation;
                        $labels[$key]->usage = 0;
                    }
                }
            }

            /*
             * Set billing address.
             */
            $fields = array('first_name',
                            'last_name',
                            'company',
                            'email',
                            'phone',
                            'country',
                            'address_first',
                            'address_second',
                            'city',
                            'state',
                            'zip_code');

            foreach ($fields as $field){
                $key = 'ba_'.$field;
                $labels[$key] = new stdClass;
                $labels[$key]->label = $DOPBSP->text('ORDER_ADDRESS_'.strtoupper($field));
                $labels[$key]->usage = 0;
            }

            /*
             * Set shipping address.
             */
            foreach ($fields as $field){
                $key = 'sa_'.$field;
                $labels[$key] = new stdClass;
                $labels[$key]->label = $DOPBSP->text('ORDER_ADDRESS_'.strtoupper($field));
                $labels[$key]->usage = 0;
            }

            /*
             * Set reservation date created.
             */
            $labels['date_created'] = new stdClass;
            $labels['date_created']->label = $DOPBSP->text('RESERVATIONS_RESERVATION_DATE_CREATED');
            $labels['date_created']->usage = 0;
        }

        /*
         * Get reservations XLS.
         *
         * @usage
         *      In FILE search for function call: $this->get
         *      In FILE search for function call in hooks: array(&$this, 'get')
         *      In PROJECT search for function call: $DOT->models->reservations_xls->get
         *
         * @params
         *      reservations (array): reservations list
         *
         * @post
         *      -
         *
         * @get
         *      -
         *
         * @sessions
         *      -
         *
         * @cookies
         *      -
         *
         * @constants
         *      -
         *
         * @globals
         *      DOT (object): DOT framework main class variable
         *
         * @functions
         *      application/models/xls/model-xls.php : get() // Get XLS.
         *
         *      this : data() // Get reservation XLS data.
         *      this : labels() // Get reservations XLS labels.
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      Reservations XLS content.
         *
         * @return_details
         *      -
         *
         * @dv
         *      -
         *
         * @tests
         *      -
         */
        /**
         * @param array $reservations
         */
        function get($reservations){
            global $DOT;

            $labels = array();
            $data = array();

            /*
             * Set labels.
             */
            $this->labels($labels,
                          $reservations);

            /*
             * Set data from reservations.
             */
            foreach ($reservations as $reservation){
                array_push($data,
                           $this->data($labels,
                                       $reservation));
            }

            /*
             * Get iCal.
             */
            echo $DOT->models->xls->get($labels,
                                        $data);

            exit;
        }

        /*
         * Format price type value.
         *
         * @usage
         *      In FILE search for function call: $this->price
         *      In FILE search for function call in hooks: array(&$this, 'price')
         *      In PROJECT search for function call: $DOT->models->reservations_xls->price
         *
         * @params
         *      value (float): price value
         *
         * @post
         *      -
         *
         * @get
         *      -
         *
         * @sessions
         *      -
         *
         * @cookies
         *      -
         *
         * @constants
         *      -
         *
         * @globals
         *      -
         *
         * @functions
         *      -
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      Formatted price.
         *
         * @return_details
         *      -
         *
         * @dv
         *      -
         *
         * @tests
         *      -
         */
        /**
         * @param float $value
         *
         * @return string
         */
        function price($value){
            $value = (string)round($value,
                                   2);

            if (strpos($value,
                       '.') !== false){
                $value_pieces = explode('.',
                                        $value);
                $price = $value_pieces[0]
                        .','
                        .((int)$value_pieces[1]<10
                                ? $value_pieces[1].'0'
                                : $value_pieces[1]);
            }
            else{
                $price = $value.',00';
            }

            return $price;
        }
    }
}