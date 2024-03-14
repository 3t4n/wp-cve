<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$isSCCFreeVersion      = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
$defaultFields         = json_decode( '[{"name":{"name":"Your Name","description":"Type in your name","type":"text","isMandatory":null,"trnKey":"Your Name","deletable":false}},{"email":{"name":"Your Email","description":"Type in your email","type":"email","isMandatory":null,"trnKey":"Your Email","deletable":false}},{"phone":{"name":"Your Phone","description":"phone","type":"phone","isMandatory":null,"trnKey":"Your Phone (Optional)","deletable":false}}]', true );
$formFieldsArray       = empty( $form->formFieldsArray ) ? $defaultFields : json_decode( $form->formFieldsArray, true );
$form->formFieldsArray = $formFieldsArray;
global $calculatorCount;

if ( ! isset( $calculatorCount ) ) {
    $calculatorCount = 1;
} else {
    ++$calculatorCount;
}

if ( $calculatorCount > 2 ) {
    echo '<p>Only one calculator is allowed on the same page with the free version of Stylish Cost Calculator. The premium version allows multiple calculator forms on a single page because it writes unique CSS classes for each calculator form. Please upgrade to premium if you want more than one calculator form on a single web page.</p>';

    return;
}
$paypalConfigArray       = wp_parse_args(
    json_decode( $form->paypalConfigArray, true ),
    [
        'paypal_email'               => null,
        'paypal_shopping_cart_name'  => null,
        'paypal_checked'             => null,
        'paypal_checked'             => false,
        'paypalSuccessURL'           => null,
        'paypalCancelURL'            => null,
        'objectTaxInclusionInPayPal' => false,
    ]
);
$stripeConfig            = ( get_option( 'df_scc_stripe_keys' ) == '' ) ? [
    'pubKey'  => null,
    'privKey' => null,
] : get_option( 'df_scc_stripe_keys' );
$stripeConfig['enabled'] = $form->isStripeEnabled && ( $form->isStripeEnabled !== 'false' ) ? true : false;
$webhookConfigArray      = $isSCCFreeVersion ? [] : json_decode( $form->webhookSettings, true );
//handles error if webhook is not correct
if ( ! is_array( $webhookConfigArray ) ) {
    $webhookConfigArray = [];
}
$webhookQuote      = meks_wp_parse_args(
    $webhookConfigArray[0],
    [
        'scc_set_webhook_quote' => [
            'enabled' => false,
            'webhook' => '',
        ],
    ]
);
$webhookDetailView = meks_wp_parse_args(
    $webhookConfigArray[1],
    [
        'scc_set_webhook_detail_view' => [
            'enabled' => false,
            'webhook' => '',
        ],
    ]
);
// unset webhook endpoint if the user is not in wp-admin dashboard
if ( ! is_admin() ) {
    unset( $webhookQuote['scc_set_webhook_quote']['webhook'] );
    unset( $webhookDetailView['scc_set_webhook_detail_view']['webhook'] );
}
$webhookSettings = [
    $webhookQuote,
    $webhookDetailView,
];
/**
 * *Translates Preview
 * ?Form translate array if column is empty
 */
$translateArray = $form->translation;
$translateArray = json_decode( stripslashes( $translateArray ) );

if ( ! function_exists( 'getTranslatables' ) ) {
    function getTranslatables( $translateArray ) {
        $arrt = [];

        foreach ( $translateArray as $value ) {
            if ( $value->translation != '' ) {
                $a                = [];
                $a['key']         = $value->key;
                $a['translation'] = $value->translation;
                array_push( $arrt, $a );
            }
        }

        return $arrt;
    }
}
( $translateArray != null || $translateArray != '' ) ? $transletables = getTranslatables( $translateArray ) : $transletables = [];
$sccConfig                                                            = [
    'form_id'                       => $form->id,
    'formname'                      => $form->formname,
    'quoteFormFields'               => $formFieldsArray,
    //fonts
    'title'                         => [
        'color'       => $form->titleColorPicker,
        'size'        => $form->titleFontSize,
        'font_family' => $fontFamilyTitle2,
        'font_weight' => $form->titleFontWeight,
    ],
    'service'                       => [
        'color'        => $form->ServiceColorPicker,
        'size'         => $form->ServicefontSize,
        'font_familly' => $fontFamilyService2,
        'font_weight'  => $form->fontWeight,
    ],

    'fontConfig'                    => [
        'serviceFont'     => $fontFamilyService2,
        'titleFont'       => $fontFamilyTitle2,
        'googleFontLinks' => $google_font_links,
    ],
    'objectColor'                   => $form->objectColorPicker,
    'captcha'                       => [
        'isCaptchaEnabled' => false,
        'recaptchaSiteKey' => '',
    ],
    'pdf'                           => [
        'disableUnitColumn' => $form->turnoffUnit == 'true' ? true : false,
        'disableQtyColumn'  => $form->turnoffQty == 'true' ? true : false,
        'dateFormat'        => get_option( 'scc_pdf_datefmt', 'mm-dd-yyyy' ),
        'turnoffSave'       => $form->turnoffSave == 'true' ? true : false,
        'turnoffTax'        => $form->turnoffTax == 'true' ? true : false,
        'removeTitle'       => $form->removeTitle == 'true' ? true : false,
        'footer'            => wp_kses_post( get_option( 'df_scc_footerdisclaimer' ) ),
        'bannerImage'       => get_option( 'df_scc_email_banner_image', false ),
        'logo'              => get_option( 'df_scc_email_logo_image', false ),
        'isPremium'         => ! $isSCCFreeVersion,
        'isAdmin'           => is_admin(),
    ],
    'showFormInDetail'              => $form->ShowFormBuilderOnDetails,
    'paypalConfig'                  => $paypalConfigArray,
    'webhookConfig'                 => $webhookSettings,
    'addToCartRedirect'             => $form->addtoCheckout,
    'useCurrencyLetters'            => (bool) $form->symbol,
    'taxVat'                        => $form->taxVat,
    'currencyCode'                  => $currency,
    'currency_conversion_mode'      => $currency_conversion_mode,
    'currency_conversion_selection' => $currency_conversion_selection,
    'removeCurrency'                => $form->removeCurrency === 'true',
    'minimumTotal'                  => $form->minimumTotal,
    'minimumTotalChoose'            => $form->minimumTotalChoose,
    'sections'                      => $form->sections,
    'enableStripe'                  => $stripeConfig['enabled'] == 'true' ? true : false,
    'enableWoocommerceCheckout'     => $form->isWoocommerceCheckoutEnabled == 'true',
    'captcha'                       => [
        'enabled' => get_option( 'df_scc-captcha-enablement-status', false ),
        'siteKey' => get_option( 'df_scc-recaptcha-site-key', null ),
    ],
    'tseparator'                    => get_option( 'df_scc_currency_style' ),
    'translation'                   => $transletables,
    'stripePubKey'                  => $stripeConfig['pubKey'],
    'preCheckoutQuoteForm'          => $form->preCheckoutQuoteForm == 'true' ? true : false,
    'coupon'                        => '',
];
$calc_wrapper_max_width = isset( $form->wrapper_max_width ) ? $form->wrapper_max_width . 'px' : '1000px';
$wrapper_styles         = defined( 'DOING_AJAX' ) ? '' : "style=\"max-width:$calc_wrapper_max_width\"";
?>
<script id="scc-config-<?php echo intval( $form->id ); ?>" type="text/json">
	<?php echo json_encode( $sccConfig ); ?>
</script>
<script>
	// create data object and mount current calculator
	var formId = <?php echo intval( $form->id ); ?>;
	/**
	 * *Gives style to title section elements
	 */
	  </script>
 <div id=<?php echo 'scc_form_' . intval( $form->id ); ?> <?php echo $wrapper_styles; ?> class="calc-wrapper evaluation-copy">
	<?php
    /**
     * *Style for buttons in calculator
     * todo: add php variable to style classes
     */
    $sccButtonStyle    = 'printTable-45';
$colorfonts_button     = '#FFFFFF';

if ( $form->buttonStyle == 2 ) {
    $sccButtonStyle    = 'scc-btn-style-2';
    $colorfonts_button = $form->objectColorPicker;
}
$style_scc_calculator = null;

if ( $form->elementSkin == 'style_2' ) {
    $style2_add_container = $form->addContainer == 'true' ? 'scc-has-shadow' : false;
    $style_scc_calculator = 'form_field_item_style_2' . ' ' . $style2_add_container;
} else {
    $style_scc_calculator = 'form_fields_style_1';
}

foreach ( $form->sections as $index => $section ) {
    $accordion_index = $index . '-' . intval( $form->id );
    $hasAccordion    = $section->accordion == 'true' ? true : false;
    ?>
		<?php if ( $hasAccordion ) { ?>
			<div class="scc-accordion" id="section_<?php echo esc_attr( $accordion_index ); ?>" style="color:white !important;overflow: hidden;"><?php echo esc_attr( $section->name ); ?></div>
		<?php } ?>
		<?php if ( ! $hasAccordion ) { ?>
			<div class="scc-title-s" id="section_title_<?php echo $accordion_index; ?>"> <?php echo esc_attr( wp_unslash( $section->name ) ); ?></div>
			<div style="border: 1px solid #E8E8E8;"></div>
		<?php } ?>
		<div class="scc_font_45 description_section_preview <?php echo $hasAccordion ? 'scc-accordion-panel section_' . esc_attr( $accordion_index ) : ''; ?>">
			<?php echo wp_kses( wp_unslash( $section->description ), SCC_ALLOWTAGS ); ?>
		</div>
		<?php foreach ( $section->subsection as $sub ) { ?>
			<?php
        foreach ( $sub->element as $el ) {
            /**
             * apply custom column to the element if there are column value defined for mobile and desktop view
             */
            $applyCustomColumn = ( $form->elementSkin == 'style1' ) && ( $el->titleColumnDesktop > 0 && $el->titleColumnDesktop <= 12 ) && ( $el->titleColumnMobile > 0 && $el->titleColumnMobile <= 12 );

            if ( $applyCustomColumn ) {
                $firstColumnSize        = intval( $el->titleColumnDesktop );
                $secondColumnSize       = ( $firstColumnSize < 12 ) ? intval( 12 - $el->titleColumnDesktop ) : intval( $firstColumnSize );
                $firstMobileColumnSize  = intval( $el->titleColumnMobile );
                $secondMobileColumnSize = ( $firstMobileColumnSize < 12 ) ? intval( 12 - $el->titleColumnMobile ) : intval( $firstMobileColumnSize );
                $disableFlex            = $firstMobileColumnSize == 12 && $firstMobileColumnSize;
            }

            if ( $el->type == 'checkbox' ) {
                $numberOfColumns = empty( $el->value2 ) ? '1' : intval( $el->value2 );
                // patch against value more than 3
                if ( $numberOfColumns > 3 ) {
                    $numberOfColumns = 1;
                }

                if ( $numberOfColumns > 1 ) {
                    $applyCustomColumn = false;
                }
                ?>
					<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" data-typeElement="<?php echo esc_attr( $el->type ); ?>" class="scc-chkbx-container 
												 <?php
                                                if ( $numberOfColumns > 1 ) {
                                                    echo esc_attr( "scc-d-flex scc-chkbx-cols-$numberOfColumns" );
                                                } echo esc_attr( " use-$form->elementSkin " );
                echo $hasAccordion ? 'scc-accordion-panel section_' . esc_attr( $accordion_index ) : '';
                ?>
					" >
						<?php
                        switch ( $el->value1 ) {
                            case '1':
                                // Checkbox (circle) (Animated 1)
                                foreach ( $el->elementitems as $item ) {
                                    $item->opt_default = false;
                                    ?>
									<?php if ( $applyCustomColumn ) { ?>
										<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> clearfix" >
											<div class="scc-circle-checkbox-field-wrapper">
												<div class="scc_customradio-45 scc_customradio scc_customradio_style_1">
													<div class="scc-circle-checkbox-field-label <?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?>" style="
																										   <?php
                                                                                                            if ( $form->elementSkin == 'style1' ) {
                                                                                                                echo 'padding: 0;';
                                                                                                            }
									    ?>
										<?php
                                        if ( $form->elementSkin == 'style1' ) {
                                            echo 'width: 100%;';
                                        }
									    ?>
display:initial!important;"><?php echo esc_attr( wp_unslash( $item->name ) ); ?>
														<?php if ( $form->elementSkin !== 'style1' ) { ?>
															<span class="price-hint-text" style="display: none; margin-left: 1rem;"><?php echo floatval( $item->price ); ?></span>
														<?php } ?>
													</div>
													<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" type="checkbox" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="10" data-inputtype="switchoption" class="itemCreated" name="product1" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" >
													<label for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" class="ssc-circle-checkbox-label <?php echo 'scc-col-sm-' . intval( $secondMobileColumnSize ) . ' scc-col-md-' . intval( $secondColumnSize ) . ' scc-col-lg-' . intval( $secondColumnSize ) . ' scc-p-0'; ?>" style="float:left !important;margin: 0 0 0 4px" ></label>
													<?php if ( $form->elementSkin == 'style1' ) { ?>
														<span class="price-hint-text" style="display: none; margin-left: 1rem;"><?php echo floatval( $item->price ); ?></span>
													<?php } ?>
												</div>
											</div>
										</div>
									<?php } else { ?>   
										<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> clearfix" >
											<div class="scc-circle-checkbox-field-wrapper">
												<div class="scc_customradio-45 scc_customradio scc_customradio_style_1">
													<div class="scc-circle-checkbox-field-label 
													<?php
									                if ( $numberOfColumns == 1 ) {
									                    echo 'scc-col-md-4 scc-col-xs-10';
									                }
									    ?>
													" style="
										<?php
                                        if ( $form->elementSkin == 'style1' ) {
                                            echo 'padding: 0;';
                                        }
									    ?>

										<?php
									    if ( $form->elementSkin == 'style1' ) {
									        echo 'width: 100%;';
									    }
									    ?>
display:initial!important;"><?php echo esc_attr( wp_unslash( $item->name ) ); ?>
														<?php if ( $form->elementSkin !== 'style1' ) { ?>
															<span class="price-hint-text" style="display: none; margin-left: 1rem;"><?php echo floatval( $item->price ); ?></span>
														<?php } ?>
													</div>
													<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" type="checkbox" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="10" data-inputtype="switchoption" class="itemCreated" name="product1" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" >
													<label for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" class="ssc-circle-checkbox-label" style="float:left !important;margin: 0"></label>
													<?php if ( $form->elementSkin == 'style1' ) { ?>
														<span class="price-hint-text" style="display: none; margin-left: 1rem;"><?php echo floatval( $item->price ); ?></span>
													<?php } ?>
												</div>
											</div>
										</div>
									<?php } ?>
									<?php if ( $form->elementSkin == 'style1' ) { ?>
									<div style="clear: both;"></div>
									<?php } ?>
									<?php
                                }
                                break;

                            case '2':
                                // squared checkboxes "Checkbox (Animated)"
                                foreach ( $el->elementitems as $item ) {
                                    $item->opt_default = false;
                                    ?>
									<?php if ( $applyCustomColumn ) { ?>
									<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<label for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" class="label-cbx  label-cbx-45 scc-square-checkbox-field-wrapper" style="font-weight:400 !important; width:100%; padding:0px; padding-top:0px;">
											<span class="<?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?>" style="
																	<?php
                                                                    if ( $form->elementSkin == 'style_2' ) {
                                                                        echo 'width: 100%;';
                                                                    }
									    ?>
											padding:0px;padding-top:0px;float:left;"><?php echo esc_attr( wp_unslash( $item->name ) ); ?>
												<?php if ( $form->elementSkin !== 'style1' ) { ?>
												<span class="price-hint-text" style="display: none; margin-left: 1rem;float: right;"><?php echo floatval( $item->price ); ?></span>
												<?php } ?>
											</span>
											<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>"  type="checkbox" style="border: 0px solid !important;" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" name="12" datainputprice="100" data-inputtype="switchoption" class="itemCreated invisible" id="itemcreated_0_0_<?php echo intval( $item->id ); ?>" >
											<div class="checkbox " style=" margin-top:0px;margin-left: 0px !important">
												<svg width="20px" height="20px" viewBox="0 0 20 20">
													<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
													<polyline points="4 11 8 15 16 6"></polyline>
												</svg>
											</div>
											<?php if ( $form->elementSkin == 'style1' ) { ?>
												<span class="price-hint-text" style="display: none; margin-left: 1rem;float: right;"><?php echo floatval( $item->price ); ?></span>
											<?php } ?>
											<div style="clear: both;"></div>
										</label>
									</div>
									<?php } else { ?>
									<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<label for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" class="label-cbx  label-cbx-45 scc-square-checkbox-field-wrapper" style="font-weight:400 !important; width:100%; padding:0px; padding-top:0px;">
											<span class="scc-col-md-4 scc-col-xs-10" style="
											<?php
                                            if ( $form->elementSkin == 'style_2' ) {
                                                echo 'width: 100%;';
                                            }
									    ?>
											padding:0px;padding-top:0px;float:left;"><?php echo esc_attr( wp_unslash( $item->name ) ); ?>
												<?php if ( $form->elementSkin !== 'style1' ) { ?>
												<span class="price-hint-text" style="display: none; margin-left: 1rem;float: right;"><?php echo floatval( $item->price ); ?></span>
												<?php } ?>
											</span>
											<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>"  type="checkbox" style="border: 0px solid !important;" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" name="12" datainputprice="100" data-inputtype="switchoption" class="itemCreated invisible" id="itemcreated_0_0_<?php echo intval( $item->id ); ?>" >
											<div class="checkbox " style=" margin-top:0px;margin-left: 0px !important">
												<svg width="20px" height="20px" viewBox="0 0 20 20">
													<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
													<polyline points="4 11 8 15 16 6"></polyline>
												</svg>
											</div>
											<?php if ( $form->elementSkin == 'style1' ) { ?>
												<span class="price-hint-text" style="display: none; margin-left: 1rem;float: right;"><?php echo floatval( $item->price ); ?></span>
											<?php } ?>
											<div style="clear: both;"></div>
										</label>
									</div>
									<?php } ?>
									<div style="clear: both;"></div>
									<?php
                                }
                                break;

                            case '3':
                                // Toggle Switch (Rectangle)
                                foreach ( $el->elementitems as $item ) {
                                    $item->opt_default = false;

                                    if ( $applyCustomColumn ) {
                                        ?>
									<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<div class="can-toggle">
										<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" type="checkbox" data-toggle="toggle" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="100" data-inputtype="switchoption" class="itemCreated" name="name" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" >
											<label style="padding-top:7px;" class="<?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?> scc-form-field-item-label scc_font_45"><?php echo esc_attr( wp_unslash( $item->name ) ); ?>
											</label>
											<label class="scc-form-field-can-toggle-control" for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>">
												<div class="trn can-toggle__switch can-toggle__switch_45" data-checked="Yes" data-unchecked="No" style="border: 1px solid <?php echo esc_attr( $form->objectColorPicker ); ?>; background: <?php echo esc_attr( $form->objectColorPicker ); ?>; " data-trn-key="">
												</div>
												<span class="price-hint-text" style="display: none; margin-left: 1rem; white-space: nowrap;"><?php echo floatval( $item->price ); ?></span>
											</label>
										</div>
									</div>
									<?php } else { ?>
									<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<div class="can-toggle">
										<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" type="checkbox" data-toggle="toggle" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="100" data-inputtype="switchoption" class="itemCreated" name="name" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" >
											<label style="padding-top:7px;
											<?php
                                            if ( $form->elementSkin == 'style_2' ) {
                                                echo 'width: 100%;';
                                            }
									    ?>
											" class="scc-col-md-4 scc-col-xs-10 scc-form-field-item-label scc_font_45"><?php echo esc_attr( wp_unslash( $item->name ) ); ?>
											<?php if ( $form->elementSkin !== 'style1' ) { ?>
												<span class="price-hint-text" style="display: none; margin-left: 65%; white-space: nowrap;"><?php echo floatval( $item->price ); ?></span>
											<?php } ?>
											</label>
											<label class="scc-form-field-can-toggle-control" for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>">
												<div class="trn can-toggle__switch can-toggle__switch_45" data-checked="Yes" data-unchecked="No" style="border: 1px solid <?php echo esc_attr( $form->objectColorPicker ); ?>; background: <?php echo esc_attr( $form->objectColorPicker ); ?>; " data-trn-key="">
												</div>
												<?php if ( $form->elementSkin == 'style1' ) { ?>
													<span class="price-hint-text" style="display: none; margin-left: 1rem; white-space: nowrap;"><?php echo floatval( $item->price ); ?></span>
												<?php } ?>
											</label>
										</div>
									</div>
									<?php } ?>
									<div style="clear: both;"></div>
									<?php
                                }
                                break;

                            case '4':
                                // Toggle Switch (Circle)
                                foreach ( $el->elementitems as $item ) {
                                    $item->opt_default = false;

                                    if ( $applyCustomColumn ) {
                                        ?>
									<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<div class="can-toggle demo-rebrand-2">
										<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" type="checkbox" data-toggle="toggle" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="100" data-inputtype="switchoption" class="itemCreated" name="retgergt" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" >
											<label sytle="padding-top:10px;" class="<?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?> scc-form-field-item-label control-label scc_font_45">
												<?php echo esc_attr( wp_unslash( $item->name ) ); ?>
											</label>
											<label class="scc-form-field-can-toggle-control" for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>">
												<div class="can-toggle__switch can-toggle__switch_45 trn" data-checked="Yes" data-unchecked="No" style="margin-left:15px; border: 1px solid <?php echo esc_attr( $form->objectColorPicker ); ?>; background: <?php echo esc_attr( $form->objectColorPicker ); ?>; " data-trn-key="">
												</div>
												<span class="price-hint-text" style="display: none; margin-left: 1rem; white-space: nowrap;"><?php echo floatval( $item->price ); ?></span>
											</label>
											<div style="clear: both;"></div>
										</div>
										<div style="clear: both;"></div>
									</div>
									<?php } else { ?>
									<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<div class="can-toggle demo-rebrand-2">
										<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" type="checkbox" data-toggle="toggle" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="100" data-inputtype="switchoption" class="itemCreated" name="retgergt" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" >
											<label sytle="padding-top:10px;
											<?php
                                            if ( $form->elementSkin == 'style_2' ) {
                                                echo 'width: 100%;';
                                            }
									    ?>
											" class="scc-col-md-4 scc-col-xs-10 scc-form-field-item-label control-label scc_font_45">
												<?php echo esc_attr( wp_unslash( $item->name ) ); ?>
												<?php if ( $form->elementSkin !== 'style1' ) { ?>
													<span class="price-hint-text" style="display: none; margin-left: 65%; white-space: nowrap;"><?php echo floatval( $item->price ); ?></span>
												<?php } ?>
											</label>
											<label class="scc-form-field-can-toggle-control" for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>">
												<div class="can-toggle__switch can-toggle__switch_45 trn" data-checked="Yes" data-unchecked="No" style="margin-left:15px; border: 1px solid <?php echo esc_attr( $form->objectColorPicker ); ?>; background: <?php echo esc_attr( $form->objectColorPicker ); ?>; " data-trn-key="">
												</div>
												<?php if ( $form->elementSkin == 'style1' ) { ?>
												<span class="price-hint-text" style="display: none; margin-left: 1rem; white-space: nowrap;"><?php echo floatval( $item->price ); ?></span>
												<?php } ?>
											</label>
											<div style="clear: both;"></div>
										</div>
										<div style="clear: both;"></div>
									</div>
									<?php } ?>
									<?php
                                }
                                break;

                            case '5':
                                // Checkbox (circle) (Animated 2)
                                foreach ( $el->elementitems as $item ) {
                                    $item->opt_default = false;

                                    if ( $applyCustomColumn ) {
                                        ?>
									<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<label class="btn-radio btn-radio-45" style="font-weight: 400; width:100%">
											<span class="<?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?> scc-form-left-radio-label" style="
																	<?php
                                                                    if ( $form->elementSkin == 'style_2' ) {
                                                                        echo 'width: 100%;';
                                                                    }
                                        ?>
											margin:0px;padding:0px;margin-top:0px;line-height:20px;vertical-align:middle;">
												<?php echo esc_attr( wp_unslash( $item->name ) ); ?>
											</span>
											<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" type="checkbox" style="border: 0px solid !important;" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="100" data-inputtype="switchoption" class="itemCreated" name="4554"  data-selected="true">
											<svg width="20px" height="20px" viewBox="0 0 20 20" class="btn-radio-span">
												<circle cx="10" cy="10" r="9"></circle>
												<path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path>
												<path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path>
											</svg>
											<span class="price-hint-text" style="display: none; margin-left: 1rem;"><?php echo floatval( $item->price ); ?></span>
										</label>
										<div style="clear: both;"></div>
									</div>
									<?php } else { ?>
									<div class="scc-form-field-item df-scc-checkbox clearfix <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<label class="btn-radio btn-radio-45" style="font-weight: 400; width:100%">
											<span class="
											<?php
                                            if ( ! ( $numberOfColumns > 1 ) ) {
                                                echo 'scc-col-md-4 scc-col-xs-10';
                                            }
									    ?>
											 scc-form-left-radio-label" style="
											<?php
									    if ( $form->elementSkin == 'style_2' ) {
									        echo 'width: 100%;';
									    }
									    ?>
margin:0px;padding:0px;margin-top:0px;line-height:20px;vertical-align:middle;">
												<?php echo esc_attr( wp_unslash( $item->name ) ); ?>
												<?php if ( $form->elementSkin !== 'style1' ) { ?>
													<span class="price-hint-text" style="display: none; margin-left: 1rem;"><?php echo floatval( $item->price ); ?></span>
												<?php } ?>
											</span>
											<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" type="checkbox" style="border: 0px solid !important;" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="100" data-inputtype="switchoption" class="itemCreated" name="4554"  data-selected="true">
											<svg width="20px" height="20px" viewBox="0 0 20 20" class="btn-radio-span">
												<circle cx="10" cy="10" r="9"></circle>
												<path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path>
												<path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path>
											</svg>
											<?php if ( $form->elementSkin == 'style1' ) { ?>
											<span class="price-hint-text" style="display: none; margin-left: 1rem;"><?php echo floatval( $item->price ); ?></span>
											<?php } ?>
										</label>
									</div>
									<?php } ?>
									<?php
                                }
                                break;

                            case '6':
                                ?>
								<div class="scc-simple-button-container ">
								<?php
                                foreach ( $el->elementitems as $item ) {
                                    $item->opt_default = false;
                                    ?>
										<!--SIMPLE BUTTONS-->
										<div class="simple_button control-label scc-form-field-item scc_add_btn_secn <?php echo esc_attr( $style_scc_calculator ) . '_button'; ?> " style="margin-top: unset;" >
											<div style="position: relative">
												<span class="price-hint-text" style="display: none; position: absolute; bottom: 20px; font-weight: 800;width: 55px;"><?php echo floatval( $item->price ); ?></span>
											</div>
											<label class="checkbox-inline checkbox-inline-45 " style="cursor:pointer;border: 1px solid <?php echo esc_attr( $colorObject ); ?>; font-size: 16px;color:<?php echo esc_attr( $colorObject ); ?>;user-select:none">
											<input data-checkboxtype="<?php echo esc_attr( $el->value1 ); ?>" data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" type="checkbox" class="itemCreated scc_button_section" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" onclick="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" onchange="changeButtonToogle(this)" data-inputtype="switchoption" datainputprice="100" name="asddas" data-show-pricehint="true" ><?php echo esc_attr( wp_unslash( $item->name ) ); ?></label>
											</div>
										<?php
                                }
                                ?>
									</div><!-- button container close -->
									<?php
                                break;

                            case '7':
                                // Radio (Single Choice)
                                foreach ( $el->elementitems as $item ) {
                                    $item->opt_default = false;

                                    if ( $applyCustomColumn ) {
                                        ?>
									<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<label class="btn-radio btn-radio-45" style="font-weight: 400; width:100%" for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>">
											<span class="<?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?> scc-form-left-radio-label" style="margin:0px;padding:0px;margin-top:0px;"><?php echo esc_attr( wp_unslash( $item->name ) ); ?><span class="price-hint-text" style="display: none; margin-left: 1rem;"><?php echo floatval( $item->price ); ?></span></span>
											<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" radio-item-number="<?php echo intval( $item->id ); ?>" type="radio" data-radio-type="single" style="border: 0px solid !important;" onclick="triggerSubmit(6,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="100" data-inputtype="switchoption" class="itemCreated" name="<?php echo esc_attr( $el->uniqueId ); ?>" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" >
											<svg width="20px" height="20px" viewBox="0 0 20 20" class="btn-radio-span">
												<circle cx="10" cy="10" r="9"></circle>
												<path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path>
												<path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path>
											</svg>
										</label>
										<div style="clear: both;"></div>
									</div>
									<?php } else { ?>
									<div class="scc-form-field-item df-scc-checkbox <?php echo esc_attr( $style_scc_calculator ); ?> " >
										<label class="btn-radio btn-radio-45" style="font-weight: 400; width:100%" for="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>">
											<span class="scc-col-md-4 scc-col-xs-10 scc-form-left-radio-label" style="margin:0px;padding:0px;margin-top:0px;"><?php echo esc_attr( wp_unslash( $item->name ) ); ?><span class="price-hint-text" style="display: none; margin-left: 1rem;"><?php echo floatval( $item->price ); ?></span></span>
											<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" radio-item-number="<?php echo intval( $item->id ); ?>" type="radio" data-radio-type="single" style="border: 0px solid !important;" onclick="triggerSubmit(6,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>);pricehint(<?php echo intval( $form->id ); ?>,this,<?php echo esc_attr( $el->showPriceHint ); ?>)" datainputprice="100" data-inputtype="switchoption" class="itemCreated" name="<?php echo esc_attr( $el->uniqueId ); ?>" id="itemcreated_0_0_0_0_<?php echo intval( $item->id ); ?>" >
											<svg width="20px" height="20px" viewBox="0 0 20 20" class="btn-radio-span">
												<circle cx="10" cy="10" r="9"></circle>
												<path d="M10,7 C8.34314575,7 7,8.34314575 7,10 C7,11.6568542 8.34314575,13 10,13 C11.6568542,13 13,11.6568542 13,10 C13,8.34314575 11.6568542,7 10,7 Z" class="inner"></path>
												<path d="M10,1 L10,1 L10,1 C14.9705627,1 19,5.02943725 19,10 L19,10 L19,10 C19,14.9705627 14.9705627,19 10,19 L10,19 L10,19 C5.02943725,19 1,14.9705627 1,10 L1,10 L1,10 C1,5.02943725 5.02943725,1 10,1 L10,1 Z" class="outer"></path>
											</svg>
										</label>
										<div style="clear: both;"></div>
									</div>
									<?php } ?>
									<div style="clear: both;"></div>
										<?php
                                }
                                break;

                            case '8':
                                break;
                                ?>
									<div class="df-scc-row df-scc-image-buttons" style="margin-top:20px">
									<?php
                                        foreach ( $el->elementitems as $item ) {
                                            $item->opt_default = false;
                                            ?>
										<div class="scc-col-sm-6 scc-col-md-2 scc-col-lg-4 text-center" style="">
										<input data-optdefault="<?php echo esc_attr( $item->opt_default ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-itemid="<?php echo intval( $item->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" radio-item-number="<?php echo intval( $item->id ); ?>" onchange="triggerSubmit(1,this,<?php echo intval( $el->id ); ?>, '<?php echo 'chkbx-' . intval( $item->id ); ?>', <?php echo intval( $form->id ); ?>)" type="checkbox" id="btn_image_<?php echo intval( $item->id ); ?>" />
										<label class="item-container 
										<?php
                                            if ( $el->value4 == 'true' ) {
                                                echo ' img-bordered';
                                            }
                                            ?>
										" for="btn_image_<?php echo intval( $item->id ); ?>">
												<img src="<?php echo empty( $item->value1 ) ? esc_url( SCC_ASSETS_URL ) . '/images/image.png' : esc_attr( $item->value1 ); ?>"/>
												<span class="item-name"><?php echo esc_attr( wp_unslash( $item->name ) ); ?></span>
											</label>
										</div>
									<?php } ?>
									</div>
									<?php
                                    break;
                        }
                ?>
						</div>
						<?php echo scc_frontend_alerts( 'mandatory-element' ); ?>
						<div style="clear:both"></div>
						<?php
            }

            if ( $el->type == 'Dropdown Menu' ) {
                ?>
					<?php if ( $applyCustomColumn ) { ?>   
							<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" data-typeElement="<?php echo esc_attr( $el->type ); ?>" class="clearfix has-custom-columns scc_dropdown_ 
														 <?php
                                                        echo esc_attr( $style_scc_calculator );

					    if ( $hasAccordion ) {
					        echo ' scc-accordion-panel section_' . esc_attr( $accordion_index );
					    }

					    if ( $disableFlex ) {
					        echo ' scc-d-block';
					    }
					    ?>
							">
							<label class="scc-form-field-item-label <?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?>"><?php echo wp_kses( $el->titleElement, SCC_ALLOWTAGS ); ?></label>
							<!-- <div id="byjson<?php echo intval( $el->id ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" class="<?php echo 'scc-col-sm-' . intval( $secondMobileColumnSize ) . ' scc-col-md-' . intval( $secondColumnSize ) . ' scc-col-lg-' . intval( $secondColumnSize ) . ' scc-p-0'; ?>"></div> -->
							<select id="byjson<?php echo intval( $el->id ); ?>" class="form-select-tom <?php echo "scc-col-sm-$secondMobileColumnSize scc-col-md-$secondColumnSize scc-col-lg-$secondColumnSize scc-p-0"; ?>" data-formid="<?php echo intval( $form->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" data-placeholder="Choose an option...">
								<option value="null" class="scc-tom-select-placeholder">Choose an option...</option>
								<?php for ( $i = 0; $i < count( $el->elementitems ); $i++ ) { ?>
									<option 
										data-title="<?php echo wp_kses( $el->elementitems[ $i ]->name, SCC_ALLOWTAGS ); ?>"
										<?php
                                        if ( intval( $el->elementitems[ $i ]->opt_default ) == 1 ) {
                                            echo 'selected';
                                        }
								    ?>
										<?php if ( filter_var( $el->elementitems[ $i ]->value1, FILTER_VALIDATE_URL ) !== false ) { ?>
										data-src="<?php echo esc_url( $el->elementitems[ $i ]->value1 ); ?>"
										<?php } ?>
										value="<?php echo esc_attr( $el->elementitems[ $i ]->id ); ?>">
											<?php echo wp_kses( $el->elementitems[ $i ]->name, SCC_ALLOWTAGS ); ?>
													   <?php
								                    echo stripslashes( esc_attr( 'scc-dropdown-opt-content' ) );
								    echo stripslashes( esc_attr( $el->elementitems[ $i ]->description ) );
								    ?>
									</option>
									<?php
								}
					    ?>
								
							</select>
						<?php } else { ?>
							<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" data-typeElement="<?php echo esc_attr( $el->type ); ?>" class="scc_dropdown_ 
														 <?php
					                                echo esc_attr( $style_scc_calculator );

						    if ( $hasAccordion ) {
						        echo ' scc-accordion-panel section_' . esc_attr( $accordion_index );
						    }
						    ?>
							">
							<label class="scc-form-field-item-label"><?php echo wp_kses( wp_unslash( $el->titleElement ), SCC_ALLOWTAGS ); ?></label>
							<select id="byjson<?php echo intval( $el->id ); ?>" class="form-select-tom" data-formid="<?php echo intval( $form->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" data-placeholder="Choose an option...">
								<option value="null" class="scc-tom-select-placeholder scc-pick-an-option">Choose an option...</option>
								<?php
                                for ( $i = 0; $i < count( $el->elementitems ); $i++ ) {
                                    //print_r($el->elementitems[$i]);
                                    ?>
									<option 
										data-title="<?php echo  wp_kses( $el->elementitems[ $i ]->name, SCC_ALLOWTAGS ); ?>"
										<?php
                                        if ( intval( $el->elementitems[ $i ]->opt_default ) == 1 ) {
                                            echo 'selected';
                                        }
                                    ?>
										data-src="<?php echo esc_url( $el->elementitems[ $i ]->value1 ); ?>" value="<?php echo esc_attr( $el->elementitems[ $i ]->id ); ?>"><?php echo wp_kses( $el->elementitems[ $i ]->name, SCC_ALLOWTAGS ); ?>
									<?php
                                    echo stripslashes( esc_attr( 'scc-dropdown-opt-content' ) );
                                    echo stripslashes( esc_attr( $el->elementitems[ $i ]->description ) );
                                    ?>
									</option>
									<?php
                                }
						    ?>
							</select>
						<?php } ?>
						<script id="json" type="application/json">
						<?php echo json_encode( $el->elementitems ); ?>
						</script>
					</div>
					<?php echo scc_frontend_alerts( 'mandatory-element' ); ?>
					<?php
            }

            if ( $el->type == 'slider' ) {
                /**
                 * *Populates slider elements
                 * !Bootstrap-slider needs to be charged
                 * todo: Needs range values
                 */
                $show_on_detailed_list = $el->displayDetailList;
                $calculation_type      = $el->value1;
                $range                 = null;

                foreach ( $el->elementitems as $key => $ei ) {
                    $coma = '';

                    if ( count( $el->elementitems ) != $key + 1 ) {
                        $coma = ',';
                    }

                    if ( in_array( $calculation_type, [ 'default', 'quantity_mod' ] ) ) {
                        if ( $key === 0 ) {
                            $coma = '';
                        }

                        if ( $key > 0 ) {
                            continue;
                        }
                    }
                    $range .= $ei->value1 . ',' . $ei->value2 . ',' . $ei->value3 . $coma;
                }
                $range_ex      = explode( ',', $range );
                $min           = $range_ex[0];
                $max           = $range_ex[ count( $range_ex ) - 2 ];
                $showPriceHint = 'false';
                $max           = $range_ex[ count( $range_ex ) - 2 ];

                if ( $el->showPriceHint == '1' ) {
                    $showPriceHint = 'true';
                }
                // var_dump($range_ex)
                ?>
					<?php if ( $applyCustomColumn ) { ?>
					<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" data-typeElement="<?php echo esc_attr( $el->type ); ?>" class="clearfix scc_sliderr__ scc-form-field-item 
												 <?php
                                                echo esc_attr( $style_scc_calculator );

					    if ( $hasAccordion ) {
					        echo ' scc-accordion-panel section_' . $accordion_index;
					    }

					    if ( $disableFlex ) {
					        echo ' scc-d-block clearfix';
					    }
					    ?>
					 slider_in_mobile  scc_font_45">
						<label class="<?php echo "scc-col-sm-$firstMobileColumnSize scc-col-md-$firstColumnSize scc-col-lg-$firstColumnSize scc-p-0"; ?> control-label scc_font_45 scc-form-field-item-label"><?php echo stripslashes( wp_kses( $el->titleElement, SCC_ALLOWTAGS ) ); ?></label>
							<div class="<?php echo "scc-col-sm-$secondMobileColumnSize scc-col-md-$secondColumnSize scc-col-lg-$secondColumnSize scc-p-0"; ?> slider-styled scc_sld_cntrl scc-form-field-item-control" data-slider-step="<?php echo esc_attr( $el->value2 ); ?>" data-start-value="<?php echo esc_attr( $el->value3 ); ?>" data-slider-min="<?php echo esc_attr( $min ); ?>" data-slider-max="<?php echo esc_attr( $max ); ?>" data_range="<?php echo esc_attr( $range ); ?>" data-currency="<?php echo esc_attr( $currency ); ?>" data-symbol="<?php echo esc_attr( $form->symbol ); ?>" data-show-pricehint="<?php echo esc_attr( $showPriceHint ); ?>" data-slider-id="slider_itemcreateds_0_0-<?php echo intval( $el->id ); ?>" data-show-on-detailed-list="<?php echo esc_attr( $show_on_detailed_list ); ?>" data-calculation-type="<?php echo esc_attr( $calculation_type ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-subId="<?php echo intval( $sub->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" id="itemcreateds_0_0_<?php echo intval( $el->id ); ?>"></div>
					</div><!-- slider close -->
					<?php } else { ?>
					<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" data-typeElement="<?php echo esc_attr( $el->type ); ?>"  class="clearfix scc_sliderr__ scc-form-field-item 
												 <?php
					    echo esc_attr( $style_scc_calculator );
					    echo $hasAccordion ? ' scc-accordion-panel section_' . esc_attr( $accordion_index ) : '';
					    ?>
					 slider_in_mobile  scc_font_45">
						<?php if ( $form->elementSkin !== 'style_2' ) { ?>
							<label class="scc-col-md-4 scc-col-xs-12 control-label scc_font_45 scc-form-field-item-label"><?php echo stripslashes( wp_kses( $el->titleElement, SCC_ALLOWTAGS ) ); ?></label>
							<div class="scc-col-md-8 scc-col-xs-12 scc-p-0 slider-styled" data-slider-step="<?php echo esc_attr( $el->value2 ); ?>" data-start-value="<?php echo esc_attr( $el->value3 ); ?>" data-slider-min="<?php echo esc_attr( $min ); ?>" data-slider-max="<?php echo esc_attr( $max ); ?>" data_range="<?php echo esc_attr( $range ); ?>" data-currency="<?php echo esc_attr( $currency ); ?>" data-symbol="<?php echo esc_attr( $form->symbol ); ?>" data-show-pricehint="<?php echo esc_attr( $showPriceHint ); ?>" data-slider-id="slider_itemcreateds_0_0-<?php echo intval( $el->id ); ?>" data-show-on-detailed-list="<?php echo esc_attr( $show_on_detailed_list ); ?>" data-calculation-type="<?php echo esc_attr( $calculation_type ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-subId="<?php echo intval( $sub->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" id="itemcreateds_0_0_<?php echo intval( $el->id ); ?>"></div>
						<?php } ?>
						<?php if ( $form->elementSkin == 'style_2' ) { ?>
							<label class="scc-col-xs-12 control-label scc_font_45 scc-form-field-item-label"><?php echo stripslashes( wp_kses( $el->titleElement, SCC_ALLOWTAGS ) ); ?></label>
							<div class="scc-form-field-item scc-slider-with-input">
								<div class="scc-col-md-12 scc-col-xs-12 scc-p-0 slider-styled" data-slider-step="<?php echo esc_attr( $el->value2 ); ?>" data-start-value="<?php echo esc_attr( $el->value3 ); ?>" data-slider-min="<?php echo esc_attr( $min ); ?>" data-slider-max="<?php echo esc_attr( $max ); ?>" data_range="<?php echo esc_attr( $range ); ?>" data-currency="<?php echo esc_attr( $currency ); ?>" data-symbol="<?php echo esc_attr( $form->symbol ); ?>" data-show-pricehint="<?php echo esc_attr( $showPriceHint ); ?>" data-slider-id="slider_itemcreateds_0_0-<?php echo intval( $el->id ); ?>" data-show-on-detailed-list="<?php echo esc_attr( $show_on_detailed_list ); ?>" data-calculation-type="<?php echo esc_attr( $calculation_type ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-subId="<?php echo intval( $sub->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" id="itemcreateds_0_0_<?php echo intval( $el->id ); ?>"></div>
							</div>
						<?php } ?>
					</div><!-- slider close -->
					<?php } ?>
					<?php echo scc_frontend_alerts( 'mandatory-element' ); ?>
					<?php
            }

            if ( $el->type == 'comment box' ) {
                ?>
					<?php if ( $applyCustomColumn ) { ?>
					<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" class="scc-form-field-item 
												 <?php
                                                echo esc_attr( $style_scc_calculator );
					    echo $hasAccordion ? ' scc-accordion-panel section_' . esc_attr( $accordion_index ) : '';

					    if ( $disableFlex ) {
					        echo ' scc-d-block clearfix';
					    }
					    ?>
						 ">
						<label class="<?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?> scc_font_45 scc-form-field-item-label" for="selectbasic"><?php echo wp_kses( wp_unslash( $el->titleElement ), SCC_ALLOWTAGS ); ?></label>
						<div class="control-label <?php echo 'scc-col-sm-' . intval( $secondMobileColumnSize ) . ' scc-col-md-' . intval( $secondColumnSize ) . ' scc-col-lg-' . intval( $secondColumnSize ) . ' scc-p-0'; ?> scc_select_opt scc-form-field-item-control" style="padding:0px">
							<?php if ( $el->value2 <= 1 ) { ?>
								<input id="itemcreateds_0_0_0_<?php echo intval( $el->id ); ?>" class="comment_box_text itemCreated mandatory_no_45" data-inputtype="comment_input" onchange="triggerSubmit(7, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)" style="width:100%;border-radius:18px;border: #E8E8E8 2px solid!important; padding:12px" placeholder="<?php echo esc_attr( $el->value3 ); ?>">
							<?php } else { ?>
								<textarea id="itemcreateds_0_0_0_45" class="comment_box_text itemCreated mandatory_no_45" data-inputtype="comment_input" onchange="triggerSubmit(7, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)" style="width:100%;border-radius:12px;border: #E8E8E8 2px solid!important; padding:12px" placeholder="<?php echo esc_attr( $el->value3 ); ?>" rows="<?php echo esc_attr( $el->value2 ); ?>"></textarea>
							<?php } ?>
						</div>
						<div style="clear: both;"></div>
					</div>
					<?php } else { ?>
					<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" class="scc-form-field-item 
												 <?php
					    echo esc_attr( $style_scc_calculator );
					    echo $hasAccordion ? ' scc-accordion-panel section_' . esc_attr( $accordion_index ) : '';
					    ?>
					 ">
					<label class="scc-col-md-4 scc-col-lg-4 scc-col-xs-12 scc_font_45 scc-form-field-item-label" for="selectbasic"><?php echo wp_kses( wp_unslash( $el->titleElement ), SCC_ALLOWTAGS ); ?></label>
					<div class="control-label scc-col-md-8 scc-col-lg-8 scc-col-xs-12 scc_select_opt scc-form-field-item-control" style="padding:0px">
						<?php if ( $el->value2 <= 1 ) { ?>
							<input id="itemcreateds_0_0_0_<?php echo intval( $el->id ); ?>" class="comment_box_text itemCreated mandatory_no_45" data-inputtype="comment_input" onchange="triggerSubmit(7, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)" style="width:100%;border-radius:18px;border: #E8E8E8 2px solid!important; padding:12px" placeholder="<?php echo esc_attr( $el->value3 ); ?>">
						<?php } else { ?>
							<textarea id="itemcreateds_0_0_0_45" class="comment_box_text itemCreated mandatory_no_45" data-inputtype="comment_input" onchange="triggerSubmit(7, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)" style="width:100%;border-radius:12px;border: #E8E8E8 2px solid!important; padding:12px" placeholder="<?php echo esc_attr( $el->value3 ); ?>" rows="<?php echo esc_attr( $el->value2 ); ?>"></textarea>
						<?php } ?>
					</div>
					<div style="clear: both;"></div>
				</div>
				<?php } ?>
				<?php echo scc_frontend_alerts( 'mandatory-element' ); ?>
					<?php
            }

            if ( $el->type == 'quantity box' ) {
                $input_default_value = empty( $el->value4 ) ? 'value=' . 0 : 'value=' . intval( $el->value4 );
                $enable_commas       = ( isset( $el->value5 ) && $el->value5 == 2 ) ? true : false;
                ?>
				<!-- NEEDS ATTENTION -->
					<?php if ( $applyCustomColumn ) { ?>
				<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" class="scc-form-field-item 
											 <?php
                                            echo esc_attr( $style_scc_calculator );
					    echo $hasAccordion ? ' scc-accordion-panel section_' . esc_attr( $accordion_index ) : '';

					    if ( $disableFlex ) {
					        echo ' scc-d-block clearfix';
					    }
					    ?>
					">
					<label class="<?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?> scc-form-field-item-label scc_font_45" for="selectbasic"><?php echo wp_kses( wp_unslash( $el->titleElement ), SCC_ALLOWTAGS ); ?> </label>
					<div class="control-label <?php echo 'scc-col-sm-' . intval( $secondMobileColumnSize ) . ' scc-col-md-' . intval( $secondColumnSize ) . ' scc-col-lg-' . intval( $secondColumnSize ) . ' scc-p-0'; ?> scc_select_opt scc-form-field-item-control" style="padding:0px;">
						<?php if ( $el->value1 == 'default' ) { ?>
							<input id="itemcreateds_0_0_0" <?php if ( $el->value4 && intval( $el->value4 ) > 0 ) { ?>
										onblur="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)" 
									<?php } else { ?>
									onkeyup="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
									onchange="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
									<?php } ?>
									<?php if ( $enable_commas ) { ?>
										oninput="addCommas(this)"
									<?php } ?>
									class="number_box_text number_box_text-45 itemCreated mandatory_no_45"
									data-inputtype="number_input"
									type="<?php echo $enable_commas ? 'text' : 'number'; ?>"
									data-value="1" 
							 <?php echo esc_attr( $input_default_value ); ?>>
>
						<?php } else { ?>
							<span class="input-number-decrement" onclick="changeNumberQuantity(this,'-',<?php echo intval( $el->id ); ?>,<?php echo intval( $form->id ); ?>)">–</span>
							<input id="itemcreateds_0_0_0"
									<?php if ( $el->value4 && intval( $el->value4 ) > 0 ) { ?>
										onblur="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
									<?php } else { ?>
										onkeyup="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
										onchange="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
									<?php } ?>
									<?php if ( $enable_commas ) { ?>
										oninput="addCommas(this)"
										data-enable-commas="1"
									<?php } ?>
									class="number_box_2_text number_box_text-45 itemCreated mandatory_no_45"
									data-inputtype="number_input"
									type="<?php echo $enable_commas ? 'text' : 'number'; ?>"
									data-value="4"
								<?php echo esc_attr( $input_default_value ); ?>
>
							<span class="input-number-increment" onclick="changeNumberQuantity(this,'+',<?php echo intval( $el->id ); ?>,<?php echo intval( $form->id ); ?>)">+</span>
						<?php } ?>
					</div>
					<div style="clear: both;"></div>
				</div>
				<?php } else { ?>
				<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" class="scc-form-field-item 
											 <?php
					    echo esc_attr( $style_scc_calculator );
				    echo $hasAccordion ? ' scc-accordion-panel section_' . esc_attr( $accordion_index ) : '';
				    ?>
				">
				<label class="scc-col-md-4 scc-col-lg-4 scc-col-xs-6 scc-form-field-item-label scc_font_45" for="selectbasic"><?php echo wp_kses( wp_unslash( $el->titleElement ), SCC_ALLOWTAGS ); ?> </label>
				<div class="control-label scc-col-md-8 scc-col-lg-8 scc-col-xs-6 scc_select_opt scc-form-field-item-control" style="padding:0px;">
						<?php if ( $el->value1 == 'default' ) { ?>
							<input id="itemcreateds_0_0_0" <?php if ( $el->value4 && intval( $el->value4 ) > 0 ) { ?>
										onblur="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)" 
									<?php } else { ?>
									onkeyup="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
									onchange="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
									<?php } ?>
									<?php if ( $enable_commas ) { ?>
										oninput="addCommas(this)"
									<?php } ?>
									class="number_box_text number_box_text-45 itemCreated mandatory_no_45"
									data-inputtype="number_input"
									type="<?php echo $enable_commas ? 'text' : 'number'; ?>"
									data-value="1" 
							<?php echo esc_attr( $input_default_value ); ?>
>
					<?php } else { ?>
						<span class="input-number-decrement" onclick="changeNumberQuantity(this,'-',<?php echo intval( $el->id ); ?>,<?php echo intval( $form->id ); ?>)">–</span>
						<input id="itemcreateds_0_0_0"
									<?php if ( $el->value4 && intval( $el->value4 ) > 0 ) { ?>
										onblur="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
									<?php } else { ?>
										onkeyup="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
										onchange="triggerSubmit(3, this, <?php echo intval( $el->id ); ?>, 0, <?php echo intval( $form->id ); ?>)"
									<?php } ?>
									<?php if ( $enable_commas ) { ?>
										oninput="addCommas(this)"
										data-enable-commas="1"
									<?php } ?>
									class="number_box_2_text number_box_text-45 itemCreated mandatory_no_45"
									data-inputtype="number_input"
									type="<?php echo $enable_commas ? 'text' : 'number'; ?>"
									data-value="4"
							<?php echo esc_attr( $input_default_value ); ?>
>
						<span class="input-number-increment" onclick="changeNumberQuantity(this,'+',<?php echo intval( $el->id ); ?>,<?php echo intval( $form->id ); ?>)">+</span>
					<?php } ?>
				</div>
				<div style="clear: both;"></div>
			</div>
			<?php } ?>
			<?php echo scc_frontend_alerts( 'mandatory-element' ); ?>
					<?php
            }

            if ( $el->type == 'custom math' ) {
                continue;

                if ( $el->displayFrontend == 1 ) {
                    ?>
					<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" data-typeElement="<?php echo esc_attr( $el->type ); ?>" data-formid="<?php echo intval( $form->id ); ?>" data-elementid="<?php echo intval( $el->id ); ?>" data-show-detailed-list="true" class="scc-form-field-item 
												 <?php
                                                echo esc_attr( $style_scc_calculator );
                    echo $hasAccordion ? ' scc-accordion-panel section_' . esc_attr( $accordion_index ) : '';
                    ?>
					 ssc_custom_math__" style="
						<?php
                        if ( $el->displayFrontend != 1 ) {
                            echo 'visibility:hidden;height:0;margin-top:0';
                        }
                    ?>
					">
					<div class="scc-custom-math-element control-label scc-col-md-12 scc-col-lg-12 scc-col-xs-12 scc_select_opt scc-form-field-item-control">
						<label style="font-weight: 300;" data-name="custom math" id="itemcreateds_0_0_0" class="itemCreated" data-inputtype="scc_custom_math" data-math-type="+" data-value="1"  data-math-uniqueid="e05h2c">
									<?php echo wp_kses( $el->titleElement, SCC_ALLOWTAGS ); ?>: <?php echo esc_attr( $el->value1 ); ?> <span class="scc_custom_math_value_front_end"><?php echo esc_attr( $el->value2 ); ?></span> </label>
					</div>
					<div style="clear: both;"></div>
					</div>
					<?php echo scc_frontend_alerts( 'mandatory-element' ); ?>
							<?php
                }
            }

            if ( $el->type == 'file upload' ) {
                continue;
                /**
                 * *Adds dots to acept formats
                 */
                $acepts  = null;
                $coma    = ',';
                $formats = explode( ',', $el->value3 );

                foreach ( $formats as $key => $f ) {
                    if ( $key + 1 == count( $formats ) ) {
                        $coma = '';
                    }
                    $acepts .= '.' . $f . $coma;
                }
                ?>
					<?php if ( $applyCustomColumn ) { ?>
					<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" data-typeElement="<?php echo esc_attr( $el->type ); ?>" class="scc-form-field-item scc-file-upload 
												 <?php
                                                echo esc_attr( $style_scc_calculator );
					    echo $hasAccordion ? ' scc-accordion-panel section_' . esc_attr( $accordion_index ) : '';

					    if ( $disableFlex ) {
					        echo ' scc-d-block clearfix';
					    }
					    ?>
				" data-condition="1 > 0">
				<label class="<?php echo 'scc-col-sm-' . intval( $firstMobileColumnSize ) . ' scc-col-md-' . intval( $firstColumnSize ) . ' scc-col-lg-' . intval( $firstColumnSize ) . ' scc-p-0'; ?> scc-form-field-item-label scc_font_45" for="selectbasic"><?php echo wp_kses( $el->titleElement, SCC_ALLOWTAGS ); ?></label>
				<div class="control-label <?php echo 'scc-col-sm-' . intval( $secondMobileColumnSize ) . ' scc-col-md-' . intval( $secondColumnSize ) . ' scc-col-lg-' . intval( $secondColumnSize ) . ' scc-p-0'; ?> scc_select_opt scc-form-field-item-control" style="padding:0px;">
					<div class="field">
						<div class="scc-ui scc-fu-action scc-fu-input">
							<input type="text" placeholder="<?php echo esc_attr( $el->value2 ); ?>" class="mandatory_no_45" readonly="">
							<input type="file" style="display: none;" accept="<?php echo esc_attr( $acepts ); ?>" data-max-size="<?php echo esc_attr( $el->value4 ); ?>">
							<div class="scc-ui scc-fu-button">
								<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M18.5 16H7c-2.21 0-4-1.79-4-4s1.79-4 4-4h12.5c1.38 0 2.5 1.12 2.5 2.5S20.88 13 19.5 13H9c-.55 0-1-.45-1-1s.45-1 1-1h9.5V9.5H9c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5h10.5c2.21 0 4-1.79 4-4s-1.79-4-4-4H7c-3.04 0-5.5 2.46-5.5 5.5s2.46 5.5 5.5 5.5h11.5V16z"/></svg>
								</div>
							</div>
						</div>
						</div>
				</div>
				<div style="clear: both;"></div>
				<?php } else { ?>
					<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" data-typeElement="<?php echo esc_attr( $el->type ); ?>" class="scc-form-field-item scc-file-upload 
												 <?php
					    echo esc_attr( $style_scc_calculator );
				    echo $hasAccordion ? ' scc-accordion-panel section_' . esc_attr( $accordion_index ) : '';
				    ?>
				" data-condition="1 > 0">
				<label class="scc-col-md-4 scc-col-lg-4 scc-col-xs-12 scc-form-field-item-label scc_font_45" for="selectbasic"><?php echo wp_kses( $el->titleElement, SCC_ALLOWTAGS ); ?></label>
				<div class="control-label scc-col-md-8 scc-col-lg-8 scc-col-xs-12 scc_select_opt scc-form-field-item-control" style="padding:0px;">
					<div class="field">
						<div class="scc-ui scc-fu-action scc-fu-input">
							<input type="text" placeholder="<?php echo esc_attr( $el->value2 ); ?>" class="mandatory_no_45" readonly="">
							<input type="file" style="display: none;" accept="<?php echo esc_attr( $acepts ); ?>" data-max-size="<?php echo esc_attr( $el->value4 ); ?>">
							<div class="scc-ui scc-fu-button">
								<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M18.5 16H7c-2.21 0-4-1.79-4-4s1.79-4 4-4h12.5c1.38 0 2.5 1.12 2.5 2.5S20.88 13 19.5 13H9c-.55 0-1-.45-1-1s.45-1 1-1h9.5V9.5H9c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5h10.5c2.21 0 4-1.79 4-4s-1.79-4-4-4H7c-3.04 0-5.5 2.46-5.5 5.5s2.46 5.5 5.5 5.5h11.5V16z"/></svg>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="clear: both;"></div>
				<?php } ?>
				<?php echo scc_frontend_alerts( 'mandatory-element' ); ?>
					<?php
            }

            if ( $el->type == 'texthtml' ) {
                continue;
                ?>
				<div id="ssc-elmt-<?php echo intval( $el->id ); ?>" class="scc-form-field-item <?php echo $hasAccordion ? ' scc-accordion-panel section_' . esc_attr( $accordion_index ) : ''; ?>">
					<div class="scc-custom-code element-id-45">
						<div style="clear:both">
							<?php echo wp_kses( $el->value2, SCC_ALLOWTAGS ); ?>
						</div>
					</div>
				</div>
					<?php
            }
        }
		}
}
?>
<div class="sections_divider"></div>
<span class="scc-minimum-msg" <?php echo ( $form->minimumTotal <= 0 ) ? 'style="display:none"' : ''; ?>><span class="trn" data-trn-key="The total price must be a minimum of">The price must be at least</span> <span id="label_currency_min_prefix"></span> <?php echo esc_attr( $form->minimumTotal ); ?> <span id="label_currency_min_sufix"></span> </span>
	<div class="scc-btn-hndle" style="margin-top:20px;min-height: 60px">
		<?php if ( $isSCCFreeVersion ? false : ( $sccConfig['paypalConfig']['paypal_checked'] == 'true' ) ) { ?>
			<div class="scc-usr-act-btns-container no-ajaxy" style="padding:0px;">
				<form class="paypal_form" action="https://www.paypal.com/cgi-bin/webscr" method="post" style=""> <input type="hidden" name="cmd" value="_cart"> <input type="hidden" name="upload" value="1"> <input type="hidden" name="business" value="mevi_yayo@hotmail.com"> <input type="hidden" name="item_name" value="wq"> <input type="hidden" name="currency_code" value="USD">
					<div class="paypal_form_add_items"></div>
				</form><span onclick="javascript:preCheckout(<?php echo intval( $form->id ); ?>, () => jQuery(this).prev().submit());" style="color: white;">
					<div class="scc-usr-act-btns btPayPalButtonCustom" style='background-image:url(<?php echo esc_url( SCC_ASSETS_URL . '/images/paypal.png' ); ?>)'></div>
				</span>
			</div>
		<?php } ?>
		<?php if ( $isSCCFreeVersion ? false : ( $stripeConfig['enabled'] == 'true' ) ) { ?>
			<div class="scc-usr-act-btns-container no-ajaxy" style="padding:0px;">
				<span onclick="javascript:preCheckout(<?php echo intval( $form->id ); ?>, () => sccProcessCheckout(<?php echo intval( $form->id ); ?>));" style="color: white;">
					<div class="scc-usr-act-btns btnStripe" style="background-image:url(<?php echo esc_url( SCC_ASSETS_URL . '/images/stripe.png' ); ?>)"></div>
				</span>
			</div>
		<?php } ?>
		<?php if ( $form->turnoffemailquote != 'true' ) { ?>
			<div class="scc-usr-act-btns-container no-ajaxy" style="padding:0px;">
				<a style="text-decoration:none;" class="scc-trigger scc-email-quote-btn scc-usr-act-btns calc-id-45 <?php echo esc_attr( $sccButtonStyle ); ?>" href="javascript:void(0)" onclick="initiateQuoteForm(<?php echo intval( $form->id ); ?>)">
					<div><span class="trn" data-trn-key="Email Quote">Email Quote</span></div>
				</a>
			</div>
			<?php
		}

    if ( $form->turnviewdetails != 'true' ) {
        ?>
			<div class="scc-usr-act-btns-container no-ajaxy" style="padding:0px;">
				<a style="text-decoration:none;" class="scc-trigger scc-detailed-list-btn scc-usr-act-btns calc-id-45 <?php echo esc_attr( $sccButtonStyle ); ?>" href="javascript:void(0)" onclick="showQuoteDetailView(<?php echo intval( $form->id ); ?>)">
					<div>
						<span class="trn" data-trn-key="Detailed List">Detailed List</span>
					</div>
				</a>
			</div>
			<?php
    }

    if ( $isSCCFreeVersion ? false : ( $form->isWoocommerceCheckoutEnabled == 'true' ) ) {
        ?>
			<div class="scc-usr-act-btns-container no-ajaxy" style="padding:0px;">
				<a style="text-decoration:none;" class="scc-trigger scc-enter-coupon-btn scc-usr-act-btns calc-id-45 <?php echo esc_attr( $sccButtonStyle ); ?>" href="javascript:void(0)" onclick="sccWoocommerceAction(<?php echo intval( $form->id ); ?>)">
					<div><span class="trn" data-trn-key="Add to Cart">Add to Cart</span></div>
				</a>
			</div>
			<?php
    }

    if ( $isSCCFreeVersion ? false : ( $form->turnoffcoupon != 'true' ) ) {
        ?>
			<div class="scc-usr-act-btns-container no-ajaxy" style="padding:0px;" id="coupon_button_45">
				<a style="text-decoration:none;" class="scc-trigger scc-enter-coupon-btn scc-usr-act-btns calc-id-45 <?php echo esc_attr( $sccButtonStyle ); ?>" href="javascript:void(0)" onclick="addCouponCodeModal(<?php echo intval( $idvalue ); ?>)">
					<div><span class="trn" data-trn-key="Coupon Code">Coupon Code</span></div>
				</a>
			</div>
		<?php } ?>
	</div>
	<div class="scc-total-section" style="padding: 5px 0px 0 5px; font-size:<?php echo esc_attr( $form->titleFontSize ); ?>">
		<?php
        if ( $form->removeTotal != 'true' ) {
            $taxtotal = 0;

            if ( $form->barstyle == 'scc_tp_style1' ) {
                ?>
				<!-- Total Price Bar - Style 1 -->
				<div class="
				<?php
                echo ( $form->showTaxBeforeTotal == 'true' ) ? ' total1 ' : ' total12 ';

                if ( $currency_conversion_mode == 'off' ) {
                    echo 'c_off';
                }
                ?>
				">
				<div class="total" style="color: #fff!important;">
						<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>" class="trn Total scc_first" id="total_price_with_label" data-trn-key="Total">Total</span>
						<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>" class="totalprice_prefix_currency_label"></span>
						<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>" id="totalvalue" class="<?php echo 'scc-total-' . intval( $form->id ); ?>">0</span>
						<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>" class="totalprice_sufix_currency_label"></span>
					</div>
					<?php if ( $form->showTaxBeforeTotal == 'true' ) { ?>
					<div class="tax_label">
						<span style="color: #fff" class="trn">TAX</span>
					</div>
					<div class="tax_number">
						<span class=" tax-placeholder"></span>
					</div>
					<?php } ?>
					<div class="conversion">
						<span class="df_scc_cc_span" style="padding-right: 0px; background-color:#FFF;display:block;width:fit-content;border-radius:0"></span>
					</div>
				</div>
			<?php } ?>
			<!-- Total style 2 -->
			<?php if ( $form->barstyle == 'scc_tp_style2' ) { ?>
				<div class="
				<?php
                echo ( $form->showTaxBeforeTotal == 'true' ) ? 'total2' : 'total22';

			    if ( $currency_conversion_mode == 'off' ) {
			        echo ' c_off';
			    }
			    ?>
				" style="border-radius:10px">
					<div class="totalPrice_label" style="background-color:<?php echo ( esc_attr( $form->titleColorPicker ) == '#000' ) ? '#000000' : esc_attr( $form->titleColorPicker ); ?>E6;height:100%">
					<span style="color:#FFF;font-weight:300; display:block;" class="trn Total" id="total_price_with_label" data-trn-key="Total">Total</span>
					</div>
					<div class="total_number" style="background: <?php echo esc_attr( $form->titleColorPicker ); ?> !important;">
						<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>" class="totalprice_prefix_currency_label"></span>
						<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>" id="totalvalue" class="<?php echo 'scc-total-' . intval( $form->id ); ?>">0</span>
						<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>" class="totalprice_sufix_currency_label"></span>
					</div>
					<div class="_tax" style="color:<?php echo esc_attr( $form->titleColorPicker ); ?>">
					<?php if ( $form->showTaxBeforeTotal == 'true' ) { ?>
						<span class="trn">Tax</span> <span class=" tax-placeholder" ></span>
					<?php } ?>
					</div>
					<div class="_convertion" style="color:<?php echo esc_attr( $form->titleColorPicker ); ?>">
					<span style="display:block" class="df_scc_cc_span" ></span>
					</div>
				</div>
			<?php } ?>
			<!-- Total style 3 -->
			<?php if ( $form->barstyle == 'scc_tp_style3' || $form->barstyle == null || $form->barstyle == '0' ) { ?>
				<div  style="padding:0px;height:60px">
					<div style=" padding:0;" class="totalPrice totalPrice_45">
					<span style="max-width: 375px; float: right;width: 100%; ">
					<span style="float:right;display: block;color:#c3c3c4;font-weight:normal;font-size:60%" class="trn Total" data-trn-key="Total">Total</span>
							<div class="scc_tp_style_3" style="display: block;clear:both;font-size:110%" id="total_price_with_label">
								<span style="font-size:110%; color:<?php echo esc_attr( $form->titleColorPicker ); ?>;" class="totalprice_prefix_currency_label"></span>
								<span style="font-size:110%; color:<?php echo esc_attr( $form->titleColorPicker ); ?>;" id="totalvalue" class="<?php echo 'scc-total-' . intval( $form->id ); ?>" style="">0</span>
								<span style="font-size:110%; color:<?php echo esc_attr( $form->titleColorPicker ); ?>;" class="totalprice_sufix_currency_label"></span>
							</div>
							<p style="color:#c3c3c4;font-size:20px;font-weight:normal">
							<span class="df_scc_cc_span" style="padding-right: 0px; display: inline;"></span>
							<?php if ( $form->showTaxBeforeTotal == 'true' ) { ?>
								<span class="trn">Tax</span> <span class=" tax-placeholder"></span>
							<?php } ?>
							</p>
							
						</span>
					</div>
				</div>
			<?php } ?>
			<!-- Total Price Bar - Style 4 -->
			<?php if ( $form->barstyle == 'scc_tp_style4' ) { ?>
				<div style="padding:0px;height:60px">
					<div style="padding:0;" class="totalPrice totalPrice_45">
						<span>
							<?php if ( $form->showTaxBeforeTotal == 'true' ) { ?>
								<span class="scc-tax-container"style="padding:0 5px 2px 5px;color:#FFF;background-color:<?php echo esc_attr( $form->titleColorPicker ); ?>;font-size:15px"><span class="trn">Tax</span> <span class=" tax-placeholder" ></span></span>
							<?php } ?>
							<p style="margin-bottom:0">
							<span style="font-weight:400;font-size:30px;color:<?php echo esc_attr( $form->titleColorPicker ); ?>;" class="trn Total" id="total_price_with_label" data-trn-key="Total">Total</span>
							<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>;color:<?php echo esc_attr( $form->titleColorPicker ); ?>;" class="totalprice_prefix_currency_label"></span>
							<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>;color:<?php echo esc_attr( $form->titleColorPicker ); ?>;" id="totalvalue" class="<?php echo 'scc-total-' . intval( $form->id ); ?>">0</span>
							<span style="font-size:<?php echo esc_attr( explode( 'px', $form->titleFontSize )[0] + 5 . 'px' ); ?>;color:<?php echo esc_attr( $form->titleColorPicker ); ?>;" class="totalprice_sufix_currency_label"></span>
							</p>
						</span>
						<span class="df_scc_cc_span" style="font-size:15px;padding-right: 0px; display: table;color:<?php echo esc_attr( $form->titleColorPicker ); ?>;"></span>
					</div>
				</div>
				<?php
			}
        }
?>
	</div>
</div>
<style>
.calc-wrapper .scc-title {
	font-size: 30px;
	color: black;
}
.calc-wrapper .total-bottom-preview {
	font-size: 30px;
	font-weight: bolder;
	color: black;
}
.calc-wrapper .sections_divider {
	border: 1px solid #E8E8E8;
	margin-top: 20px
}
[id^="scc_form"] .description_section_preview {
	color: <?php echo esc_attr( $colorObject ); ?>;
	margin-top: 5px;
}
.scc-title-s{
	margin-top:30px;
}
/* Switch button */
.btn-default.btn-on.active {
	background-color: #5BB75B;
	color: white;
}
.btn-default.btn-off.active {
	background-color: #DA4F49;
	color: white;
}
.btn-default.btn-on-1.active {
	background-color: #006FFC;
	color: white;
}
.btn-default.btn-off-1.active {
	background-color: #DA4F49;
	color: white;
}
.btn-default.btn-on-2.active {
	background-color: #00D590;
	color: white;
}
.btn-default.btn-off-2.active {
	background-color: #A7A7A7;
	color: white;
}
.btn-default.btn-on-3.active {
	color: #5BB75B;
	font-weight: bolder;
}
.btn-default.btn-off-3.active {
	color: #DA4F49;
	font-weight: bolder;
}
.btn-default.btn-on-4.active {
	background-color: #006FFC;
	color: #5BB75B;
}
.btn-default.btn-off-4.active {
	background-color: #DA4F49;
	color: #DA4F49;
}
/* CHECKBOX STARTS */
/* IT SEEMS TO BE NOT WORKING */
	/*
		CHECKBOX BUTTON  Start
		*/
	/*
		RADIO BUTTON NORMAL Start
		*/
		.itemCreated input[type="checkbox"]+label:after {
			box-shadow: inset 0 0 0 1px #666565, inset 0 0 0 16px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
		}
		.itemCreated input[type="checkbox"]+label:hover {
			color: <?php echo esc_attr( $colorObject ); ?> !important;
		}
		.itemCreated input[type="checkbox"]+label:hover:after {
			animation-duration: .5s;
			animation-name: change-size;
			animation-iteration-count: infinite;
			animation-direction: alternate;
			box-shadow: inset 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 16px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
		}
		.itemCreated input[type="checkbox"]:checked+label:after {
			animation-duration: .2s;
			animation-name: select-radio;
			animation-iteration-count: 1;
			animation-direction: Normal;
			box-shadow: inset 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 3px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
		}
		.form_field_item_style_1 .scc_customradio_style_1 {
			display: initial !important;
			width: 100% !important;
		}
		.form_field_item_style_2 .scc_customradio_style_1 {
			width: 100% !important;
		}
		.scc_customradio-45 input[type="checkbox"]+label:after {
			box-shadow: inset 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 16px #f6f6f6, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
		}
		.scc_customradio-45 input[type="checkbox"]+label:hover {
			color: <?php echo esc_attr( $colorObject ); ?> !important;
		}
		.scc_customradio-45 input[type="checkbox"]+label:hover:after {
			animation-duration: .5s;
			animation-name: change-size;
			animation-iteration-count: infinite;
			animation-direction: alternate;
			box-shadow: inset 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 16px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
		}
		.scc_customradio-45 input[type="checkbox"]:checked+label:after {
			animation-duration: .2s;
			animation-name: select-radio;
			animation-iteration-count: 1;
			animation-direction: Normal;
			box-shadow: inset 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 3px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
		}
		@keyframes change-size {
			from {
				box-shadow: 0 0 0 0 <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 16px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
			}
			to {
				box-shadow: 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 16px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
			}
		}
		@keyframes select-radio {
			0% {
				box-shadow: 0 0 0 0 <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 2px #FFFFFF, inset 0 0 0 3px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 16px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
			}
			90% {
				box-shadow: 0 0 0 10px #E8FFF0, inset 0 0 0 0 #FFFFFF, inset 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 2px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
			}
			100% {
				box-shadow: 0 0 0 12px #E8FFF0, inset 0 0 0 0 #FFFFFF, inset 0 0 0 1px <?php echo esc_attr( $colorObject ); ?>, inset 0 0 0 3px #FFFFFF, inset 0 0 0 16px <?php echo esc_attr( $colorObject ); ?>;
			}
		}
	/*
		RADIO BUTTON NORMAL End
		*/
	/*
		RADIO BUTTON ANIMATED Start
		*/
		.btn-radio-45 svg path {
			stroke: <?php echo esc_attr( $colorObject ); ?>;
		}
		/*CHECKBOX BUTTON  Start */
		.label-cbx-45 input:checked+.checkbox {
			border-color: <?php echo esc_attr( $colorObject ); ?>;
		}
		.label-cbx-45 input:checked+.checkbox svg path {
			fill: <?php echo esc_attr( $colorObject ); ?>;
		}
		.label-cbx-45 .checkbox svg path {
			fill: none;
			stroke: <?php echo esc_attr( $colorObject ); ?>;
			stroke-width: 2;
			stroke-linecap: round;
			stroke-linejoin: round;
			stroke-dasharray: 71px;
			stroke-dashoffset: 71px;
			transition: all 0.6s ease;
		}
		/* dropdown */
		.dd-scc-45 {
			border: 2px solid #E8E8E8;
			border-left: 4px solid <?php echo esc_attr( $colorObject ); ?>;
		}
		/* dropdown */
		.ddChild li .ddlabel {
			padding-left: 0px !important;
		}
		.ddChild li .description {
			padding-left: 0px !important;
		}
		[id$="_msdd"] {
			border: 2px solid #E8E8E8;
			border-top-color: rgb(232, 232, 232);
			border-top-style: solid;
			border-top-width: 2px;
			border-right-color: rgb(232, 232, 232);
			border-right-style: solid;
			border-right-width: 2px;
			border-bottom-color: rgb(232, 232, 232);
			border-bottom-style: solid;
			border-bottom-width: 2px;
			border-left-color: rgb(0, 0, 0);
			border-left-style: solid;
			border-left-width: 4px;
			border-image-source: initial;
			border-image-slice: initial;
			border-image-width: initial;
			border-image-outset: initial;
			border-image-repeat: initial;
			border-left: 4px solid <?php echo esc_attr( $colorObject ); ?>;
			border-left-width: 4px;
			border-left-style: solid;
			/* border-left-color: rgb(0, 0, 0); */
			width: 100% !important;
		}
		/* slider */
		.slider-handle {
			background-image: -moz-linear-gradient(top, <?php echo esc_attr( $colorObject ); ?>, <?php echo esc_attr( $colorObject ); ?>);
			background-image: -webkit-gradient(linear, 0 0, 0 100%, from(<?php echo esc_attr( $colorObject ); ?>), to(<?php echo esc_attr( $colorObject ); ?>));
			background-image: -webkit-linear-gradient(top, <?php echo esc_attr( $colorObject ); ?>, <?php echo esc_attr( $colorObject ); ?>);
			background-image: -o-linear-gradient(top, <?php echo esc_attr( $colorObject ); ?>, <?php echo esc_attr( $colorObject ); ?>);
			background-image: linear-gradient(to bottom, <?php echo esc_attr( $colorObject ); ?>, <?php echo esc_attr( $colorObject ); ?>);
			background-repeat: repeat-x;
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0480BE', endColorstr='#036fa5', GradientType=0);
			-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
			-moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
			box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
			position: absolute;
			top: 0;
			/* width: 20px; */
			/* height: 20px; */
			background-color: <?php echo esc_attr( $colorObject ); ?>;
			border: 0px solid transparent;
		}
		.slider-track .slider-selection {
			background-color: <?php echo esc_attr( $colorObject ); ?>;
		}
		.slider-track .slider-track-high {
			background-color: white;
		}
		.slider.slider-horizontal {
			width: 100% !important;
		}
		/* custom math */
		.scc-custom-math-element {
			border-left: 3px solid <?php echo esc_attr( $colorObject ); ?>;
			padding: 10px !important;
		}
		input#itemcreateds_0_0_0 {
			width: 120px;
			height: 50px;
			border-radius: 10px;
			padding-left: 5px;
			text-align: center;
			padding-right: 5px;
		}
		input#itemcreateds_0_0_0.number_box_2_text::-webkit-outer-spin-button,
		input#itemcreateds_0_0_0.number_box_2_text::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
		input#itemcreateds_0_0_0.number_box_text::-webkit-inner-spin-button, 
		input#itemcreateds_0_0_0.number_box_text::-webkit-outer-spin-button {  
			opacity: 1;
		}
		input#itemcreateds_0_0_0[type=number] {
			-moz-appearance: textfield;
			/* Firefox */
		}
		span.input-number-increment,
		span.input-number-decrement {
			border: none !important;
			cursor: pointer;
			color: <?php echo esc_attr( $colorObject ); ?> !important;
			width: 30px;
			font-weight: bold;
			font-size: 18px;
			user-select: none;
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
		}
		/* toogle button */
		.clicked-buttom-checkbox {
			background-color: <?php echo esc_attr( $colorObject ); ?> !important;
			color: #fff !important;
		}
		/* buttons at the bottom */
		.printTable-45 {
			background: <?php echo esc_attr( $colorObject ); ?>;
			color: #fff;
			border-radius: 80px;
			font-size: 14px;
			text-align: center;
			display: flex;
			justify-content: center;
			align-items: center;
			/*height: 100%;*/
			width: auto;
			padding: 10px 20px 10px 20px;
			margin: 1px 5px 5px 5px;
			/* box-shadow: 3px 2px 3px 0px #d8d8d2; */
		}
		select.form-select-tom,
		.form-select-tom .ts-control,
		.form-select-tom .ts-control {
			border-left: 4px solid <?php echo esc_attr( $colorObject ); ?> !important;
		}
		a.scc-trigger {
			color: <?php echo esc_attr( $colorfonts_button ); ?> !important;
		}
		.scc-btn-style-2:after {
			background-color: <?php echo esc_attr( $colorfonts_button ); ?> !important;
		}
		/* paypal and stripe button  starts */
		.btPayPalButtonCustom,
		.btnStripe {
			display: flex;
			margin: 0;
			background: transparent;
			border: <?php echo ( $form->turnoffborder != 'true' ) ? '2px solid ' . esc_attr( $colorObject ) : ''; ?>;
			border-radius: 50px;
			height: 44px;
			cursor: pointer;
			-webkit-transition: box-shadow 380ms ease;
			transition: box-shadow 380ms ease;
			background-position: center center;
			background-size: 70%;
			background-repeat: no-repeat;
			flex: 0 0 130px;
			padding: 0 100px 0 28px;
			margin-left: 6px;
		}
		.rtl .btPayPalButtonCustom,
		.rtl .btnStripe {
			margin-left: 0;
			margin-right: 30px;
			margin-top: -15px;
		}
		.btPayPalButtonCustom:hover,
		.btnStripe:hover {
			box-shadow: <?php echo ( $form->turnoffborder != 'true' ) ? '2px 2px 3px 1px ' . esc_attr( $colorObject ) : ''; ?>;
		}
		/* paypal and stripe button  ends */
		/* bootstrap inherit font-weight */
		.scc-form-field-item-label {
			/*font-weight: 100;*/
		}
		.alert.alert-danger span {
			font-family:'Open Sans' !important;
		}
	</style>
