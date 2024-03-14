<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once SCC_DIR . '/lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * *Handles all ajaxes request
 * ?all ajax request must be coded here
 * Testing by Mike second version.
 */
class ajaxRequest {

    public function __construct() {
        if ( current_user_can( 'manage_options' ) ) {
            add_action( 'wp_ajax_sccCalculatorOp', [ $this, 'scc_calculator_op' ] );
            /*
             * *Handles restore backup from json file
             * !each time is added a column o modified a table, must be modified here as well
             */
            //load examples
            add_action( 'wp_ajax_sscLoadExample', [ $this, 'ssc_loadExample' ] );
            //ajax of elements
            add_action( 'wp_ajax_sccAddCheckboxItems', [ $this, 'scc_addCheckboxItems' ] );
            add_action( 'wp_ajax_sccAddElementCheckbox', [ $this, 'scc_addElementCheckbox' ] );
            add_action( 'wp_ajax_sccAddElementCommentBox', [ $this, 'scc_addElementCommentBox' ] );
            add_action( 'wp_ajax_sccAddElementQuantityBox', [ $this, 'scc_addElementQuantityBox' ] );
            add_action( 'wp_ajax_sccAddElementFileUpload', [ $this, 'scc_addElementFileUpload' ] );

            add_action( 'wp_ajax_sccAddElementTextHtml', [ $this, 'scc_addElementTextHtml' ] );
            add_action( 'wp_ajax_sccAddElementSlider', [ $this, 'scc_addElementSlider' ] );
            add_action( 'wp_ajax_sccSaveSection', [ $this, 'scc_saveSection' ] );
            add_action( 'wp_ajax_sccDelSection', [ $this, 'scc_delSection' ] );
            add_action( 'wp_ajax_sccUpSection', [ $this, 'scc_upSection' ] );
            add_action( 'wp_ajax_sccDelSubsection', [ $this, 'scc_delSubsection' ] );
            add_action( 'wp_ajax_sccAddSubsection', [ $this, 'scc_addSubsection' ] );
            add_action( 'wp_ajax_sccDelElement', [ $this, 'scc_delElement' ] );
            add_action( 'wp_ajax_sccDelElementItem', [ $this, 'scc_delElementItem' ] );
            add_action( 'wp_ajax_sccAddElementSwichoption', [ $this, 'scc_addElementSwichoption' ] );
            add_action( 'wp_ajax_sccUpElement', [ $this, 'scc_upElement' ] );
            add_action( 'wp_ajax_sccUpElementOrder', [ $this, 'sccUpElementOrder' ] );
            add_action( 'wp_ajax_sccUpElementItemSwichoption', [ $this, 'scc_upElementItemSwichoption' ] );
            add_action( 'wp_ajax_sccAddElementDropdownMenu', [ $this, 'scc_addsElementDropdownMenu' ] );
            add_action( 'wp_ajax_sccUpElementItemSlider', [ $this, 'scc_upElementItemSlider' ] );
            add_action( 'wp_ajax_sccAddElementItemSlider', [ $this, 'scc_addElementItemSlider' ] );
            //saves setting and translations of calculator
            add_action( 'wp_ajax_sccSaveForm', [ $this, 'scc_saveFormNameSettings' ] );
            //shows shortcode in backend
            add_action( 'wp_ajax_sccPreviewOneForm', [ $this, 'scc_previewOneForm' ] );
            //duplicate element function
            add_action( 'wp_ajax_sccDuplicateElement', [ $this, 'scc_duplicateElement' ] );
            add_action( 'wp_ajax_sccGlobalSettings', [ $this, 'scc_globalSettings' ] );
            // migration ajax automatic
            // add_action('wp_ajax_sccMigrateAuto', array($this, 'scc_migrateAuto'));
            add_action( 'wp_ajax_sccMigrateAuto2', [ $this, 'scc_migrateAuto2' ] );
            // migration ajax automatic
            // add_action('wp_ajax_sccMigrateManual', array($this, 'scc_migrateManual'));
            // update section order
            add_action( 'wp_ajax_sccUpdateSectionOrder', [ $this, 'scc_updateSectionOrder' ] );

            add_action( 'wp_ajax_sccPDFSettings', [ $this, 'sccPDFSettings' ] );
            add_action( 'wp_ajax_scc_feedback_manage', [ $this, 'sccFeedbackManage' ] );
            add_action( 'wp_ajax_scc_get_debug_items', [ $this, 'get_debug_items' ] );
            add_action( 'wp_ajax_scc_update_slider_ranges', [ $this, 'update_slider_ranges' ] );
            add_action( 'wp_ajax_scc_send_wizard_quiz_data', [ $this, 'scc_send_wizard_quiz_data' ] );
            add_action( 'wp_ajax_scc_set_telemetry_state', [ $this, 'scc_set_telemetry_state' ] );
            add_action( 'wp_ajax_df_scc_uninstall_survey', [ $this, 'submit_uninstall_survey' ] );
        }

        // public ajax calls
        add_action( 'wp_ajax_nopriv_sccUpdateUrlStats', [ $this, 'sccUpdateUrlStats' ] );
        add_action( 'wp_ajax_sccUpdateUrlStats', [ $this, 'sccUpdateUrlStats' ] );
    }

    public function sccUpElementOrder() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        $e    = new elementController();
        $data = sanitize_text_or_array_field( $_GET['arr'] );

        foreach ( $data as $v ) {
            $el['id']            = intval( $v['id_element'] );
            $el['orden']         = intval( $v['order'] );
            $el['subsection_id'] = intval( $v['subsection'] );
            $e->update( $el );
        }
        wp_send_json( [ 'passed' => true ] );
    }

    /**
     * *Handles migration of coupons
     * ?is used in manual and auto updates
     *
     * @param $migration migration controller instance
     */
    public static function migrate_coupon( $migration ) {
        require_once __DIR__ . '/admin/controllers/couponController.php';
        $cc  = new \couponController();
        $ccc = $migration->getAllOldCoupons();

        foreach ( $ccc as $c ) {
            $cc->create( (array) $c );
        }
    }
    /**
     * *Handles migration of global settings
     * ?is use in manual and auto updates
     *
     * @param $migration migration controller instance
     */
    public function scc_migrateAuto2() {
        global $wpdb;
        $wpdb->query( "DELETE FROM {$wpdb->prefix}df_scc_forms" );
        self::scc_migrateAuto();
    }
    public static function migrate_global( $migration ) {
        $migration::update_wpOptions();
    }
    public static function scc_migrateAuto() {
        require_once __DIR__ . '/admin/controllers/migrateController.php';
        require_once __DIR__ . '/admin/controllers/formController.php';
        require_once __DIR__ . '/admin/controllers/conditionController.php';
        require_once __DIR__ . '/admin/controllers/sectionController.php';
        require_once __DIR__ . '/admin/controllers/subsectionController.php';
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        require_once __DIR__ . '/admin/controllers/quoteSubmissionsController.php';
        $calculatorC  = new formController();
        $conditionalC = new conditionController();
        $sectionC     = new sectionController();
        $subsectionC  = new subsectionController();
        $elementC     = new elementController();
        $elementitemC = new elementitemController();
        $quoteC       = new quoteSubmissionsController();
        $m            = new migrateController();
        $cals         = $m->getAllOldCalulator();

        foreach ( $cals as $c ) {
            $json_data_ = $m->getCalculatorData( $c->id );
            self::migrate_to( $json_data_, $calculatorC, $conditionalC, $sectionC, $subsectionC, $elementC, $elementitemC, true, $quoteC, false );
        }
        self::migrate_coupon( $m );
        // self::migrate_global($m);
        wp_send_json( [ 'passed' => true ] );
        die();
    }
    /**
     * *Handles migration
     * ?used multiple times
     *
     * @param array $json_data    data of items
     * @param mixed $calculatorC  form Controller instance
     * @param mixed $sectionC     section Controller instance
     * @param mixed $subsectionC  subsection Controller instance
     * @param mixed $elementC     element Controller instance
     * @param mixed $elementitemC elementItem Controller instance
     * @param bool  $quote        send true or false to backup quotes
     * @param bool  $restore_     dont send the id if is restored json
     *
     * @return bool true to backup quotes
     *              ?for automatic backup send true for the $quotes param
     */
    public static function migrate_to( $json_data, $calculatorC, $conditionalC, $sectionC, $subsectionC, $elementC, $elementitemC, $quote, $quoteC, $restore_ ) {
        if ( $json_data['scc_form'] ) {
            $json_data['scc_form']                          = json_decode( json_encode( $json_data['scc_form'] ), true );
            $json_data['scc_form_parameters']               = json_decode( json_encode( $json_data['scc_form_parameters'] ), true );
            $json_data['scc_form_parameters']['parameters'] = json_decode( json_encode( $json_data['scc_form_parameters']['parameters'] ), true );

            if ( ! $restore_ ) {
                $c['id'] = $json_data['scc_form']['id'];
            }
            $paraa                   = $json_data['scc_form_parameters']['parameters'];
            $c['formname']           = sanitize_text_field( $json_data['scc_form']['formname'] );
            $c['fontType']           = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['fontType'] );
            $c['fontWeight']         = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['fontTypeVariant'] );
            $c['ServiceColorPicker'] = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['colorPicker'] );
            $c['ServicefontSize']    = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['servicepricefontsize'] );
            $c['objectColorPicker']  = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['objectColorPicker'] );
            $c['objectSize']         = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['objectServicepricefontsize'] );
            $c['titleFontType']      = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['titleFontType'] );
            $c['titleFontWeight']    = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['titleFontTypeVariant'] );
            $c['titleColorPicker']   = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['titleColorPicker'] );
            $c['titleFontSize']      = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['titleServicepricefontsize'] );
            $c['titleFontSize']      = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['titleServicepricefontsize'] );
            $c['barstyle']           = ( $paraa['objecttotalpricestyle'] !== 'scc_tp_style1' && $paraa['objecttotalpricestyle'] !== 'scc_tp_style2' &&
            $paraa['objecttotalpricestyle'] !== 'scc_tp_style3' && $paraa['objecttotalpricestyle'] !== 'scc_tp_style4' ) ? 'scc_tp_style1' : sanitize_text_field( $paraa['objecttotalpricestyle'] );
            $c['elementSkin']        = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['form_field_style'] );
            $c['symbol']             = sanitize_text_field( $json_data['scc_form_parameters']['parameters']['currency_style'] );

            if ( isset( $paraa['objectDisableQtyCol'] ) && $paraa['objectDisableQtyCol'] == 'turn_off_save_icon' ) {
                $c['turnoffQty'] = 'true';
            }
            //?Extra parameters
            if ( isset( $paraa['inheritFontType'] ) ) {
                $c['inheritFontType'] = sanitize_text_field( $paraa['inheritFontType'] );
            }

            if ( isset( $paraa['objectsendquote'] ) && $paraa['objectsendquote'] == 'turn_off_send_quote' ) {
                $c['turnoffemailquote'] = 'true';
            }

            if ( isset( $paraa['objectdetailedlist'] ) && $paraa['objectdetailedlist'] == 'turn_off_viewed_detailed_list' ) {
                $c['turnviewdetails'] = 'true';
            }

            if ( isset( $paraa['objectscccoupon'] ) && $paraa['objectscccoupon'] == 'turn_off_coupon' ) {
                $c['turnoffcoupon'] = 'true';
            }

            if ( isset( $paraa['objectscctotalprice'] ) && $paraa['objectscctotalprice'] == 'scc_turn_off_total_price_view' ) {
                $c['removeTotal'] = 'true';
            }

            if ( isset( $paraa['objectscctitlelabel'] ) && $paraa['objectscctitlelabel'] == 'scc_remove_detailed_list_title' ) {
                $c['removeTitle'] = 'true';
            }

            if ( isset( $paraa['objectDisableUnitCol'] ) && $paraa['objectDisableUnitCol'] == 'turn_off_save_icon' ) {
                $c['turnoffUnit'] = 'true';
            }

            if ( isset( $paraa['objectsaveicon'] ) && $paraa['objectsaveicon'] == 'turn_off_save_icon' ) {
                $c['turnoffSave'] = 'true';
            }

            if ( isset( $paraa['objectscctax'] ) && $paraa['objectscctax'] == 'turn_off_tax' ) {
                $c['turnoffTax'] = 'true';
            }

            if ( isset( $paraa['woocommerce_checked'] ) ) {
                $c['isWoocommerceCheckoutEnabled'] = sanitize_text_field( $paraa['woocommerce_checked'] );
            }

            if ( isset( $paraa['isStripeEnabled'] ) ) {
                $c['isStripeEnabled'] = sanitize_text_field( $paraa['isStripeEnabled'] );
            }

            if ( isset( $paraa['isPayBtnHoverEffectEnabled'] ) ) {
                $c['turnoffborder'] = sanitize_text_field( $paraa['isPayBtnHoverEffectEnabled'] );
            }

            if ( isset( $paraa['formFieldsArray'] ) ) {
                $c['formFieldsArray'] = sanitize_text_field( $paraa['formFieldsArray'] );
            }
            $pp = $json_data['scc_form_parameters']['parameters'];

            if (
                isset( $pp['paypal_email'] ) && isset( $pp['paypal_shopping_cart_name'] ) && isset( $pp['paypal_checked'] )
                && isset( $pp['paypalSuccessURL'] ) && isset( $pp['paypalCancelURL'] ) && isset( $pp['objectTaxInclusionInPayPal'] )
                && isset( $pp['paypal_currency'] )
            ) {
                $payPalJson             = [
                    'paypal_email'               => sanitize_email( $json_data['scc_form_parameters']['parameters']['paypal_email'] ),
                    'paypal_shopping_cart_name'  => sanitize_text_field( $json_data['scc_form_parameters']['parameters']['paypal_shopping_cart_name'] ),
                    'paypal_checked'             => sanitize_text_field( $json_data['scc_form_parameters']['parameters']['paypal_checked'] ),
                    'paypalSuccessURL'           => sanitize_text_field( $json_data['scc_form_parameters']['parameters']['paypalSuccessURL'] ),
                    'paypalCancelURL'            => sanitize_text_field( $json_data['scc_form_parameters']['parameters']['paypalCancelURL'] ),
                    'objectTaxInclusionInPayPal' => sanitize_text_field( $json_data['scc_form_parameters']['parameters']['objectTaxInclusionInPayPal'] ),
                    'paypal_currency'            => sanitize_text_field( $json_data['scc_form_parameters']['parameters']['paypal_currency'] ),
                ];
                $c['paypalConfigArray'] = json_encode( $payPalJson );
            }
            //handles webhook migration TESTING
            if ( isset( $json_data['scc_form_parameters']['parameters']['webhookSettings'] ) ) {
                $c['webhookSettings'] = json_encode( json_decode( stripslashes( $json_data['scc_form_parameters']['parameters']['webhookSettings'] ) ) );
            }
            $c['translation'] = json_encode( $json_data['scc_form']['formtranslate'] );
            $id_c             = $calculatorC->create( $c );

            foreach ( $json_data['scc_form']['formstored'] as $section ) {
                $section           = json_decode( json_encode( $section ), true );
                $sc['name']        = sanitize_text_field( $section['name'] );
                $sc['description'] = sanitize_text_field( $section['desc'] );
                $sc['order']       = intval( $section['section'] );

                if ( isset( $section['accordion'] ) && $section['accordion'] == true ) {
                    $sc['accordion'] = 'true';
                }

                if ( isset( $section['showSectionTotal'] ) && $section['showSectionTotal'] == true ) {
                    $sc['showSectionTotal'] = 'true';
                }
                $sc['form_id'] = intval( $id_c );
                $id_sec        = $sectionC->create( $sc );

                foreach ( $section['value'] as $subsection ) {
                    $sb['order']      = intval( $subsection['subsection'] );
                    $sb['section_id'] = intval( $id_sec );
                    $id_sub           = $subsectionC->create( $sb );

                    if ( isset( $subsection['minmax'] ) && count( $subsection['minmax'] ) > 0 ) {
                        $e['type']          = 'slider';
                        $e['orden']         = count( $subsection['Nooptions'] ) + 1;
                        $e['titleElement']  = wp_kses( $subsection['minmax'][0]['title'], SCC_ALLOWTAGS );
                        $e['mandatory']     = sanitize_text_field( $subsection['minmax'][0]['mandatory'] );
                        $e['value2']        = intval( $subsection['step'] );
                        $e['value3']        = intval( $subsection['defaultValue'] );
                        $e['showPriceHint'] = sanitize_text_field( $subsection['showPriceHint'] );

                        if ( isset( $subsection['uniqueID'] ) ) {
                            $e['uniqueId'] = $subsection['uniqueID'] . $id_c;
                        }

                        if ( isset( $subsection['minmax'][0]['column_dskp'] ) ) {
                            $e['titleColumnDesktop'] = intval( $subsection['minmax'][0]['column_dskp'] );
                        }

                        if ( isset( $subsection['minmax'][0]['column_mobl'] ) ) {
                            $e['titleColumnMobile'] = intval( $subsection['minmax'][0]['column_mobl'] );
                        }
                        ( $subsection['isSlidingScale'] ) ? $e['value1'] = 'sliding' : $e['value1'] = 'bulk';

                        $e['subsection_id'] = $id_sub;
                        $id_eemin           = $elementC->create( $e );

                        foreach ( $subsection['minmax'] as $minmax ) {
                            $mi['orden']  = sanitize_text_field( $minmax['No'] );
                            $mi['value1'] = sanitize_text_field( $minmax['name'] );
                            $mi['value2'] = sanitize_text_field( $minmax['value1'] );
                            $mi['value3'] = sanitize_text_field( $minmax['value2'] );
                            //?not needed for slider
                            if ( isset( $minmax['scc_woo_commerce_product_id'] ) ) {
                                $e['woocomerce_product_id'] = intval( $minmax['scc_woo_commerce_product_id'] );
                            }
                            $mi['element_id'] = $id_eemin;
                            $elementitemC->create( $mi );
                        }
                    }

                    foreach ( $subsection['Nooptions'] as  $element ) {
                        //!add conditions
                        $el['orden'] = intval( $element['element'] );
                        switch ( $element['type'] ) {
                            case 'selectoption':
                                $el['type']                                                       = 'Dropdown Menu';
                                ( $element['value'][0]['mandatory'] == 'yes' ) ? $el['mandatory'] = '1' : $el['mandatory'] = '0';
                                break;

                            case 'switchoption':
                                $el['type']                                                       = 'checkbox';
                                $el['value1']                                                     = sanitize_text_field( $element['value'][0]['value2'] );
                                ( $element['value'][0]['mandatory'] == 'yes' ) ? $el['mandatory'] = '1' : $el['mandatory'] = '0';
                                break;

                            case 'comment_option':
                                $el['type']                                                       = 'comment box';
                                $el['value2']                                                     = sanitize_text_field( $element['value'][0]['value1'] );
                                $el['value3']                                                     = sanitize_text_field( $element['value'][0]['value2'] );
                                ( $element['value'][0]['mandatory'] == 'yes' ) ? $el['mandatory'] = '1' : $el['mandatory'] = '0';
                                break;

                            case 'number_option':
                                $el['type']   = 'quantity box';
                                $el['value2'] = sanitize_text_field( $element['value'][0]['value1'] );
                                $el['value1'] = sanitize_text_field( $element['value'][0]['inputBoxVariant'] );
                                break;

                            case 'fileupload_option':
                                $el['type']   = 'file upload';
                                $el['value2'] = sanitize_text_field( $element['value'][0]['value2'][0]['fileUploadPlaceholderText'] );
                                $el['value3'] = sanitize_text_field( $element['value'][0]['value2'][0]['fileUploadAllowedTypes'] );
                                $el['value4'] = sanitize_text_field( $element['value'][0]['value1'] );
                                break;

                            case 'custom_code':
                                $el['type']   = 'texthtml';
                                $el['value2'] = wp_kses( $element['value'][0]['name'], SCC_ALLOWTAGS );
                                break;

                            case 'scc_custom_math':
                                $el['type']              = 'custom math';
                                $el['value1']            = sanitize_text_field( $element['value'][0]['name'] );
                                $el['value2']            = sanitize_text_field( $element['value'][0]['value2'] );
                                $el['displayFrontend']   = '1';
                                $el['displayDetailList'] = '1';
                                break;

                            case "'scc_custom_math'":
                                $el['type']              = 'custom math';
                                $el['value1']            = sanitize_text_field( $element['value'][0]['name'] );
                                $el['value2']            = sanitize_text_field( $element['value'][0]['value2'] );
                                $el['displayFrontend']   = '1';
                                $el['displayDetailList'] = '1';
                                break;
                        }

                        if ( isset( $element['uniqueId'] ) ) {
                            $el['uniqueId'] = sanitize_text_field( $element['uniqueId'] ) . $id_c;
                        }
                        $el['orden']        = intval( $element['element'] );
                        $el['titleElement'] = wp_kses( $element['value'][0]['title'], SCC_ALLOWTAGS );

                        if ( isset( $element['value'][0]['column_dskp'] ) ) {
                            $el['titleColumnDesktop'] = sanitize_text_field( $element['value'][0]['column_dskp'] );
                        }

                        if ( isset( $element['value'][0]['column_mobl'] ) ) {
                            $el['titleColumnMobile'] = sanitize_text_field( $element['value'][0]['column_mobl'] );
                        }
                        $el['subsection_id'] = $id_sub;
                        $ell_id              = $elementC->create( $el );

                        foreach ( $element['value'] as $items ) {
                            if ( $element['type'] === 'selectoption' ) {
                                if ( isset( $items['uniqueId'] ) ) {
                                    $eli['uniqueId'] = sanitize_text_field( $items['uniqueId'] ) . $id_c;
                                }
                                $eli['order']                                             = intval( $items['No'] );
                                $eli['name']                                              = sanitize_text_field( $items['name'] );
                                $eli['price']                                             = sanitize_text_field( $items['value1'] );
                                $eli['description']                                       = sanitize_text_field( $items['value2'] );
                                ( $items['opt_default'] == 'true' ) ? $eli['opt_default'] = '1' : $eli['opt_default'] = '0';

                                if ( isset( $items['dropdownLogo'] ) ) {
                                    $eli['value1'] = urldecode( $items['dropdownLogo'] );
                                }
                                $eli['element_id'] = $ell_id;
                                $elementitemC->create( $eli );
                            }

                            if ( $element['type'] === 'switchoption' ) {
                                if ( isset( $items['uniqueId'] ) ) {
                                    $eli['uniqueId'] = sanitize_text_field( $items['uniqueId'] ) . $id_c;
                                }
                                $eli['order']                                             = intval( $items['No'] );
                                $eli['name']                                              = sanitize_text_field( $items['name'] );
                                $eli['price']                                             = sanitize_text_field( $items['value1'] );
                                ( $items['opt_default'] == 'true' ) ? $eli['opt_default'] = '1' : $eli['opt_default'] = '0';
                                $eli['element_id']                                        = $ell_id;
                                $elementitemC->create( $eli );
                            }
                        }
                    }
                }
            }
            //!Recently done, must be tested
            if ( isset( $json_data['scc_form_parameters']['parameters']['conditionObject'] ) ) {
                $conditonObj = json_decode( stripslashes( stripslashes( $json_data['scc_form_parameters']['parameters']['conditionObject'] ) ), true );
                $conditonals = [];

                foreach ( $conditonObj as $key => $con ) {
                    $result = $elementC->getByUniqueId( $key . $id_c );

                    foreach ( $con[0] as $key22 => $ccc ) {
                        $cond = [];
                        //insert in element
                        $cond['element_id'] = $result->id;
                        $cond['op']         = sanitize_text_field( $ccc['op'] );

                        if ( $cond['op'] == 'gt' ) {
                            $cond['op'] = 'gr';
                        }

                        if ( $ccc['val'] != 'unset' ) {
                            $cond['value'] = sanitize_text_field( $ccc['val'] );
                        }

                        if ( $ccc['val'] == 'true' ) {
                            $cond['value'] = 'chec';
                        }

                        if ( $ccc['val'] == 'false' ) {
                            $cond['value'] = 'unc';
                        }
                        $resulte = $elementC->getByUniqueId( $key22 . $id_c );

                        if ( $resulte ) {
                            $cond['condition_element_id'] = $resulte->id;
                            //if dropdown search with order and insert in elementitem :/
                            if ( $resulte->type == 'Dropdown Menu' ) {
                                $rrr = $elementitemC->readOfElement( $resulte->id );
                                unset( $cond['value'] );
                                $pppp = '';

                                foreach ( $rrr as $ii ) {
                                    if ( $ii->order == intval( $ccc['val'] ) - 1 ) {
                                        $pppp = $ii->id;
                                    }
                                }

                                if ( $pppp != '' ) {
                                    $cond['elementitem_id'] = $pppp;
                                }
                            }
                        }
                        $resultei = $elementitemC->getByUniqueId( $key22 . $id_c );

                        if ( $resultei ) {
                            $cond['elementitem_id'] = $resultei->id;
                        }
                        array_push( $conditonals, $cond );
                    }
                }

                foreach ( $conditonals as $i ) {
                    $conditionalC->create( (array) $i );
                }
            }

            if ( $quote ) {
                foreach ( $json_data['scc_form']['quotes'] as $q ) {
                    $q['calc_id'] = $id_c;
                    $quoteC->create( (array) $q );
                }
            }

            return true;
        } else {
            return false;
        }
    }
    public function scc_globalSettings() {
        check_ajax_referer( 'global-settings-page', 'nonce' );
        $currency = sanitize_text_field( $_GET['currency'] );
        update_option( 'df_scc_currency', $currency );
        $format = sanitize_text_field( $_GET['format'] );
        update_option( 'df_scc_currency_style', $format );
        $mode_convertion = sanitize_text_field( $_GET['mode'] );
        update_option( 'df_scc_currency_coversion_mode', 'off' );

        echo json_encode( [ 'passed' => true ] );
        die();
    }
    public function scc_duplicateElement() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        require_once __DIR__ . '/admin/controllers/conditionController.php';
        $e          = new elementController();
        $ei         = new elementitemController();
        $con        = new conditionController();
        $id_element = intval( $_GET['id_element'] );
        $el         = $e->read( $id_element );

        if ( $el->type == 'slider' ) {
            wp_send_json(
                [
                    'passed' => false,
                    'error'  => 'You can only have one slider per subsection',
                ]
            );

            return;
        }
        $iserted = $e->create( (array) $el );
        $items   = $ei->readOfElement( $id_element );
        $condts  = $con->readOfElement( $id_element );
        $idsEl   = [];

        foreach ( $items as $i ) {
            $i->element_id = $iserted;
            $idItemsResult = $ei->create( (array) $i );
            array_push( $idsEl, $idItemsResult );
        }
        $idCon = [];

        foreach ( $condts as $c ) {
            $c->element_id = $iserted;
            $idContiResult = $con->create( (array) $c );
            array_push( $idCon, $idContiResult );
        }
        wp_send_json(
            [
                'passed' => true,
                'id'     => $iserted,
                'ids'    => $idsEl,
                'ids_c'  => $idCon,
            ]
        );
        die();
    }

    private function find_quiz_choice_by_key( $choice_key ) {
        $choice_config = array_merge( DF_SCC_QUIZ_CHOICES['step2'], DF_SCC_QUIZ_CHOICES['step3'], DF_SCC_QUIZ_CHOICES['step4'], DF_SCC_QUIZ_CHOICES['step5'] );
        // filter the choice config array to find the choice with the given key
        $choice_config = array_filter(
            $choice_config,
            function ( $choice ) use ( $choice_key ) {
                return $choice['key'] === $choice_key;
            }
        );

        return array_shift( $choice_config );
    }

    private function email_choice_suggestion_card( $content_cofig ) {
        $content    = '';
        $loop_index = 0;

        foreach ( $content_cofig as $choice_key => $card_value ) {
            $choice_config                   = $this->find_quiz_choice_by_key( $choice_key );
            $card_title                      = $choice_config['choiceTitle'];
            $choice_logo                     = SCC_ASSETS_URL . '//email-images//' . $choice_config['icon'] . '.png';
            $choice_suggestion_padding_style = $loop_index === 0 ? 'font-size:0px;padding:10px 40px 10px 40px;word-break:break-word;' : 'font-size:0px;padding:0px 40px;word-break:break-word;';
            $content .= '<tr>
                <td align="left" style="' . $choice_suggestion_padding_style . '">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                    <tr>
                        <td align="left" style="/* background-color: #fafafa; */font-size:0px;/* padding:10px 40px; */word-break:break-word;">
                            <div style="font-family:Nunito,BlinkMacSystemFont,-apple-system,Arial,sans-serif;font-size:15px;line-height:1;text-align:left;color:#616161;padding: 10px 8px 10px 10px;background-color: #fafafa;">For ' . $card_title . '</div>
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" width="100%" border="0" style="background-color: #fafafa;color:#000000;font-family:Nunito,BlinkMacSystemFont,-apple-system,Arial,sans-serif;font-size:13px;line-height:22px;table-layout:auto;width:100%;border:none;padding-bottom:7px;">
                    <tr>
                    <td style="width: 15%;padding-left: 12px;"><img style="background-color: #f2f3fd;padding: 15px;height: 24px;width: 24px;" src="' . $choice_logo . '" alt="' . $card_title . '" width="20px" /></td>
                    <td>
                        ' . $card_value . '
                    </td>
                    </tr>
                </table>
                </td>
            </tr>
            <tr>
                <td style="font-size:0px;word-break:break-word;">
                <div style="height:10px;line-height:10px;">&#8202;</div>
                </td>
            </tr>';
            $loop_index++;
        }

        return $content;
    }

    private function email_suggestion_template_builder( $content_config ) {
        $content = '<!-- section start -->
        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
        <div style="margin:0px auto;max-width:600px;">
          <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
            <tbody>
              <tr>
                <td style="direction:ltr;font-size:0px;padding:0;text-align:center;">
                  <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><![endif]-->
                  <!-- col start -->
                  <!--[if mso | IE]><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
                  <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                      <tbody>';

        foreach ( $content_config as $title => $fragment ) {
            if ( $title !== 'Your Pricing Structure' ) {
                $content .= '<tr>
                <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                  <p style="border-top:dashed 1px lightgrey;font-size:1px;margin:0px auto;width:100%;">
                  </p>
                  <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:dashed 1px lightgrey;font-size:1px;margin:0px auto;width:550px;" role="presentation" width="550px" ><tr><td style="height:0;line-height:0;"> &nbsp;
                </td></tr></table><![endif]-->
                </td>
              </tr>';
            }
            $content .= '<tr>
                              <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                <div style="font-family:Nunito,BlinkMacSystemFont,-apple-system,Arial,sans-serif;font-size:26px;font-weight:bold;line-height:1;text-align:left;color:#000000;">' . $title . '</div>
                              </td>
                            </tr>
                            ' . $this->email_choice_suggestion_card( $fragment );
        }

        $content .= '</tbody>
                </table>
            </div>
            <!--[if mso | IE]></td><![endif]-->
            <!-- col end -->
            <!--[if mso | IE]></tr></table><![endif]-->
            </td>
        </tr>
        </tbody>
        </table>
        </div>
        <!--[if mso | IE]></td></tr></table><![endif]-->
        <!-- sec end -->';

        return $content;
    }

    private function apply_wizard_result_features( $features_suggested, $calc_id, $formC ) {
        foreach ( $features_suggested as $key => $value ) {
            if ( 'use-live-currency-conversion' === $key ) {
                $live_currency_conversion_mode = get_option( 'df_scc_currency_coversion_mode', 'off' );

                if ( 'off' == $live_currency_conversion_mode ) {
                    update_option( 'df_scc_currency_coversion_mode', 'auto_detect' );
                }
            }

            if ( 'turn-off-detailed-list' === $key ) {
                // disabling the 'View Detailed List' button
                $formC->update( [ 'turnviewdetails' => 'true', 'id' => $calc_id ] );
            }
        }

        return $features_suggested;
    }

    private function generate_tutorial_email_from_wizard( $args ) {
        $results_email_form_data = $args['resultsEmailFormData'];
        $to                      = $results_email_form_data['email'];

        // Email Subject
        $subject = 'Your Tailored Setup Guide - Curated Just for You!';

        $message_pricing_structure_fragment = [];
        $message_use_cases_fragment         = [];
        $message_unique_needs_fragment      = [];

        $all_suggestions_by_choices          = array_merge( $args['featuresByChoice'], $args['elementsByChoice'] );
        $unique_needs_choice_suggestion      = [];
        $use_cases_choice_suggestion         = [];
        $pricing_structure_choice_suggestion = [];

        foreach ( $args['Unique Needs'] as $suggestion ) {
            $suggestion = sanitize_text_field( $suggestion );

            if ( empty( $suggestion ) ) {
                continue;
            }

            // find the $suggestion in the values of $all_suggestions_by_choices and return the key
            $choice = array_filter( $all_suggestions_by_choices, function ( $suggestions, $key ) use ( $suggestion ) {
                return in_array( $suggestion, $suggestions );
            }, ARRAY_FILTER_USE_BOTH );

            $choice_key = ( isset( $choice ) && ! empty( $choice ) ) ? array_keys( $choice )[0] : '';

            if ( empty( $choice_key ) ) {
                continue;
            }
            // only add the suggestion if not already added
            if ( ! isset( $unique_needs_choice_suggestion[ $choice_key ] ) ) {
                $unique_needs_choice_suggestion[ $choice_key ] = [];
            }

            if ( ! in_array( $suggestion, $unique_needs_choice_suggestion[ $choice_key ] ) ) {
                $unique_needs_choice_suggestion[ $choice_key ][] = $suggestion;
            }
        }

        foreach ( $unique_needs_choice_suggestion as $choice => $suggestion ) {
            $message_unique_needs_fragment[$choice] = '<ul style="padding-left: 20px; margin: 0;">';

            foreach ( $suggestion as $s ) {
                $suggestion_details = df_scc_find_suggested_feature_helplink( $s );

                if ( empty( $suggestion_details ) ) {
                    // If it is not a feature, must be an element
                    $suggestion_details = df_scc_find_suggested_element_helplink( $s );
                }
                $suggestion_nice_name           = ucwords( str_replace( '-', ' ', $s ) );
                $message_unique_needs_fragment[$choice] .= '<li><a style="text-decoration: none;" href="' . $suggestion_details['helpLink'] . '"><strong>' . $suggestion_details['choiceTitle'] . '</strong></a></li>';
            }
            $message_unique_needs_fragment[$choice] .= '</ul>';
        }

        foreach ( $args['Use Cases'] as $suggestion ) {
            $suggestion = sanitize_text_field( $suggestion );

            if ( empty( $suggestion ) ) {
                continue;
            }

            // find the $suggestion in the values of $all_suggestions_by_choices and return the key
            $choice = array_filter( $all_suggestions_by_choices, function ( $suggestions, $key ) use ( $suggestion ) {
                return in_array( $suggestion, $suggestions );
            }, ARRAY_FILTER_USE_BOTH );

            $choice_key = ( isset( $choice ) && ! empty( $choice ) ) ? array_keys( $choice )[0] : '';

            if ( empty( $choice_key ) ) {
                continue;
            }
            // only add the suggestion if not already added
            if ( ! isset( $use_cases_choice_suggestion[ $choice_key ] ) ) {
                $use_cases_choice_suggestion[ $choice_key ] = [];
            }

            if ( ! in_array( $suggestion, $use_cases_choice_suggestion[ $choice_key ] ) ) {
                $use_cases_choice_suggestion[ $choice_key ][] = $suggestion;
            }
        }

        foreach ( $use_cases_choice_suggestion as $choice => $suggestion ) {
            $message_use_cases_fragment[$choice] = '<ul style="padding-left: 20px; margin: 0;">';

            foreach ( $suggestion as $s ) {
                $suggestion_details = df_scc_find_suggested_feature_helplink( $s );

                if ( empty( $suggestion_details ) ) {
                    // If it is not a feature, must be an element
                    $suggestion_details = df_scc_find_suggested_element_helplink( $s );
                }
                $suggestion_nice_name           = ucwords( str_replace( '-', ' ', $s ) );
                $message_use_cases_fragment[$choice] .= '<li><a style="text-decoration: none;" href="' . $suggestion_details['helpLink'] . '"><strong>' . $suggestion_details['choiceTitle'] . '</strong></a></li>';
            }
            $message_use_cases_fragment[$choice] .= '</ul>';
        }

        foreach ( $args['Pricing Structure'] as $suggestion ) {
            $suggestion = sanitize_text_field( $suggestion );

            if ( empty( $suggestion ) ) {
                continue;
            }

            // find the $suggestion in the values of $all_suggestions_by_choices and return the key
            $choice = array_filter( $all_suggestions_by_choices, function ( $suggestions, $key ) use ( $suggestion ) {
                return in_array( $suggestion, $suggestions );
            }, ARRAY_FILTER_USE_BOTH );

            $choice_key = ( isset( $choice ) && ! empty( $choice ) ) ? array_keys( $choice )[0] : '';

            if ( empty( $choice_key ) ) {
                continue;
            }
            // only add the suggestion if not already added
            if ( ! isset( $pricing_structure_choice_suggestion[ $choice_key ] ) ) {
                $pricing_structure_choice_suggestion[ $choice_key ] = [];
            }

            if ( ! in_array( $suggestion, $pricing_structure_choice_suggestion[ $choice_key ] ) ) {
                $pricing_structure_choice_suggestion[ $choice_key ][] = $suggestion;
            }
        }

        foreach ( $pricing_structure_choice_suggestion as $choice => $suggestion ) {
            $message_pricing_structure_fragment[$choice] = '<ul style="padding-left: 20px; margin: 0;">';

            foreach ( $suggestion as $s ) {
                $suggestion_details = df_scc_find_suggested_feature_helplink( $s );

                if ( empty( $suggestion_details ) ) {
                    // If it is not a feature, must be an element
                    $suggestion_details = df_scc_find_suggested_element_helplink( $s );
                }
                $suggestion_nice_name           = ucwords( str_replace( '-', ' ', $s ) );
                $message_pricing_structure_fragment[$choice] .= '<li><a style="text-decoration: none;" href="' . $suggestion_details['helpLink'] . '"><strong>' . $suggestion_details['choiceTitle'] . '</strong></a></li>';
            }
            $message_pricing_structure_fragment[$choice] .= '</ul>';
        }

        // Preparing the body of the email

        $template_collection = [
            'Your Pricing Structure' => $message_pricing_structure_fragment,
            'Your Use Cases'         => $message_use_cases_fragment,
            'Your Unique Needs'      => $message_unique_needs_fragment,
        ];

        // remove from the template collection that has empty values
        $template_collection = array_filter( $template_collection, function ( $value ) {
            return ! empty( $value );
        } );

        $message_body = $this->email_suggestion_template_builder( $template_collection );

        $website_name = str_replace( [ 'http://', 'https://' ], '', get_site_url() );

        // Email Body
        $message = '<!doctype html>
        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
        
        <head>
          <title>
          </title>
          <!--[if !mso]><!-->
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <!--<![endif]-->
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <style type="text/css">
            #outlook a {
              padding: 0;
            }
        
            body {
              margin: 0;
              padding: 0;
              -webkit-text-size-adjust: 100%;
              -ms-text-size-adjust: 100%;
            }
        
            table,
            td {
              border-collapse: collapse;
              mso-table-lspace: 0pt;
              mso-table-rspace: 0pt;
            }
        
            img {
              border: 0;
              height: auto;
              line-height: 100%;
              outline: none;
              text-decoration: none;
              -ms-interpolation-mode: bicubic;
            }
        
            p {
              display: block;
              margin: 13px 0;
            }
          </style>
          <!--[if mso]>
                <noscript>
                <xml>
                <o:OfficeDocumentSettings>
                  <o:AllowPNG/>
                  <o:PixelsPerInch>96</o:PixelsPerInch>
                </o:OfficeDocumentSettings>
                </xml>
                </noscript>
                <![endif]-->
          <!--[if lte mso 11]>
                <style type="text/css">
                  .mj-outlook-group-fix { width:100% !important; }
                </style>
                <![endif]-->
          <!--[if !mso]><!-->
          <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,500,700" rel="stylesheet" type="text/css">
          <style type="text/css">
            @import url(https://fonts.googleapis.com/css?family=Nunito:300,400,500,700);
          </style>
          <!--<![endif]-->
          <style type="text/css">
            @media only screen and (min-width:480px) {
              .mj-column-per-100 {
                width: 100% !important;
                max-width: 100%;
              }
            }
          </style>
          <style media="screen and (min-width:480px)">
            .moz-text-html .mj-column-per-100 {
              width: 100% !important;
              max-width: 100%;
            }
          </style>
          <style type="text/css">
            @media only screen and (max-width:480px) {
              table.mj-full-width-mobile {
                width: 100% !important;
              }
        
              td.mj-full-width-mobile {
                width: auto !important;
              }
            }
          </style>
        </head>
        
        <body style="word-spacing:normal;">
        <div style="page-break-inside: avoid;">
            <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
            <div style="margin:0px auto;max-width:600px;">
              <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                <tbody>
                  <tr>
                    <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
                      <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
                      <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                          <tbody>
                            <tr>
                              <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                                  <tbody>
                                    <tr>
                                      <td style="width:200px;">
                                        <img height="auto" src="https://stylishcostcalculator.com/wp-content/uploads/2020/04/scc-logo209-721.png" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="200" />
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                <div style="font-family:Nunito,BlinkMacSystemFont,-apple-system,Arial,sans-serif;font-size:36px;line-height:1;text-align:left;color:#000000;">
                                    <p>Your Tailored Setup Guide - <b>Curated Just for You</b></p>
                                </div>
                                <div style="font-family:Nunito,BlinkMacSystemFont,-apple-system,Arial,sans-serif;font-size:20px;line-height:1;text-align:left;color:#000000;">
                                    <p>Finish Your Setup, Unleash Lead Generation!</p>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td align="left" style="font-size:0px;padding:25px 25px 0px 25px;word-break:break-word;">
                                <div style="font-family:Nunito,BlinkMacSystemFont,-apple-system,Arial,sans-serif;font-size:16px;line-height:1;text-align:left;color:#000000;">
                                    <p>Hi ' . ucwords( $results_email_form_data['name'] ) . ',</p>
                                    <p>Here are your customized setup instructions, specially for <strong>' . $website_name . '</strong>.</p>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td align="left" style="font-size:0px;padding:0px 25px 40px 25px;word-break:break-word;">
                                <div style="font-family:Nunito,BlinkMacSystemFont,-apple-system,Arial,sans-serif;font-size:16px;line-height:1;text-align:left;color:#000000;">Follow the steps in this email to complete your calculator form setup. Get ready to elevate user engagement and conversions.</div>
                              </td>
                            </tr>
                            <tr>
                                <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                    <p style="border-top:dashed 1px lightgrey;font-size:1px;margin:0px auto;width:100%;">
                                    </p>
                                    <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:dashed 1px lightgrey;font-size:1px;margin:0px auto;width:550px;" role="presentation" width="550px" ><tr><td style="height:0;line-height:0;"> &nbsp;
            </td></tr></table><![endif]-->
                                </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <!--[if mso | IE]></td></tr></table><![endif]-->
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!--[if mso | IE]></td></tr></table><![endif]-->
            ' . $message_body . '
          </div>
        </body>
        
        </html>';

        if ( $results_email_form_data['optin'] ) {
            // Set content-type for HTML email
            $headers[] = 'Content-Type: text/html; charset=UTF-8';

            $args['__quizAnswersStore']['quizChoicesAndSuggestions'] = [
                'features'          => $args['featuresByChoice'],
                'elements'          => $args['elementsByChoice'],
                'emailFormData'     => $results_email_form_data,
                'Unique Needs'      => $args['Unique Needs'],
                'Use Cases'         => $args['Use Cases'],
                'Pricing Structure' => $args['Pricing Structure'],
            ];

            $this->scc_send_wizard_quiz_data( $args['__quizAnswersStore'] );

            if ( ! $results_email_form_data['optin'] ) {
                return;
            }
        }else{
            //download PDF with tutorial
            return $this->generate_tutorial_pdf_from_wizard( $message, $subject );
        }
    }

    public function generate_tutorial_pdf_from_wizard( $data, $title ){
        $options = new Options();
        $options->set( 'defaultFont', 'freesans' );
        $font_directory       = SCC_DIR . '/lib/dompdf/vendor/dompdf/dompdf/lib/fonts/';
        $font_cache_directory = SCC_DIR . '/lib/dompdf/vendor/dompdf/dompdf/lib/fonts_cache/';
        //if cache directory does not exist, create it
        if ( ! is_dir( $font_cache_directory ) ) {
            if ( ! mkdir( $font_cache_directory, 0777, true ) ) {
                die( 'Could not create font cache' );
            }
        }
        $options->set( 'fontDir', $font_cache_directory );
        $options->set( 'fontCache', $font_directory );
        $options->set( 'isHtml5ParserEnabled', true );
        $options->set( 'isRemoteEnabled', true );
        $dompdf = new Dompdf( $options );
        $dompdf->setPaper(array(0, 0, 595.28, 841.89*4), 'portrait'); // A4 height * 20
        $dompdf->loadHtml( $data );
        $dompdf->render();
        $pdf_data = $dompdf->output();
        $base_64_pdf = base64_encode($pdf_data);

        return $base_64_pdf;
    }
    public function scc_calculator_op() {
        require_once __DIR__ . '/admin/controllers/formController.php';
        require_once __DIR__ . '/admin/controllers/sectionController.php';
        require_once __DIR__ . '/admin/controllers/subsectionController.php';
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        $formC        = new formController();
        $sectionC     = new sectionController();
        $subsectionC  = new subsectionController();
        $elementC     = new elementController();
        $elementitemC = new elementitemController();
        switch ( sanitize_text_field( $_GET['op'] ) ) {
            case 'del':
                check_ajax_referer( 'all-calculators-page', 'nonce' );
                $id       = intval( $_GET['id_form'] );
                $response = $formC->delete( $id );

                if ( $response ) {
                    echo json_encode( [ 'passed' => true ] );
                } else {
                    echo json_encode( [ 'passed' => false ] );
                }
                break;

            case 'add':
                check_ajax_referer( 'add-calculator-page', 'nonce' );
                $form['formname']    = sanitize_text_field( $_GET['calculator_name'] );
                $response            = $formC->create( $form );
                $sec['form_id']      = $response;
                $secid               = $sectionC->create( $sec );
                $sub['section_id']   = $secid;
                $subid               = $subsectionC->create( $sub );
                $el['subsection_id'] = $subid;
                $el['type']          = 'Dropdown Menu';
                $el['titleElement']  = 'Dropdown';
                $elid                = $elementC->create( $el );
                $eli['name']         = 'Name';
                $eli['description']  = 'Description';
                $eli['price']        = '10';
                $eli['element_id']   = $elid;
                $elementitemC->create( $eli );
                echo json_encode(
                    [
                        'passed' => true,
                        'data'   => $response,
                    ]
                );
                break;

            case 'load_by_params':
                check_ajax_referer( 'add-calculator-page', 'nonce' );
                $input_args              = file_get_contents( 'php://input' );
                $args                    = json_decode( $input_args, true );
                $wizard_results_features = [];
                $wizard_results_elements = [];

                foreach ( $args['featuresByChoice'] as $key => $value ) {
                    foreach ( $value as $key2               => $value2 ) {
                        $wizard_results_features[ $value2 ] = true;
                    }
                }

                foreach ( $args['elementsByChoice'] as $key => $value ) {
                    foreach ( $value as $key2               => $value2 ) {
                        $wizard_results_elements[ $value2 ] = true;
                    }
                }
                $form['formname']               = sanitize_text_field( $_REQUEST['calculator_name'] );
                $form['secondaryCtaButtons']    = wp_slash( wp_json_encode( ['detailed_list_view', 'coupon_code'] ) );
                $created_calc_id                = $formC->create( $form );
                $sec['form_id']                 = intval( $created_calc_id );
                // find if $wizard_results_features has 'activate-accordion' key
                $activate_accordion_key_exists  = false;
                $sec['accordion']               = $activate_accordion_key_exists ? 'true' : 'false';
                $secid                          = $sectionC->create( $sec );
                $sub['section_id']              = intval( $secid );
                $subid                          = $subsectionC->create( $sub );
                $el['subsection_id']            = intval( $subid );
                $el['type']                     = 'Dropdown Menu';
                $el['titleElement']             = 'Dropdown';
                $elid                           = $elementC->create( $el );
                $eli['name']                    = 'Name';
                $eli['description']             = 'Description';
                $eli['price']                   = '10';
                $eli['element_id']              = intval( $elid );
                $elementitemC->create( $eli );
                $this->apply_wizard_result_features( $wizard_results_features, $created_calc_id, $formC );
                $this->generate_tutorial_email_from_wizard( $args );
                $pdf_object = $this->generate_tutorial_email_from_wizard( $args );
                echo json_encode(
                    [
                        'passed' => true,
                        'data'   => $created_calc_id,
                        'pdfData' => $pdf_object,
                    ]
                );
                break;
        }
        die();
    }
    public function ssc_loadExample() {
        check_ajax_referer( 'add-calculator-page', 'nonce' );
        $data_example = intval( $_GET['el'] );
        $json1        = json_decode( file_get_contents( __DIR__ . '/assets/templates/' . $data_example . '.json' ), true );
        function scc_insert_db_( $json ) {
            require_once __DIR__ . '/admin/controllers/formController.php';
            require_once __DIR__ . '/admin/controllers/sectionController.php';
            require_once __DIR__ . '/admin/controllers/subsectionController.php';
            require_once __DIR__ . '/admin/controllers/elementController.php';
            require_once __DIR__ . '/admin/controllers/elementitemController.php';
            $sectionC     = new sectionController();
            $subsectionC  = new subsectionController();
            $elementC     = new elementController();
            $elementitemC = new elementitemController();
            $formC        = new formController();
            //calculator name and settings
            $f['formname']           = sanitize_text_field( $json['name'] );
            $f['showTaxBeforeTotal'] = 'false';
            $f['turnoffTax']         = 'false';
            $f['turnoffborder']      = 'true';
            $f['turnviewdetails']    = 'false';
            $f['turnoffcoupon']      = 'true';
            $f['removeTotal']        = 'false';
            $f['removeTitle']        = 'false';
            $f['turnoffUnit']        = 'false';
            $f['turnoffSave']        = 'false';
            $f['turnoffTax']         = 'false';
            $f['turnoffemailquote']  = 'true';
            $f['titleFontType']      = sanitize_text_field( $json['settings']['titleFontType'] );
            $f['titleColorPicker']   = sanitize_text_field( $json['settings']['titleColorPicker'] );
            $f['titleFontSize']      = sanitize_text_field( $json['settings']['titleServicepricefontsize'] );
            $f['fontType']           = sanitize_text_field( $json['settings']['fontType'] );
            $f['ServiceColorPicker'] = sanitize_text_field( $json['settings']['colorPicker'] );
            $f['ServiceFontSize']    = sanitize_text_field( $json['settings']['servicepricefontsize'] );
            $f['objectColorPicker']  = sanitize_text_field( $json['settings']['objectColorPicker'] );
            $f['inheritFontType']    = 'false';
            $id_c                    = $formC->create( $f );
            //sections
            foreach ( $json['content'] as $key => $section ) {
                $s['name']        = sanitize_text_field( $section['name'] );
                $s['description'] = wp_kses( $section['desc'], SCC_ALLOWTAGS );
                $s['order']       = intval( $section['section'] );
                $s['form_id']     = $id_c;

                if ( isset( $section['accordion'] ) ) {
                    $s['accordion'] = sanitize_text_field( $section['accordion'] );
                }
                $id_sec = $sectionC->create( $s );
                //subsections
                foreach ( $section['value'] as $key => $sub ) {
                    $sb['order']      = intval( $sub['subsection'] );
                    $sb['section_id'] = $id_sec;
                    $sb_id            = $subsectionC->create( $sb );
                    //elements
                    foreach ( $sub['Nooptions'] as $element ) {
                        $ell['order'] = intval( $element['element'] );
                        $ell['type']  = sanitize_text_field( $element['type'] );

                        if ( $element['type'] == 'custom math' || $element['type'] == 'file upload' || $element['type'] == 'texthtml' ) {
                            continue;
                        }

                        if ( $element['type'] == 'checkbox' && $element['value1'] == '8' ) {
                            continue;
                        }
                        $ell['subsection_id'] = $sb_id;

                        if ( isset( $element['title'] ) ) {
                            $ell['titleElement'] = wp_kses( $element['title'], SCC_ALLOWTAGS );
                        }

                        if ( isset( $element['value1'] ) ) {
                            $ell['value1'] = sanitize_text_field( $element['value1'] );
                        }

                        if ( isset( $element['value2'] ) ) {
                            $ell['value2'] = sanitize_text_field( $element['value2'] );
                        }

                        if ( isset( $element['value3'] ) ) {
                            $ell['value3'] = sanitize_text_field( $element['value3'] );
                        }

                        if ( isset( $element['value4'] ) ) {
                            $ell['value4'] = sanitize_text_field( $element['value4'] );
                        }

                        if ( isset( $element['mandatory'] ) ) {
                            $ell['mandatory'] = sanitize_text_field( $element['mandatory'] );
                        }

                        if ( isset( $element['displayFrontend'] ) ) {
                            $ell['displayf'] = sanitize_text_field( $element['displayFrontend'] );
                        }
                        $id_el = $elementC->create( $ell );

                        foreach ( $element['value'] as $key => $e ) {
                            //check if is element only or if its elementitem
                            //element
                            $ellit['order']      = intval( $e['No'] );
                            $ellit['element_id'] = $id_el;

                            if ( isset( $e['name'] ) ) {
                                $ellit['name'] = sanitize_text_field( $e['name'] );
                            }

                            if ( isset( $e['price'] ) ) {
                                $ellit['price'] = sanitize_text_field( $e['price'] );
                            }

                            if ( isset( $e['description'] ) ) {
                                $ellit['description'] = sanitize_text_field( $e['description'] );
                            }

                            if ( isset( $e['value1'] ) ) {
                                $ellit['value1'] = sanitize_text_field( $e['value1'] );
                            }

                            if ( isset( $e['value2'] ) ) {
                                $ellit['value2'] = sanitize_text_field( $e['value2'] );
                            }

                            if ( isset( $e['value3'] ) ) {
                                $ellit['value3'] = sanitize_text_field( $e['value3'] );
                            }

                            if ( isset( $e['value4'] ) ) {
                                $ellit['value4'] = sanitize_text_field( $e['value4'] );
                            }

                            if ( isset( $e['opt_default'] ) ) {
                                $ellit['opt_default'] = sanitize_text_field( $e['opt_default'] );
                            }
                            $elementitemC->create( $ellit );
                        }
                    }
                }
            }

            return $id_c;
        }
        function insert_calculator( $json ) {
            require_once __DIR__ . '/admin/controllers/formController.php';
            require_once __DIR__ . '/admin/controllers/sectionController.php';
            require_once __DIR__ . '/admin/controllers/subsectionController.php';
            require_once __DIR__ . '/admin/controllers/elementController.php';
            require_once __DIR__ . '/admin/controllers/elementitemController.php';
            require_once __DIR__ . '/admin/controllers/conditionController.php';
            $formC        = new formController();
            $sectionC     = new sectionController();
            $subsectionC  = new subsectionController();
            $elementC     = new elementController();
            $conditionalC = new conditionController();
            $elementitemC = new elementitemController();
            //?fixes possible conflic id due not autoincrement id
            unset( $json['id'] );
            $calculator_id = $formC->create( $json );

            foreach ( $json['sections'] as $section ) {
                $section['form_id'] = intval( $calculator_id );
                $section_id         = $sectionC->create( $section );

                foreach ( $section['subsection'] as $subsection ) {
                    $subsection['section_id'] = intval( $section_id );
                    $subsection_id            = $subsectionC->create( $subsection );

                    foreach ( $subsection['element'] as $element ) {
                        $element['subsection_id'] = intval( $subsection_id );
                        $element['uniqueId']      = sanitize_text_field( $element['uniqueId'] ) . intval( $calculator_id );
                        $id_element               = $elementC->create( $element );

                        foreach ( $element['elementitems'] as $items ) {
                            $items['uniqueId']   = sanitize_text_field( $items['uniqueId'] ) . intval( $calculator_id );
                            $items['element_id'] = intval( $id_element );
                            $elementitemC->create( $items );
                        }

                        foreach ( $element['conditions'] as $condition ) {
                            //!Recently done must be tested
                            $condition['element_id'] = intval( $id_element );

                            if ( $condition['element_condition'] ) {
                                $condition_element                 = (array) $elementC->getByUniqueId( sanitize_text_field( $condition['element_condition']['uniqueId'] ) . intval( $calculator_id ) );
                                $condition['condition_element_id'] = intval( $condition_element['id'] );
                            } else {
                                unset( $condition['condition_element_id'] );
                            }

                            if ( $condition['elementitem_name'] ) {
                                $condition_elementItem       = (array) $elementitemC->getByUniqueId( sanitize_text_field( $condition['elementitem_name']['uniqueId'] ) . intval( $calculator_id ) );
                                $condition['elementitem_id'] = intval( $condition_elementItem['id'] );
                            } else {
                                unset( $condition['elementitem_id'] );
                            }
                            $conditionalC->create( (array) $condition );
                        }
                    }
                }
            }
            update_option( 'scc_cstmjs_calc_' . $calculator_id, wp_json_encode( $json['customJsConfig'] ) );

            return $calculator_id;
        }

        if ( isset( $json1['name'] ) ) {
            $i = scc_insert_db_( $json1 );
            wp_send_json(
                [
                    'passed' => true,
                    'data'   => $i,
                ]
            );
            die();
        }

        if ( ! isset( $json1['name'] ) ) {
            $inserted = insert_calculator( $json1 );

            if ( $inserted ) {
                wp_send_json(
                    [
                        'passed' => true,
                        'data'   => $inserted,
                    ]
                );
            }
            die();
        }
    }
    public function scc_addCheckboxItems() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        require_once __DIR__ . '/admin/models/editElementModel.php';
        $elementitemC              = new elementitemController();
        $eit['name']               = 'Name';
        $eit['price']              = '10';
        $eit['element_id']         = intval( $_GET['element_id'] );
        $element_id                = $elementitemC->create( $eit );
        $load_woocommerce_products = esc_attr( $_GET['enableWoocommerce'] ) === 'true';
        $count                     = intval( $_GET['count'] );
        $is_image_checkbox         = esc_attr( $_GET['is_image_checkbox'] ) === 'true';
        $edit_page_func            = new Stylish_Cost_Calculator_Edit_Page( 0, true, $load_woocommerce_products );
        $element_item_html         = $edit_page_func->checkbox_setup_checkbox_item( $count, array_merge( $eit, [ 'id' => $element_id ] ), $is_image_checkbox );
        echo ( $element_id ) ? json_encode(
            [
                'msj'        => 'The element was created',
                'passed'     => true,
                'id_element' => $element_id,
                'DOMhtml'    => $element_item_html,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addElementFileUpload() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/models/editElementModel.php';
        $edit_page_func      = new Stylish_Cost_Calculator_Edit_Page( 0, true );
        $elementC            = new elementController();
        $el['orden']         = intval( $_GET['order'] );
        $el['type']          = 'file upload';
        $el['subsection_id'] = intval( $_GET['id_sub'] );
        $html                = $edit_page_func->renderAdvancedOptions( (object) $el );
        $element_id          = $elementC->create( $el );
        $eli['value1']       = '1';
        $eli['value2']       = 'Please choose a file';
        $eli['value3']       = 'png,pdf,jpeg,jpg';
        $eli['element_id']   = intval( $element_id );
        $body_html           = $edit_page_func->renderFileUploadSetupBody2( (object) array_merge( $eli, $el, [ 'elementItem_id' => $elementItem_id ] ), [ 1 => [] ] ) . $edit_page_func->renderElementLoader();
        echo ( $element_id ) ? json_encode(
            [
                'msj'        => 'The element was created',
                'passed'     => true,
                'id_element' => $element_id,
                'DOMhtml'    => [
                    'advanced_settings' => $html,
                    'fileupload_body'   => $body_html,
                ],
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addElementCheckbox() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        require_once __DIR__ . '/admin/models/editElementModel.php';
        $edit_page_func      = new Stylish_Cost_Calculator_Edit_Page( 0, true );
        $elementC            = new elementController();
        $elementitemC        = new elementitemController();
        $el['orden']         = intval( $_GET['order'] );
        $el['value1']        = sanitize_text_field( $_GET['type'] );
        $el['type']          = 'checkbox';
        $el['subsection_id'] = intval( $_GET['id_sub'] );
        $el['titleElement']  = 'New element';
        $html                = $edit_page_func->renderAdvancedOptions( (object) $el );
        $element_id          = $elementC->create( $el );
        $elit['order']       = '0';
        $elit['name']        = 'Name';
        $elit['price']       = '10';
        $elit['element_id']  = intval( $element_id );
        $elementItem_id      = $elementitemC->create( $elit );
        // TODO add type of checkbox on ajax response
        $type       = '';
        $elit['id'] = $elementItem_id;
        $body_html  = $edit_page_func->renderCheckboxSetupBody( (object) array_merge( $el, [ 'elementitems' => [ 0 => (object) $elit ] ] ), [ 1 => [] ] ) . $edit_page_func->renderElementLoader();
        echo ( $element_id ) ? json_encode(
            [
                'msj'             => 'The element was created',
                'passed'          => true,
                'id_element'      => $element_id,
                'id_element_item' => $elementItem_id,
                'type'            => $type,
                'DOMhtml'         => [
                    'advanced_settings' => $html,
                    'checkbox_body'     => $body_html,
                ],
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addElementCommentBox() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/models/editElementModel.php';
        $edit_page_func      = new Stylish_Cost_Calculator_Edit_Page( 0, true );
        $elementC            = new elementController();
        $el['orden']         = intval( $_GET['order'] );
        $el['type']          = 'comment box';
        $el['titleElement']  = 'New Comment Box';
        $el['subsection_id'] = intval( $_GET['id_sub'] );
        $html                = $edit_page_func->renderAdvancedOptions( (object) $el );
        $element_id          = $elementC->create( $el );
        $eli['value1']       = '1';
        $eli['value2']       = '10';
        $eli['value3']       = '2';
        $eli['element_id']   = intval( $element_id );
        $body_html           = $edit_page_func->renderCommentBoxSetupBody2( (object) array_merge( $eli, $el, [ 'elementItem_id' => $elementItem_id ] ), [ 1 => [] ] ) . $edit_page_func->renderElementLoader();
        $element_id ? wp_send_json(
            [
                'msj'        => 'The element was created',
                'passed'     => true,
                'id_element' => $element_id,
                'DOMhtml'    => [
                    'advanced_settings' => $html,
                    'commentbox_body'   => $body_html,
                ],
            ]
        ) : wp_send_json(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addElementQuantityBox() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/models/editElementModel.php';
        $edit_page_func      = new Stylish_Cost_Calculator_Edit_Page( 0, true );
        $elementC            = new elementController();
        $el['orden']         = intval( $_GET['order'] );
        $el['type']          = 'quantity box';
        $el['titleElement']  = 'New Quantity Input Box';
        $el['value1']        = 'default';
        $el['value2']        = '0';
        $el['subsection_id'] = intval( $_GET['id_sub'] );
        $html                = $edit_page_func->renderAdvancedOptions( (object) $el );
        $element_id          = $elementC->create( $el );
        $eli['value1']       = '1';
        $eli['value2']       = '10';
        $eli['value3']       = '2';
        $eli['element_id']   = intval( $element_id );
        $body_html           = $edit_page_func->renderQuantityBoxSetupBody2( (object) array_merge( $eli, $el, [ 'elementItem_id' => $elementItem_id ] ), [ 1 => [] ] ) . $edit_page_func->renderElementLoader();
        echo ( $element_id ) ? json_encode(
            [
                'msj'        => 'The element was created',
                'passed'     => true,
                'id_element' => $element_id,
                'DOMhtml'    => [
                    'advanced_settings' => $html,
                    'quantitybox_body'  => $body_html,
                ],
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addElementTextHtml() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/models/editElementModel.php';
        $edit_page_func      = new Stylish_Cost_Calculator_Edit_Page( 0, true );
        $elementC            = new elementController();
        $el['orden']         = intval( $_GET['order'] );
        $el['type']          = 'texthtml';
        $el['subsection_id'] = intval( $_GET['id_sub'] );
        $html                = $edit_page_func->renderAdvancedOptions( (object) $el );
        $element_id          = $elementC->create( $el );
        echo ( $element_id ) ? json_encode(
            [
                'msj'        => 'The element was created',
                'passed'     => true,
                'id_element' => $element_id,
                'DOMhtml'    => [ 'advanced_settings' => $html ],
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addElementSlider() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        require_once __DIR__ . '/admin/models/editElementModel.php';
        $edit_page_func      = new Stylish_Cost_Calculator_Edit_Page( 0, true );
        $elementC            = new elementController();
        $elementitemC        = new elementitemController();
        $el['orden']         = intval( $_GET['order'] );
        $el['titleElement']  = 'New Slider Element';
        $el['type']          = 'slider';
        $el['value2']        = '1';
        $el['value3']        = '1';
        $el['subsection_id'] = intval( $_GET['id_sub'] );
        $html                = $edit_page_func->renderAdvancedOptions( (object) $el );
        $elmts               = $elementC->getBySubsection( intval( $_GET['id_sub'] ) );

        foreach ( $elmts as $e ) {
            if ( $e->type == 'slider' ) {
                echo json_encode(
                    [
                        'passed' => false,
                        'msj'    => 'slider already',
                    ]
                );
                die();
            }
        }
        $element_id        = $elementC->create( $el );
        $eli['value1']     = '1';
        $eli['value2']     = '10';
        $eli['value3']     = '2';
        $eli['element_id'] = $element_id;
        $elementItem_id    = $elementitemC->create( $eli );
        $body_html         = $edit_page_func->renderSliderSetupBody2( (object) array_merge( $eli, $el, [ 'elementItem_id' => $elementItem_id ] ), [ 1 => [] ] ) . $edit_page_func->renderElementLoader();

        if ( $element_id ) {
            // finding first adding of a slider element
            $slider_used_total = get_option( 'scc-free-slider-used-times', 0 );

            if ( $slider_used_total == 0 ) {
                update_option( 'scc-free-slider-used-times', $slider_used_total + 1 );
            }
        }
        echo ( $element_id ) ? json_encode(
            [
                'msj'              => 'The element was created',
                'passed'           => true,
                'id_element'       => $element_id,
                'id_elementitem'   => $elementItem_id,
                'first_slider_use' => $slider_used_total == 0,
                'DOMhtml'          => [ 'slider_body' => $body_html ],
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    // ADD ONE SECTION
    public function scc_saveSection() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/sectionController.php';
        require_once __DIR__ . '/admin/controllers/subsectionController.php';
        $sectionC         = new sectionController();
        $subsectionC      = new subsectionController();
        $s['form_id']     = intval( $_GET['id_form'] );
        $s['order']       = intval( $_GET['order'] );
        $section_id       = $sectionC->create( $s );
        $sb['section_id'] = $section_id;
        $subsection_id    = $subsectionC->create( $sb );

        if ( $section_id && $subsection_id ) {
            echo json_encode(
                [
                    'msj'           => 'The section was created',
                    'passed'        => true,
                    'id_section'    => $section_id,
                    'id_subsection' => $subsection_id,
                ]
            );
        } else {
            echo json_encode(
                [
                    'msj'    => 'There was an error, please try again',
                    'passed' => false,
                ]
            );
        }
        die();
    }
    // DELETES ONE SECTION
    public function scc_delSection() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/sectionController.php';
        $sectionC = new sectionController();
        $id       = intval( $_GET['id_section'] );
        $request  = $sectionC->delete( $id );
        echo ( $request ) ? json_encode(
            [
                'msj'    => 'the section was deleted',
                'passed' => true,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_upSection() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/sectionController.php';
        $sectionC = new sectionController();
        $s['id']  = intval( $_GET['id_section'] );

        if ( isset( $_GET['accordion'] ) ) {
            $s['accordion'] = sanitize_text_field( $_GET['accordion'] );
        }

        if ( isset( $_GET['showTotal'] ) ) {
            $s['showSectionTotal'] = sanitize_text_field( $_GET['showTotal'] );
        }

        if ( isset( $_GET['title'] ) ) {
            $s['name'] = sanitize_text_field( $_GET['title'] );
        }

        if ( isset( $_GET['description'] ) ) {
            $s['description'] = wp_kses( $_GET['description'], SCC_ALLOWTAGS );
        }

        if ( isset( $_GET['showSectionTotalOnPdf'] ) ) {
            $s['showSectionTotalOnPdf'] = sanitize_text_field( $_GET['showSectionTotalOnPdf'] );
        }
        $request = $sectionC->update( $s );
        echo ( $request ) ? json_encode(
            [
                'msj'    => 'the section was updated',
                'passed' => true,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_delSubsection() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/subsectionController.php';
        $subsectionC = new subsectionController();
        $id          = intval( $_GET['id_subsection'] );
        $request     = $subsectionC->delete( $id );
        echo ( $request ) ? json_encode(
            [
                'msj'    => 'the subsection was deleted',
                'passed' => true,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addSubsection() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/subsectionController.php';
        $subsectionC      = new subsectionController();
        $sb['order']      = intval( $_GET['order'] );
        $sb['section_id'] = intval( $_GET['section_id'] );
        $subsection_id    = $subsectionC->create( $sb );
        echo ( $subsection_id ) ? json_encode(
            [
                'msj'           => 'The subsection was created',
                'passed'        => true,
                'id_subsection' => $subsection_id,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );

        die();
    }
    public function scc_delElementItem() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        $elementitemC   = new elementitemController();
        $id_elementitem = intval( $_GET['element_id'] );
        $request        = $elementitemC->delete( $id_elementitem );
        echo ( $request ) ? json_encode(
            [
                'msj'    => 'The element was deleted',
                'passed' => true,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addElementSwichoption() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        require_once __DIR__ . '/admin/models/editElementModel.php';
        $elementitemC              = new elementitemController();
        $load_woocommerce_products = esc_attr( $_GET['enableWoocommerce'] ) === 'true';
        $edit_page_func            = new Stylish_Cost_Calculator_Edit_Page( 0, true, $load_woocommerce_products );
        $eli['order']              = '0';
        $eli['name']               = 'Name of product';
        $eli['price']              = '10';
        $eli['description']        = 'Description example';
        $eli['element_id']         = intval( $_GET['element_id'] );
        $items_count               = intval( $_GET['itemCount'] ) - 1;
        $element_id                = $elementitemC->create( $eli );
        $element_item_html         = $edit_page_func->element_setup_part_dropdown_item_beta( $items_count, array_merge( $eli, [ 'id' => $element_id ] ) );
        echo ( $element_id ) ? json_encode(
            [
                'msj'        => 'The element was created',
                'passed'     => true,
                'element_id' => $element_id,
                'html'       => $element_item_html,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_upElement() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        $elementC = new elementController();
        $el['id'] = intval( $_GET['id_element'] );

        if ( isset( $_GET['title'] ) ) {
            $el['titleElement'] = wp_kses( $_GET['title'], SCC_ALLOWTAGS );
        }

        if ( isset( $_GET['typecheckbox'] ) ) {
            $el['value1'] = sanitize_text_field( $_GET['typecheckbox'] );
        }

        if ( isset( $_GET['value2'] ) ) {
            $el['value2'] = ( isset( $_GET['tt'] ) && $_GET['tt'] == 'texthtml' ) ? wp_kses( $_GET['value2'], SCC_ALLOWTAGS ) : sanitize_text_field( $_GET['value2'] );
        }

        if ( isset( $_GET['value3'] ) ) {
            $el['value3'] = sanitize_text_field( $_GET['value3'] );
        }

        if ( isset( $_GET['value4'] ) ) {
            $el['value4'] = sanitize_text_field( $_GET['value4'] );
        }

        if ( isset( $_GET['value5'] ) ) {
            $el['value5'] = sanitize_text_field( $_GET['value5'] );
        }

        if ( isset( $_GET['mandatory'] ) ) {
            $el['mandatory'] = sanitize_text_field( $_GET['mandatory'] );
        }

        if ( isset( $_GET['desktop'] ) ) {
            $el['titleColumnDesktop'] = sanitize_text_field( $_GET['desktop'] );
        }

        if ( isset( $_GET['mobile'] ) ) {
            $el['titleColumnMobile'] = sanitize_text_field( $_GET['mobile'] );
        }

        if ( isset( $_GET['pricehint'] ) ) {
            $el['showPriceHint'] = sanitize_text_field( $_GET['pricehint'] );
        }

        if ( isset( $_GET['displayFront'] ) ) {
            $el['displayFrontend'] = sanitize_text_field( $_GET['displayFront'] );
        }

        if ( isset( $_GET['displayDetail'] ) ) {
            $el['displayDetailList'] = sanitize_text_field( $_GET['displayDetail'] );
        }

        if ( isset( $_GET['order'] ) ) {
            $el['orden'] = intval( $_GET['order'] );
        }

        if ( isset( $_GET['subsection'] ) ) {
            $el['subsection_id'] = sanitize_text_field( $_GET['subsection'] );
        }

        if ( isset( $_GET['showTitlePdf'] ) ) {
            $el['showTitlePdf'] = sanitize_text_field( $_GET['showTitlePdf'] );
        }

        if ( isset( $_GET['element_woocomerce_product_id'] ) ) {
            $el['element_woocomerce_product_id'] = intval( $_GET['element_woocomerce_product_id'] );
        }

        if ( isset( $_GET['showInputBoxSlider'] ) ) {
            $el['showInputBoxSlider'] = sanitize_text_field( $_GET['showInputBoxSlider'] );
        }

        $request = $elementC->update( $el );
        echo json_encode( [ 'passed' => true ] );
        die();
    }
    public function scc_upElementItemSwichoption() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        $elementitemC = new elementitemController();
        $eli['id']    = intval( $_GET['id_elementitem'] );

        if ( isset( $_GET['name'] ) ) {
            $eli['name'] = sanitize_text_field( $_GET['name'] );
        }

        if ( isset( $_GET['description'] ) ) {
            $eli['description'] = sanitize_text_field( $_GET['description'] );
        }

        if ( isset( $_GET['price'] ) ) {
            $eli['price'] = sanitize_text_field( $_GET['price'] );
        }

        if ( isset( $_GET['image'] ) ) {
            $eli['value1'] = esc_url_raw( $_GET['image'] );
        }

        if ( isset( $_GET['default'] ) ) {
            $eli['opt_default'] = sanitize_text_field( $_GET['default'] );
        }

        if ( isset( $_GET['woocomerce_product_id'] ) ) {
            $eli['woocomerce_product_id'] = intval( $_GET['woocomerce_product_id'] );
        }
        $request = $elementitemC->update( $eli );
        //set to 0 opt_default to rest of elementitem of the same element
        if ( isset( $_GET['id_element'] ) && $_GET['default'] == 1 ) {
            $eli2 = $elementitemC->readOfElement( intval( $_GET['id_element'] ) );

            foreach ( $eli2 as $e ) {
                if ( $e->id != intval( $_GET['id_elementitem'] ) ) {
                    $ee['opt_default'] = 0;
                    $ee['id']          = $e->id;
                    $elementitemC->update( $ee );
                }
            }
        }
        ( $request ) ? wp_send_json(
            [
                'msj'    => 'The element has changed',
                'passed' => true,
            ]
        ) : wp_send_json(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addsElementDropdownMenu() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        require_once __DIR__ . '/admin/models/editElementModel.php';
        $edit_page_func      = new Stylish_Cost_Calculator_Edit_Page( 0, true );
        $elementC            = new elementController();
        $elementitemC        = new elementitemController();
        $el['orden']         = intval( $_GET['order'] );
        $el['titleElement']  = 'Title';
        $el['type']          = 'Dropdown Menu';
        $el['subsection_id'] = intval( $_GET['id_sub'] );
        $html                = $edit_page_func->renderAdvancedOptions( (object) $el );
        $element_id          = $elementC->create( $el );
        $eli['order']        = '0';
        $eli['name']         = 'Name';
        $eli['price']        = '10';
        $eli['description']  = 'Description';
        $eli['element_id']   = $element_id;
        $elementItem_id      = $elementitemC->create( $eli );
        $body_html           = $edit_page_func->renderDropdownSetupBody( (object) array_merge( $eli, $el, [ 'elementItem_id' => $elementItem_id ] ), [ 1 => [] ] ) . $edit_page_func->renderElementLoader();
        echo ( $element_id ) ? json_encode(
            [
                'msj'             => 'The element was created',
                'passed'          => true,
                'id_element'      => $element_id,
                'id_element_item' => $elementItem_id,
                'DOMhtml'         => [
                    'advanced_settings' => $html,
                    'slider_body'       => $body_html,
                ],
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_upElementItemSlider() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        $elementitemC = new elementitemController();
        $eli['id']    = intval( $_GET['id_element'] );

        if ( isset( $_GET['value1'] ) ) {
            $eli['value1'] = sanitize_text_field( $_GET['value1'] );
        }

        if ( isset( $_GET['value2'] ) ) {
            $eli['value2'] = sanitize_text_field( $_GET['value2'] );
        }

        if ( isset( $_GET['value3'] ) ) {
            $eli['value3'] = sanitize_text_field( $_GET['value3'] );
        }
        $request = $elementitemC->update( $eli );
        echo ( $request ) ? json_encode(
            [
                'msj'    => 'The title has changed',
                'passed' => true,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_addElementItemSlider() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        $elementitemC          = new elementitemController();
        $previous_row_maxvalue = sanitize_text_field( $_GET['value1'] );
        $eli['value1']         = $previous_row_maxvalue;
        $eli['value2']         = $previous_row_maxvalue + 1;
        $eli['value3']         = '2';
        $eli['element_id']     = intval( $_GET['id_element'] );
        $elementitem_id        = $elementitemC->create( $eli );
        echo ( $elementitem_id ) ? json_encode(
            [
                'msj'            => 'The element was created',
                'passed'         => true,
                'id_elementitem' => $elementitem_id,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error, please try again',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_delElement() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/elementController.php';
        $el         = new elementController();
        $id_element = intval( $_GET['element_id'] );
        $request    = $el->delete( $id_element );
        echo ( $request ) ? json_encode(
            [
                'msj'    => 'The element was deleted',
                'passed' => true,
            ]
        ) : json_encode(
            [
                'msj'    => 'There was an error',
                'passed' => false,
            ]
        );
        die();
    }
    // CHANGES NAME OF FORM
    public function scc_saveFormNameSettings() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require_once __DIR__ . '/admin/controllers/formController.php';
        $formC                  = new formController();
        $f['id']                = intval( $_POST['id_form'] );
        $f['formname']          = sanitize_text_field( $_POST['data']['formname'] );
        $f['elementSkin']       = sanitize_text_field( $_POST['data']['elementSkin'] );
        $f['addContainer']      = sanitize_text_field( $_POST['data']['addContainer'] );
        $f['buttonStyle']       = sanitize_text_field( $_POST['data']['buttonStyle'] );
        $f['turnoffemailquote'] = sanitize_text_field( $_POST['data']['turnoffemailquote'] );
        $f['turnviewdetails']   = sanitize_text_field( $_POST['data']['turnviewdetails'] );
        $f['turnoffcoupon']     = sanitize_text_field( $_POST['data']['turnoffcoupon'] );
        $f['barstyle']          = sanitize_text_field( $_POST['data']['barstyle'] );
        $f['turnofffloating']   = sanitize_text_field( $_POST['data']['turnofffloating'] );
        $f['removeTitle']       = sanitize_text_field( $_POST['data']['removeTitle'] );
        $f['turnoffUnit']       = sanitize_text_field( $_POST['data']['turnoffUnit'] );
        $f['turnoffQty']        = sanitize_text_field( $_POST['data']['turnoffQty'] );
        $f['turnoffSave']       = sanitize_text_field( $_POST['data']['turnoffSave'] );
        $f['turnoffTax']        = sanitize_text_field( $_POST['data']['turnoffTax'] );
        $f['symbol']            = sanitize_text_field( $_POST['data']['symbol'] );
        $f['removeCurrency']    = sanitize_text_field( $_POST['data']['removeCurrency'] );
        $f['userCompletes']     = sanitize_text_field( $_POST['data']['userCompletes'] );
        $f['userClicksf']       = sanitize_text_field( $_POST['data']['userClicksf'] );
        $f['translation']       = sanitize_text_field( $_POST['translations'] );
        $f['wrapper_max_width'] = absint( $_POST['data']['calcWrapperMaxWidth'] );
        $request                = $formC->update( $f );
        echo ( $request ) ? json_encode( [ 'passed' => true ] ) : json_encode(
            [
                'msj'    => 'There was an error',
                'passed' => false,
            ]
        );
        die();
    }
    public function scc_previewOneForm() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        echo do_shortcode( "[scc_calculator type=' text' idvalue='" . intval( $_GET['id_form'] ) . "' ]" );
        die();
    }
    /**
     * @return array
     */
    public function custom_mails( $args ) {
        $sender    = get_option( 'df_scc_emailsender', get_option( 'admin_email' ) );
        $sender    = empty( $sender ) ? get_option( 'admin_email' ) : $sender;
        $bcc_email = sanitize_email( $sender );

        if ( is_array( $args['headers'] ) ) {
            $args['headers'][] = 'Bcc: ' . $bcc_email;
        } else {
            $args['headers'] .= 'Bcc: ' . $bcc_email . "\r\n";
        }

        return $args;
    }
    /**
     * @return string
     */
    public function new_mail_from() {
        $sender = get_option( 'df_scc_emailsender' );
        $sender = empty( $sender ) ? 'wordpress@' . parse_url( get_site_url() )['host'] : sanitize_email( $sender );

        return $sender;
    }
    /**
     * Save image URL to options
     */
    public function pdf_logo_save_uploaded_image( $data ) {
        update_option( sanitize_text_field( $data['name'] ), sanitize_text_field( $data['value'] ) );
        wp_send_json_success(
            [
                'url' => sanitize_text_field( $data['value'] ),
            ]
        );
    }
    /**
     * Manage admin ajax functionality
     */
    public function scc_updateSectionOrder() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require __DIR__ . '/admin/controllers/sectionController.php';
        $sectionC  = new sectionController();
        $sections2 = sanitize_text_or_array_field( $_GET['sections'] );

        foreach ( $sections2 as $s ) {
            $e['id']    = intval( $s['id'] );
            $e['order'] = intval( $s['order'] );
            $sectionC->update( $e );
        }
        wp_send_json( [ 'passed' => true ] );
    }
    public function sccPDFSettings() {
        check_ajax_referer( 'global-settings-page', 'nonce' );
        $pdf_font    = sanitize_text_field( stripslashes( $_POST['pdfSettings']['sccPDFFont'] ) );
        $pdf_datefmt = sanitize_text_field( stripslashes( $_POST['pdfSettings']['sccPDFDateFmt'] ) );
        // echo $pdf_font;
        update_option( 'sccPDFFont', $pdf_font );
        update_option( 'scc_pdf_datefmt', $pdf_datefmt );
    }
    public function sccUpdateUrlStats() {
        require __DIR__ . '/admin/controllers/urlStatsController.php';
        $url    = sanitize_text_field( $_POST['url'] );
        $calcId = intval( $_POST['calcId'] );
        check_ajax_referer( 'calculator-front-page' . $calcId, 'nonce' );
        $stats  = new urlStatsController( $calcId, $url );
        $result = $stats->update( $url );

        if ( $result ) {
            wp_send_json_success( [ 'ok' => 'ok' ] );
        } else {
            wp_send_json_error( [ 'error' => 'something didn\'t work' ] );
        }
    }
    public function sccFeedbackManage() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        $args = isset( $_POST['btn-type'] ) ? sanitize_text_field( $_POST['btn-type'] ) : false;

        if ( $args ) {
            scc_feedback_invocation( $args );
            wp_send_json_success();
        }
        $data = json_decode( file_get_contents( 'php://input' ), true );
        $data = wp_parse_args( $data, [
            'rating'        => 0,
            'text'          => '',
            'optedForEmail' => false,
        ] );
        $survey_store_url = SCC_TELEMETRY_ENDPOINT . '/api/public/user-survey';
        $headers          = [
            'user-agent'        => 'SCC/' . STYLISH_COST_CALCULATOR_VERSION . ';',
            'Accept'            => 'application/json',
            'Content-Type'      => 'application/json',
            'X-App-Version'     => STYLISH_COST_CALCULATOR_VERSION,
            'X-Site-Url'        => md5( get_site_url() ),
            'X-Release-Channel' => 'demo',
        ];
        wp_remote_post( $survey_store_url, [
            'method'      => 'POST',
            'timeout'     => 5,
            'redirection' => 5,
            'httpversion' => '1.1',
            'blocking'    => false,
            'headers'     => $headers,
            'body'        => wp_json_encode( $data ),
            'cookies'     => [],
        ] );
        scc_feedback_invocation( 'comment_and_rating' );
        wp_send_json( [ 'ok' => $data ] );
    }
    public function get_debug_items() {
        check_ajax_referer( 'edit-calculator-page', 'nonce' );
        require __DIR__ . '/admin/views/diagnostic.php';
        $existing_ignore_list = get_option( 'scc-diag-dissmissed', [] );

        if ( isset( $_REQUEST['method'] ) && $_REQUEST['method'] == 'set_ignore' ) {
            $ignored_param = sanitize_text_field( $_REQUEST['value'] );
            array_push( $existing_ignore_list, $ignored_param );
            $existing_ignore_list = array_unique( $existing_ignore_list );
            $saved                = update_option( 'scc-diag-dissmissed', $existing_ignore_list );

            wp_send_json( [ 'saved' => $saved ] );
        }

        if ( isset( $_REQUEST['method'] ) && $_REQUEST['method'] == 'skip_sg_optim_warning' ) {
            // get invokation count
            $sg_optim_alert            = get_option( 'scc-sg-optimizer-alert', ['respawn' => 0, 'show' => true] );
            $save_count                = intval( get_option( 'df-scc-save-count' ) );
            $sg_optim_alert['respawn'] = $save_count + 15;
            $sg_optim_alert['show']    = false;
            update_option( 'scc-sg-optimizer-alert', $sg_optim_alert );
        }
        $diag_page = new Stylish_Cost_Calculator_Diagnostic();
        $res       = $diag_page->diagnostic_page( true );
        wp_send_json(
            [
                'debug_items' => [
                    'diag_items' => $res,
                    'exclusions' => $existing_ignore_list,
                ],
                'sg_optimizer_alert' => [
                    'config'    => get_option( 'scc-sg-optimizer-alert', ['respawn' => 0, 'show' => true] ),
                    'is_active' => is_plugin_active( 'sg-cachepress/sg-cachepress.php' ),
                ],
                'save_count' => intval( get_option( 'df-scc-save-count' ) ),
            ]
        );
    }

    public function update_slider_ranges() {
        check_ajax_referer( 'edit-calculator-page' );
        $new_range = json_decode( file_get_contents( 'php://input' ), 1 );
        require_once __DIR__ . '/admin/controllers/elementitemController.php';
        $elementitemC = new elementitemController();
        $query_status = [];

        foreach ( $new_range['cleanRangeCollection'] as $key => $range ) {
            $eli['id']     = intval( $range['rangeId'] );
            $eli['value1'] = floatval( $range['from']['value'] );
            $eli['value2'] = floatval( $range['to']['value'] );
            $eli['value3'] = floatval( $range['ppu']['value'] );
            $request       = $elementitemC->update( $eli );
            array_push( $query_status, $request );
        }
        $saved_range_data = $elementitemC->readOfElement( $new_range['elementId'] );
        // reading the database records and comparing the incoming range value
        foreach ( $saved_range_data as $key => $range ) {
            $found_incoming_range = array_values( array_filter( $new_range['cleanRangeCollection'], function ( $d ) use ( $range ) {
                return $d['rangeId'] == $range->id;
            } ) );
            $matched_ranges = [$found_incoming_range[0]['from']['value'] == $range->value1,
            $found_incoming_range[0]['to']['value'] == $range->value2,
            $found_incoming_range[0]['ppu']['value'] == $range->value3, ];
            // if does not match, an error response is sent
            if ( in_array( false, $matched_ranges ) ) {
                wp_send_json_error( (array) $range, 500 );
            }
        }
        wp_send_json_success( [] );
    }
    private function scc_send_wizard_quiz_data( $data ) {
        $telemetry_url = SCC_TELEMETRY_ENDPOINT . '/api/public/collect';

        $headers      = [
            'user-agent'    => 'SCC/' . STYLISH_COST_CALCULATOR_VERSION . ';',
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'X-App-Version' => STYLISH_COST_CALCULATOR_VERSION,
            'X-Site-Url'    => md5( get_site_url() ),
        ];

        $response     = wp_remote_post(
            $telemetry_url,
            [
                'method'      => 'POST',
                'timeout'     => 5,
                'redirection' => 5,
                'httpversion' => '1.1',
                'headers'     => $headers,
                'body'        => wp_json_encode( $data ),
                'cookies'     => [],
            ]
        );

        // wp_send_json_success( ['done' => true] );
        return;
    }

    public function scc_set_telemetry_state() {
        check_ajax_referer( 'add-calculator-page', 'nonce' );
        $state = sanitize_text_field( (bool) intval( $_POST['state'] ) );
        update_option( 'scc_opted_in_for_telemetry', $state );
        wp_send_json_success();
    }

    public function submit_uninstall_survey() {
        check_ajax_referer( 'uninstall-df-scc-calculator-page', 'nonce' );
        $data = json_decode( file_get_contents( 'php://input' ), true );
        $data = wp_parse_args( $data, [
            'answer'           => 0,
            'comment'          => '',
            'site'             => '',
        ] );
        $data['site']     = md5( get_site_url() );
        $survey_store_url = SCC_TELEMETRY_ENDPOINT . '/api/public/uninstall-survey';
        $headers          = [
            'user-agent'        => 'SCC/' . STYLISH_COST_CALCULATOR_VERSION . ';',
            'Accept'            => 'application/json',
            'Content-Type'      => 'application/json',
            'X-App-Version'     => STYLISH_COST_CALCULATOR_VERSION,
            'X-Site-Url'        => md5( get_site_url() ),
            'X-Release-Channel' => 'demo',
        ];
        wp_remote_post( $survey_store_url, [
            'method'      => 'POST',
            'timeout'     => 5,
            'redirection' => 5,
            'httpversion' => '1.1',
            'headers'     => $headers,
            'body'        => wp_json_encode( $data ),
            'cookies'     => [],
        ] );
        wp_send_json( [ 'ok' => $data ] );
    }
}
new ajaxRequest();
