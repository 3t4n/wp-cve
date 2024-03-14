<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$defaultFields                = json_decode( '[{"name":{"name":"Your Name","description":"Type in your name","type":"text","isMandatory":null,"trnKey":"Your Name","deletable":false}},{"email":{"name":"Your Email","description":"Type in your email","type":"email","isMandatory":true,"trnKey":"Your Email","deletable":false}},{"phone":{"name":"Your Phone","description":"phone","type":"phone","isMandatory":null,"trnKey":"Your Phone (Optional)","deletable":false}}]', true );
$formFieldsArray              = empty( $f1->formFieldsArray ) ? $defaultFields : json_decode( $f1->formFieldsArray, 1 );
$paypalConfig                 = json_decode( $f1->paypalConfigArray, true );
$stripeConfig                 = ( get_option( 'df_scc_stripe_keys' ) == '' ) ? [
    'pubKey'  => null,
    'privKey' => null,
] : get_option( 'df_scc_stripe_keys' );
$stripeConfig['enabled']      = $f1->isStripeEnabled && ( $f1->isStripeEnabled !== 'false' ) ? true : false;
$stripeData                   = '';
$isStripeSetupDone            = $stripeConfig['pubKey'] && $stripeConfig['privKey'];
$isPayPalEnabled              = $paypalConfig && $paypalConfig['paypal_checked'] == 'true' ? true : false;
$isStripeEnabled              = $stripeConfig && $stripeConfig['enabled'] == 'true' ? true : false;
$isWoocommerceCheckoutEnabled = $f1->isWoocommerceCheckoutEnabled == 'true' ? true : false;
$isForceQuoteFormEnabled      = $f1->preCheckoutQuoteForm == 'true' ? true : false;
$ShowFormBuilderOnDetails     = ( $f1->ShowFormBuilderOnDetails == 'false' || ! $f1->ShowFormBuilderOnDetails ) ? false : true;

if ( $isStripeSetupDone ) {
    $stripeDataAttr = 'data-pub-key=' . $stripeConfig['pubKey'] . ' ' . 'data-priv-key=' . $stripeConfig['privKey'];
}
$df_scc_form_currency = get_option( 'df_scc_currency', 'USD' );
$isWoocommerceActive  = false;

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    $isWoocommerceActive = true;
}
$isWoocommerceCheckoutEnabled = $f1->isWoocommerceCheckoutEnabled == 'true' ? true : false;

if ( $isWoocommerceActive && get_option( 'df_scc_licensed' ) == 1 && $isWoocommerceCheckoutEnabled ) {
    $woo_commerce_products = [];
    $args                  = [
        'post_type'      => 'product',
        'posts_per_page' => -1,
    ];
    $loop                  = new WP_Query( $args );

    while ( $loop->have_posts() ) {
        $loop->the_post();
        global $product;
        array_push( $woo_commerce_products, $product );
    }
    wp_reset_query();
}
// preparing an array for use in dropdown choices in the elements added via ajax
$woocommerce_products_array = [];

if ( $isWoocommerceCheckoutEnabled && $isWoocommerceActive ) {
    foreach ( $woo_commerce_products as $product ) {
        if ( $product->is_type( 'variable' ) ) {
            $available_variations = $product->get_available_variations();

            foreach ( $available_variations as $product_variable ) {
                $attributes = [];

                foreach ( $product_variable['attributes'] as $key => $value ) {
                    $attributes[] = $product->get_name() . ': ' . $value;
                }
                array_push( $woocommerce_products_array, [ esc_html( $product_variable['variation_id'] ) => esc_html( implode( ' | ', $attributes ) ) . ' | Price: ' . get_woocommerce_currency_symbol() . '' . esc_html( $product_variable['display_regular_price'] ) ] );
            }
        } else {
            array_push( $woocommerce_products_array, [ esc_html( $product->get_id() ) => esc_html( $product->get_name() ) . ' | Price: ' . get_woocommerce_currency_symbol() . '' . esc_html( $product->get_price() ) ] );
        }
    }
}
wp_localize_script( 'scc-backend', 'pageEditCalculator', [ 'nonce' => wp_create_nonce( 'edit-calculator-page' ) ] );
$edit_page_func = new Stylish_Cost_Calculator_Edit_Page();
?>

<script>
	window["woocommerceProducts"] = <?php echo json_encode( $woocommerce_products_array ); ?>;
	jQuery(document).ready(function() {
		var isNewCalculator = "<?php echo ( isset( $_GET['new'] ) ) ? 1 : 0; ?>"
		if (isNewCalculator == 1) {
			showSweet(true, "Calculator was successfully created")
		}
	})
</script>
<div class="row ms-2 mt-4 scc-no-gutter">
	<input type="text" id="id_scc_form_" value="<?php echo intval( $f1->id ); ?>" hidden>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 scc-left-pane">
		<div class="scc-pane-container">
			<div class="scc-calculator-builder-pane">
				<div class="scc-pane-options row">
					<div class="col-10">
						<div class="scc-pane-title">Calculator Builder</div>
						<div class="scc-pane-description">Edit your calculator preferences</div>
					</div>
					<div class="col-2">
						<div class="scc-menu-dropdown">
							<button class="scc-dropbtn" onclick="sccToggleMenuDropdown( this )" ><span class="scc-icn-wrapper">
									<?php echo scc_get_kses_extended_ruleset( $scc_icons['menu'] ); ?>
								</span></button>
							<div class="scc-menu-dropdown-content scc-hidden">
								<a class="scc-font-settings-dropdown" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal" onclick="sccToggleMenuDropdown( this )" ><span class="scc-icn-wrapper">
									<?php echo scc_get_kses_extended_ruleset( $scc_icons['edit-3'] ); ?>
								</span> Font Settings</a>
								<hr>
								<a class="scc-calculator-settings-dropdown" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal1" onclick="sccToggleMenuDropdown( this )" ><span class="scc-icn-wrapper">
									<?php echo scc_get_kses_extended_ruleset( $scc_icons['tool'] ); ?>
								</span> Calculator Settings</a>
								<hr>
								<a class="scc-wordings-settings-dropdown" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal2" onclick="sccToggleMenuDropdown( this )" ><span class="scc-icn-wrapper">
									<?php echo scc_get_kses_extended_ruleset( $scc_icons['file-text'] ); ?>
								</span> Wordings</a>
								<hr>
								<a class="scc-coupon-codes-dropdown" href="<?php echo esc_url( admin_url( 'admin.php?page=scc-coupons-management' ) ); ?>"><span class="scc-icn-wrapper">
									<?php echo scc_get_kses_extended_ruleset( $scc_icons['percent'] ); ?>
								</span> Coupon Codes</a>
								<hr>
								<a class="scc-global-settings-dropdown" href="<?php echo esc_url( admin_url( 'admin.php?page=scc-global-settings' ) ); ?>"><span class="scc-icn-wrapper">
									<?php echo scc_get_kses_extended_ruleset( $scc_icons['settings'] ); ?>
								</span> Global Settings</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- SECTION -->
		<div id="allinputstoadd" class="scc-col-xs-6 scc-col-lg-12 scc-col-md-12">
			<?php
            foreach ( $f1->sections as $section_key => $section ) {
                ?>
				<div class="addedFieldsStyle" style="display:grid;" id="Sccvo_0">
					<input class="id_section_class" type="text" value="<?php echo intval( $section->id ); ?>" hidden>
					<div id="title54-bar-btns" class="scc-section-setting-container">
						<div class="scc-section-setting-bar">
							<button id="up-btn" class="scc-section-setting-btn up d-none" title="Push this section above" href="javascript:void(0)" onclick="rup(this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['push-up'] ); ?></span></button>
							<button id="down-btn" class="scc-section-setting-btn down" title="Push this section below" href="javascript:void(0)" onclick="rdown(this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['push-down'] ); ?></span></button>	
							<button id="settings-btn" class="scc-section-setting-btn" href="javascript:void(0)" title="Section settings" onclick="settingsIconShow(this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['sliders'] ); ?></span></button>
							<button id="close-btn" class="scc-section-setting-btn" href="javascript:void(0)" title="Delete Section" onclick="preDeletionDialog('section', removeSection, this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['x'] ); ?></span></button>
						</div>
					</div>
					<div class="scc-accordion-container scc-accordion-tooltip-hidden">
							<div class="scc-accordion-tooltip scc-accordion-content">
								<!-- SETTINGS TOOGLE -->
								<p>
									<label class="scc-accordion_switch_button">
										<input class="scc_accordion_section" onchange="changeAccordion(this)" name="scc_accordion_section" type="checkbox" 
										<?php
                                        if ( $section->accordion == 'true' ) {
                                            echo 'checked';
                                        }
                ?>
																																							>
										<span class="scc-accordion_toggle_button round"></span>
									</label>
									Accordion
								</p>
								<p class="scc-opacity-05">
									<label class="scc-accordion_switch_button use-premium-tooltip" data-tooltip-image="<?php echo esc_url( SCC_TOOLTIP_BASEURL . '/section-total.png' ); ?>">
										<input class="scc-section-total" onchange="changeShowSectionTotal(this)" name="scc-section-total" type="checkbox" disabled>
										<span class="scc-accordion_toggle_button round"></span>
									</label>
									Show Section Total
								</p>
								<p class="section-total-on-pdf-container" style="display : <?php echo esc_attr( $section->showSectionTotal ) == 'true' ? 'inline' : 'none'; ?>">
									<label class="scc-accordion_switch_button use-premium-tooltip">
										<input disabled onchange="changeShowSectionTotalOnPdf(this)" name="scc-section-total-on-pdf" type="checkbox" 
										<?php
                if ( $section->showSectionTotalOnPdf == 'true' ) {
                    echo 'checked';
                }
                ?>
																																			>
										<span class="scc-accordion_toggle_button round"></span>
									</label>
									Show Section Total on PDF/Detail View
								</p>
								<p class="section-split-to-page scc-opacity-05">
									<label class="scc-accordion_switch_button">
										<input disabled onchange="changeSectionToPage(this)" name="section-split-to-page" type="checkbox" 
										<?php
                if ( $section->section_in_page == 1 ) {
                    echo 'checked';
                }
                ?>
										>
										<span class="scc-accordion_toggle_button round"></span>
									</label>
									<span class="scc-adv-opt-lbl use-premium-tooltip">
										Activate Multi-Step Form</span>
								</p>
							</div>
					</div>
					<!-- TITLE -->
					<div class="title_section_no_edit_container" style="">
						<!--<div class="scc-col-md-1" style="padding:0px;margin-right:5px;">
							<img style="cursor: pointer" onclick="toggleEditTitle(this)" src="<?php echo esc_url( SCC_URL . '/assets/images/pen-blue.png' ); ?>" width="15">
						</div>-->
						<div class="scc-col-md-10" style="padding:0px" onclick="toggleEditTitle(this)">
							<i class="fa fa-pen text-primary"></i>
							<p style="margin-top:-5px; margin-bottom: 1px;font-size: 20px" class="title_section_no_edit d-inline">
								<?php echo esc_attr( wp_unslash( $section->name ) ); ?>
							</p>
						</div>
						<div class="section-title-edit-wrapper">
							<i class="fa fa-check text-warning" onclick="toggleEditTitle(this)" style="display:none" role="button"></i>
							<input value="<?php echo esc_attr( wp_unslash( $section->name ) ); ?>" onkeyup="changeTitleSection(this)" type="text" class="input_pad sectiontitle scc_edit_section_input" placeholder="Section Title" value="" style="border: none !important;box-shadow: none !important; outline: 0; width: 95%; font-size: 20px; border-bottom: 1px solid #314af3 !important;border-radius:10px;margin-top:10px;height: 50px;margin-bottom:10px;display:none">
							<span class="mandatory" style="display:none">*</span>
						</div>
					</div>
					<!-- DESCRIPTION -->
					<div class="description_section_no_edit_container" style="">
						<!--<div class="scc-col-md-1" style="padding:0px;margin-right:5px;">
							<img style="cursor: pointer;float:right" onclick="toggleEditDescription(this)" src="<?php echo esc_url( SCC_URL . '/assets/images/pen-blue.png' ); ?>" width="15">
							<i class="material-icons-outlined more-settings-info" title="You can add html tags: h4,br,b,i,'ul,li,hr" style="color:#314af3;cursor:pointer">info</i>
						</div>-->
						<div class="scc-col-md-10" style="padding:0px" onclick="toggleEditDescription(this)">
							<i class="fa fa-pen text-primary"></i>
							<p class="description_section_no_edit d-inline">
								<?php echo esc_attr( wp_unslash( $section->description ) ); ?>
							</p>
						</div>
						<div class="description-wrapper">
							<i class="fa fa-check text-warning" onclick="toggleEditDescription(this)" style="display:none" role="button"></i>
							<textarea onkeyup="changeDescriptionSection(this)" class="input_pad sectionDescription scc_section_description_textarea" placeholder="Description of the products/services that will be listed below. (Optional)" style="background: rgb(255, 255, 255); height: 125px; padding: 15px; width: 95%; margin-bottom: 20px; border-bottom: 1px solid #314af3 !important; margin-top: 15px; display: none;"><?php echo wp_kses( $section->description, SCC_ALLOWTAGS ); ?></textarea>
						</div>
					</div>
					<!-- SUBSECTION -->
					<div class="fieldDatatoAdd">
						<?php
                        foreach ( $section->subsection as $sub ) {
                            ?>
							<div class="boardOption">
								<input class="input_subsection_id" type="text" value="<?php echo intval( $sub->id ); ?>" hidden>
								<div class="scc-subsection">
									<div>
										<button class="collapsible subsect-title">Subsection <i class="material-icons-outlined with-tooltip"  data-setting-tooltip-type="subsection-note-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i></button>
										<div class="scc_help_btn_right" style="left:26px;float:right;top:23px;font-size:18px;"></div>
									</div>
									
									<div class="scc-section-setting-bar">
										<button id="close-btn" class="scc-section-setting-btn" title="Delete Subsection" onclick="preDeletionDialog('subsection', removeSubsection, this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['x'] ); ?></span></button>
									</div>
								</div>
								<div class="subsection-area BodyOption ">
									<?php
                                    foreach ( $sub->element as $el ) {
                                        ?>
										<?php
                                        /**
                                         * Here, we sort the conditions by the 'condition_set' property.
                                         * the $conditionsBySet object is used to store it.
                                         */
                                        $conditionsBySet = [];

                                        for ( $i = 0; $i < count( $el->conditions ); $i++ ) {
                                            $tmpConditionSet = $el->conditions[ $i ]->condition_set;

                                            if ( ! in_array( $tmpConditionSet, array_keys( $conditionsBySet ) ) ) {
                                                $conditionsBySet += [ $tmpConditionSet => [ $el->conditions[ $i ] ] ];
                                            } else {
                                                array_push( $conditionsBySet[ $tmpConditionSet ], $el->conditions[ $i ] );
                                            }
                                        }
                                        /*
                                         * if condition set are empty, we set a condition set with key 1, so the choice
                                         * dropdowns show up
                                         */
                                        if ( ! count( $conditionsBySet ) ) {
                                            $conditionsBySet = [ 1 => [] ];
                                        }
                                        /**
                                         * define truncated element title
                                         */
                                        $truncatedTitleElement = strlen( $el->titleElement ) > 30 ? substr( stripslashes( $el->titleElement ), 0, 29 ) . '..' : stripslashes( $el->titleElement );
                                        $truncatedTitleElement = htmlentities( $truncatedTitleElement );

                                        if ( $el->type == 'Dropdown Menu' ) {
                                            ?>
											<div class="elements_added">
												<input type="text" class="input_id_element" value="<?php echo intval( $el->id ); ?>" hidden>
												<div class="elements_added_v2">
													<div class="element-icon">
														<i class="far fa-list-alt" style="font-size:25px;"></i>
													</div>
													<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
														<div class="title-desc-wrapper">
															<span class="element-title"><?php echo esc_attr( $el->type ); ?></span>
															<p class="element-description">
																<?php echo esc_attr( $truncatedTitleElement ); ?>
															</p>
														</div>
													</div>
													<?php echo scc_output_editing_page_element_actions( 'dropdown', false ); ?>
												</div>
												<?php echo $edit_page_func->renderDropdownSetupBody( $el, $conditionsBySet ); ?>
												<?php echo $edit_page_func->renderElementLoader(); ?>
											</div>
											<?php
                                        }

                                        if ( $el->type == 'checkbox' ) {
                                            if ( $el->value1 == '8' ) {
                                                continue;
                                            }
                                            $hasCheckboxColumns          = $el->value2 > 1;
                                            $responsiveColumnTooltipText = $hasCheckboxColumns ? 'It is disabled due to "checkbox columns" feature being utilized' : 'Please enter a number between 1 and 12. 1 being the smallest and 12 being the largest, for your title. If you have a large title, we recommend between 6 and 12.';
                                            $responsiveColumnTooltipText = htmlentities( $responsiveColumnTooltipText );
                                            $type__                      = '';
                                            $tooltip_type                = '';
                                            $checkboxIcon                = 'far fa-check-square';
                                            $typeDescription             = 'Shows a checkbox on the frontend';
                                            switch ( $el->value1 ) {
                                                case '1':
                                                case '5':
                                                case '2':
                                                    $type__       = 'Checkbox Buttons';
                                                    $tooltip_type = 'checkbox-buttons';
                                                    break;

                                                case '6':
                                                    $type__          = 'Simple Buttons';
                                                    $tooltip_type    = 'simple-buttons';
                                                    $checkboxIcon    = 'use-material-simple-btn';
                                                    $typeDescription = 'Shows simple buttons on the frontend';
                                                    break;

                                                case '3':
                                                case '4':
                                                    $type__          = 'Toggle Switches';
                                                    $tooltip_type    = 'toggle-switches';
                                                    $checkboxIcon    = 'use-material-simple-btn';
                                                    $typeDescription = 'Shows toggle switch on the frontend';
                                                    break;

                                                case '7':
                                                    $type__          = 'Radio Buttons';
                                                    $tooltip_type    = 'radio-buttons';
                                                    $typeDescription = 'Shows checkbox items with only one selectable';
                                                    break;

                                                case '8':
                                                    $type__          = 'Image Buttons';
                                                    $tooltip_type    = 'image-buttons';
                                                    $checkboxIcon    = 'fas fa-image';
                                                    $typeDescription = 'Shows selectable image choices';
                                                    break;
                                                default:
                                                    $type__       = 'Checkbox/Toggle/Button';
                                                    $tooltip_type = 'default-checkbox';
                                                    break;
                                            }
                                            ?>
											<div class="elements_added">
												<input type="text" class="input_id_element" value="<?php echo intval( $el->id ); ?>" hidden>
												<div class="elements_added_v2">
													<div class="element-icon">
														<?php if ( $checkboxIcon == 'use-material-simple-btn' ) { ?>
															<span class="material-icons-outlined" style="font-size:25px;">smart_button</span>
														<?php } else { ?>
															<i class="<?php echo esc_attr( $checkboxIcon ); ?>" style="font-size:25px;"></i>
														<?php } ?>
													</div>
													<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
														<div class="title-desc-wrapper">
															<span class="element-title"><?php echo esc_attr( $type__ ); ?></span>
															<p class="element-description">
																<?php echo esc_attr( $truncatedTitleElement ); ?>
															</p>
														</div>
													</div>
													<?php echo scc_output_editing_page_element_actions( $tooltip_type, false ); ?>
												</div>
												<?php echo $edit_page_func->renderCheckboxSetupBody( $el, $conditionsBySet ); ?>
												<?php echo $edit_page_func->renderElementLoader(); ?>
											</div>
											<?php
                                        }

                                        if ( $el->type == 'comment box' ) {
                                            ?>
											<div class="elements_added">
												<input type="text" class="input_id_element" value="<?php echo intval( $el->id ); ?>" hidden>
												<div class="elements_added_v2">
													<div class="element-icon">
														<i class="fas fa-comment" style="font-size:25px;"></i>
													</div>
													<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
														<div class="title-desc-wrapper">
															<span class="element-title">Comment Box</span>
															<p class="element-description">
																<?php echo esc_attr( $truncatedTitleElement ); ?>
															</p>
														</div>
													</div>
													<?php echo scc_output_editing_page_element_actions( 'comment-box', false ); ?>
												</div>
												<?php echo $edit_page_func->renderCommentBoxSetupBody2( $el, $conditionsBySet ); ?>
												<?php echo $edit_page_func->renderElementLoader(); ?>
											</div>
											<?php
                                        }

                                        if ( $el->type == 'quantity box' ) {
                                            ?>
											<div class="elements_added">
												<input type="text" class="input_id_element" value="<?php echo intval( $el->id ); ?>" hidden>
												<div class="elements_added_v2">
													<div class="element-icon">
														<i class="material-icons" style="font-size:25px;">exposure</i>
													</div>
													<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
														<div class="title-desc-wrapper">
															<span class="element-title">Quantity Input Box</span>
															<p class="element-description">
																<?php echo esc_attr( $truncatedTitleElement ); ?>
															</p>
														</div>
													</div>
													<?php echo scc_output_editing_page_element_actions( 'quantity-input-box', false ); ?>
												</div>
												<?php echo $edit_page_func->renderQuantityBoxSetupBody2( $el, $conditionsBySet ); ?>
												<?php echo $edit_page_func->renderElementLoader(); ?>
											</div>
											<?php
                                        }

                                        if ( $el->type == 'custom math' ) {
                                            continue;
                                            ?>
											<div class="elements_added">
												<input type="text" class="input_id_element" value="<?php echo intval( $el->id ); ?>" hidden>
												<div class="elements_added_v2">
													<div class="element-icon">
														<i class="fas fa-calculator" style="font-size:25px;"></i>
													</div>
													<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
														<div class="title-desc-wrapper">
															<span class="element-title">Custom Math</span>
															<p class="element-description">
																<?php echo esc_attr( $truncatedTitleElement ); ?>
															</p>
														</div>
													</div>
													<?php echo scc_output_editing_page_element_actions( 'custom-math', false ); ?>
												</div>
												<div class="scc-element-content" value="selectoption" style="display:none; height:auto">
													<!-- CONTENT OF EACH ELEMENT -->
													<div class="edit-element-notice" style="margin-left: 15px;">
														<i class="material-icons with-tooltip" title="Note: Custom Math applies extra calculation over the value returned by the elements in a subsection. E.g. If the subsection returns 100, and the custom math is 10%, the final value of the subsection will be 110. You can use to decrease the subsection total using negative sign to the value, for example, -10%">help_outline</i>
													</div>
													<!-- ELEMENT -->
													<div class="row m-0 selopt5 col-xs-12 col-md-12" style="margin-top: 20px; padding: 0px;">
														<div class="col-xs-6 col-md-2" style="padding:0px;height:40px;background: #f8f9ff;"><span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Name</span></div>
														<div class="col-xs-6 col-md-7" style="padding:0px;"><input type="text" class="input_pad inputoption_title" onkeyup="clickedTitleElement(this)" style="height:40px;width:100%;" placeholder="Title" value="<?php echo esc_html( wp_unslash( $el->titleElement ) ); ?>"></div>
													</div>
													<!-- ELEMENTS INSIDE ELEMENTS -->
													<div class="col-md-12 col-xs-12" style="margin-top:10px;padding:0px;">
														<div class="col-xs-6 col-md-2" style="padding:0px;background:#f8f9ff;height:40px;">
															<span class="scc_label" style="margin-top:5px;text-align:right;padding-right:10px;">Type</span>
														</div>
														<div class="col-xs-6 col-md-7" style="padding:0px;padding:0px;text-align:left;">
															<select onchange="changeValue1(this)" class="input_pad scc_custom_math_type" style="text-align:center;height:40px;width:150px; padding: 1px!important;text-align-last: center;font-size: 17px;">
																<option value="+" 
																<?php
                                                                if ( $el->value1 == '+' ) {
                                                                    echo 'selected';
                                                                }
                                            ?>
																					>+</option>
																<option value="-" 
																<?php
                                            if ( $el->value1 == '-' ) {
                                                echo 'selected';
                                            }
                                            ?>
																					>-</option>
																<option value="x" 
																<?php
                                            if ( $el->value1 == 'x' ) {
                                                echo 'selected';
                                            }
                                            ?>
																					>x</option>
																<option value="%" 
																<?php
                                            if ( $el->value1 == '%' ) {
                                                echo 'selected';
                                            }
                                            ?>
																					>%</option>
																<option value="/" 
																<?php
                                            if ( $el->value1 == '/' ) {
                                                echo 'selected';
                                            }
                                            ?>
																					>/</option>
															</select>
														</div>
													</div>
													<div class="col-md-12 col-xs-12" style="margin-top:10px;padding:0px;margin-bottom:10px">
														<div class="col-xs-6 col-md-2" style="padding:0px;background:#f8f9ff;height:40px;">
															<span class="scc_label" style="margin-top:5px;text-align:right ;padding-right:10px;">Value</span>
														</div>
														<div>
															<div class="col-xs-6 col-md-7" style="padding:0px;text-align:left">
																<input onkeyup="changeValue2(this)" onchange="changeValue2(this)" type="number" class="input_pad scc_custom_math_value" style="text-align:center;height:40px;left:-8px;width:150px;" value="<?php echo esc_attr( $el->value2 ); ?>">
															</div>
														</div>
													</div>
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
															<?php echo $edit_page_func->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															<div class="scc-content" style="display: none;">
																<div class="scc-transition">
																	<?php
                                                foreach ( $conditionsBySet as $key => $conditionCollection ) {
                                                    ?>
																		<?php if ( $key > 1 ) { ?>
																			<p>OR condition <?php echo intval( $key ); ?></p>
																		<?php } ?>
																		<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
																			<?php
                                                        foreach ( $conditionCollection as $index => $condition ) {
                                                            if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
                                                                ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo esc_attr( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select disabled class="first-conditional-step col-xs-3" style="height: 40px;">
																										<option style="font-size: 10px" value="0"><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																									</select>
																									<select disabled class="second-conditional-step col-xs-3" style="height: 40px;">
																										<?php
                                                                                    if ( $condition->op == 'eq' ) {
                                                                                        echo '<option value="eq">Equal To</option>';
                                                                                    }
                                                                ?>
																										<?php
                                                                if ( $condition->op == 'ne' ) {
                                                                    echo '<option value="ne">Not Equal To</option>';
                                                                }
                                                                ?>
																										<?php
                                                                if ( $condition->op == 'any' ) {
                                                                    echo '<option value="any">Any</option>';
                                                                }
                                                                ?>
																									</select>
																									<?php if ( $condition->op != 'any' ) { ?>
																										<select disabled class="third-conditional-step col-xs-3" style="height: 40px;">
																											<option value="eq"><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																										</select>
																									<?php } ?>
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                                            }

                                                            if ( $condition->elementitem_id && ! $condition->condition_element_id ) {
                                                                ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select disabled class="first-conditional-step col-xs-3" style="height: 40px;">
																										<option style="font-size: 10px" value="0"><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<select disabled class="second-conditional-step col-xs-3" style="height: 40px;">
																										<?php if ( $condition->op == 'chec' ) { ?>
																											<option value="">Checked</option>
																										<?php } else { ?>
																											<option value="">Unchecked</option>
																										<?php } ?>
																									</select>
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                                            }

                                                            if ( $condition->condition_element_id ) {
                                                                if ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) {
                                                                    ?>
																						<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																							<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																							<div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">
																								<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																							</div>
																							<div class="col-xs-11 col-md-11" style="padding:0px;">
																								<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																									<div class="item_conditionals">
																										<select disabled class="first-conditional-step col-xs-3" style="height: 40px;">
																											<option style="font-size: 10px" value="0"><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																										</select>
																										<select disabled class="second-conditional-step col-xs-3" style="height: 40px;">
																											<?php
                                                                                        if ( $condition->op == 'eq' ) {
                                                                                            echo '<option style="font-size: 10px" value="0">Equal To</option>';
                                                                                        }
                                                                    ?>
																											<?php
                                                                    if ( $condition->op == 'ne' ) {
                                                                        echo '<option style="font-size: 10px" value="0">Not Equal To</option>';
                                                                    }
                                                                    ?>
																											<?php
                                                                    if ( $condition->op == 'gr' ) {
                                                                        echo '<option style="font-size: 10px" value="0">Greater than</option>';
                                                                    }
                                                                    ?>
																											<?php
                                                                    if ( $condition->op == 'les' ) {
                                                                        echo '<option style="font-size: 10px" value="0">Less than</option>';
                                                                    }
                                                                    ?>
																										</select>
																										<input disabled value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 40px;" class="conditional-number-value col-xs-2">
																										<div class="btn-group" style="margin-left: 10px;">
																											<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																						<?php
                                                                }
                                                            }
                                                        }
                                                    ?>
																			<!-- <div style="background-color: black;height:50px"></div> -->
																			<div class="row col-xs-12 col-md-12 conditional-selection 
																			<?php
                                                    if ( count( $conditionCollection ) ) {
                                                        echo 'hidden';
                                                    }
                                                    ?>
																			" style="padding: 0px; margin-bottom: 5px;">
																				<div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">
																					<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
																				</div>
																				<div class="col-xs-11 col-md-11" style="padding:0px;">
																					<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																						<div class="item_conditionals">
																							<select onfocus="loadDataItemsCondition(this)" onchange="loadSecondSelectCondition(this)" class="first-conditional-step col-xs-3" style="height: 40px;">
																								<option style="font-size: 10px" value="0">Select an element</option>
																							</select>
																							<select onchange="changeSecondSelectCondition(this)" class="second-conditional-step col-xs-3" style="height: 40px;display:none">
																							</select>
																							<select class="third-conditional-step col-xs-3" style="height: 40px;display:none">
																							</select>
																							<input type="number" placeholder="Number" style="height: 40px;display:none" class="conditional-number-value col-xs-2">
																							<div class="btn-group" style="margin-left: 10px;display:none">
																								<button onclick="addConditionElement(this)" class="btn btn-addcondition">Save</button>
																								<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
																			<?php
                                                    if ( empty( count( $el->conditions ) ) ) {
                                                        echo 'hidden';
                                                    }
                                                    ?>
																			">+ AND</button>
																		</div>
																	<?php } ?>
																	<div style="margin-left: auto; margin-right: auto; width: 28%">
																		<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php
                                        }

                                        if ( $el->type == 'file upload' ) {
                                            ?>
											<div class="elements_added">
												<input type="text" class="input_id_element" value="<?php echo intval( $el->id ); ?>" hidden>
												<div class="elements_added_v2">
													<div class="element-icon">
														<i class="fas fa-paperclip" style="font-size:25px;"></i>
													</div>
													<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
														<div class="title-desc-wrapper">
															<span class="element-title">File Upload Field</span>
															<p class="element-description">
																<?php echo esc_attr( $truncatedTitleElement ); ?>
															</p>
														</div>
													</div>
													<?php echo scc_output_editing_page_element_actions( 'file-upload', false ); ?>
												</div>
												<?php echo $edit_page_func->renderFileUploadSetupBody2( $el, $conditionsBySet ); ?>
												<?php echo $edit_page_func->renderElementLoader(); ?>
											</div>
											<?php
                                        }

                                        if ( $el->type == 'texthtml' ) {
                                            continue;
                                            ?>
											<div class="elements_added">
												<input type="text" class="input_id_element" value="<?php echo intval( $el->id ); ?>" hidden>
												<div class="elements_added_v2">
													<div class="element-icon">
														<i class="fas fa-code" style="font-size:25px;"></i>
													</div>
													<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
														<div class="title-desc-wrapper">
															<span class="element-title">Text/HTML Field</span>
															<p class="element-description">
																<?php echo esc_attr( $truncatedTitleElement ); ?>
															</p>
														</div>
													</div>
													<?php echo scc_output_editing_page_element_actions( 'text-html-field', false ); ?>
												</div>
												<div class="scc-element-content" value="selectoption" style="display:none; height:auto;">
													<!-- CONTENT OF EACH ELEMENT -->
													<!-- ELEMENT -->
													<div class="row m-0 selopt5 col-xs-12 col-md-12" style="padding: 0px;">
														<div class="col-xs-6 col-md-2" style="padding:0px;height:40px;background: #f8f9ff;"><span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Title</span></div>
														<div class="col-xs-6 col-md-7" style="padding:0px;"><input type="text" class="input_pad inputoption_title" onkeyup="clickedTitleElement(this)" style="height:40px;width:100%;" placeholder="Title" value="<?php echo esc_attr( wp_unslash( $el->titleElement ) ); ?>"></div>
													</div>
													<!-- ELEMENTS INSIDE ELEMENTS -->
													<div class="row m-0 mt-2 col-xs-12 col-md-12" style="padding:0px;margin-bottom:10px;margin-top: 15px;">
														<div class="col-xs-6 col-md-2" style="padding:0px;background:#f8f9ff;height:40px;">
															<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Your Custom Code</span>
														</div>
														<div class="col-xs-6 col-md-7 p-0 ">
															<div style="padding:0px;">
																<textarea data-type="<?php echo esc_html( $el->type ); ?>" onkeyup="changeValue2(this)" rows="5" cols="33" class="input_pad inputoption_text" style="width: 100%;" placeholder="<div></div>"><?php echo esc_attr( $el->value2, SCC_ALLOWTAGS ); ?></textarea>
															</div>
														</div>
													</div>
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
															<?php echo $edit_page_func->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															<div class="scc-content" style="display: none;">
																<div class="scc-transition">
																	<?php
                                                                    foreach ( $conditionsBySet as $key => $conditionCollection ) {
                                                                        ?>
																		<?php if ( $key > 1 ) { ?>
																			<p>OR condition <?php echo intval( $key ); ?></p>
																		<?php } ?>
																		<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
																			<?php
                                                                            foreach ( $conditionCollection as $index => $condition ) {
                                                                                if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
                                                                                    ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select disabled class="first-conditional-step col-xs-3" style="height: 40px;">
																										<option style="font-size: 10px" value="0"><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																									</select>
																									<select disabled class="second-conditional-step col-xs-3" style="height: 40px;">
																										<?php
                                                                                                        if ( $condition->op == 'eq' ) {
                                                                                                            echo '<option value="eq">Equal To</option>';
                                                                                                        }
                                                                                    ?>
																										<?php
                                                                                    if ( $condition->op == 'ne' ) {
                                                                                        echo '<option value="ne">Not Equal To</option>';
                                                                                    }
                                                                                    ?>
																										<?php
                                                                                    if ( $condition->op == 'any' ) {
                                                                                        echo '<option value="any">Any</option>';
                                                                                    }
                                                                                    ?>
																									</select>
																									<?php if ( $condition->op != 'any' ) { ?>
																										<select disabled class="third-conditional-step col-xs-3" style="height: 40px;">
																											<option value="eq"><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																										</select>
																									<?php } ?>
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                                                                }

                                                                                if ( $condition->elementitem_id && ! $condition->condition_element_id ) {
                                                                                    ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select disabled class="first-conditional-step col-xs-3" style="height: 40px;">
																										<option style="font-size: 10px" value="0"><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<select disabled class="second-conditional-step col-xs-3" style="height: 40px;">
																										<?php if ( $condition->op == 'chec' ) { ?>
																											<option value="">Checked</option>
																										<?php } else { ?>
																											<option value="">Unchecked</option>
																										<?php } ?>
																									</select>
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                                                                }

                                                                                if ( $condition->condition_element_id ) {
                                                                                    if ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) {
                                                                                        ?>
																						<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																							<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																							<div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">
																								<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																							</div>
																							<div class="col-xs-11 col-md-11" style="padding:0px;">
																								<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																									<div class="item_conditionals">
																										<select disabled class="first-conditional-step col-xs-3" style="height: 40px;">
																											<option style="font-size: 10px" value="0"><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																										</select>
																										<select disabled class="second-conditional-step col-xs-3" style="height: 40px;">
																											<?php
                                                                                                            if ( $condition->op == 'eq' ) {
                                                                                                                echo '<option style="font-size: 10px" value="0">Equal To</option>';
                                                                                                            }
                                                                                        ?>
																											<?php
                                                                                        if ( $condition->op == 'ne' ) {
                                                                                            echo '<option style="font-size: 10px" value="0">Not Equal To</option>';
                                                                                        }
                                                                                        ?>
																											<?php
                                                                                        if ( $condition->op == 'gr' ) {
                                                                                            echo '<option style="font-size: 10px" value="0">Greater than</option>';
                                                                                        }
                                                                                        ?>
																											<?php
                                                                                        if ( $condition->op == 'les' ) {
                                                                                            echo '<option style="font-size: 10px" value="0">Less than</option>';
                                                                                        }
                                                                                        ?>
																										</select>
																										<input disabled value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 40px;" class="conditional-number-value col-xs-2">
																										<div class="btn-group" style="margin-left: 10px;">
																											<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																						<?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        ?>
																			<!-- <div style="background-color: black;height:50px"></div> -->
																			<div class="row col-xs-12 col-md-12 conditional-selection  
																			<?php
                                                                        if ( count( $conditionCollection ) ) {
                                                                            echo 'hidden';
                                                                        }
                                                                        ?>
																			" style="padding: 0px; margin-bottom: 5px;">
																				<div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">
																					<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
																				</div>
																				<div class="col-xs-11 col-md-11" style="padding:0px;">
																					<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																						<div class="item_conditionals">
																							<select onfocus="loadDataItemsCondition(this)" onchange="loadSecondSelectCondition(this)" class="first-conditional-step col-xs-3" style="height: 40px;">
																								<option style="font-size: 10px" value="0">Select an element</option>
																							</select>
																							<select onchange="changeSecondSelectCondition(this)" class="second-conditional-step col-xs-3" style="height: 40px;display:none">
																							</select>
																							<select class="third-conditional-step col-xs-3" style="height: 40px;display:none">
																							</select>
																							<input type="number" placeholder="Number" style="height: 40px;display:none" class="conditional-number-value col-xs-2">
																							<div class="btn-group" style="margin-left: 10px;display:none">
																								<button onclick="addConditionElement(this)" class="btn btn-addcondition">Save</button>
																								<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
																			<?php
                                                                        if ( empty( count( $el->conditions ) ) ) {
                                                                            echo 'hidden';
                                                                        }
                                                                        ?>
																			">+ AND</button>
																		</div>
																	<?php } ?>
																	<div style="margin-left: auto; margin-right: auto; width: 28%">
																		<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php
                                        }

                                        if ( $el->type == 'slider' ) {
                                            ?>
											<div class="elements_added">
												<input type="text" class="input_id_element" value="<?php echo intval( $el->id ); ?>" hidden>
												<div class="elements_added_v2">
													<div class="element-icon">
														<i class="fas fa-sliders-h" style="font-size:25px;"></i>
													</div>
													<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
														<div class="title-desc-wrapper">
															<span class="element-title">Slider</span>
															<p class="element-description">
																<?php echo esc_attr( $truncatedTitleElement ); ?>
															</p>
														</div>
													</div>
													<?php echo scc_output_editing_page_element_actions( 'slider-element', false ); ?>
												</div>
												<?php echo $edit_page_func->renderSliderSetupBody2( $el, $conditionsBySet ); ?>
												<?php echo $edit_page_func->renderElementLoader(); ?>
											</div>
											<?php
                                        }
                                        ?>
										<?php
                                    }
                            ?>
									<!-- ELEMENTS SHOWS HERE -->
								</div>
								<!-- BUTTONS AREA -->
								<div class="scc-col-md-12 scc-col-xs-12">
									<label class="scc_label_2" style="margin-top:15px;margin-right: 0px !important;padding: 8px;margin-top: 20px;border-radius:6px;">
										<a style="" class="add-element-btn save_button" onclick="togglebuttonsadd(this)">
											+ Add Element
										</a>
									</label>
								</div>
								<div class="df_scc_groupbuttonsadd scc-col-md-12 scc-col-xs-12" style="margin:15px; display:none ">
								<button class="scc_button btn-backend" onclick="addSliderElement(this)">
										<div class="scc-slider-tooltip-panel use-tooltip" data-setting-tooltip-type="slider-disabled-tt" data-bs-original-title="" title=""></div>
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="fas fa-sliders-h" style="padding-top:3px;"></i>
										<div class="btn-backend-text">Slider</div>
									</button>
									<button value="number_input" class="scc_button btn-backend" onclick="addQuantityBox(this)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="material-icons" style="padding-top:3px;">exposure</i>
										<div class="btn-backend-text">Quantity Box</div>
									</button>
									<button value="dropdowninput" class="scc_button btn-backend" onclick="addDropdownMenuElement(this)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="far fa-list-alt" style="padding-top:3px;"></i>
										<div class="btn-backend-text">Dropdown</div>
									</button>
									<button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,1)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="far fa-check-square" style="padding-top:3px;"></i>
										<div class="btn-backend-text">Checkbox</div>
									</button>
									<!--<button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,3)"><i class="fas fa-toggle-off" style="padding-top:3px;"></i>
										<div class="btn-backend-text">Toggle Switch</div>
									</button>-->
									<button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,6)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="far fa-rectangle-wide" style="border:1px solid black;margin-top:5px;width:26px;height: 9px;background-color: white;"></i>
										<div class="btn-backend-text">Simple Button</div>
									</button>
									<button value="switchinput" class="scc_button btn-backend  with-tooltip" data-element-tooltip-type="image-buttons-tt" data-bs-original-title="" onclick="addCheckboxElement(this,8)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="fas fa-image" style="margin-top:3px;"></i>
										<div class="btn-backend-text">Image Button </div>
									</button>
									<input class="inputoption_slidchk" type="checkbox" onClick="addSlider(this)" style="display:none;" />
									<button class="scc_button btn-backend with-tooltip" data-element-tooltip-type="custom-math-tt" data-bs-original-title="" onclick="addCustomMath(this)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="fas fa-calculator" style="padding-top:3px;"></i>
										<div class="btn-backend-text">Custom Math</div>
									</button>
									<button value="custom_code" class="scc_button btn-backend with-tooltip" data-element-tooltip-type="variable-math-tt" data-bs-original-title="" onclick="addTextHtml(this)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="fas fa-calculator" style="padding-top:3px;"></i>
										<div class="btn-backend-text">Variable Math</div>
									</button>
									<button value="file_input" class="scc_button btn-backend with-tooltip" data-element-tooltip-type="file-upload-tt" data-bs-original-title="" onclick="addFileUpload(this)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="fas fa-paperclip" style="padding-top:3px;"></i>
										<div class="btn-backend-text">File Upload</div>
									</button>
									<button value="custom_code" class="scc_button btn-backend with-tooltip" data-element-tooltip-type="date-picker-tt" data-bs-original-title="" onclick="addTextHtml(this)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="fas fa-calendar-alt" style="padding-top:3px;"></i>
										<div class="btn-backend-text">Date Picker</div>
									</button>
									<button value="distance" class="scc_button btn-backend with-tooltip"  data-element-tooltip-type="distance-cost-tt" data-bs-original-title="" >
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="fas fa-map-marker-alt" style="padding-top:3px;margin-left:0;"></i>
										<div class="btn-backend-text">Distance-Based Cost</div>
									</button>
									<input class="inputoption_slidchk" type="checkbox" onClick="addSlider(this)" style="display:none;" />
									<button value="comment_input" class="scc_button btn-backend" onclick="addCommentBoxElement(this)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="fas fa-comment" style="padding-top:3px;"></i>
										<div class="btn-backend-text">Comment Box</div>
									</button>
									<button value="custom_code" class="scc_button btn-backend with-tooltip" data-element-tooltip-type="text-html-field-tt" data-bs-original-title="" onclick="addTextHtml(this)">
										<i class="scc-btn-spinner scc-d-none"></i>
										<i class="fas fa-code" style="padding-top:3px;"></i>
										<div class="btn-backend-text">Text/HTML Field</div>
									</button>
									<br>
									<span style="font-size:13px;margin-top:5px;">Add 1 or more elements to this subsection</span>
									<input class="scc_custom_math_checkbox" type="checkbox" style="display:none;" />
								</div>
							</div>
							<?php
                        }
                ?>
						<div class="boardOption1">
							<label class="add-subsection-btn">
								<!-- This one works -->
								<a href="javascript:void(0)" onclick="addSubSectionElement(this)" style="border-radius:6px;padding:8px;background:#314af3;color:white" class="crossnadd2">+ Add Subsection
								</a>
							</label>
						</div>
					</div>
				</div>
				<?php
            }
?>
		</div>
		<div class="scc-col-xs-6 scc-col-lg-12 scc-col-md-12 clearfix" style="width:100% !important; padding-left:0px !important;">
			<label class="scc-col-xs-12 scc-col-lg-12 scc-col-md-12 add-section-btn" style="max-width: 985px;">
				<a href="javascript:void(0)" onclick="addSectionSubsectionElement()" style="border-radius:6px;padding:8px;background:#314af3;color:white" class="crossnadd2 scc_new_sec">+ Add Section </a>
			</label>
		</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-5">
		<div class="scc-preview-pane">
			<div class="scc-pane-options row">
				<div class="col-10">
					<div class="scc-pane-title">Preview Pane</div>
					<div class="scc-pane-description">See how will look your calculator </div>
				</div>
				<div class="col-2">

				</div>
			</div>

		</div>
		<!-- PREVIEW -->
		<div class="preview_form_right_side">
			<div class="df-scc-progress df-scc-progress-striped active">
				<div class="df-scc-progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="background-color: orange; width: 100%"></div>
			</div>
		</div>
		<!-- END PREVIEW -->
		<!--Start Of Embed To Page-->
		<div class="scc-embed-tips-wrapper">
			<button id="btn_df_scc_tabembed_" class="btn btn-settings-bar" onclick="document.querySelector('#df_scc_tabembed_').classList.toggle('d-none')">
				<span>Embed To Page</span>
				<span class="material-icons-outlined">code</span>
			</button>
			<div id="df_scc_tabembed_" class="d-none">
				<div class="scc-embed-tip-wrapper">
					<h3>Embed to Page</h3>
					<div style="background:white; padding: 18px; border-radius:20px;margin-bottom:20px;margin-top:30px">
						<h3 style="font-weight:bold;font-size:24px;margin-top:0px;width: 100%;color: #314af3;border: 0px; box-shadow: none;">Calculator Form <a href="https://designful.freshdesk.com/support/solutions/articles/48000945180-adding-the-shortcode-calculator-to-your-webpage" target="_blank"><i class="material-icons-outlined" style="cursor: pointer;font-size: 20px;">help_outline</i></a></h3>
						<input disabled="" value="[scc_calculator type='text' idvalue='<?php echo intval( $f1->id ); ?>']" style="color:#000;font-size:18px;margin-top:0px;width: 100%;border: 0px; box-shadow: none;background:none!important;">
					</div>
					<div style="background:white; padding: 18px; border-radius:20px;margin-bottom:20px;">
						<h3 style="font-weight:bold;font-size:24px;margin-top:0px;width: 100%;color: #314af3;border: 0px; box-shadow: none;">Custom Calculator Totals <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001216489-custom-totals-a-complete-guide" target="_blank"><i class="material-icons-outlined" style="cursor: pointer;font-size: 20px;">help_outline</i></a></h3>
						<input disabled="" value="[scc_calculator-total type='text' idvalue='<?php echo intval( $f1->id ); ?>']" style="color:#000;font-size:18px;margin-top:0px;width: 100%;border: 0px; box-shadow: none;background:none!important;">
					</div>
					<div style="background:white; padding: 18px; border-radius:20px;margin-bottom:20px;">
						<h3 style="font-weight:bold;font-size:24px;margin-top:0px;width: 100%;color: #314af3;border: 0px; box-shadow: none;">Floating Itemized List <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001215062-floating-itemized-list-detailed-list-view-a-complete-guide" target="_blank"><i class="material-icons-outlined" style="cursor: pointer;font-size: 20px;">help_outline</i></a></h3>
						<input disabled="" value="[scc_calculator-detail type='text' idvalue='<?php echo intval( $f1->id ); ?>']" style="color:#000;font-size:18px;margin-top:0px;width: 100%;border: 0px; box-shadow: none;background:none!important;">
						This is a premium-only feature.
					</div>
					<i class="material-icons-outlined" style="cursor: pointer;font-size: 20px;">help_outline</i><span style="padding-top:20px;font-size:14px;">Copy and paste this shortcode <a href="https://designful.freshdesk.com/support/solutions/articles/48000945180-adding-the-shortcode-calculator-to-your-webpage" target="_blank"><b><i><u>properly</u></i></b></a> into a code, text, shortcode, or shortblock widget within your page builder. Do not use the visual text box.</span>
				</div>
			</div>
		</div>
		<!--End Of Embed To Page-->
	</div>
</div>
<div class="modal df-scc-modal fade in" id="webhook-setup-placeholder" style="padding-right: 0px;" role="dialog" data-backdrop="0"></div>
<script type="text/javascript">
	/** preview */
	/**
	 * *Loads the preview with page load
	 * @param id calulator
	 */
	jQuery(function() {
		var id = jQuery("#id_scc_form_").val()
		loadPreviewForm(id)
		// find existing quote form fields backend buttons and register click events on it
		addEventsToQuoteFormBtns(jQuery('.editing-action-cards.action-quoteform .btn.btn-cards:not(.btn-plus)'))

		// checking for debug points
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'scc_get_debug_items',
				nonce: pageEditCalculator.nonce
			},
			success: function(data, textStatus, request) {
				const debugItems = data.debug_items;
				const sgOptimAlert = data.sg_optimizer_alert;
				const saveCount = data.save_count;
				const isCloudFlare = request.getResponseHeader('server') == 'cloudflare';
				const diagMsgsWrapper = document.querySelector('#debug_messages_wrapper');
				const sgOptimizerAlertWrapper = document.querySelector('#sg_optimizer_message_wrapper');
				if ( sgOptimAlert.is_active && sgOptimAlert.config.show ) {
					sgOptimizerAlertWrapper.classList.remove('d-none');
				}
				if ( sgOptimAlert.is_active && !sgOptimAlert.config.show && saveCount > sgOptimAlert.config.respawn ) {
					sgOptimizerAlertWrapper.classList.remove('d-none');
				}
				diagMsgsWrapper.innerHTML = wp.template('scc-diag-alert')(debugMessages(debugItems, isCloudFlare));
				diagMsgsWrapper.classList.remove('d-none');
			}
		})
	})
	const df_scc_resources = {
		dropdownTumbnailDefaultImage: "<?php echo esc_url( SCC_ASSETS_URL . '/images/image.png' ); ?>",
		assetsPath: "<?php echo esc_url( SCC_ASSETS_URL ); ?>"
	}

	const debugMessages = (data, isCloudFlare = false) => {
		let msgs = data.diag_items
		let ignore_list = data.exclusions
		let messages = {
			php_incompatible_version: {
				title: `Bad PHP version: ${msgs.php_version}`,
				message: 'Change your PHP level in your cPanel, or ask your hosting comapny to do so.',
				show: msgs.php_supported_version == false
			},
			wp_incompatible_version: {
				title: `Incompatible WordPress version: ${msgs.wp_version}`,
				message: 'Your WordPress core version is really outdated. Please backup, then upgrade.',
				show: msgs.wp_supported_version == false
			},
			has_mod_security: {
				title: 'ModSecurity',
				message: msgs.mod_security_message,
				show: msgs.rhas_mod_security == true
			},
			bad_charset: {
				title: msgs.bad_charset_msg,
				message: 'Warning: You should edit the DB_CHARSET variable in your wp_config.php file to utf8mb4',
				show: msgs.has_good_charset == false
			},
			has_cloudflare: {
				title: 'You are using CloudFlare',
				message: 'You are using CloudFlare, please ensure you have disabled CloudFlare \'Rocket Loader\' from your CloudFlare dashboard',
				show: Boolean(isCloudFlare)
			},
			has_active_cache_js_plugin: {
				title: 'Cache/JS optimization Plugin',
				message: msgs?.cache_js_plugin_msg,
				show: msgs?.has_active_cache_js_plugin
			}
		}
		let shownMessages = Object.keys(messages).filter(msgKey => messages[msgKey].show);
		// let notShownMessages = shownMessages.filter(msgKey => messages[msgKey].show);
		Object.keys(messages).forEach(msgKey => {
			if (shownMessages.findIndex(e => e == msgKey) < 0) {
				delete messages[msgKey];
			}
		})
		ignore_list.forEach(msgKey => {
			if (shownMessages.findIndex(e => e == msgKey) >= 0) {
				delete messages[msgKey];
			}
		})
		return messages;
	}

	function loadPreviewForm(formId) {
		var previewContainer = jQuery("body").find(".preview_form_right_side")
		var load = jQuery("body").find(".loading")
		// load.empty()
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccPreviewOneForm',
				id_form: formId,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				previewContainer.html(data)
				previewContainer.trigger('previewLoaded')
				registerWebhookActions(formId)
			}
		})
	}
	/**
	 * 
	 */
	function changeColumnsCheckbox(element) {
		showLoadingChanges()
		var numberCols = jQuery(element).val()
		var id_element = jQuery(element).closest('.elements_added').find('.input_id_element').val()
		jQuery.ajax({
			url: ajaxurl,
			element,
			numberCols,
			cache: false,
			data: {
				action: 'sccUpElement',
				id_element,
				value2: numberCols,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					if (this.numberCols > 1) {
						jQuery(this.element).closest('.elements_added').find('.scc-accordion-tooltip:first input').attr('disabled', true)
						jQuery(this.element).closest('.elements_added').find('.scc-accordion-tooltip:first .tooltipadmin-right').attr('title', 'It is disabled due to "checkbox columns" feature being utilized')
					} else {
						jQuery(this.element).closest('.elements_added').find('.scc-accordion-tooltip:first input').attr('disabled', false)
						jQuery(this.element).closest('.elements_added').find('.scc-accordion-tooltip:first .tooltipadmin-right').attr('title', 'Please enter a number between 1 and 12. 1 being the smallest and 12 being the largest, for your title. If you have a large title, we recommend between 6 and 12.')
					}
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, "There was an error, please try again.")
				}
			}
		})
	}
	/** preview */
	/**
	 * *Add sortable feature to elements in subsection
	 * !impotant after element is moved the order of the element is updated
	 * !this is mage with sortable lib, it needs to be loaded
	 */
	jQuery(document).ready(function() {
		<?php
        if ( ( get_option( 'df-scc-save-count' ) !== 0 && get_option( 'df-scc-save-count' ) >= scc_feedback_invocation( 'form' ) ) && scc_feedback_invocation( 'form' ) != 0 ) {
            echo "sccBackendUtils.setupSurveyModal(document.querySelector('#user-scc-sv'));" . PHP_EOL;
        } else {
            // start the tour if it's a new page and the survey modal is not shown
            if ( isset( $_REQUEST['new'] ) ) {
                echo "sccBackendUtils.knowingEditingPageGuidedTour( 'scc-introjs-new-editing-page' );" . PHP_EOL;
            }
        }
?>

		sccBackendUtils.handleCalculatorTourLinks();

		var sortableContainers = [];
		jQuery('body').find('.subsection-area').each(function(index, ob) {
			if (!jQuery(ob).is('[class*="sortable_container"]')) {
				jQuery(ob).addClass('sortable_container_' + index);
				sortableContainers.push({
					'element': ob,
					'className': 'sortable_container_' + index
				})
			}
		})
		const barra = jQuery("#wpadminbar");
		for (var i = 0; i < sortableContainers.length; i++) {
			//console.log(sortableContainers[0].element);
			new Sortable(sortableContainers[i].element, {
				group: 'foo',
				filter: '.categor-sec-first',
				scrollSensitivity: 100,
				ghostClass: 'scc_sortable-ghosts',
				scrollSpeed: 10,
				preventOnFilter:false,
				handle: '.sortable_subsection_element',
				forceFallback: true,
				animation: 100,
				onStart: function(event) {
					barra.hide(); // element index within parent
				},
				onEnd: function (e) {
					barra.show();
					showLoadingChanges()
					var arr = []
					var idElement = jQuery(e.to).find(".input_id_element").val()
					var idSubDest = jQuery(e.to).parent().find(".input_subsection_id").val()
					var todos = jQuery(e.to).parent().find(".elements_added").each(function(index) {
						// usamos el index para actualizar los elementos
						var obj = {}
						var id = jQuery(this).find(".input_id_element").val()
						obj.id_element = id
						obj.order = index
						obj.subsection = idSubDest
						arr.push(obj)
					})
					jQuery.ajax({
						url: ajaxurl,
						cache: false,
						data: {
							action: 'sccUpElementOrder',
							arr,
							nonce: pageEditCalculator.nonce
						},
						success: function(data) {
							console.log(data)
							if (data.passed == true) {
								showSweet(true, "The changes have been saved. Test")
							} else {
								showSweet(false, "There was an error, please try again.")
							}
						}
					})
				}
			});
		}
		sortableContainers = [];
	})
	/**
	 * *Duplicates element
	 */
	function handleElementCopy(element, type = 1) {
		showLoadingChanges()
		var el = jQuery(element);
		//1 dropdown, checkboxes, coment box //2 slider
		var id_element = el.closest('.elements_added').find(".input_id_element").val();
		var clonedElement = el.closest('.elements_added').clone()
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccDuplicateElement',
				id_element: id_element,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				if (data.passed) {
					switch (type) {
						case 1:
							duplicatedDrop(data.id, data.ids, data.ids_c)
							break
						case 2:
							duplicateSlider(data.id, data.ids, data.ids_c)
							break
					}
					showSweet(true, "The element was duplicated")
				} else {
					showSweet(false, data?.error);
				}
			}
		})
		//reder duplicated element in dom
		function duplicatedDrop(id, arrayItemsIds, arrayConditional) {
			clonedElement.find(".input_id_element").attr("value", id)
			clonedElement.find(".swichoptionitem_id").each(function(e, i) {
				jQuery(this).attr("value", arrayItemsIds[e])
			})
			clonedElement.find(".id_conditional_item").each(function(e, i) {
				jQuery(this).attr("value", arrayConditional[e])
			})
			clonedElement.find("[data-element-item-id]").each(function(i, e) {
				e.setAttribute('data-element-item-id', arrayItemsIds[i])
			})
			clonedElement.css({
				border: '2px solid rgb(138, 153, 248)'
			}).delay(10000).queue(function(next) {
				jQuery(this).css({
					border: 'unset'
				});
				next();
			});
			el.closest('.elements_added').after(clonedElement);
		}

		function duplicateSlider(id, arrayItemsIds, arrayConditional) {
			clonedElement.find(".input_id_element").attr("value", id)
			// var opop = ["10","20","30","40"]
			clonedElement.find(".id_element_slider_item").each(function(e, i) {
				jQuery(this).attr("value", arrayItemsIds[e])
			})
			clonedElement.find(".id_conditional_item").each(function(e, i) {
				jQuery(this).attr("value", arrayConditional[e])
			})
			clonedElement.css({
				border: '2px solid rgb(138, 153, 248)'
			}).delay(10000).queue(function(next) {
				jQuery(this).css({
					border: 'unset'
				});
				next();
			});
			el.closest('.elements_added').after(clonedElement);
		}
	}
	/**
	 * *Show title of element in pdf
	 */
	function changeShowTitlePdf(element) {
		var element_id = jQuery(element).parentsUntil(".elements_added").parent().find(".input_id_element").val()
		var s = jQuery(element).is(":checked")
		var show_pdf
		if (s) {
			change_show("1")
		} else {
			change_show("0")
		}

		function change_show(value) {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: element_id,
					showTitlePdf: value,
					nonce: pageEditCalculator.nonce
				},
				beforeSend: function(){
					sccBackendUtils.disableSaveBtnAjax(true, element);
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false, element);
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						showSweet(true, "The changes have been saved.")
					} else {
						showSweet(false, "There was an error, please try again.")
					}
				}
			})
		}
	}
	/**
	 * *Updates database section accordion column to true or false after toggle
	 * @params section_id
	 */
	function changeAccordion(element) {
		var id = jQuery(element).parentsUntil(".addedFieldsStyle").parent().find(".id_section_class").val()
		var acordion = jQuery(element).is(":checked")
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccUpSection',
				id_section: id,
				accordion: acordion,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, "There was an error, please try again.")
				}
			}
		})
		console.log(id)
	}
	/**
	 * *Changes database section showTotalSection column after toogle
	 * @param section_id
	 */
	function changeShowSectionTotal(element) {
		var parentElement = jQuery(element).parentsUntil(".addedFieldsStyle").parent()
		var id = parentElement.find(".id_section_class").val()
		var show = jQuery(element).is(":checked")
		if (show) {
			parentElement.find(".section-total-on-pdf-container").show(300)
		}
		if (!show) {
			parentElement.find(".section-total-on-pdf-container").hide(300)
		}
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccUpSection',
				id_section: id,
				showTotal: show,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, "There was an error, please try again.")
				}
			}
		})
		console.log(id)
	}
	/**
	 * *Shows/hides Title input field in section 
	 */
	function toggleEditTitle(element) {
		var input = jQuery(element).parents(".title_section_no_edit_container").find(".scc_edit_section_input")
		var penIcon = jQuery(element).parents(".title_section_no_edit_container").find(".fa-pen.text-primary")
		jQuery(input).next().toggle()
		jQuery(input).prev().toggle()
		penIcon.toggle()
		input.toggle()
		var text = jQuery(element).parents(".title_section_no_edit_container").find(".title_section_no_edit")
		text.toggleClass('d-none')
	}
	/**
	 * *Shows/hide Description input field in section 
	 */
	function toggleEditDescription(element) {
		var textarea = jQuery(element).parents(".description_section_no_edit_container").find(".scc_section_description_textarea")
		var penIcon = jQuery(element).parents(".description_section_no_edit_container").find(".fa-pen.text-primary")
		textarea.toggle()
		textarea.prev().toggle()
		penIcon.toggle()
		var text = jQuery(element).parents(".description_section_no_edit_container").find(".description_section_no_edit")
		text.toggleClass('d-none')
	}
	/**
	 * *On keyup changes title section in db and updates title text in section
	 * @param Section_id,text
	 */
	var timeChangeTitleSection = null

	function changeTitleSection(element) {
		var idSection = jQuery(element).parents(".addedFieldsStyle").find(".id_section_class").val()
		var text = jQuery(element).parents(".title_section_no_edit_container").find(".title_section_no_edit")
		var value = jQuery(element).val()
		text.text(value)
		jQuery(element).focusout(function() {
			timeChangeTitleSection = 0
		})
		sccBackendUtils.disableSaveBtnAjax(true);
		clearTimeout(timeChangeTitleSection)
		timeChangeTitleSection = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpSection',
					id_section: idSection,
					title: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false);
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						showSweet(true, "The changes have been saved.")
					} else {
						showSweet(false, "There was an error, please try again.")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *On keyup changes description section in db and updates description text in section
	 */
	var timechangeDescriptionSection = null

	function changeDescriptionSection(element) {
		var idSection = jQuery(element).parents(".addedFieldsStyle").find(".id_section_class").val()
		var text = jQuery(element).parents(".description_section_no_edit_container").find(".description_section_no_edit")
		var value = jQuery(element).val()
		text.text(value)
		jQuery(element).focusout(function() {
			timechangeDescriptionSection = 0
		})
		sccBackendUtils.disableSaveBtnAjax(true);
		clearTimeout(timechangeDescriptionSection)
		timechangeDescriptionSection = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpSection',
					id_section: idSection,
					description: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false);
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						showSweet(true, "The changes have been saved.")
					} else {
						showSweet(false, "There was an error, please try again.")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Changes colum showFrontend of element in db after toggle 
	 * @param element_id
	 */
	function changeDisplayFrontend(element) {
		var element_id = jQuery(element).parentsUntil(".elements_added").parent().find(".input_id_element").val()
		var checked = jQuery(element).is(":checked")
		if (checked) {
			updateDisplayFrontend("1")
		} else {
			updateDisplayFrontend("0")
		}

		function updateDisplayFrontend(value) {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: element_id,
					displayFront: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						showSweet(true, "The changes have been saved.")
					} else {
						showSweet(false, "There was an error, please try again.")
					}
				}
			})
		}
	}
	/**
	 * *Changes column DisplayinDetail column of element in db after toggle
	 * @param element_id
	 */
	function changeDisplayDetail(element) {
		var element_id = jQuery(element).parentsUntil(".elements_added").parent().find(".input_id_element").val()
		var checked = jQuery(element).is(":checked")
		if (checked) {
			updateDisplayDetail("1")
		} else {
			updateDisplayDetail("0")
		}

		function updateDisplayDetail(value) {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: element_id,
					displayDetail: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						showSweet(true, "The changes have been saved.")
					} else {
						showSweet(false, "There was an error, please try again.")
					}
				}
			})
		}
	}
	/**
	 * *Changes column DisplayinDetail column of element in db after toggle for slider title
	 * @param element_id
	 */
	function toggleSliderDisplayinDetail(element) {
		var element_id = jQuery(element).parentsUntil(".elements_added").parent().find(".input_id_element").val()
		var checked = jQuery(element).is(":checked")
		if (checked) {
			updateDisplayDetail("0")
		} else {
			updateDisplayDetail("3")
		}

		function updateDisplayDetail(value) {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: element_id,
					displayDetail: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						showSweet(true, "The changes have been saved.")
					} else {
						showSweet(false, "There was an error, please try again.")
					}
				}
			})
		}
	}
	/**
	 * *Onkeyup changes column DesktopColum of element 
	 * @param element_id
	 */
	var timeColumnDesktop = null

	function changeColumnDesktop(element) {
		reloadform()
		var element_id = jQuery(element).parentsUntil(".elements_added").parent().find(".input_id_element").val()
		var value = jQuery(element).val()
		jQuery(element).focusout(function() {
			timeColumnDesktop = 0
		})
		clearTimeout(timeColumnDesktop)
		timeColumnDesktop = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: element_id,
					desktop: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						console.warn(true, "The changes have been saved.")
					} else {
						console.warn(false, "There was an error, please try again.")
					}
				}
			})
		}, 2000);
	}

	/**
	 * *Onkeyup changes mobileColumn of element
	 * @param element_id
	 */
	var timeColumnMobile = null

	function changeColumnMobile(element) {
		reloadform()
		var element_id = jQuery(element).parentsUntil(".elements_added").parent().find(".input_id_element").val()
		var value = jQuery(element).val()
		jQuery(element).focusout(function() {
			timeColumnMobile = 0
		})
		clearTimeout(timeColumnMobile)
		timeColumnMobile = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: element_id,
					mobile: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						console.warn(true, "The changes have been saved.")
					} else {
						console.warn(false, "There was an error, please try again.")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *On toggle changes Mandatory column of element in db to true or false 
	 * @param id_element
	 */
	function changeMandatoryElement(element) {
		var element_id = jQuery(element).parentsUntil(".elements_added").parent().find(".input_id_element").val()
		var checked = jQuery(element).is(":checked")
		if (checked) {
			updateMandatory("1")
		} else {
			updateMandatory("0")
		}

		function updateMandatory(value) {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					mandatory: value,
					id_element: element_id,
					nonce: pageEditCalculator.nonce
				},
				beforeSend: function(){
					sccBackendUtils.disableSaveBtnAjax(true, element);
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false, element);
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						showSweet(true, "The changes have been saved.")
					} else {
						showSweet(false, "There was an error, please try again.")
					}
				}
			})
		}
	}
	/**
	 * *On toggle changes showPriceHint column of element in db to true or false 
	 * @param element_id
	 */
	function changeShowPriceHintElement(element) {
		var element_id = jQuery(element).parentsUntil(".elements_added").parent().find(".input_id_element").val()
		var checked = jQuery(element).is(":checked")
		console.log(element_id)
		if (checked) {
			updateMandatory("1")
		} else {
			updateMandatory("0")
		}

		function updateMandatory(value) {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: element_id,
					pricehint: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						showSweet(true, "The changes have been saved.")
					} else {
						showSweet(false, "There was an error, please try again.")
					}
				}
			})
		}
	}
	/**
	 * *Onclick Deletes the condition row of db
	 * @param condition_id
	 */
	function deleteCondition(element) {
		return
	}
	/**
	 * *Onchange if 2nd select is set to any 3rd select in codition is hidden
	 */
	function changeSecondSelectCondition(element) {
		return
	}

	function addConditionElement(element) {

	}
	function toggleSliderInputBoxShowHide(element) {
		jQuery(element).removeAttr('checked')
		return
	}
	function insertConditionaDiv(divText = "And", hidden = true) {
		var element = `<div class="row col-xs-12 col-md-12 conditional-selection ${hidden ? "hidden" : ''}" style="padding: 0px; margin-bottom: 5px;">`
		element += '    <div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;"> <span class="scc_label"'
		element += `            style="text-align:right;padding-right:10px;margin-top:5px;">${divText}</span> </div>`
		element += '    <div class="col-xs-11 col-md-11" style="padding:0px;">'
		element += '        <div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">'
		element += '            <div class="item_conditionals"> <select onfocus="loadDataItemsCondition(this)"'
		element += '                    onchange="loadSecondSelectCondition(this)" class="first-conditional-step col-xs-3"'
		element += '                    style="height: 40px;">'
		element += '                    <option style="font-size: 10px" value="0">Select an element</option>'
		element += '                </select> <select onchange="changeSecondSelectCondition(this)" class="second-conditional-step col-xs-3" style="height: 40px;display:none"> </select>'
		element += '                <select class="third-conditional-step col-xs-3" style="height: 40px;display:none"> </select> <input'
		element += '                    type="number" placeholder="Number" style="height: 40px;display:none"'
		element += '                    class="conditional-number-value col-xs-2">'
		element += '                <div class="btn-group" style="margin-left: 10px;display:none"> <button'
		element += '                        onclick="addConditionElement(this)" class="btn btn-addcondition">Save</button> '
		element += '                        <button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>'
		element += '               </div>'
		element += '            </div>'
		element += '        </div>'
		element += '    </div>'
		element += '</div>'
		return element
	}

	function loadDataItemsCondition(element) {
		return
	}

	function loadSecondSelectCondition(element) {
		return
	}
	/**
	 * *Adds element to db with type of slider, after sucess adds the element to dom
	 * @param order,subsection_id
	 */
	function addSliderElement(element) {
		// showLoadingChanges()
		var subContainer = jQuery(element).parent().parent().find(".subsection-area.BodyOption")
		var idSub = jQuery(element).parent().parent().find(".input_subsection_id").val()
		var containerButtons = jQuery(element).parent()
		var count = jQuery(element).parent().parent().find(".elements_added").length + 1

		//these variables are used to hightlight the first slider added on the subsection
		let subsection = element.closest('.boardOption')
		let firstSlider = subsection.querySelector('[data-element-setup-type="slider"]');
		let firstSliderPanel = firstSlider?.closest('.elements_added');
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccAddElementSlider',
				id_sub: idSub,
				order: count,
				nonce: pageEditCalculator.nonce
			},
			srcElement: element,
			beforeSend: function () {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.add('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.remove('scc-d-none')
			},
			success: function(data) {
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					// falta pasar id
					let elementDOM = datajson.DOMhtml;
					var element = insertSliderElement(datajson.id_element, datajson.id_elementitem, elementDOM)
					element = jQuery(element);
					sccBackendUtils.handleTooltipAjaxAddedElements( element[0] );
					sccBackendUtils.handleSliderSetupBox(element.find('.scc-element-content')[0]);
					subContainer.append(element)
					containerButtons.hide()
					if (datajson.first_slider_use) {
						Swal.fire({
							title: `Slider information`,
							html: `<p> Please check this documentation in order to understand how the Slider works. </br><a class="text-dark bg-white" target="_blank" href="https://designful.freshdesk.com/support/solutions/articles/48000964920-important-understanding-the-math-of-a-slider-sub-section-or-elements">Slider Documentation</a></p>
								<p> This video tutorial may help you too: <a class="text-dark bg-white" target="_blank" href="https://www.youtube.com/watch?v=wDZZyg8U_hQ">Tutorial video</a> </p>`,
						}).then((result) => {
							// handle Ok btn click
						})
					} else {
						showSweet(true, "The changes have been saved.")
					}
				} else if (datajson.passed === false && datajson.msj === "slider already") {
					showSweet(false, "You can have only one slider per subsection")
					if(firstSliderPanel){
						firstSliderPanel.classList.add('scc-dotted-highlight');
						setTimeout(() => {
							firstSliderPanel.classList.remove('scc-dotted-highlight');
						}, 3000);
					}
				} else {
					showSweet(false, "There was an error, please try again")
				}
			},
			complete: function() {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.remove('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.add('scc-d-none')
			}
		})
	}
	/**
	 * *Adds new row range price in slider element
	 * todo: to add new row range in slider is nedded the preview max value
	 * todo: the min value in new row is disabled to be edited and is updated 
	 * todo: when the previous element max changes
	 * @param element_id, previous_max
	 */
	function addNewRangeSlider(element) {
		var elementSetupContainer = jQuery(element).closest('.elements_added')
		var previousTo = elementSetupContainer.find('.price-slider-item:last .col:eq(1) input').val()
		var container = jQuery(element).parent().parent().find(".price-range-container")
		// var item = newPriceRangeItem(previousTo, 5)
		previousTo = parseInt(previousTo) + 1
		var id_element = elementSetupContainer.find(".input_id_element").val()
		console.log(id_element)
		sccBackendUtils.disableSaveBtnAjax(true, element);
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccAddElementItemSlider',
				id_element: id_element,
				value1: previousTo,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				sccBackendUtils.disableSaveBtnAjax(false, element);
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					showSweet(true, "the value has changed")
					var item = newPriceRangeItem(previousTo, datajson.id_elementitem)
					elementSetupContainer.find('.price-slider-item:last').after(item)
					setTimeout(() => {
						sccBackendUtils.handleSliderSetupBox(elementSetupContainer[0])
					}, 1000);
				} else {
					showSweet(false, "There was an error, please try again")
				}
			}
		})

		function newPriceRangeItem(previousNumber, idnewElement) {
			var item2 = `<div data-slider-range-setup class="row g-3 price-slider-item ">
				<input data-range-id="${idnewElement}" type="text" class="id_element_slider_item" value="${idnewElement}" hidden="">
				<div class="col">
					<input class="form-control scc-input" disabled type="number" min="0" value="${previousNumber}">
				</div>
				<div class="col">
					<input class="form-control scc-input" value="${ previousNumber + 1 }" type="number" min="0">
				</div>
				<div class="col d-inline-flex scc-input-icon">
					<span class="input-group-text" style="height: fit-content;"><?php echo df_scc_get_currency_symbol_by_currency_code( $df_scc_form_currency ); ?></span>
					<input class="form-control scc-input" type="number" min="0" value="2">
				</div>
				<i onclick="deleteSliderItem(this)" class="material-icons-outlined range-close-btn">close</i>
			</div>`
			return item2
		}
	}
	
	/**
	 * *Changes the min value of the next slider range item
	 */
	function changeSliderToNumber(element) {
		reloadform()
		var number = jQuery(element).val()
		number = parseInt(number) + 1
		var input = jQuery(element).parent().parent().next("div").find(".scc_label.input_pad.sliderinputoption.scc_input")
		var input2 = jQuery(element).parent().parent().next("div").find(".scc_label.input_pad.sliderinputoption_2.scc_input")
		var id_inputChanged = jQuery(input).parent().parent().find(".id_element_slider_item").val()
		input.val(number)
		if (input.val() >= input2.val()) {
			input2.val(number + 1)
		}
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccUpElementItemSlider',
				id_element: id_inputChanged,
				value1: number,
				value2: input2.val(),
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
			}
		})
	}
	/**
	 * *Deleted Slider range row
	 * @param id_range
	 */
	function deleteSliderItem(element) {
		var id_item = jQuery(element).parent().find(".id_element_slider_item").val()
		var elementItem = jQuery(element).parent()
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccDelElementItem',
				element_id: id_item,
				nonce: pageEditCalculator.nonce
			},
			beforeSend: function(){
				sccBackendUtils.disableSaveBtnAjax(true, element);
			},
			success: function(data) {
				sccBackendUtils.disableSaveBtnAjax(false, element);
				var response = JSON.parse(data);
				if (response.passed == true) {
					elementItem.remove()
					showSweet(true, response.msj)
				} else {
					showSweet(false, response.msj)
				}
			}
		})
	}
	/**
	 * *Updates the column value1 of element in db 
	 * !this value1 is use multiple tipe for more that one element
	 * @param element_id
	 */
	var timeElementitemValue1 = null

	function changeElementItemValue1(element) {
		reloadform()
		var elementSetupContainer = jQuery(element).closest('.elements_added')
		var id_elementItem = jQuery(element).closest('.price-slider-item').find('input.id_element_slider_item').val()
		var value = jQuery(element).val()
		jQuery(element).focusout(function() {
			timeElementitemValue1 = 0
		})
		clearTimeout(timeElementitemValue1)
		timeElementitemValue1 = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElementItemSlider',
					id_element: id_elementItem,
					value1: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						console.warn(true, "the value has changed")
					} else {
						console.warn(false, "There was an error, please try again")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Updates coulmn value2 of element in db
	 * !this value2 is use multiple times for more than one element
	 * @param element_id
	 */
	var timeElementitemValue2 = null

	function changeElementItemValue2(element) {
		reloadform()
		var id_elementItem = jQuery(element).closest('.price-slider-item').find('input.id_element_slider_item').val()
		var value = jQuery(element).val()
		jQuery(element).focusout(function() {
			timeElementitemValue2 = 0
		})
		clearTimeout(timeElementitemValue2)
		timeElementitemValue2 = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElementItemSlider',
					id_element: id_elementItem,
					value2: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						console.warn(true, "the value has changed")
					} else {
						console.warn(false, "There was an error, please try again")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Updates column value3 of element in db
	 * !this value2 is use multiple times for more than one element
	 */
	var timeElementitemValue3 = null

	function changeElementItemValue3(element) {
		reloadform()
		var id_elementItem = jQuery(element).closest('.price-slider-item').find('input.id_element_slider_item').val()
		var value = jQuery(element).val()
		jQuery(element).focusout(function() {
			timeElementitemValue3 = 0
		})
		clearTimeout(timeElementitemValue3)
		timeElementitemValue3 = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElementItemSlider',
					id_element: id_elementItem,
					value3: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						console.warn(true, "the value has changed")
					} else {
						console.warn(false, "There was an error, please try again")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Adds element to db with type checkbox after success the element is added to dom
	 * @param subsection_id
	 */
	function addTextHtml(element) {
		return
	}
	/**
	 * *Adds element to db with type fileUpload after success the element is added to dom
	 * @params subsection_id
	 */
	function addFileUpload(element) {
		return
	}
	/**
	 * *Adds element to db with type CustomMath after success the element is added to dom
	 * @param subsection_id
	 */
	function addCustomMath(element) {
		return
	}
	/**
	 * *Adds element to db with type QuantityBox after success the element is added to dom
	 * @param subsection_id
	 */
	function addQuantityBox(element) {
		// showLoadingChanges()
		var subContainer = jQuery(element).parent().parent().find(".subsection-area.BodyOption")
		var idSub = jQuery(element).parent().parent().find(".input_subsection_id").val()
		var containerButtons = jQuery(element).parent()
		var count = jQuery(element).parent().parent().find(".elements_added").length + 1
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccAddElementQuantityBox',
				id_sub: idSub,
				order: count,
				nonce: pageEditCalculator.nonce
			},
			srcElement: element,
			beforeSend: function () {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.add('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.remove('scc-d-none')
			},
			success: function(data) {
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					let elementDOM = datajson.DOMhtml
					// falta pasar id
					var element = insertQuantityBox(datajson.id_element, elementDOM)
					element = jQuery(element)
					sccBackendUtils.handleTooltipAjaxAddedElements( element[0] );
					subContainer.append(element)
					containerButtons.hide()
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, "There was an error, please try again")
				}
			},
			complete: function() {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.remove('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.add('scc-d-none')
			}
		})
	}
	/**
	 * *Updates the column value4 of element in db 
	 * !this value4 is use multiple times for more than one element
	 */
	var timeElementValue4 = null

	function changeValue4(element) {
		var id_element = jQuery(element).closest('.elements_added').find(".input_id_element").val()
		var value = jQuery(element).val()
		var time = 2000
		//added for image button border
		if (jQuery(element).hasClass('scc_show_border')) {
			value = jQuery(element).prop('checked')
			time = 0
		}
		jQuery(element).focusout(function() {
			timeElementValue4 = 0
		})
		sccBackendUtils.disableSaveBtnAjax(true, element);
		clearTimeout(timeElementValue4)
		timeElementValue4 = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: id_element,
					value4: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false, element);
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						showSweet(true, "The value has changed")
					} else {
						showSweet(false, "There was an error, please try again")
					}
				}
			})
		}, time);
	}
	/**
	 * *Updates the column value3 of element in db 
	 * !this value3 is use multiple times for more than one element
	 * @param element_id
	 */
	var timeElementValue3

	function changeValue3(element) {
		reloadform()
		var el_container = jQuery(element).closest('.elements_added')
		id_element = el_container.find(".input_id_element").val()
		var value = jQuery(element).val()
		jQuery(element).focusout(function() {
			timeElementValue3 = 0
		})
		sccBackendUtils.disableSaveBtnAjax(true, element);
		clearTimeout(timeElementValue3)
		timeElementValue3 = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: id_element,
					value3: value,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false, element);
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						console.warn(true, "The placeholder has changed")
					} else {
						console.warn(false, "There was an error, please try again")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Updates the column value2 of element in db 
	 * !this value2 is use multiple times for more than one element
	 * @param element_id
	 */
	var timeElementValue2 = null

	function changeValue2(element) {
		reloadform()
		var id_element = jQuery(element).closest('.elements_added').find(".input_id_element").css("background-color", "red").val()
		var value = jQuery(element).val()
		jQuery(element).focusout(function() {
			timeElementValue2 = 0
		})
		var tt = jQuery(element).attr('data-type')
		sccBackendUtils.disableSaveBtnAjax(true, element);
		clearTimeout(timeElementValue2)
		timeElementValue2 = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: id_element,
					value2: value,
					nonce: pageEditCalculator.nonce,
					tt
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false, element);
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						console.warn(true, "the value has changed")
					} else {
						console.warn(false, "There was an error, please try again")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Adds element to db with type CommentBox after success the element is added to dom
	 * @param subsection_id
	 */
	function addCommentBoxElement(element) {
		// showLoadingChanges()
		var subContainer = jQuery(element).parent().parent().find(".subsection-area.BodyOption")
		var idSub = jQuery(element).parent().parent().find(".input_subsection_id").val()
		var containerButtons = jQuery(element).parent()
		var count = jQuery(element).parent().parent().find(".elements_added").length + 1
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccAddElementCommentBox',
				id_sub: idSub,
				order: count,
				nonce: pageEditCalculator.nonce
			},
			srcElement: element,
			beforeSend: function () {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.add('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.remove('scc-d-none')
			},
			success: function(data) {
				// var datajson = JSON.parse(data)
				if (data.passed == true) {
					// let elementDOM = data.DOMhtml
					// falta pasar id
					var element = insertCommentBoxElement(data.id_element, data.DOMhtml)
					element = jQuery(element)
					sccBackendUtils.handleTooltipAjaxAddedElements( element[0] );
					subContainer.append(element)
					containerButtons.hide()
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, "There was an error, please try again")
				}
			},
			complete: function() {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.remove('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.add('scc-d-none')
			}
		})
	}
	/**
	 * *Adds element to db with type Checkbox and value1 with the type of checkbox
	 * todo: the values for type are: 6 - simple buttons, 1 - Checkbox circle (animated 1)
	 * todo: 5 - Checkbox circle (animated 2), 2 - Checkbox Animated, 3 - Toggle Swicht (Rectangle)
	 * todo: 4 - Toggle Switch (Circle), 7 - Radio (Single Choice) 
	 * todo: 8 - Image button
	 * !this types are also passed with the bottons in view
	 * @param subsection_id
	 */
	function addCheckboxElement(element, type) {
		// showLoadingChanges()
		var subContainer = jQuery(element).parent().parent().find(".subsection-area.BodyOption")
		var idSub = jQuery(element).parent().parent().find(".input_subsection_id").val()
		var containerButtons = jQuery(element).parent()
		var count = jQuery(element).parent().parent().find(".elements_added").length + 1
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccAddElementCheckbox',
				id_sub: idSub,
				order: count,
				type: type,
				nonce: pageEditCalculator.nonce
			},
			srcElement: element,
			beforeSend: function () {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.add('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.remove('scc-d-none')
			},
			success: function(data) {
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					let elementDOM = datajson.DOMhtml
					var element = insertCheckboxElement(datajson.id_element, datajson.id_element_item, type, elementDOM)
					element = jQuery(element)

					// applying tooltips
					sccBackendUtils.handleTooltipAjaxAddedElements( element[0] );
					subContainer.append(element)
					toolPrem()
					containerButtons.hide()
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, "There was an error, please try again")
				}
			},
			complete: function() {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.remove('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.add('scc-d-none')
			}
		})
	}
	/**
	 * *Adds elementItems of element to db  
	 * @params element_id
	 */
	function addCheckboxItems(element) {
		//showLoadingChanges()
		var elementContainer = jQuery(element).closest('.elements_added');
		var itemsContainer = elementContainer.find('.selectoption_2')
		var id_element = elementContainer.find(".input_id_element").val();
		var count = jQuery(itemsContainer).find(".selopt3").length + 1
		var ocho = jQuery(element).closest("img")
		var type = elementContainer.find('select').val()
		var enableWoocommerce = sccData[getCalcId()].config["enableWoocommerceCheckout"]
		sccBackendUtils.disableSaveBtnAjax(true, element);
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccAddCheckboxItems',
				element_id: id_element,
				nonce: pageEditCalculator.nonce,
				enableWoocommerce,
				count,
				is_image_checkbox: (type == 8)
			},
			success: function(data) {
				sccBackendUtils.disableSaveBtnAjax(false, element);
				var response = JSON.parse(data);
				if (response.passed == true) {
					// var newElementitem = newElementItemCheckbox(response.id_element, count, type);
					itemsContainer.append(response.DOMhtml)
					toolPrem()
					showSweet(true, response.msj)
				} else {
					showSweet(false, response.msj)
				}
				console.log(response.msj)
			}
		})
	}
	/**
	 * *Updates the column value1 of element in db 
	 * !this value1 is use multiple times for more than one element
	 * @param element_id
	 */
	function changeValue1(element) {
		var selected = jQuery(element).val()
		// determine if the function is called from a slider price structure setup
		var isPricingStructureChoice = jQuery(element).hasClass('pricing-structure-dd')
		var elementSetupContainer = jQuery(element).closest('.elements_added')
		//added to change element header on change
		var elementHeader = ''
		switch (selected) {
			case '1':
			case '5':
			case '2':
				elementHeader = 'Checkboxes'
				break
			case '6':
				elementHeader = 'Simple Buttons'
				break
			case '3':
			case '4':
				elementHeader = 'Toggle Switches'
				break
			case '7':
				elementHeader = 'Radio Buttons'
				break
			case '8':
				elementHeader = 'Image Buttons'
				break
			default:
				elementHeader = 'Checkbox/Toggle/Button'
				break
		}
		elementSetupContainer.find('.scc-title-type-element').html(elementHeader)
		if (selected == 8) {
			elementSetupContainer.find('.checkbox-image').css('display', '')
			elementSetupContainer.find('.image-button-border').css('display', '')
		} else {
			elementSetupContainer.find('.checkbox-image').css('display', 'none')
			elementSetupContainer.find('.image-button-border').css('display', 'none')
		}
		var id_element = elementSetupContainer.find(".input_id_element").val()

		if (isPricingStructureChoice) {
			let priceRanges = elementSetupContainer.find('.price-slider-item')
			let sliderPriceTitle = '';
			switch (selected) {
				case 'default':
					sliderPriceTitle = "Price Per Unit";
					break;
				case 'bulk':
					sliderPriceTitle = "Price Per Unit";
					break;
				case 'sliding':
					sliderPriceTitle = "Price For Range";
					break;
				case 'quantity_mod':
					sliderPriceTitle = "(not used for this mode)";
					break;
				default:
					// 
					break;
			}
			elementSetupContainer.find('.price-slider-item-header .col:last .form-label').text(sliderPriceTitle)
			let tooltipTarget = elementSetupContainer.find('.material-icons-outlined.v-align-middle')
			tooltipTarget.attr('data-element-tooltip-type', 'slider-type-' + selected)
			let tooltip = bootstrap.Tooltip.getInstance(tooltipTarget[0])
			tooltip && tooltip.dispose()
			applyElementTooltip(tooltipTarget[0])
			if (["quantity_mod", "default"].some(e => e == selected)) {
				selected == "quantity_mod" ?
					elementSetupContainer.find('.price-slider-item:first .col:last input').prop('disabled', true) :
					elementSetupContainer.find('.price-slider-item:first .col:last input').prop('disabled', false)
				elementSetupContainer.find('.link-primary.text-decoration-none').parent().addClass('d-none')
				if (priceRanges.length > 1) {
					elementSetupContainer.find('.price-slider-item:not(:eq(0))').addClass('d-none')
				}
			} else {
				elementSetupContainer.find('.price-slider-item:first .col:last input').prop('disabled', false)
				elementSetupContainer.find('.link-primary.text-decoration-none').parent().removeClass('d-none')
				if (priceRanges.length > 1) {
					elementSetupContainer.find('.price-slider-item:not(:eq(0))').removeClass('d-none')
				}
			}
		}

		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccUpElement',
				id_element: id_element,
				typecheckbox: selected,
				nonce: pageEditCalculator.nonce
			},
			beforeSend: function(){
				sccBackendUtils.disableSaveBtnAjax(true, element);
			},
			success: function(data) {
				sccBackendUtils.disableSaveBtnAjax(false, element);
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, "There was an error, please try again")
				}
			}
		})
	}
	/**
	 * *Adds element to db with type of Dropdown
	 * @param subsection_id
	 */
	function addDropdownMenuElement(element) {
		// showLoadingChanges()
		var subContainer = jQuery(element).parent().parent().find(".subsection-area.BodyOption")
		var idSub = jQuery(element).parent().parent().find(".input_subsection_id").val()
		var containerButtons = jQuery(element).parent()
		var count = jQuery(element).parent().parent().find(".elements_added").length + 1
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccAddElementDropdownMenu',
				id_sub: idSub,
				order: count,
				nonce: pageEditCalculator.nonce
			},
			srcElement: element,
			beforeSend: function () {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.add('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.remove('scc-d-none')
			},
			success: function(data) {
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					let elementDOM = datajson.DOMhtml
					var element = insertDropdownElement(datajson.id_element, datajson.id_element_item, elementDOM)
					element = jQuery(element)
					sccBackendUtils.handleTooltipAjaxAddedElements( element[0] );
					subContainer.append(element)
					toolPrem()
					containerButtons.hide()
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, "There was an error, please try again")
				}
			},
			complete: function() {
				let {srcElement} = this
				srcElement.querySelectorAll(':scope > :not(i.scc-btn-spinner)').forEach(el => el.classList.remove('scc-d-none'))
				srcElement.querySelector(':scope > i.scc-btn-spinner').classList.add('scc-d-none')
			}
		})
	}
	/**
	 * *Updates the title of the elements
	 * todo: Not all elements have title
	 * @param element_id
	 */
	var timeTitledropdown = null

	function clickedTitleElement(element) {
		reloadform()
		var elementSetupContainer = jQuery(element).closest('.elements_added')
		var id_element = elementSetupContainer.find(".input_id_element").val()
		var title_element_dom = elementSetupContainer.find(".element-description")
		var text = jQuery(element).val()
		var truncatedText = truncateElementTitle(text, 30)
		jQuery(title_element_dom).text(truncatedText)
		jQuery(element).focusout(function() {
			timeTitledropdown = 0
		});
		sccBackendUtils.disableSaveBtnAjax(true, element);
		clearTimeout(timeTitledropdown)
		timeTitledropdown = setTimeout(() => {
			if (text == "") {
				showSweet(false, "the title is empty")
				return
			}
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElement',
					id_element: id_element,
					title: text,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false, element);
					var datajson = JSON.parse(data)
					if (datajson.passed == true) {
						console.warn(true, "The changes have been saved.")
					} else {
						console.warn(false, "There was an error, please try again")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Updates name elementItem of DropdownElement 
	 * @param elementItem_id
	 */
	var titmeNameElementItem = null

	function changeNameElementItem(element, idFromDataAttribute = false) {
		reloadform()
		var id_elemntItem = jQuery(element).parent().parent().find(".swichoptionitem_id").val()
		if (idFromDataAttribute) {
			id_elemntItem = jQuery(element).closest(".dd-item-field-container").data('elementItemId')
		}
		jQuery(element).focusout(function() {
			titmeNameElementItem = 0
		});
		var name = jQuery(element).val();
		sccBackendUtils.disableSaveBtnAjax(true, element);
		clearTimeout(titmeNameElementItem)
		titmeNameElementItem = setTimeout(() => {
			// showLoadingChanges()
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElementItemSwichoption',
					id_elementitem: id_elemntItem,
					name: name,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false, element);
					if (data.passed == true) {
						console.warn(true, "The changes have been saved.")
					} else {
						console.error(false, "There was an error, please try again")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Updates description elementItem of Dropdown 
	 * @param elementItem_id
	 */
	var timeDescriptionElementItem = null

	function changeDescriptionElementItem(element, idFromDataAttribute = false) {
		reloadform()
		var id_elemntItem = jQuery(element).parent().parent().find(".swichoptionitem_id").val()
		if (idFromDataAttribute) {
			id_elemntItem = jQuery(element).closest(".dd-item-field-container").data('elementItemId')
		}
		var description = jQuery(element).val();
		jQuery(element).focusout(function() {
			timeDescriptionElementItem = 0
		})
		sccBackendUtils.disableSaveBtnAjax(true, element);
		clearTimeout(timeDescriptionElementItem)
		timeDescriptionElementItem = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElementItemSwichoption',
					id_elementitem: id_elemntItem,
					description: description,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false, element);
					if (data.passed == true) {
						console.warn(true, "The changes have been saved.")
					} else {
						console.warn(false, "There was an error, please try again")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Updates price elementItem of DropdownElement
	 * @param elementItem_id
	 */
	var timePriceElementItem = null

	function changePriceElementItem(element, idFromDataAttribute = false) {
		reloadform()
		var id_elemntItem = jQuery(element).parent().parent().find(".swichoptionitem_id").val()
		if (idFromDataAttribute) {
			id_elemntItem = jQuery(element).closest(".dd-item-field-container").data('elementItemId')
		}
		var price = jQuery(element).val()
		jQuery(element).focusout(function() {
			timePriceElementItem = 0
		})
		sccBackendUtils.disableSaveBtnAjax(true, element);
		clearTimeout(timePriceElementItem)
		timePriceElementItem = setTimeout(() => {
			jQuery.ajax({
				url: ajaxurl,
				cache: false,
				data: {
					action: 'sccUpElementItemSwichoption',
					id_elementitem: id_elemntItem,
					price: price,
					nonce: pageEditCalculator.nonce
				},
				success: function(data) {
					sccBackendUtils.disableSaveBtnAjax(false, element);
					if (data.passed == true) {
						console.warn(true, "The changes have been saved.")
					} else {
						console.warn(false, "There was an error, please try again")
					}
				}
			})
		}, 2000);
	}
	/**
	 * *Updates column default elementItems in db
	 * @param {boolean} multi
	 * @param {dom} element
	 * !two types multiple and single
	 */
	function setDefaultOption(element, multi = true, idFromDataAttribute = false) {
		return
	}
	/**
	 * *Loads to dom selected image of slider dropdown elementitem   
	 */
	function choseImageElementItem(element) {
		event.preventDefault();
		formField = jQuery(element);
		if (window.hasOwnProperty('mediaUploader')) {
			mediaUploader.open();
			return;
		}
		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});
		mediaUploader.on("select", onEditFormMediaImageSelect);
		mediaUploader.open();
	}
	/**
	 * *Update value1 column of elementItems in db with selected image in dropdown
	 * @param elementItem_id
	 */
	function onEditFormMediaImageSelect() {
		var attachment = mediaUploader.state().get('selection').first().toJSON()
		var field = formField;
		field.attr('src', attachment.sizes.thumbnail.url);
		var src = field.attr("src")
		var id_elementitem = jQuery(field).parent().parent().find(".swichoptionitem_id").val()
		if (!id_elementitem) {
			id_elementitem = jQuery(field).closest(".dd-item-field-container").data('elementItemId')
		}
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccUpElementItemSwichoption',
				id_elementitem: id_elementitem,
				image: src,
				nonce: pageEditCalculator.nonce
			},
			beforeSend: function(){
				sccBackendUtils.disableSaveBtnAjax(true);
			},
			success: function(data) {
				sccBackendUtils.disableSaveBtnAjax(false);
				if (data.passed == true) {
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, "There was an error, please try again")
				}
			}
		})
	}
	/**
	 * *Adds section and subsection in db after success the section and subsection is added to dom
	 * @param form_id, order
	 */
	function addSectionSubsectionElement() {
		showLoadingChanges()
		var numberSections = jQuery("body").find("#allinputstoadd").find(".addedFieldsStyle").length;
		var container = jQuery("body").find("#allinputstoadd")
		var order = numberSections + 1;
		var id_form = jQuery("#id_form_input").val();
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccSaveSection',
				id_form: id_form,
				order: order,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				var response = JSON.parse(data);
				console.log(response)
				if (response.passed == true) {
					var id_newSection = response.id_section;
					var id_newSubsection = response.id_subsection;
					var newSection = insertSection(id_newSection, id_newSubsection);
					newSection = jQuery(newSection);
					newSection.find('.btnn.material-icons').each((index, element) => {
						new bootstrap.Tooltip(element, {
							delay: { show: 600, hide: 300 },
							trigger: 'hover focus',
							html: true
						});
					});
					container.append(newSection);
					document.querySelectorAll('.scc_button.btn-backend').forEach(function(e){
						unabled(e)
					})
					// agregamos a dom si no mostramos un error para que actualize
					showSweet(true, response.msj);
				} else {
					showSweet(false, response.msj);
				}
			}
		})
	}
	/**
	 * *Adds subsection in db after success the its added to dom
	 * @param section_id, order
	 */
	function addSubSectionElement(element) {
		showLoadingChanges()
		// var tag = jQuery(element).parent().parent().parent().find(".boardOption").last().css("background-color","red")
		var containerAfter = jQuery(element).closest(".fieldDatatoAdd").parent().find(".boardOption").last() //ADD AFTER THIS
		var numberofsubsections = jQuery(element).parent().parent().parent(".fieldDatatoAdd").find(".boardOption").length;
		var ordersubsection = numberofsubsections + 1;
		var sectionid = jQuery(element).parent().parent().parent().parent().find(".id_section_class").val();
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccAddSubsection',
				order: ordersubsection,
				section_id: sectionid,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				console.log(data)
				var response = JSON.parse(data);
				if (response.passed == true) {
					var id_newSubsection = response.id_subsection;
					var newSubsection = insertSubSection(id_newSubsection);
					if (containerAfter.length <= 0) {
						containerAfter = jQuery(element).closest(".fieldDatatoAdd")
						containerAfter.prepend(newSubsection)
					} else {
						jQuery(newSubsection).insertAfter(containerAfter)
					}
					document.querySelectorAll('.scc_button.btn-backend').forEach(function(e){
						unabled(e)
					})
					// agregamos a dom si no mostramos un error para que actualize
					showSweet(true, response.msj)
				} else {
					showSweet(false, response.msj)
				}
				console.log(response.msj)
			}
		})
	}
	/**
	 * *show success toast message after any change in element
	 * !this is mage with sweetalert2 lib, needs to be loaded
	 * todo: if there is any error in update shows error toast message
	 */
	function showSweet(respuesta, message) {
		if (respuesta) {
			Swal.fire({
				toast: true,
				title: message,
				icon: "success",
				showConfirmButton: false,
				timer: 3000,
				position: 'top-end',
				background: 'white',
			})
			var id_form = jQuery("#id_scc_form_").val()
			jQuery(".preview_form_right_side").html(`<div class="df-scc-progress df-scc-progress-striped active">
				<div class="df-scc-progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="background-color: orange; width: 100%">
				</div>
				</div>`);
			loadPreviewForm(id_form)
		} else {
			Swal.fire({
				toast: true,
				title: message,
				icon: "error",
				showConfirmButton: false,
				timer: 5000,
				position: 'top-end',
				background: 'white',
			})
		}
	}
	/**
	 * *Adds elementItem to dropdown element after success its added to dom
	 * @param element_id
	 */
	function addOptiontoSelect(element) {
		//showLoadingChanges()
		
		var container = jQuery(element).parent().find(".selectoption_2")
		var id_element = jQuery(element).closest('.elements_added').find(".input_id_element").val();
		var count = jQuery(container).find(".selopt3").length + 1
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccAddElementSwichoption',
				element_id: id_element,
				nonce: pageEditCalculator.nonce,
				itemCount: count,
				enableWoocommerce: sccData[getCalcId()].config["enableWoocommerceCheckout"]
			},
			beforeSend: function(){
				sccBackendUtils.disableSaveBtnAjax(true, element);
			},
			success: function(data) {
				sccBackendUtils.disableSaveBtnAjax(false, element);
				var response = JSON.parse(data);
				if (response.passed == true) {
					container.append(response.html)
					showSweet(true, response.msj)
					toolPrem()
				} else {
					showSweet(false, response.msj)
				}
				// console.log(response.msj)
			}
		})
	}
	/**
	 * *Shows/hides advance option content of elements
	 */
	function showAdvanceoptions(element) {
		var advance = jQuery(element).next(".scc-content")
		advance.toggle();
		disabledInput(advance)
	}
	function disabledInput(toogle){
		let input = toogle.find('[name="scc_show_inputbox_slider"]')
		if(!input.length) return
		let oo = toogle.find('[name="scc_show_inputbox_slider"]').closest('div')
		input.attr('disabled',true)
		input.removeAttr('checked')
		oo.css('width','fit-content')
		new bootstrap.Tooltip(oo, {
			delay: { show: 600, hide: 300 },
			trigger: 'hover focus',
			html: true,
			title: needLicenseKeyTooltip,
			placement: 'right'
		})
	}
	/**
	 * *Shows/hides conditional content of elements
	 */
	function showConditionaLogic(element) {
		element = jQuery(element);
		if (element.attr('disabled')) return;
		var conditional = element.next(".scc-content");
		conditional.toggle();
	}
	var array = <?php echo isset( $f1->formstored ) ? json_encode( $f1->formstored ) : json_encode( [] ); ?>;
	console.log(array);
	jQuery.each(array, (i, value) => {
		console.log(i);
		console.log(value);
		var sec = populate_sections(value.name, value.desc);
		// console.log(sec);
		//jQuery("#opopi").append(sec);
	});
	/**
	 * *Deletes element of database after success its deleted of dom
	 * @param element_id
	 */
	function removeElement(element1) {
		showLoadingChanges()
		var element = jQuery(element1).closest(".elements_added");
		var id_element = jQuery(element).find(".input_id_element").val();
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccDelElement',
				element_id: id_element,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				var response = JSON.parse(data);
				if (response.passed == true) {
					element.remove()
					// agregamos a dom si no mostramos un error para que actualize
					showSweet(true, response.msj)
				} else {
					showSweet(false, response.msj)
				}
				console.log(response.msj)
			}
		})
	}
	/**
	 * *Deletes elementitem of dropdown element after success its deleted of dom
	 * @param element_id
	 */
	function removeSwitchOptionDropdown(element, idFromDataAttribute = false) {
		var elementitem = jQuery(element).closest(".selopt3")
		var idElementItem = jQuery(elementitem).find(".swichoptionitem_id").val()
		if (idFromDataAttribute) {
			elementitem = jQuery(element).closest(".dd-item-field-container")
			idElementItem = elementitem.data('elementItemId')
		}
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccDelElementItem',
				element_id: idElementItem,
				nonce: pageEditCalculator.nonce
			},
			beforeSend: function(){
				sccBackendUtils.disableSaveBtnAjax(true, element);
			},
			success: function(data) {
				sccBackendUtils.disableSaveBtnAjax(false, element);
				var response = JSON.parse(data);
				if (response.passed == true) {
					elementitem.remove()
					showSweet(true, response.msj)
				} else {
					showSweet(false, response.msj)
				}
			}
		})
	}
	/**
	 * *Shows/hides add elements buttons 
	 */
	function togglebuttonsadd(element) {
		let groupButtons = element.closest('div').nextElementSibling;
		let subsection = element.closest('.boardOption')
		let sliderElement = subsection.querySelector('[data-element-setup-type="slider"]');
		let sliderButton = groupButtons.firstElementChild;
		let sliderTooltip = sliderButton.querySelector('.scc-slider-tooltip-panel');
		//if slider element is present, disable add button
		if(sliderElement){
			sliderTooltip.style.display = 'block';
			sliderButton.style.opacity = 0.5;
			sliderButton.setAttribute('data-setting-tooltip-type', 'add-container-tt');
			sliderButton.setAttribute('data-bs-original-title', '');
			sliderButton.setAttribute('title', '');
		}else{
			if( sliderTooltip ){
				sliderTooltip.style.display = 'none';
			}
			
			sliderButton.style.opacity = 1;
		}
		//toggle buttons group
		if (groupButtons.style.display === 'none') {
			groupButtons.style.display = '';
		} else {
			groupButtons.style.display = 'none';
		}
	}
	/**Collapses/extends elements*/
	function collapseElement(item) {
		var s = jQuery(item).closest('.elements_added').find(".scc-element-content");
		var elementTitleNode = jQuery(item).closest('.elements_added').find('.element-title-desc');
		let relatedElementsForCollapsing = [...document.querySelectorAll('.element-title-desc')].filter(e => e !== elementTitleNode[0] )
		s.slideToggle(function() {
			if (jQuery(this).is(":visible")) {
				// jQuery(this).parent().css('border', '2px solid rgb(138, 153, 248)')
				jQuery('.slider-setup-body').css('border', 'none')
				jQuery('.scc-element-content').css('border', 'none')
				relatedElementsForCollapsing.forEach((e, key) => {
					let target = jQuery(e).closest('.elements_added').find(".scc-element-content");
					var textNode = jQuery(e).closest('.elements_added').find('.element-action-icons .material-icons-outlined:first');
					let targetVisible = target.is(':visible');
					if (targetVisible) {
						textNode.text(function(index, text) {
							return text === "expand_more" ? "expand_less" : "expand_more";
						});
						jQuery(target).hide().parent().css('border', '');
					}
					if ( key + 1 == relatedElementsForCollapsing.length && targetVisible ) {
						window.scrollTo(0, sccGetOffset(jQuery(this).parent()[0]).top - 80)
					}
				})
			} else {
				jQuery(this).parent().css('border', '')
			}
		});
		jQuery(item).text(function(index, text) {
			return text === "expand_more" ? "expand_less" : "expand_more";
		});
	}
	/**Collapses/extends elements for titles*/
	function collapseElementTitle(item) {
		var s = jQuery(item).closest('.elements_added').find(".scc-element-content");
		let relatedElementsForCollapsing = [...document.querySelectorAll('.element-title-desc')].filter(e => e !== item );
		var textNode = jQuery(item).closest('.elements_added').find('.element-action-icons .material-icons-outlined:first');
		s.slideToggle(function() {
			if (jQuery(this).is(":visible")) {
				jQuery(item).closest('.elements_added').css('border', '2px solid rgb(138, 153, 248)');
				let elementEditBoxType = this.getAttribute('data-element-setup-type');
				if (elementEditBoxType == 'slider') {
					sccBackendUtils.handleSliderSetupBox(this);
				}
				relatedElementsForCollapsing.forEach((e, key) => {
					let target = jQuery(e).closest('.elements_added').find(".scc-element-content");
					var textNode = jQuery(e).closest('.elements_added').find('.element-action-icons .material-icons-outlined:first');
					let targetVisible = target.is(':visible');
					if (targetVisible) {
						textNode.text(function(index, text) {
							return text === "expand_more" ? "expand_less" : "expand_more";
						});
						jQuery(target).hide().parent().css('border', '');
					}
					if ( key + 1 == relatedElementsForCollapsing.length && targetVisible ) {
						window.scrollTo(0, sccGetOffset(jQuery(this).parent()[0]).top - 80)
					}
				})
			} else {
				jQuery(item).closest('.elements_added').css('border', '')
			}
		});
		textNode.text(function(index, text) {
			return text === "expand_more" ? "expand_less" : "expand_more";
		});
	}
	/**
	 * *Shows section settings 
	 */
	function settingsIconShow(settingsIcon) {
		// Toggle 'is-open' class for the settingsIcon
		settingsIcon.classList.toggle( 'is-open' );
		// Toggle 'scc-accordion-tooltip-hidden' class for the grandparent's next sibling
		let grandParent = settingsIcon.closest( '.scc-section-setting-container' );
		let nextSibling = grandParent.nextElementSibling;
		nextSibling.classList.toggle( 'scc-accordion-tooltip-hidden' );

		// Named function expression
		let outsideClickListener = function( event ) {
			// Check if the click was outside the accordion
			if ( !grandParent.contains( event.target ) && event.target !== settingsIcon && !nextSibling.contains( event.target ) ) {
				// Hide the accordion
				settingsIconHide( settingsIcon );
				// Remove the event listener to avoid multiple listeners
				document.removeEventListener( 'click', outsideClickListener );
			}
		};
		// Add an event listener to the document to detect clicks outside the accordion
		document.addEventListener( 'click', outsideClickListener );
	}
	/**
	 * *Hides section settings
	 */
	function settingsIconHide(settingsIcon) {
		// Find the closest '.addedFieldsStyle' and its '#settings-btn'
		let sectionGear = settingsIcon.closest( '.addedFieldsStyle' ).querySelector( '#settings-btn' );
		
		// Toggle 'is-open' class for sectionGear
		if (sectionGear) {
			sectionGear.classList.toggle( 'is-open' );
		}
		// Find all '.scc-accordion-container' inside the grandparent and toggle the classes
		let grandParent = settingsIcon.parentElement.parentElement.parentElement;
		let accordionContainers = grandParent.querySelectorAll( '.scc-accordion-container' );

		accordionContainers.forEach(function(ob) {
			ob.classList.toggle('scc-accordion-tooltip-hidden');
		});
	}
	/**
	 * *Deletes section in db after success its deleted from dom
	 * @param section_id
	 */
	function removeSection(section1) {
		showLoadingChanges()
		// REMOVE FROM DOM AND FROM DB
		var section = jQuery(section1).parent().parent().parent(".addedFieldsStyle");
		var id_section = jQuery(section).find(".id_section_class").val();
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccDelSection',
				id_section: id_section,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				var response = JSON.parse(data);
				if (response.passed == true) {
					section.remove();
					// agregamos a dom si no mostramos un error para que actualize
					showSweet(true, response.msj)
				} else {
					showSweet(false, response.msj)
				}
				// console.log(response.msj)
			}
		})
	}
	/**
	 * *Removes subsection in db after success its deleted from dom
	 */
	function removeSubsection(element) {
		showLoadingChanges()
		var id_subsection = jQuery(element).parent().parent().parent().find(".input_subsection_id").val();
		// its removing the fist subsection istead of the section corespondant
		var subsection = jQuery(element).parent().parent().parent(".boardOption")
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccDelSubsection',
				id_subsection: id_subsection,
				nonce: pageEditCalculator.nonce
			},
			success: function(data) {
				var response = JSON.parse(data);
				if (response.passed == true) {
					subsection.remove();
					// agregamos a dom si no mostramos un error para que actualize
					showSweet(true, response.msj)
				} else {
					showSweet(false, response.msj)
				}
			}
		})
	}
	/**
	 * *It moves section down
	 * !its not implemented yet with db, must be implemented
	 * ?this only changes in dom
	 */
	function rdown($this) {
		var wrapper = jQuery($this).closest('.addedFieldsStyle'); //.closest('div');
		wrapper.insertAfter(wrapper.next());
	}
	/**
	 * *Checkbox element to be inserted in dom after success insert in db
	 * @param element_id, order
	 */
	function newElementItemCheckbox(idnewitem, count, type) {
		var item = '<div class="row m-0 selopt3 col-md-12 col-xs-12" style="margin-bottom:5px;padding:0px">'
		item += '   <div class="row">'
		item += '    <div class="row p-0 m-0 mt-2 col-md-11 col-xs-11">'
		item += '        <input class="swichoptionitem_id" type="text" value="' + idnewitem + '" hidden="">'
		item += '        <div class="col-xs-2 col-md-2 tooltipadmin-left" onclick="setDefaultOption(this)" id="dropdownOpt" style="padding: 0px; height: 40px;" data-tooltip="Click me make this option default.">'
		item += '            <label class="" style="float: none;margin-top:10px;font-size:14px;font-weight: normal;">' + count + '</label>'
		item += '        </div>'
		item += '        <div class="col-md-5 col-xs-6" style="padding: 0px 5px 0px 1px;">'
		item += '            <input type="text" onkeyup="changeNameElementItem(this)" class="input_pad inputoption" style="width:100%;height:40px;" value="Name" placeholder="Product or service name">'
		item += '        </div>'
		item += '        <div class="col-md-2 col-xs-3" style="padding:0px">'
		item += '            <input type="number" onchange="changePriceElementItem(this)" onkeyup="changePriceElementItem(this)" class="input_pad inputoption_2" style="width:100%;text-align:center;height:40px;" placeholder="Price" value="10">'
		item += '        </div>'
		item += '        <div class="col-md-1 col-xs-1" style="padding:0px;">'
		item += '        </div>'
		item += '    </div>'
		item += '    <div class="col-md-1 col-xs-1" style="padding-left:0">'
		item += '        <button onclick="removeSwitchOptionDropdown(this)" class="deleteBackendElmnt"><i class="fa fa-trash"></i></button>'
		item += '    </div>'
		// <!-- Added for image button -->    
		item += '<div class="col-md-2 col-xs-2 checkbox-image" style="padding:5px;'
		if (type != 8) {
			item += 'display:none'
		}
		item += '">'
		item += '    <img class="scc-image-picker" style="height: 50px;width:50px;object-fit:contain;" onclick="choseImageElementItem(this)" src="<?php echo esc_url( SCC_URL . '/assets/images/image.png' ); ?>" title="Pick an image. Please choose an image with a 1:1 aspect ratio for best results.">'
		item += '    <span class="scc-dropdown-image-remove" onclick="removeDropdownImage(this)">x</span>'
		item += '</div>'
		// <!-- Added for image button -->   
		item += '   </div>'
		item += '</div>'
		return item;
	}
	/**
	 * *Subsection to be inserted in dom after success insert in db
	 * @param subsection id
	 */
	function insertSubSection(idsubsection) {
		var subs = '                            <div class="boardOption" style="border: 2px solid rgb(138, 153, 248);">'
		subs += '                                <input class="input_subsection_id" type="text" value="' + idsubsection + '" hidden="">'
		subs += '                                <div class="scc-subsection">'
		subs += '									<div>'
		subs += '										<button class="collapsible subsect-title">Subsection <i class="material-icons-outlined with-tooltip"  data-setting-tooltip-type="subsection-note-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i></button>'
		subs += '										<div class="scc_help_btn_right" style="left:26px;float:right;top:23px;font-size:18px;"></div>'
		subs += '									</div>'				
		subs += '									<div class="scc-section-setting-bar">'
		subs += '										<button id="close-btn" class="scc-section-setting-btn" title="Delete Subsection" onclick="preDeletionDialog(\'subsection\', removeSubsection, this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['x'] ); ?></span></button>'
		subs += '									</div>'
		subs += '								</div>'
		subs += '                                <div class="subsection-area BodyOption ">'
		subs += '                                                                        <!-- ELEMENTS SHOWS HERE -->'
		subs += '                                </div>'
		subs += '                                 <!-- BUTTONS AREA -->'
		subs += '                                  <div class="scc-col-md-12 scc-col-xs-12">'
		subs += '                                      <label class="scc_label_2" style="margin-top:15px;margin-right: 0px !important;padding: 8px;margin-top: 20px;border-radius:6px;">'
		subs += '                                          <a class="add-element-btn save_button" onclick="togglebuttonsadd(this)">'
		subs += '                                              + Add Element'
		subs += '                                          </a>'
		subs += '                                      </label>'
		subs += '                                  </div>'
		subs += '                                  <div class="df_scc_groupbuttonsadd scc-col-md-12 scc-col-xs-12" style="margin:15px; display:none ">'
		subs += '                                      <button class="scc_button btn-backend" onclick="addSliderElement(this)"><div class="scc-slider-tooltip-panel use-tooltip" data-setting-tooltip-type="slider-disabled-tt" data-bs-original-title="" title=""></div><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-sliders-h" style="padding-top:3px;font-size:13px;"></i>'
		subs += '                                          <div class="btn-backend-text">Slider</div>'
		subs += '                                      </button>'
		subs += '                                      <button value="number_input" class="scc_button btn-backend" onclick="addQuantityBox(this)"><i class="scc-btn-spinner scc-d-none"></i><i class="material-icons" style="padding-top:3px;font-size:13px;">exposure</i>'
		subs += '                                          <div class="btn-backend-text">Quantity Box</div>'
		subs += '                                      </button>'
		subs += '                                      <button value="dropdowninput" class="scc_button btn-backend" onclick="addDropdownMenuElement(this)"><i class="scc-btn-spinner scc-d-none"></i><i class="far fa-list-alt" style="padding-top:3px;font-size:13px;"></i>'
		subs += '                                          <div class="btn-backend-text">Dropdown</div>'
		subs += '                                      </button>'
		subs += '                                      <button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,1)"><i class="scc-btn-spinner scc-d-none"></i><i class="far fa-check-square" style="padding-top:3px;font-size:13px;"></i>'
		subs += '                                          <div class="btn-backend-text">Checkbox</div>'
		subs += '                                      </button>'
		subs += '                                      <button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,3)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-toggle-off" style="padding-top:3px;font-size:13px;"></i>'
		subs += '                                          <div class="btn-backend-text">Toggle Switch</div>'
		subs += '                                      </button>'
		subs += '                                      <button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,6)"><i class="scc-btn-spinner scc-d-none"></i><i class="far fa-rectangle-wide" style="margin-top:5px;font-size:13px;width:26px;height: 9px;background-color: white;"></i>'
		subs += '                                          <div class="btn-backend-text">Simple Button </div>'
		subs += '                                      </button>'
		subs += '                                    <button value="switchinput" class="scc_button btn-backend scc-premium-element" onclick="addCheckboxElement(this,8)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-image" style="font-size:13px;"></i>'
		subs += '                                        <div class="btn-backend-text">Image Button </div>'
		subs += '                                    </button>'
		subs += '                                      <button class="scc_button btn-backend scc-premium-element" onclick="addCustomMath(this)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-calculator" style="padding-top:3px;font-size:13px;"></i>'
		subs += '                                          <div class="btn-backend-text">Custom Math</div>'
		subs += '                                      </button>'
		subs += '										<button value="custom_code" class="scc_button btn-backend scc-premium-element" onclick="addTextHtml(this)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-calculator" style="padding-top:3px;font-size:13px;"></i>'
		subs += '											<div class="btn-backend-text">Variable Math</div>'
		subs += '										</button>'
		subs += '                                      <input class="inputoption_slidchk scc-premium-element" type="checkbox" onClick="addSlider(this)" style="display:none;" />'
		subs += '                                      <button value="file_input" class="scc_button btn-backend" onclick="addFileUpload(this)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-paperclip" style="padding-top:3px;font-size:13px;"></i>'
		subs += '                                          <div class="btn-backend-text">File Upload</div>'
		subs += '                                      </button>'
		subs += ' 										<button value="custom_code" class="scc_button btn-backend scc-premium-element" onclick="addTextHtml(this)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-calendar-alt" style="padding-top:3px;font-size:13px;"></i>'
		subs += '											<div class="btn-backend-text">Date Picker</div>'
		subs += '										</button>'
		subs += '										<button value="distance" class="scc_button btn-backend scc-premium-element" >'
		subs += '											<i class="scc-btn-spinner scc-d-none"></i>'
		subs += '											<i class="fas fa-map-marker-alt" style="padding-top:3px;margin-left:0;"></i>'
		subs += '											<div class="btn-backend-text">Distance-Based Cost</div>'
		subs += '										</button>'
		subs += '                                      <button value="custom_code" class="scc_button btn-backend scc-premium-element" onclick="addTextHtml(this)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-code" style="padding-top:3px;font-size:13px;"></i>'
		subs += '                                          <div class="btn-backend-text">Text/HTML Field</div>'
		subs += '                                      </button>'
		subs += '                                      <button value="comment_input" class="scc_button btn-backend" onclick="addCommentBoxElement(this)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-comment" style="padding-top:3px;font-size:13px;"></i>'
		subs += '                                          <div class="btn-backend-text">Comment Box</div>'
		subs += '                                      </button>'
		subs += '                                      <br>'
		subs += '                                      <span style="font-size:13px;margin-top:5px;">Add 1 or more elements to this subsection</span>'
		subs += '                                      <input class="scc_custom_math_checkbox" type="checkbox" style="display:none;" />'
		subs += '                                  </div>'
		subs += '                            </div>'
		return subs;
	}
	/**
	 * *Section to be inserted in dom after success insert in db
	 * @param section_id, subsection_id
	 */
	function insertSection(idsection, idsubsection) {
		var section = '        <div class="addedFieldsStyle" style="display:grid;" id="Sccvo_0">'
		section += '                    <input class="id_section_class" type="text" value="' + idsection + '" hidden="">'
		section += '                    <div id="title54-bar-btns" class="scc-section-setting-container">'
		section += '						<div class="scc-section-setting-bar">'
		section += '							<button id="up-btn" class="scc-section-setting-btn up" title="Push this section above" href="javascript:void(0)" onclick="rup(this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['push-up'] ); ?></span></button>'
		section += '							<button id="down-btn" class="scc-section-setting-btn down d-none" title="Push this section below" href="javascript:void(0)" onclick="rdown(this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['push-down'] ); ?></span></button>'	
		section += '							<button id="settings-btn" class="scc-section-setting-btn" href="javascript:void(0)" title="Section settings" onclick="settingsIconShow(this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['sliders'] ); ?></span></button>'
		section += '							<button id="close-btn" class="scc-section-setting-btn" href="javascript:void(0)" title="Delete Section" onclick="preDeletionDialog(\'section\', removeSection, this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['x'] ); ?></span></button>'
		section += '						</div>'
		section += '					</div>'
		section += '                    <div class="scc-accordion-container scc-accordion-tooltip-hidden">'
		section += '                        <div class="scc-accordion-tooltip scc-accordion-content">'
		section += '                            <!-- SETTINGS TOOGLE -->'
		section += '                            <p>'
		section += '                            <label class="scc-accordion_switch_button">'
		section += '                                <input class="scc_accordion_section" onchange="changeAccordion(this)" name="scc_accordion_section" type="checkbox" value="yes">'
		section += '                                <span class="scc-accordion_toggle_button round"></span>'
		section += '                            </label>'
		section += '                            Accordion'
		section += '                            </p>'
		section += '                            <p>'
		section += '                            <label class="scc-accordion_switch_button">'
		section += '                                <input class="scc-section-total" onchange="changeShowSectionTotal(this)" name="scc-section-total" type="checkbox">'
		section += '                                <span class="scc-accordion_toggle_button round"></span>'
		section += '                            </label>'
		section += '                            Show Section Total'
		section += '                            </p>'
		section += '                        </div>'
		section += '                    </div>'
		section += '                    <!-- TITLE -->'
		section += '                    <div class="title_section_no_edit_container">'
		section += '                        <div class="scc-col-md-10" style="padding:0px" onclick="toggleEditTitle(this)">'
		section += '                            <i class="fa fa-pen text-primary" role="button"></i>'
		section += '                            <p style="margin-top:-5px;margin-bottom:-5px; font-size: 24px" class="title_section_no_edit d-inline">'
		section += '                                Section title </p>'
		section += '                        </div>'
		section += '                    <div class="section-title-edit-wrapper">'
		section += '                        <i class="fa fa-check text-warning" onclick="toggleEditTitle(this)" style="display:none" role="button"></i>'
		section += '                        <input value="section" onkeyup="changeTitleSection(this)" type="text" class="input_pad sectiontitle scc_edit_section_input" placeholder="Section Title" value="" style="outline: 0px; border-radius: 10px; margin-top: 10px; width: 95%; height: 50px; margin-bottom: 10px; border-top: none !important; border-right: none !important; border-left: none !important; border-image: initial !important; box-shadow: none !important; border-bottom: 1px solid rgb(49, 74, 243) !important; display: none;">'
		section += '                        <span class="mandatory" style="display:none">*</span>'
		section += '                    </div>'
		section += '                    </div>'
		section += '                    <!-- DESCRIPTION -->'
		section += '                    <div class="description_section_no_edit_container">'
		section += '                        <div class="scc-col-md-10" style="padding:0px" onclick="toggleEditDescription(this)">'
		section += '                            <i class="fa fa-pen text-primary" role="button"></i>'
		section += '                            <p class="description_section_no_edit d-inline">'
		section += '                                Section description </p>'
		section += '                        </div>'
		section += '                        <div class="description-wrapper">'
		section += '                            <i class="fa fa-check text-warning" onclick="toggleEditDescription(this)" style="display:none" role="button"></i>'
		section += '                            <textarea onkeyup="changeDescriptionSection(this)" class="input_pad sectionDescription scc_section_description_textarea" placeholder="Description of the products/services that will be listed below. (Optional)" style="background: rgb(255, 255, 255); height: 125px; padding: 15px; width: 95%; margin-bottom: 20px; margin-top: 15px; border-bottom: 1px solid rgb(49, 74, 243) !important; display: none;">description</textarea>'
		section += '                        </div>'
		section += '                    </div>'
		section += '                    <!-- SUBSECTION -->'
		section += '                    <div class="fieldDatatoAdd">'
		section += '                           <div class="boardOption">'
		section += '                               <input class="input_subsection_id" type="text" value="' + idsubsection + '" hidden>'
		section += '                                <div class="scc-subsection">'
		section += '									<div>'
		section += '										<button class="collapsible subsect-title">Subsection <i class="material-icons-outlined with-tooltip"  data-setting-tooltip-type="subsection-note-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i></button>'
		section += '										<div class="scc_help_btn_right" style="left:26px;float:right;top:23px;font-size:18px;"></div>'
		section += '									</div>'				
		section += '									<div class="scc-section-setting-bar">'
		section += '										<button id="close-btn" class="scc-section-setting-btn" title="Delete Subsection" onclick="preDeletionDialog(\'subsection\', removeSubsection, this)"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['x'] ); ?></span></button>'
		section += '									</div>'
		section += '								</div>'
		section += '                                <div class="subsection-area BodyOption ">'
		section += '                                                                        <!-- ELEMENTS SHOWS HERE -->'
		section += '                                </div>'
		section += '                                <!-- BUTTONS AREA -->'
		section += '                                <div class="scc-col-md-12 scc-col-xs-12">'
		section += '                                    <label class="scc_label_2" style="margin-top:15px;margin-right: 0px !important;padding: 8px;margin-top: 20px;border-radius:6px;">'
		section += '                                        <a class="add-element-btn save_button" onclick="togglebuttonsadd(this)">'
		section += '                                            + Add Element'
		section += '                                        </a>'
		section += '                                    </label>'
		section += '                                </div>'
		section += '                                <div class="df_scc_groupbuttonsadd scc-col-md-12 scc-col-xs-12" style="margin:15px; display:none ">'
		section += '                                    <button value="dropdowninput" class="scc_button btn-backend" onclick="addDropdownMenuElement(this)"><i class="scc-btn-spinner scc-d-none"></i><i class="far fa-list-alt" style="padding-top:3px;font-size:13px;"></i>'
		section += '                                        <div class="btn-backend-text">DropDown</div>'
		section += '                                    </button>'
		section += '                                    <button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,1)"><i class="scc-btn-spinner scc-d-none"></i><i class="far fa-check-square" style="padding-top:3px;font-size:13px;"></i>'
		section += '                                        <div class="btn-backend-text">Checkbox</div>'
		section += '                                    </button>'
		section += '                                    <button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,3)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-toggle-off" style="padding-top:3px;font-size:13px;"></i>'
		section += '                                        <div class="btn-backend-text">Toggle Switch</div>'
		section += '                                    </button>'
		section += '                                    <button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,6)"><i class="scc-btn-spinner scc-d-none"></i><i class="far fa-rectangle-wide" style="margin-top:5px;font-size:13px;width:26px;height: 9px;background-color: white;"></i>'
		section += '                                        <div class="btn-backend-text">Simple Button </div>'
		section += '                                    </button>'
		section += '                                    <button value="switchinput" class="scc_button btn-backend" onclick="addCheckboxElement(this,8)"><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-image" style="font-size:13px;"></i>'
		section += '                                        <div class="btn-backend-text">Image Button </div>'
		section += '                                    </button>'
		section += '                                    <button class="scc_button btn-backend" onclick="addSliderElement(this)"><div class="scc-slider-tooltip-panel use-tooltip" data-setting-tooltip-type="slider-disabled-tt" data-bs-original-title="" title=""></div><i class="scc-btn-spinner scc-d-none"></i><i class="fas fa-sliders-h" style="padding-top:3px;font-size:13px;"></i>'
		section += '                                        <div class="btn-backend-text">Slider</div>'
		section += '                                    </button>'
		section += '                                    <input class="inputoption_slidchk" type="checkbox" onClick="addSlider(this)" style="display:none;" />'
		section += '                                    <button value="comment_input" class="scc_button btn-backend" onclick="addCommentBoxElement(this)"><i class="fas fa-comment" style="padding-top:3px;font-size:13px;"></i>'
		section += '                                        <div class="btn-backend-text">Comment Box</div>'
		section += '                                    </button>'
		section += '                                    <button value="number_input" class="scc_button btn-backend" onclick="addQuantityBox(this)"><i class="material-icons" style="padding-top:3px;font-size:13px;">exposure</i>'
		section += '                                        <div class="btn-backend-text">Quantity Box</div>'
		section += '                                    </button>'
		section += '                                    <button class="scc_button btn-backend scc-premium-element" onclick="addCustomMath(this)"><i class="fas fa-calculator" style="padding-top:3px;font-size:13px;"></i>'
		section += '                                        <div class="btn-backend-text">Custom Math</div>'
		section += '                                    </button>'
		section += '                                    <button value="file_input" class="scc_button btn-backend scc-premium-element" onclick="addFileUpload(this)"><i class="fas fa-paperclip" style="padding-top:3px;font-size:13px;"></i>'
		section += '                                        <div class="btn-backend-text">File Upload</div>'
		section += '                                    </button>'
		section += '                                    <button value="custom_code" class="scc_button btn-backend scc-premium-element" onclick="addTextHtml(this)"><i class="fas fa-code" style="padding-top:3px;font-size:13px;"></i>'
		section += '                                        <div class="btn-backend-text">Text/HTML Field</div>'
		section += '                                    </button>'
		section += '										<button value="custom_code" class="scc_button btn-backend scc-premium-element" onclick="addTextHtml(this)"><i class="fas fa-calculator" style="padding-top:3px;font-size:13px;"></i>'
		section += '											<div class="btn-backend-text">Variable Math</div>'
		section += '										</button>'
		section += ' 									<button value="custom_code" class="scc_button btn-backend scc-premium-element" onclick="addTextHtml(this)"><i class="fas fa-calendar-alt" style="padding-top:3px;font-size:13px;"></i>'
		section += '										<div class="btn-backend-text">Date Picker</div>'
		section += '									</button>'
		section += '									<button value="distance" class="scc_button btn-backend scc-premium-element" >'
		section += '										<i class="scc-btn-spinner scc-d-none"></i>'
		section += '										<i class="fas fa-map-marker-alt" style="padding-top:3px;margin-left:0;"></i>'
		section += '										<div class="btn-backend-text">Distance-Based Cost</div>'
		section += '									</button>'
		section += '                                    <br>'
		section += '                                    <span style="font-size:13px;margin-top:5px;">Add 1 or more elements to this subsection</span>'
		section += '                                    <input class="scc_custom_math_checkbox" type="checkbox" style="display:none;" />'
		section += '                                </div>'
		section += '                            </div>'
		section += '                                                <div class="boardOption1">'
		section += '                            <label class="add-subsection-btn">'
		section += '                                <!-- This one works -->'
		section += '                                <a href="javascript:void(0)" onclick="addSubSectionElement(this)" style="border-radius:6px;padding:8px;background:#314af3;color:white" class="crossnadd2">+ Add Subsection'
		section += '                                </a>'
		section += '                            </label>'
		section += '                        </div>'
		section += '                    </div>'
		section += '                </div>'
		return section;
	}
	/**
	 * *Checkbox element to be inserted in dom after success insert in db
	 * todo: needs to add element and elementitem in db
	 * @param element_id, elementItem_id,type
	 */
	function insertCheckboxElement(idElement, idElementitem, type, elementDOM) {
		var addWoocommerceChoice = sccData[getCalcId()].config["enableWoocommerceCheckout"];
		var checkBoxType = 'Checkbox'
		var tooltip_type = 'checkbox-buttons'
		var elementInfo = 'Shows a checkbox on the frontend'
		if (type == 3 || type == 4) {
			checkBoxType = 'Toggle switch'
			tooltip_type = 'toggle-switches'
		}
		if (type == 4) {
			elementInfo = 'Shows toggle switch on the frontend'
		}
		if (type == 6) {
			checkBoxType = 'Simple Buttons'
			elementInfo = 'Shows simple buttons on the frontend'
			tooltip_type = 'simple-buttons'
		}
		if (type == 7) {
			checkBoxType = 'Radio'
			elementInfo = 'Shows checkbox items with only one selectable'
			tooltip_type = 'radio-buttons'
		}
		if (type == 8) {
			checkBoxType = 'Image Button'
			elementInfo = 'Shows selectable image choices'
			tooltip_type = 'image-buttons'
		}
		var ddWoocommerceChoice = () => `<div class="row">
		<div class="col-md-2"></div>
			<div class="col-md-9 dd-woocommerce" style="padding:0px;">
				<div class="scc-col-xs-6 scc-col-md-2" style="padding:0px;background: #f8f9ff;height: 35px;"><img class="scc-woo-logo" src="<?php echo esc_url_raw( SCC_ASSETS_URL . '/images/logo-woocommerce.svg' ); ?>" title="Pick an item from your WooCommerce products to link to."></div>
				<div class="woo-product-dd scc-col-xs-6 scc-col-md-6" style="padding:0px;">
					<select class="scc_woo_commerce_product_id" data-target="elements_added" onchange="attachProductId(this, ${idElementitem})" style="float:left;height:35px;margin-bottom:20px;width: 100%;">
						<option style="font-size: 10px" value="0">Select a product..</option>` +
			`${woocommerceProducts.map(e => `<option value="${Object.keys(e)}">` + Object.values(e) + '</option>').join('\n')}` +
			`</select>
				</div>
			</div>
		</div>`
		var elementHead = `<div class="elements_added_v2">
			<div class="element-icon">
				<i class="far fa-check-square" style="font-size:25px;"></i>
			</div>
			<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
				<div class="title-desc-wrapper">
					<span class="element-title">${checkBoxType}</span>
					<p class="element-description">New element</p>
				</div>
			</div>
			<?php echo scc_output_editing_page_element_actions_js_template( '${tooltip_type}' ); ?>
		</div>`
		var element = '<div class="elements_added" style="border: 2px solid rgb(138, 153, 248);border-style: dashed;">'
		element += '<input type="text" class="input_id_element" value="' + idElement + '" hidden="">'
		element += elementHead
		element += elementDOM['checkbox_body']
		return element;
	}
	/**
	 * *CustomMath element to be inserted in dom after success insert in db
	 * @param element_id
	 * 
	 */
	function insertCustomMath(idnewElement) {
		var elementHead = `<div class="elements_added_v2">
			<div class="element-icon">
				<i class="fas fa-calculator" style="font-size:25px;"></i>
			</div>
			<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
				<div class="title-desc-wrapper">
					<span class="element-title">Custom Math</span>
					<p class="element-description"></p>
				</div>
			</div>
			<?php echo scc_output_editing_page_element_actions_js_template( 'custom-math' ); ?>
		</div>`
		var element = '<div class="elements_added" style="border: 2px solid rgb(138, 153, 248);border-style: dashed;">'
		element += '    <input type="text" class="input_id_element" value="' + idnewElement + '" hidden="">'
		element += elementHead
		element += '    <div class="scc-element-content" value="selectoption" style="height: auto;">'
		element += '        <!-- CONTENT OF EACH ELEMENT -->'
		element += '        <div class="edit-element-notice" style="margin-left: 15px;">'
		element += '            <i class="material-icons with-tooltip" title="Note: Custom Math applies extra calculation over the value returned by the elements in a subsection. E.g. If the subsection returns 100, and the custom math is 10%, the final value of the subsection will be 110. You can use to decrease the subsection total using negative sign to the value, for example, -10%">help_outline</i>'
		element += '        </div>'
		element += '        <!-- ELEMENT -->'
		element += '        <div class="row m-0 selopt5 col-xs-12 col-md-12" style="margin-top: 20px; padding: 0px;">'
		element += '            <div class="col-xs-6 col-md-2" style="padding:0px;height:40px;background: #f8f9ff;"><span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Name</span></div>'
		element += '            <div class="col-xs-6 col-md-7" style="padding:0px;"><input type="text" class="input_pad inputoption_title" onkeyup="clickedTitleElement(this)" style="height:40px;width:100%;" placeholder="Title" value="Custom Math Name"></div>'
		element += '        </div>'
		element += '        <!-- ELEMENTS INSIDE ELEMENTS -->'
		element += '        <div class="col-md-12 col-xs-12" style="margin-top:10px;padding:0px;">'
		element += '            <div class="col-xs-6 col-md-2" style="padding:0px;background:#f8f9ff;height:40px;">'
		element += '                <span class="scc_label" style="margin-top:5px;text-align:right;padding-right:10px;">Type</span>'
		element += '            </div>'
		element += '            <div class="col-xs-6 col-md-7" style="padding:0px;padding:0px;text-align:left;">'
		element += '                <select onchange="changeValue1(this)" class="input_pad scc_custom_math_type" style="text-align:center;height:40px;width:150px; padding: 1px!important;text-align-last: center;font-size: 17px;">'
		element += '                    <option value="+" selected>+</option>'
		element += '                    <option value="-">-</option>'
		element += '                    <option value="x">x</option>'
		element += '                    <option value="%">%</option>'
		element += '                    <option value="/">/</option>'
		element += '                </select>'
		element += '            </div>'
		element += '        </div>'
		element += '        <div class="col-md-12 col-xs-12" style="margin-top:10px;padding:0px;margin-bottom:10px">'
		element += '            <div class="col-xs-6 col-md-2" style="padding:0px;background:#f8f9ff;height:40px;">'
		element += '                <span class="scc_label" style="margin-top:5px;text-align:right ;padding-right:10px;">Value</span>'
		element += '            </div>'
		element += '           <div>'
		element += '            <div class="col-xs-6 col-md-7" style="padding:0px;text-align:left">'
		element += '                <input onkeyup="changeValue2(this)" onchange="changeValue2(this)" type="number" class="input_pad scc_custom_math_value" style="text-align:center;height:40px;left:-8px;width:150px;" value="3">'
		element += '            </div>'
		element += '           </div>'
		element += '        </div>'
		element += '        <div class="scc-new-accordion-container">'
		element += '        <div class="styled-accordion">'
		element += '    <div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">'
		element += '        <i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>'
		element += '    </div>'
		element += '    <div class="scc-content" style="display: none;">'
		element += '        <div class="scc-transition">'
		element += '            <p>'
		element += '            <label class="scc-accordion_switch_button">'
		element += '                <input onchange="changeDisplayFrontend(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox">'
		element += '                <span class="scc-accordion_toggle_button round"></span>'
		element += '            </label>'
		element += '            <span><b>Display on Frontend Form</b></span>'
		element += '            </p>'
		element += '            <p>'
		element += '            <label class="scc-accordion_switch_button">'
		element += '                <input onchange="changeDisplayDetail(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox">'
		element += '                <span class="scc-accordion_toggle_button round"></span>'
		element += '            </label>'
		element += '            <span><b>Display on Detailed List</b></span>'
		element += '            </p>'
		element += '        </div>'
		element += '    </div>'
		element += '</div>'
		element += '<div class="styled-accordion">'
		element += '    <div class="scc-title scc_accordion_conditional">'
		element += '        <i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>'
		element += '    </div>'
		element += '    <div class="scc-content" style="display: none;">'
		element += '        <div class="scc-transition">'
		element += '            <div class="condition-container clearfix" data-condition-set=1>'
		element += '                <!-- <div style="background-color: black;height:50px"></div> -->'
		element += '                <div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">'
		element += '                    <div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">'
		element += '                        <span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Show if</span>'
		element += '                    </div>'
		element += '                    <div class="col-xs-11 col-md-11" style="padding:0px;">'
		element += '                        <div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">'
		element += '                            <div class="item_conditionals">'
		element += '                                <select disabled onfocus="loadDataItemsCondition(this)" onchange="loadSecondSelectCondition(this)" class="first-conditional-step col-xs-3" style="height: 40px;">'
		element += '                                    <option style="font-size: 10px" value="0">Select an element</option>'
		element += '                                </select>'
		element += '                                <select onchange="changeSecondSelectCondition(this)" class="second-conditional-step col-xs-3" style="height: 40px;display:none">'
		element += '                                </select>'
		element += '                                <select class="third-conditional-step col-xs-3" style="height: 40px;display:none">'
		element += '                                </select>'
		element += '                                <input type="number" placeholder="Number" style="height: 40px;display:none" class="conditional-number-value col-xs-2">'
		element += '                                <div class="btn-group" style="margin-left: 10px;display:none">'
		element += '                                    <button onclick="addConditionElement(this)" class="btn btn-addcondition">Save</button>'
		element += '                                    <button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>'
		element += '                                </div>'
		element += '                            </div>'
		element += '                        </div>'
		element += '                    </div>'
		element += '                </div>'
		element += '                <button onclick="(($this) => {jQuery($this).prev().removeClass(\'hidden\'); jQuery($this).addClass(\'hidden\')})(this)" class="btn btn-addcondition cond-add-btn hidden">Add</button>';
		element += '            </div>'
		element += '            <div style="margin-left: auto; margin-right: auto; width: 28%">'
		element += '                  <button class="btn btn-primary btn-cond-or hidden">Add OR Condition</button>'
		element += '            </div>'
		element += '        </div>'
		element += '    </div>'
		element += '</div>'
		element += '            </div>'
		return element
	}
	/**
	 * *Checkbox element to be inserted in dom after success insert in db
	 * todo: it needs to be added slider element and elementItem row range
	 * @param element_id, elementItem_id
	 */
	function insertSliderElement(idnewElement, idnewElementItem, elementDOM) {
		var elementHead = `<div class="elements_added_v2">
			<div class="element-icon">
				<i class="fas fa-sliders-h" style="font-size:25px;"></i>
			</div>
			<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
				<div class="title-desc-wrapper">
					<span class="element-title">Slider</span>
					<p class="element-description"></p>
				</div>
			</div>
			<?php echo scc_output_editing_page_element_actions_js_template( 'slider-element' ); ?>
		</div>`;
		var element = '<div class="elements_added" style="border: 2px solid rgb(138, 153, 248);border-style: dashed;">'
		element += '    <input type="text" class="input_id_element" value="' + idnewElement + '" hidden="">'
		element += elementHead
		element += elementDOM['slider_body']
		element += '            </div>'
		element = jQuery(element)
		return element
	}
	/**
	 * *TextHtml element to be inserted in dom after success insert in db
	 * @param elemen_id
	 */
	function insertTextHtml(idnewElement) {
		var elementHead = `<div class="elements_added_v2">
			<div class="element-icon">
				<i class="fas fa-code" style="font-size:25px;"></i>
			</div>
			<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
				<div class="title-desc-wrapper">
					<span class="element-title">Text/HTML Field</span>
					<p class="element-description"></p>
				</div>
			</div>
			<?php echo scc_output_editing_page_element_actions_js_template( 'text-html-field' ); ?>
		</div>`
		var element = '<div class="elements_added" style="border: 2px solid rgb(138, 153, 248);border-style: dashed;">'
		element += '    <input type="text" class="input_id_element" value="' + idnewElement + '" hidden="">'
		element += elementHead
		element += '    <div class="scc-element-content" value="selectoption" style="height: auto;">'
		element += '        <!-- CONTENT OF EACH ELEMENT -->'
		element += '        <!-- ELEMENT -->'
		element += '        <div class="row m-0 selopt5 col-xs-12 col-md-12" style="padding: 0px;">'
		element += '            <div class="col-xs-6 col-md-2" style="padding:0px;height:40px;background: #f8f9ff;"><span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Title</span></div>'
		element += '            <div class="col-xs-6 col-md-7" style="padding:0px;"><input type="text" class="input_pad inputoption_title" onkeyup="clickedTitleElement(this)" style="height:40px;width:100%;" placeholder="Title" value=""></div>'
		element += '        </div>'
		element += '        <!-- ELEMENTS INSIDE ELEMENTS -->'
		element += '        <div class="row m-0 mt-2 col-xs-12 col-md-12" style="padding:0px;margin-bottom:10px;margin-top: 15px;">'
		element += '            <div class="col-xs-6 col-md-2" style="padding:0px;background:#f8f9ff;height:40px;">'
		element += '                <span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Your Custom Code</span>'
		element += '            </div>'
		element += '            <div class="p-0 col-xs-6 col-md-7">'
		element += '            <div  style="padding:0px;">'
		element += '                <textarea onkeyup="changeValue2(this)" rows="5" cols="33" class="input_pad inputoption_text" style="width: 100%;" placeholder="<div></div>"></textarea>'
		element += '            </div>'
		element += '            </div>'
		element += '        </div>'
		element += '        <div class="scc-new-accordion-container">'
		element += '        <div class="styled-accordion">'
		element += '    <div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">'
		element += '        <i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>'
		element += '    </div>'
		element += '    <div class="scc-content" style="display: none;">'
		element += '        <div class="scc-transition">'
		element += '            <p>'
		element += '                <label class="scc-accordion_switch_button">'
		element += '                    <input onchange="changeMandatoryElement(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox">'
		element += '                    <span class="scc-accordion_toggle_button round"></span>'
		element += '                </label>'
		element += '                <span><b>Mandatory</b></span>'
		element += '            </p>'
		element += '            <span style="text-align: left;display: block;font-size:16px;margin-bottom:10px;"><b>Responsive Options</b></span>'
		element += '            <div class="scc-accordion-tooltip" style="width: 95%; text-align:left;">'
		element += '                <div class="text-scc-col d-flex tooltipadmin-right" title="Please enter a number between 1 and 12. 1 being the smallest and 12 being the largest, for your title. If you have a large title, we recommend between 6 and 12.">'
		element += '                    <div class="col-md-3" style="padding:1px;">'
		element += '                        <label>Title column (desktop)</label>'
		element += '                    </div>'
		element += '                    <div class="col-md-3" style="padding:1px;">'
		element += '                        <input onchange="changeColumnDesktop(this)" onkeyup="changeColumnDesktop(this)" class="scc_title_column_dskp" min="1" max="12" name="scc_title_column_dskp" type="number" value="">'
		element += '                    </div>'
		element += '                </div>'
		element += '                <div class="text-scc-col d-flex">'
		element += '                    <div class="col-md-3" style="padding:1px;">'
		element += '                        <label>Title column (mobile)</label>'
		element += '                    </div>'
		element += '                    <div class="col-md-3" style="padding:1px;">'
		element += '                        <input onchange="changeColumnMobile(this)" onkeyup="changeColumnMobile(this)" class="scc_title_column_mobl" min="1" max="12" name="scc_title_column_mobl" type="number" value="">'
		element += '                    </div>'
		element += '                </div>'
		element += '            </div>'
		element += '        </div>'
		element += '    </div>'
		element += '</div>'
		element += '<div class="styled-accordion">'
		element += '    <div class="scc-title scc_accordion_conditional">'
		element += '        <i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>'
		element += '    </div>'
		element += '    <div class="scc-content" style="display: none;">'
		element += '        <div class="scc-transition">'
		element += '            <div class="condition-container clearfix" data-condition-set=1>'
		element += '                <!-- <div style="background-color: black;height:50px"></div> -->'
		element += '                <div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">'
		element += '                    <div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">'
		element += '                        <span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Show if</span>'
		element += '                    </div>'
		element += '                    <div class="col-xs-11 col-md-11" style="padding:0px;">'
		element += '                        <div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">'
		element += '                            <div class="item_conditionals">'
		element += '                                <select disabled onfocus="loadDataItemsCondition(this)" onchange="loadSecondSelectCondition(this)" class="first-conditional-step col-xs-3" style="height: 40px;">'
		element += '                                    <option style="font-size: 10px" value="0">Select an element</option>'
		element += '                                </select>'
		element += '                                <select onchange="changeSecondSelectCondition(this)" class="second-conditional-step col-xs-3" style="height: 40px;display:none">'
		element += '                                </select>'
		element += '                                <select class="third-conditional-step col-xs-3" style="height: 40px;display:none">'
		element += '                                </select>'
		element += '                                <input type="number" placeholder="Number" style="height: 40px;display:none" class="conditional-number-value col-xs-2">'
		element += '                                <div class="btn-group" style="margin-left: 10px;display:none">'
		element += '                                    <button onclick="addConditionElement(this)" class="btn btn-addcondition">Save</button>'
		element += '                                    <button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>'
		element += '                                </div>'
		element += '                            </div>'
		element += '                        </div>'
		element += '                    </div>'
		element += '                </div>'
		element += '                <button onclick="(($this) => {jQuery($this).prev().removeClass(\'hidden\'); jQuery($this).addClass(\'hidden\')})(this)" class="btn btn-addcondition cond-add-btn hidden">Add</button>';
		element += '            </div>'
		element += '            <div style="margin-left: auto; margin-right: auto; width: 28%">'
		element += '                  <button class="btn btn-primary btn-cond-or hidden">Add OR Condition</button>'
		element += '            </div>'
		element += '        </div>'
		element += '    </div>'
		element += '</div>'
		element += '            </div>'
		return element
	}
	/**
	 * *FileUpload element to be inserted in dom after success insert in db
	 * @param element_id
	 */
	function insertFileupload(idnewElement) {
		var elementHead = `<div class="elements_added_v2">
			<div class="element-icon">
				<i class="fas fa-paperclip" style="font-size:25px;"></i>
			</div>
			<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
				<div class="title-desc-wrapper">
					<span class="element-title">File Upload Field</span>
					<p class="element-description"></p>
				</div>
			</div>
			<?php echo scc_output_editing_page_element_actions_js_template( 'file-upload' ); ?>
		</div>`
		var element = '<div class="elements_added" style="border: 2px solid rgb(138, 153, 248);border-style: dashed;">'
		element += '    <input type="text" class="input_id_element" value="' + idnewElement + '" hidden="">'
		element += elementHead
		element += elementDOM['fileupload_body']
		element += '    <div class="scc-element-content" value="selectoption" style="height: auto;">'
		element += '        <!-- CONTENIDO DE CADA ELEMENTO -->'
		element += '        <!-- ELEMENT -->'
		element += '        <!-- ELEMENTS INSIDE ELEMENTS -->'
		element += '        <div class="scc-new-accordion-container">'
		element += '        <div class="styled-accordion">'
		element += '    <div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">'
		element += '        <i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>'
		element += '    </div>'
		element += elementDOM['advanced_settings']
		element += '</div>'
		element += '<div class="styled-accordion">'
		element += '    <div class="scc-title scc_accordion_conditional">'
		element += '        <i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>'
		element += '    </div>'
		element += '    <div class="scc-content" style="display: none;">'
		element += '        <div class="scc-transition">'
		element += '            <div class="condition-container clearfix" data-condition-set=1>'
		element += '                <!-- <div style="background-color: black;height:50px"></div> -->'
		element += '                <div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">'
		element += '                    <div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">'
		element += '                        <span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Show if</span>'
		element += '                    </div>'
		element += '                    <div class="col-xs-11 col-md-11" style="padding:0px;">'
		element += '                        <div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">'
		element += '                            <div class="item_conditionals">'
		element += '                                <select class="first-conditional-step col-3" style="height: 35px;">'
		element += '                                    <option style="font-size: 10px" value="0">Select an element</option>'
		element += '                                </select>'
		element += '                                <select class="second-conditional-step col-3" style="height: 35px;display:none">'
		element += '                                </select>'
		element += '                                <select class="third-conditional-step col-3" style="height: 35px;display:none">'
		element += '                                </select>'
		element += '                                <input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-2">'
		element += '                                <div class="btn-group" style="margin-left: 10px;display:none">'
		element += '                                    <button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>'
		element += '                                    <button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>'
		element += '                                </div>'
		element += '                            </div>'
		element += '                        </div>'
		element += '                    </div>'
		element += '                </div>'
		element += '                <button onclick="(($this) => {jQuery($this).prev().removeClass(\'hidden\'); jQuery($this).addClass(\'hidden\')})(this)" class="btn btn-addcondition cond-add-btn hidden">Add</button>';
		element += '            </div>'
		element += '            <div style="width: 28%">'
		element += '                  <button class="btn btn-primary btn-cond-or hidden">Add OR Condition</button>'
		element += '            </div>'
		element += '        </div>'
		element += '    </div>'
		element += '</div>'
		element += '            </div>'
		return element
	}
	/**
	 * *QuantityBox element to be inserted in dom after success insert in db
	 * @param element_id
	 */
	function insertQuantityBox(idnewElement, elementDOM) {
		var elementHead = `<div class="elements_added_v2">
			<div class="element-icon">
				<i class="material-icons" style="font-size:25px;">exposure</i>
			</div>
			<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
				<div class="title-desc-wrapper">
					<span class="element-title">Quantity Input Box</span>
					<p class="element-description"></p>
				</div>
			</div>
			<?php echo scc_output_editing_page_element_actions_js_template( 'quantity-input-box' ); ?>
		</div>`
		var element = '<div class="elements_added" style="border: 2px solid rgb(138, 153, 248);border-style: dashed;">'

		element += '    <input type="text" class="input_id_element" value="' + idnewElement + '" hidden="">'

		element += elementHead

		element += elementDOM['quantitybox_body']


		element += '    <div class="scc-element-content" value="selectoption" style="height: auto;">'

		element += '        <!-- CONTENIDO DE CADA ELEMENTO -->'

		element += '        <!-- ELEMENT -->'

		element += '        <div class="scc-new-accordion-container">'

		element += '        <div class="styled-accordion">'

		element += '    <div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">'

		element += '        <i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>'

		element += '    </div>'


		element += elementDOM['advanced_settings']

		element += '</div>'

		element += '<div class="styled-accordion">'

		element += '    <div class="scc-title scc_accordion_conditional">'

		element += '        <i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>'

		element += '    </div>'

		element += '    <div class="scc-content" style="display: none;">'

		element += '        <div class="scc-transition">'

		element += '            <div class="condition-container clearfix" data-condition-set=1>'

		element += '                <!-- <div style="background-color: black;height:50px"></div> -->'

		element += '                <div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">'

		element += '                    <div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">'

		element += '                        <span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Show if</span>'

		element += '                    </div>'

		element += '                    <div class="col-xs-11 col-md-11" style="padding:0px;">'

		element += '                        <div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">'

		element += '                            <div class="item_conditionals">'

		element += '                                <select class="first-conditional-step col-3" style="height: 35px;">'

		element += '                                    <option style="font-size: 10px" value="0">Select an element</option>'

		element += '                                </select>'

		element += '                                <select class="second-conditional-step col-3" style="height: 35px;display:none">'

		element += '                                </select>'

		element += '                                <select class="third-conditional-step col-3" style="height: 35px;display:none">'

		element += '                                </select>'

		element += '                                <input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-2">'

		element += '                                <div class="btn-group" style="margin-left: 10px;display:none">'

		element += '                                    <button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>'

		element += '                                    <button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>'

		element += '                                </div>'

		element += '                            </div>'

		element += '                        </div>'

		element += '                    </div>'

		element += '                </div>'

		element += '                <button onclick="(($this) => {jQuery($this).prev().removeClass(\'hidden\'); jQuery($this).addClass(\'hidden\')})(this)" class="btn btn-addcondition cond-add-btn hidden">Add</button>';

		element += '            </div>'

		element += '            <div style="width: 28%">'

		element += '                  <button class="btn btn-primary btn-cond-or hidden">Add OR Condition</button>'

		element += '            </div>'

		element += '        </div>'

		element += '    </div>'

		element += '</div>'

		element += '            </div>'
		return element
	}
	/**
	 * *CommentBox element to be inserted in dom after success insert in db
	 * @param element_id
	 */
	function insertCommentBoxElement(idnewElement, elementDOM) {
		var elementHead = `<input type="text" class="input_id_element" value="${idnewElement}" hidden="">
			<div class="elements_added_v2">
			<div class="element-icon">
				<i class="fas fa-comment" style="font-size:25px;"></i>
			</div>
			<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
				<div class="title-desc-wrapper">
					<span class="element-title">Comment Box</span>
					<p class="element-description"></p>
				</div>
			</div>
			<?php echo scc_output_editing_page_element_actions_js_template( 'comment-box' ); ?>
		</div>`
		var element = ' <div class="elements_added" style="border: 2px solid rgb(138, 153, 248);;border-style: dashed;">'
		element += elementHead
		element += elementDOM['commentbox_body']

		element += '<div class="scc-element-content" value="selectoption" style="height: auto;">'
		element += '    <!-- CONTENIDO DE CADA ELEMENTO -->'
		element += '    <!-- ELEMENT -->'
		element += '    <div class="scc-new-accordion-container">'
		element += '        <div class="styled-accordion">'
		element += '    <div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">'
		element += '        <i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>'
		element += '    </div>'
		element += elementDOM['advanced_settings']
		element += '</div>'
		element += '<div class="styled-accordion">'
		element += '    <div class="scc-title scc_accordion_conditional">'
		element += '        <i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>'
		element += '    </div>'
		element += '    <div class="scc-content" style="display: none;">'
		element += '        <div class="scc-transition">'
		element += '            <div class="condition-container clearfix" data-condition-set=1>'
		element += '                <!-- <div style="background-color: black;height:50px"></div> -->'
		element += '                <div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">'
		element += '                    <div class="col-xs-1 col-md-1" style="padding:0px;height:40px;background: #DCF1FD;">'
		element += '                        <span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;">Show if</span>'
		element += '                    </div>'
		element += '                    <div class="col-xs-11 col-md-11" style="padding:0px;">'
		element += '                        <div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">'
		element += '                            <div class="item_conditionals">'
		element += '                                <select disabled onfocus="loadDataItemsCondition(this)" onchange="loadSecondSelectCondition(this)" class="first-conditional-step col-xs-3" style="height: 40px;">'
		element += '                                    <option style="font-size: 10px" value="0">Select an element</option>'
		element += '                                </select>'
		element += '                                <select onchange="changeSecondSelectCondition(this)" class="second-conditional-step col-xs-3" style="height: 40px;display:none">'
		element += '                                </select>'
		element += '                                <select class="third-conditional-step col-xs-3" style="height: 40px;display:none">'
		element += '                                </select>'
		element += '                                <input type="number" placeholder="Number" style="height: 40px;display:none" class="conditional-number-value col-xs-2">'
		element += '                                <div class="btn-group" style="margin-left: 10px;display:none">'
		element += '                                    <button onclick="addConditionElement(this)" class="btn btn-addcondition">Save</button>'
		element += '                                    <button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>'
		element += '                                </div>'
		element += '                            </div>'
		element += '                        </div>'
		element += '                    </div>'
		element += '                </div>'
		element += '                <button onclick="(($this) => {jQuery($this).prev().removeClass(\'hidden\'); jQuery($this).addClass(\'hidden\')})(this)" class="btn btn-addcondition cond-add-btn hidden">Add</button>';
		element += '            </div>'
		element += '            <div style="margin-left: auto; margin-right: auto; width: 28%">'
		element += '                  <button class="btn btn-primary btn-cond-or hidden">Add OR Condition</button>'
		element += '            </div>'
		element += '        </div>'
		element += '    </div>'
		element += '</div>'
		element += '            </div>'
		return element
	}
	/**
	 * *Dropdown element to be inserted in dom after success insert in db
	 * todo: it needs to be added element and elementitem in db
	 * @param element_id, elementItem_id
	 */
	function insertDropdownElement(idElement, idElementitem, elementDOM) {
		
		var elementHead = `<div class="elements_added_v2">
			<div class="element-icon">
				<i class="far fa-list-alt" style="font-size:25px;"></i>
			</div>
			<div class="element-title-desc" onclick="javascript:collapseElementTitle(this)" style="cursor: pointer;">
				<div class="title-desc-wrapper">
					<span class="element-title">Dropdown Menu</span>
					<p class="element-description">
						Title
					</p>
				</div>
			</div>
			<?php echo scc_output_editing_page_element_actions_js_template( 'dropdown' ); ?>
		</div>`
		var dropd = '<div class="elements_added" style="border: 2px solid rgb(138, 153, 248);border-style: dashed;">'
		dropd += '    <input type="text" class="input_id_element" value="' + idElement + '" hidden="">'
		dropd += elementHead
		dropd += elementDOM['slider_body']
		dropd += '            </div>'
		return dropd;
	}
	/* utility function to setup unique ID for the custom quote form fields */
	function generateFormFieldUID(element) {
		var firstPart = (Math.random() * 46656) | 0;
		var secondPart = (Math.random() * 46656) | 0;
		firstPart = ("000" + firstPart.toString(36)).slice(-3);
		secondPart = ("000" + secondPart.toString(36)).slice(-3);
		return firstPart + secondPart;
	}
	/* Quote Form Setup: Add new field  */
	function addOrUpdateFormField(event, formModal) {
		event.preventDefault();
		let $formModal = jQuery(formModal);
		let formDataObject = new FormData(formModal);
		let currentFieldKey = formDataObject.get('fieldKey');
		// loading calculator configs
		const urlParams = new URLSearchParams(window.location.search);
		const calcId = urlParams.get('id_form');
		let calcConfig = JSON.parse(document.getElementById('scc-config-' + calcId).textContent);
		let {
			quoteFormFields
		} = calcConfig;
		// calculator loading finish
		let existingKeys = quoteFormFields.map((e, i) => {
			return Object.keys(e)
		}).flat();
		// detecting empty fields, later warnings can be shown based upon the field names
		var fieldsWithErrors = ['field_name', 'field_description'].filter((e, i) => {
			if (!formDataObject.get(e) || formDataObject.get(e) == 0 || !formDataObject.get(e).length) {
				return true;
			}
		});
		// try to use existing field key, if not it is a new field adding modal and needs id generation
		let fieldKey = currentFieldKey || generateFormFieldUID();
		let fieldName = formDataObject.get('field_name');
		let data = {
			id_form: calcId,
			action: 'sccHandleQuoteCustomFields',
			fieldProps: {
				[fieldKey]: {
					name: fieldName,
					description: formDataObject.get('field_description'),
					type: formDataObject.get('form-field-type'),
					isMandatory: formDataObject.get('is-mandatory') || false
				}
			},
			nonce: pageEditCalculator.nonce
		}

		function addOrUpdateButton(data) {
			if (!currentFieldKey) {
				jQuery('.editing-action-cards.action-quoteform .card-action-btns [data-btn-fieldtype="more-fields"]')
					.before(`<button class="btn btn-cards active" data-btn-fieldtype="custom" data-field-key="${fieldKey}">
					<span>${(fieldName).slice(0,20) + (fieldName.length > 21 ? '...' : '')}</span>
					<i class="scc-icon-formbuilder material-icons" data-form-builder-action-type="edit" onclick="console.log">edit</i>
					</button>`);
				addEventsToQuoteFormBtns(jQuery(`[data-field-key="${fieldKey}"]`, '.editing-action-cards.action-quoteform'));
			}
			if (currentFieldKey) {
				let newFieldName = data.props[currentFieldKey].name;
				jQuery(`[data-field-key="${currentFieldKey}"] span`, '.editing-action-cards.action-quoteform').text(newFieldName);
			}
		}
		showLoadingChanges()
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: data,
			type: 'POST',
			context: $formModal,
			beforeSend: function() {
				this.find('button[type="submit"]').prop('disabled', true);
				this.find('button[type="submit"]').children().html(`<div>
					<svg aria-hidden="true" style="width: 1em" focusable="false" data-prefix="fas" data-icon="spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-spinner fa-w-16 fa-spin"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z" class=""></path></svg>
					</div>
					<span class="trn df-scc-euiButton__text">Please wait...</span>`);
			},
			success: function(data) {
				if (data.passed == true) {
					this.find('button[type="submit"]').prop('disabled', false);
					this.find('button[type="submit"]').children().html('<span class="trn df-scc-euiButton__text">Add</span>')
					this.closest('[role=dialog]').modal('hide');
					loadPreviewForm(data.calcId);
					addOrUpdateButton(data);
					showSweet(true, "The changes have been saved.")
				} else {
					// showSweet(false, "There was an error, please try again")
				}
			}
		})
	}
	jQuery(document).ready(function($) {
		resort_updown()
		// handle "Add OR Condition" button
		$('.scc-left-pane').on('click', (event) => {
			if ($(event.target).hasClass('btn-cond-or')) {
				let lastCondContainer = $(event.target).parent().prev('.condition-container');
				let lastConditionSet = lastCondContainer.data('conditionSet');
				let newCondContainer = ($(`<div class="condition-container clearfix" data-condition-set=${lastConditionSet + 1}>
											<p>OR condition ${lastConditionSet + 1}</p>
											<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn hidden">Add</button>
										</div>`)).insertAfter(lastCondContainer);
				newCondContainer.find('.cond-add-btn').before(insertConditionaDiv("Show if", false));
			}
		})
	})
	/**
	 * *Handles up/down sections
	 */
	function resort_updown() {
		jQuery('#allinputstoadd .down:last').addClass('d-none');
		jQuery('#allinputstoadd .up:first').addClass('d-none');
		jQuery('#allinputstoadd .down').not(':last').removeClass('d-none');
		jQuery('#allinputstoadd .up').not(':first').removeClass('d-none');
		jQuery('#allinputstoadd .fieldDatatoAdd .sdown:last-child').addClass('d-none');
		jQuery('#allinputstoadd .fieldDatatoAdd .sup:first-child').addClass('d-none');
		jQuery('#allinputstoadd .fieldDatatoAdd .sdown').not(':last').removeClass('d-none')
		jQuery('#allinputstoadd .fieldDatatoAdd .sup').not(':first').removeClass('d-none')
	}

	function rup($this) {
		var wrapper = jQuery($this).closest('.addedFieldsStyle')
		wrapper.insertBefore(wrapper.prev())
		resort_updown()
		updateOrderOfSections()
	}

	function rdown($this) {
		var wrapper = jQuery($this).closest('.addedFieldsStyle')
		wrapper.insertAfter(wrapper.next())
		resort_updown()
		updateOrderOfSections()
	}

	function updateOrderOfSections() {
		jQuery.ajax({
			url: ajaxurl,
			cache: false,
			data: {
				action: 'sccUpdateSectionOrder',
				sections: getSectionsOrder(),
				nonce: pageEditCalculator.nonce
			},
			type: 'GET',
			success: function(data) {
				console.log(data)
				if (data.passed == true) {
					showSweet(true, "The changes have been saved.")
				} else {
					// showSweet(false, "There was an error, please try again")
				}
			}
		})

		function getSectionsOrder() {
			var ss = []
			jQuery("#allinputstoadd .fieldDatatoAdd").each(function(e, i) {
				var o = {}
				var sw = jQuery(this).closest(".addedFieldsStyle").find(".id_section_class").val()
				o.id = sw
				o.order = e
				ss.push(o)
			});
			return ss
		}
	}

	/**
	 * *Shows toast on input changes
	 * ?on timer ends reload preview
	 */
	let reloadToast = null

	function reloadform() {
		if (reloadToast !== null) {
			if (Swal.getTimerLeft() < 3000) {
				Swal.increaseTimer(5000)
			}
			return
		}
		reloadToast = Swal.fire({
			toast: true,
			position: 'top-end',
			title: 'Reloading preview',
			showConfirmButton: false,
			timer: 5000,
			background: 'white',
			timerProgressBar: true,
			// showClass:{
			//     popup:''
			// },
			willClose: () => {
				var id_form = jQuery("#id_scc_form_").val()
				jQuery(".preview_form_right_side").html(`<div class="df-scc-progress df-scc-progress-striped active">
				<div class="df-scc-progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="background-color: orange; width: 100%">
				</div>
				</div>`);
				loadPreviewForm(id_form)
				reloadToast = null
			}
		})
	}
</script>
<style>
	#adminmenumain, #wpfooter {
		display: none !important
	}

	#wpcontent {
		margin-left: 0 !important;
	}
</style>
