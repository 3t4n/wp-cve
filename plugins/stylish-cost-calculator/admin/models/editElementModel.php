<?php

class Stylish_Cost_Calculator_Edit_Page {

	private $calc_id;
	protected $is_from_ajax;
	protected $df_scc_form_currency;
	protected $is_woocommerce_enabled;
	public $woo_commerce_products;
	public function __construct( $calc_id = false, $is_from_ajax = false, $is_woocommerce_enabled = false ) {
		$this->calc_id                = $calc_id;
		$this->is_from_ajax           = $is_from_ajax;
		$this->df_scc_form_currency   = get_option( 'df_scc_currency', 'USD' );
		$this->is_woocommerce_enabled = false;
	}

	public function renderAdvancedOptions( $el ) {
		$defaults = array(
			'orden'              => 0,
			'titleElement'       => 'Title',
			'type'               => '',
			'subsection_id'      => 0,
			'value1'             => null,
			'value4'             => null,
			'value2'             => '',
			'value3'             => '0',
			'mandatory'          => 0,
			'showPriceHint'      => 0,
			'titleColumnDesktop' => '4',
			'titleColumnMobile'  => '12',
			'displayFrontend'    => 1,
			'displayDetailList'  => isset( $el->type ) && $el->type === 'texthtml' ? 3 : 1,
			'showTitlePdf'       => 0,
			'showInputBoxSlider' => 0,
		);
		// value3 will represent if symbol before the number be shown. By default, it should be of value '1'
		// here, he handle cases where value3 is empty
		if ( strlen( $el->value3 ) == 0 && ( $el->type == 'custom math' ) ) {
			$el->value3 = '1';
		};
		$el = (object) wp_parse_args( $el, $defaults );
		ob_start();
		?>
		<div class="scc-content" style="display: none;">
			<div class="scc-transition px-0 advanced-option-wrapper">
				<?php if ( $el->type == 'custom math' ) : ?>
						<div>
							<label class="scc-accordion_switch_button">
								<input onchange="changeDisplayFrontend(this)" class="scc_mandatory_dropdown" type="checkbox" 
								<?php
								if ( $el->displayFrontend == '1' ) {
									echo 'checked';}
								?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label>
							<span>Display on Frontend Form</span>
						</div>
						<div>
							<label class="scc-accordion_switch_button">
								<input onchange="changeDisplayDetail(this)" class="scc_mandatory_dropdown" type="checkbox" 
								<?php
								if ( $el->displayDetailList == '1' ) {
									echo 'checked';}
								?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label>
							<span>Display on Detailed List</span>
						</div>
						<div>
							<label class="scc-accordion_switch_button">
								<input onchange="changeCalculationSymbol(this)" class="scc_mandatory_dropdown" type="checkbox" 
								<?php
								if ( $el->value3 == '1' ) {
									echo 'checked';}
								?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label>
							<span>Show Calculation Symbol</span>
						</div>
					</div>
				</div>
					<?php
						$html = ob_get_clean();
						return $html;
					endif;
				?>
					<?php if ( $el->value1 != 8 ) : ?>
					<!-- Show only for image button element -->
					<div class="image-button-border" style="
						<?php
						if ( $el->value1 != 8 ) {
							echo 'display:none';}
						?>
					">
						<label class="scc-accordion_switch_button">
							<input onchange="changeValue4(this)" class="scc_show_border" name="scc_mandatory_dropdown" type="checkbox" 
							<?php
							if ( $el->value4 == 'true' ) {
								echo 'checked';}
							?>
							>
							<span class="scc-accordion_toggle_button round"></span>
						</label>
						<span>Image Border</span>
					</div>
					<!-- Show only for image button element -->
					<?php endif; ?>
					<?php if ( $el->value1 == 8 ) : ?>
						<div class="image-button-border">
							<label class="scc-accordion_switch_button">
								<input onchange="changeValue4(this)" class="scc_show_border" name="scc_mandatory_dropdown" type="checkbox" 
								<?php
								if ( $el->value4 == 'true' ) {
									echo 'checked';}
								?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label>
							<span>Image Border</span>
					</div>
						<div class="buttonsqtn">
							<label class="scc-accordion_switch_button">
								<input onchange="changeValue3__(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox" 
								<?php
								if ( $el->value3 == 'true' ) {
									echo 'checked';}
								?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label><span><span class="scc-adv-opt-lbl"> Show buttons to add quantity</span></span>
					</div>
					<?php endif; ?>
					<?php if ( $el->type !== 'texthtml' ) : ?>
					<div class="scc-advanced-option-cont">
						<label class="scc-accordion_switch_button">
							<input onchange="changeMandatoryElement(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox" 
							<?php
							if ( $el->mandatory == '1' ) {
								echo 'checked';}
							?>
							>
							<span class="scc-accordion_toggle_button round"></span>
						</label>
						<span>
							<span class="scc-adv-opt-lbl" >Mandatory</span>
							<i class="material-icons-outlined with-tooltip" data-element-tooltip-type="mandatory-elements-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
						</span>
					</div>
					
						<?php
					endif;
					if ( in_array( $el->type, array( 'quantity box' ) ) ) :
						?>
						<div class="scc-accordion-tooltip px-0" style="width: 100%; text-align:left;">
							<div class="row gx-2">
								<div class="col-md-6 input-field">
									<input onchange="changeValue3(this)" onkeyup="changeValue3(this)" id="<?php echo esc_attr( 'scc_title_column_dskp-' . $el->id ); ?>" class="scc_title_column_dskp" name="scc_title_column_dskp" type="number" value="<?php echo intval( $el->value3 ); ?>">
									<label <?php echo "class='active'"; ?> for="<?php echo esc_attr( 'scc_title_column_dskp-' . $el->id ); ?>">Max Value</label>
								</div>
								<div class="col-md-6 input-field">
									<input onchange="changeValue4(this)" onkeyup="changeValue4(this)" id="<?php echo esc_attr( 'scc_title_column_mobl-' . $el->id ); ?>" class="scc_title_column_mobl" name="scc_title_column_mobl" type="number" value="<?php echo intval( $el->value4 ); ?>">
									<label <?php echo "class='active'"; ?> for="<?php echo esc_attr( 'scc_title_column_mobl-' . $el->id ); ?>">Min Value</label>
								</div>
							</div>
						</div>
						<?php
						endif;
					?>
				<?php if ( in_array( $el->type, array( 'checkbox', 'slider' ) ) ) : ?>
				<div class="scc-advanced-option-cont">
					<label class="scc-accordion_switch_button">
						<input onchange="changeShowPriceHintElement(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox" disabled>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span><span class="scc-adv-opt-lbl">Show Price Hint</span>
					<i class="material-icons-outlined with-tooltip" data-element-tooltip-type="enable-price-hint-bubble-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
					</span>
				</div>
				<?php endif; ?>
				<?php if ( in_array( $el->type, array( 'quantity box' ) ) ) : ?>
					<p class="scc-advanced-option-cont">
						<label class="scc-accordion_switch_button">
							<input onchange="sccBackendUtils.changeNumberInputCommaFormat(this)" class="scc_mandatory_dropdown"
								name="scc_mandatory_dropdown" type="checkbox" <?php
										if ( isset( $el->value5 ) && 2 == $el->value5 ) {
											echo 'checked';
										}
										?>>
							<span class="scc-accordion_toggle_button round"></span>
						</label>
						<span>
							<span class="scc-adv-opt-lbl use-tooltip">Enable commas </span>
							<i class="material-icons-outlined with-tooltip" data-element-tooltip-type="qnt-input-comma-number" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
						</span>
					</p>
				<?php endif; ?>
				<?php if ( in_array( $el->type, array( 'slider', 'texthtml' ) ) ) : ?>
				<div class="scc-advanced-option-cont">
					<label class="scc-accordion_switch_button">
						<input onchange="toggleSliderDisplayinDetail(this)" name="scc_hide_slider_on_detailed_view" type="checkbox" 
						<?php
						if ( $el->displayDetailList != '3' ) {
							echo 'checked';}
						?>
						>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span><span class="scc-adv-opt-lbl">Show on Detailed List</span>
					<i class="material-icons-outlined with-tooltip" data-element-tooltip-type="display-on-detailed-list-pdf-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
					</span>
				</div>
				<?php endif; ?>
				<?php if ( in_array( $el->type, array( 'slider' ) ) ) : ?>
				<div class="scc-advanced-option-cont">
					<label class="scc-accordion_switch_button">
						<input onchange="toggleSliderInputBoxShowHide(this)" name="scc_show_inputbox_slider" type="checkbox" 
						<?php
						if ( $el->showInputBoxSlider != '0' ) {
							echo 'checked';}
						?>
						>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span><span class="scc-adv-opt-lbl">Add Input Box To Slider</span>
					<i class="material-icons-outlined with-tooltip" data-element-tooltip-type="append-quantity-input-box-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
					</span>
				</div>
				<div class="row gx-2 mt-2 scc-advanced-option-cont">
					<div class="col-md-6 input-field">
						<input id="<?php echo esc_attr( 'slider-starting-value-' . $el->id ); ?>" type="number" onchange="changeValue3(this, true);" onkeyup="changeValue3(this)" value="<?php echo esc_attr( $el->value3 ); ?>" style="margin-bottom: 0px;">
						<label for="<?php echo esc_attr( 'slider-starting-value-' . $el->id ); ?>" class="active form-label fw-bold">Starting value</label>
					</div>
					<div class="col-md-6 input-field">
						<input id="<?php echo esc_attr( 'slider-steps-value-' . $el->id ); ?>" type="number" onchange="changeValue2(this)" onkeyup="changeValue2(this)" value="<?php echo esc_attr( $el->value2 ); ?>" style="margin-bottom: 0px;">
						<label for="<?php echo esc_attr( 'slider-steps-value-' . $el->id ); ?>" class="active form-label fw-bold">Slider steps</label>
					</div>
				</div>
				<p class="d-none slider-start-value-warning" style="color: red;">The starting value cannot be smaller than the base from value.</p>
			   <?php endif; ?>
				<?php if ( in_array( $el->type, array( 'math', 'Dropdown Menu' ) ) ) : ?>
				<div class="scc-advanced-option-cont">
					<label class="scc-accordion_switch_button">
						<input onchange="changeShowTitlePdf(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox" 
						<?php
						if ( $el->showTitlePdf == '1' ) {
							echo 'checked';}
						?>
						>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span class="scc-adv-opt-lbl">Show Title on Detailed List & PDF 
						<i class="material-icons-outlined with-tooltip" data-element-tooltip-type="show-title-on-detailed-list-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
					</span>
				</div>
					<?php
				endif;
				if ( $el->type != 'checkbox' ) :
					?>
					<div class="text-scc-col d-flex" style="font-size:13px;">
						<div class="col-md-12 input-field use-premium-tooltip">
							<input onchange="changeTooltipText(this)" onkeyup="changeTooltipText(this)" id="<?php echo esc_attr( 'scc_tooltip_input-' . $el->id ); ?>" class="scc_title_column_mobl" name="scc_title_column_mobl" type="text" value="<?php echo ( isset($el->tooltiptext) ) ? esc_attr( $el->tooltiptext ) : ''; ?>" disabled>
							<label class="form-label fw-bold use-tooltip <?php echo ( isset( $el->tooltiptext ) && strlen( $el->tooltiptext ) > 0 ) ? 'active' : ''; ?> " for="<?php echo esc_attr( 'scc_tooltip_input-' . $el->id ); ?>" title="On the frontend, display a tooltip icon and information next to element titles. Explain what this item is about while keeping the calculator form organized.">Tooltip</label>
						</div>
					</div>
					<?php
					endif;
				?>
				<?php if ( $el->type !== 'texthtml' ) : ?>
				<div class="scc-accordion-tooltip px-0" style="width: 100%; text-align:left;"><span style="text-align: left;display: block;font-size:16px;margin-bottom:10px;">Responsive Options 
				<i class="material-icons-outlined with-tooltip" data-element-tooltip-type="responsive-options-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
				</span>
					<div class="row gx-2 mt-2">
						<div class="col-md-6 input-field use-premium-tooltip">
							<input disabled onchange="changeColumnDesktop(this)" id="<?php echo esc_attr( 'scc_title_column_dskp_r-' . $el->id ); ?>" onkeyup="changeColumnDesktop(this)" class="scc_title_column_dskp" min="1" max="12" name="scc_title_column_dskp" type="number" value="<?php echo intval( $el->titleColumnDesktop ); ?>">
							<label class="active form-label fw-bold" for="<?php echo esc_attr( 'scc_title_column_dskp_r-' . $el->id ); ?>" title="Please enter a number between 1 and 12. 1 being the smallest and 12 being the largest, for your title. If you have a large title, we recommend between 6 and 12.">Title column (desktop)</label>
						</div>
						<div class="col-md-6 input-field use-premium-tooltip">
							<input disabled onchange="changeColumnMobile(this)" id="<?php echo esc_attr( 'scc_title_column_mobl_r-' . $el->id ); ?>" onkeyup="changeColumnMobile(this)" class="scc_title_column_mobl" min="1" max="12" name="scc_title_column_mobl" type="number" value="<?php echo intval( $el->titleColumnMobile ); ?>">
							<label class="active form-label fw-bold" for="<?php echo esc_attr( 'scc_title_column_mobl_r-' . $el->id ); ?>" title="Please enter a number between 1 and 12. 1 being the smallest and 12 being the largest, for your title. If you have a large title, we recommend between 6 and 12.">Title column (mobile)</label>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<?php if ( in_array( $el->type, array( 'checkbox' ) ) ) : ?>
				<div class="scc-accordion-tooltip px-0" style="width: 100%; text-align:left;">
					<span style="text-align: left;display: block;font-weight:bold">Checkbox Columns</span>
					<div class="row gx-0">
						<select onchange="changeColumnsCheckbox(this)" name="" id="" style="width: 100%; min-width: 100%;">
							<option value="1" 
							<?php
							if ( $el->value2 == '1' ) {
								echo 'selected';}
							?>
							 >One</option>
							<option value="2" 
							<?php
							if ( $el->value2 == '2' ) {
								echo 'selected';}
							?>
							 >Two</option>
							<option value="3" 
							<?php
							if ( $el->value2 == '3' ) {
								echo 'selected';}
							?>
							 >Three</option>
						</select>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	public function renderFileUploadSetupBody2( $el, $conditionsBySet ) {
		if ( $this->is_from_ajax ) {
			$el->value1 = 'default';
		}
		$defaults = array(
			'orden'                         => 0,
			'titleElement'                  => 'Title',
			'type'                          => '',
			'subsection_id'                 => 0,
			'value1'                        => 'default',
			'value4'                        => null,
			'value3'                        => null,
			'value2'                        => '',
			'length'                        => '',
			'uniqueId'                      => '',
			'mandatory'                     => 0,
			'showTitlePdf'                  => 0,
			'titleColumnDesktop'            => '',
			'titleColumnMobile'             => '',
			'showPriceHint'                 => 0,
			'displayFrontend'               => 1,
			'displayDetailList'             => 1,
			'subsection_id'                 => 0,
			'element_woocomerce_product_id' => 0,
			'elementitems'                  => array(
				(object) array(
					'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
					'order'                 => '0',
					'name'                  => 'Name',
					'price'                 => '10',
					'description'           => 'Description',
					'value1'                => '',
					'value2'                => '',
					'value3'                => '',
					'value4'                => '',
					'uniqueId'              => 'ozkYrfNw9j',
					'woocomerce_product_id' => '',
					'opt_default'           => '0',
					'element_id'            => '0',
				),
			),
			'conditions'                    => array( 1 => array() ),
		);
		$el       = (object) meks_wp_parse_args( $el, $defaults );
		?>
		<div class="scc-element-content" value="selectoption" style="
		<?php
		if ( ! $this->is_from_ajax ) {
			echo 'display:none;';
		}
		?>
		 height:auto;">
			<div class="slider-setup-body">
													<!-- CONTENIDO DE CADA ELEMENTO -->
													<!-- ELEMENT -->
													<label class="form-label fw-bold">Title</label>
													<div class="input-group mb-3">
													<input type="text" class="input_pad inputoption_title" onkeyup="clickedTitleElement(this)" style="height:35px;width:100%;margin: 0;" placeholder="Title" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>">
													</div>
													<div class="row g-3 edit-field" style="    margin-bottom: 1rem!important;">
														<div class="col" >
															<label class="form-label fw-bold">Placeholder text</label>
															<input onkeyup="changeValue2(this)" type="text" class="input_pad inputoption_placeholder" style="width:100%;max-width:100%;float:left;height:35px;" placeholder="Please choose a file" value="<?php echo esc_attr( $el->value2 ); ?>">
														</div>
													</div>
													<div class="row g-3 edit-field" style="    margin-bottom: 1rem!important;">
														<div class="col">
															<label class="form-label fw-bold" style="width: 100%;" >Allowed file types</label>
															<input onkeyup="changeValue3(this)" type="text" class="input_pad inputoption_filetypes" style="width:100%;max-width:100%;float:left;height:35px;" placeholder="png,pdf,jpeg,jpg" value="<?php echo esc_attr( $el->value3 ); ?>">
														</div>
													</div>
													<div class="row g-3 edit-field" style="    margin-bottom: 1rem!important;">
														<div class="col">
															<label class="form-label fw-bold" style="width: 100%;">Max file size (kbs)</label>
															<input onchange="changeValue4(this)" onkeyup="changeValue4(this)" type="number" class="input_pad inputoption_2" style="float:left;margin-right:10px;width:80px;height:35px" placeholder="10" min="0" value="<?php echo esc_attr( $el->value4 ); ?>">
														</div>
													</div>
													</div>
													<div class="scc-element-content" value="selectoption" style="display:none; height:auto">
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
																<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional ">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															 <div class="scc-content" style="display: none;">
																<div class="scc-transition">
																	<?php
																	// echo json_encode($conditionsBySet);
																	foreach ( $conditionsBySet as $key => $conditionCollection ) :
																		?>
																		<?php if ( $key > 1 ) : ?>
																			<div style="margin: 10px 0px 10px -10px;">OR</div>
																		<?php endif; ?>
																		<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
																			<?php
																			foreach ( $conditionCollection as $index => $condition ) {
																				if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
																					?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="eq" 
																										<?php
																										if ( $condition->op == 'eq' ) {
																											echo 'selected';
																										}
																										?>
																										>Equal To</option>
																										<option value="ne" 
																										<?php
																										if ( $condition->op == 'ne' ) {
																											echo 'selected';
																										}
																										?>
																										>Not Equal To</option>
																										<option value="any" 
																										<?php
																										if ( $condition->op == 'any' ) {
																											echo 'selected';
																										}
																										?>
																										>Any</option>
																									</select>
																									<select class="third-conditional-step col-3" style="height: 35px;
																									<?php
																									if ( $condition->op == 'any' ) {
																										echo 'display:none';
																									}
																									?>
																									 ">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="chec" 
																										<?php
																										if ( $condition->op == 'chec' ) {
																											echo 'selected';
																										}
																										?>
																										>Checked</option>
																										<option value="unc" 
																										<?php
																										if ( $condition->op == 'unc' ) {
																											echo 'selected';
																										}
																										?>
																										>Unchecked</option>
																									</select>
																									<select class="third-conditional-step col-3" style="height: 35px;display:none">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																							<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																								<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																							</div>
																							<div class="col-xs-11 col-md-11" style="padding:0px;">
																								<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																									<div class="item_conditionals">
																										<select class="first-conditional-step col-3" style="height: 35px;">
																											<option style="font-size: 10px" value="0">Select one</option>
																											<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																										</select>
																										<select class="second-conditional-step col-3" style="height: 35px;">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="eq" 
																											<?php
																											if ( $condition->op == 'eq' ) {
																												echo 'selected';
																											}
																											?>
																											>Equal To</option>
																											<option value="ne" 
																											<?php
																											if ( $condition->op == 'ne' ) {
																												echo 'selected';
																											}
																											?>
																											>Not Equal To</option>
																											<option value="gr" 
																											<?php
																											if ( $condition->op == 'gr' ) {
																												echo 'selected';
																											}
																											?>
																											>Greater than</option>
																											<option value="les" 
																											<?php
																											if ( $condition->op == 'les' ) {
																												echo 'selected';
																											}
																											?>
																											>Less than</option>
																										</select>
																										<select class="third-conditional-step col-3" style="height: 35px;display:none">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																										</select>
																										<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
																										<div class="btn-group" style="margin-left: 10px;">
																											<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																			<div class="row col-xs-12 col-md-12 conditional-selection  
																			<?php
																			if ( count( $conditionCollection ) ) {
																				echo 'hidden';
																			}
																			?>
																			" style="padding: 0px; margin-bottom: 5px;">
																				<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																					<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
																				</div>
																				<div class="col-xs-11 col-md-11" style="padding:0px;">
																					<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																						<div class="item_conditionals">
																							<select class="first-conditional-step col-3" style="height: 35px;">
																								<option style="font-size: 10px" value="0">Select an element</option>
																							</select>
																							<select class="second-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<select class="third-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																							<div class="btn-group" style="margin-left: 10px;display:none">
																								<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																	<?php endforeach; ?>
																	<div style="width: 28%">
																		<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
																	</div>
																</div>
															 </div>
														</div>
													</div>
													</div>
													<!-- ADVANCE -->
												</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	public function renderCheckboxSetupBody( $el, $conditionsBySet ) {
		$defaults = array(
			'orden'                         => 0,
			'titleElement'                  => 'Title',
			'type'                          => '',
			'subsection_id'                 => 0,
			'value1'                        => 'default',
			'value4'                        => null,
			'value3'                        => null,
			'value2'                        => '',
			'length'                        => '',
			'uniqueId'                      => '',
			'mandatory'                     => 0,
			'showTitlePdf'                  => 0,
			'titleColumnDesktop'            => '4',
			'titleColumnMobile'             => '12',
			'showPriceHint'                 => 0,
			'displayFrontend'               => 1,
			'displayDetailList'             => 1,
			'subsection_id'                 => 0,
			'element_woocomerce_product_id' => 0,
			'elementitems'                  => array(
				(object) array(
					'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
					'order'                 => '0',
					'name'                  => 'Name',
					'price'                 => '10',
					'description'           => 'Description',
					'value1'                => '',
					'value2'                => '',
					'value3'                => '',
					'value4'                => '',
					'uniqueId'              => 'ozkYrfNw9j',
					'woocomerce_product_id' => '',
					'opt_default'           => '0',
					'element_id'            => '0',
				),
			),
			'conditions'                    => array(),
		);
		$el       = (object) meks_wp_parse_args( $el, $defaults );
		ob_start();
		?>
	<div class="scc-element-content checkbox-content" value="selectoption" style="
		<?php
		if ( ! $this->is_from_ajax ) {
			echo 'display:none;';}
		?>
	 height:auto">
		<!-- CONTENIDO DE CADA ELEMENTO -->
		<!-- Simple Buttons - ELEMENT -->
		<div class="slider-setup-body">
		<label class="form-label fw-bold" title="For checkboxes, this will not appear on the frontend. Its for internal references only.">Title (Internal reference only)</label>
			<div class="input-group mb-3">
			<input type="text" class="form-control" onkeyup="clickedTitleElement(this)" value="<?php echo esc_attr( wp_unslash( $el->titleElement ) ); ?>">
		</div>
		<div class="row g-3 edit-field">
			<div class="col">
				<label class="form-label fw-bold" style="align-items: center;
    display: flex;">Input Box Style 
					<i class="material-icons-outlined with-tooltip" style="margin-left:3px;" data-element-tooltip-type="checkbox-styles-tt" title="" data-bs-original-title="">help_outline</i>
				</label>
					<select onchange="changeValue1(this)" class="fieldFormat" style="width:100%;max-width:100%;height:35px;border-color:#f8f9ff;">
					<option value="6" 
					<?php
					if ( $el->value1 == '6' ) {
						echo 'selected';
					}
					?>
										>Simple Buttons (Inline)</option>
					<option value="1" 
					<?php
					if ( $el->value1 == '1' ) {
						echo 'selected';
					}
					?>
										>Circle Checkbox</option>
					<option value="5" 
					<?php
					if ( $el->value1 == '5' ) {
						echo 'selected';
					}
					?>
										>Circle Checkbox (Animated)</option>
					<option value="2" 
					<?php
					if ( $el->value1 == '2' ) {
						echo 'selected';
					}
					?>
										>Square Checkbox (Animated)</option>
					<option value="3" 
					<?php
					if ( $el->value1 == '3' ) {
						echo 'selected';
					}
					?>
										>Rectangle Toggle Switch</option>
					<option value="4" 
					<?php
					if ( $el->value1 == '4' ) {
						echo 'selected';
					}
					?>
										>Rounded Toggle Switch </option>
					<option value="7" 
					<?php
					if ( $el->value1 == '7' ) {
						echo 'selected';
					}
					?>
										>Radio (Single Choice)</option>
					<option value="" disabled>Multi-Items Radio Switch (Premium only)</option>
					<option value="" disabled>Image Buttons (Premium only)</option>
				</select>
														</div>
									</div>
		<!-- Image Button & Checkboxes & Simple Buttons Elements - (Pulled from DB) -->
		<div class="selectoption_2 col-xs-12 col-md-12" style="margin-top:20px;">
			<?php
			foreach ( $el->elementitems as $key => $elit ) {
				$count = $key + 1;
				echo $this->checkbox_setup_checkbox_item( $count, $elit, $el->value1 == 8 );
			}
			?>
		</div>
		<div style="margin-top:5px;"><a onclick="addCheckboxItems(this)" data-type="<?php echo ( $el->value1 == 8 ) ? 'image-button' : 'otro'; ?>" class="crossnadd" style="margin-top:5px;margin-bottom:20px;">+ Item </a>
		</div>
		</div>
		</div>
		<div class="scc-element-content" value="selectoption" style="
		<?php
		if ( ! $this->is_from_ajax ) {
			echo 'display:none;';}
		?>
		 height:auto">
		<div class="scc-new-accordion-container">
			<div class="styled-accordion">
				<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
					<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
				</div>
				<?php echo $this->renderAdvancedOptions( $el ); ?>
			</div>
			<div class="styled-accordion">
				<div class="scc-title scc_accordion_conditional ">
					<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
				</div>
				<div class="scc-content" style="display: none;">
					<div class="scc-transition">
						<?php
						// echo json_encode($conditionsBySet);
						foreach ( $conditionsBySet as $key => $conditionCollection ) :
							?>
							<?php if ( $key > 1 ) : ?>
								<div style="margin: 10px 0px 10px -10px;">OR</div>
							<?php endif; ?>
							<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
								<?php
								foreach ( $conditionCollection as $index => $condition ) {
									if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
										?>
										<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
											<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
											<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
												<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
											</div>
											<div class="col-xs-11 col-md-11" style="padding:0px;">
												<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
													<div class="item_conditionals">
														<select class="first-conditional-step col-3" style="height: 35px;">
															<option style="font-size: 10px" value="0">Select one</option>
															<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
														</select>
														<select class="second-conditional-step col-3" style="height: 35px;">
															<option value="0" style="font-size: 10px">Select one</option>
															<option value="eq" 
															<?php
															if ( $condition->op == 'eq' ) {
																echo 'selected';
															}
															?>
																				>Equal To</option>
															<option value="ne" 
															<?php
															if ( $condition->op == 'ne' ) {
																echo 'selected';
															}
															?>
																				>Not Equal To</option>
															<option value="any" 
															<?php
															if ( $condition->op == 'any' ) {
																echo 'selected';
															}
															?>
																				>Any</option>
														</select>
														<select class="third-conditional-step col-3" style="height: 35px;
																										<?php
																										if ( $condition->op == 'any' ) {
																											echo 'display:none';
																										}
																										?>
																										">
															<option value="0" style="font-size: 10px">Select one</option>
															<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
														</select>
														<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
														<div class="btn-group" style="margin-left: 10px;">
															<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
											<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
												<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
											</div>
											<div class="col-xs-11 col-md-11" style="padding:0px;">
												<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
													<div class="item_conditionals">
														<select class="first-conditional-step col-3" style="height: 35px;">
															<option style="font-size: 10px" value="0">Select one</option>
															<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
														</select>
														<select class="second-conditional-step col-3" style="height: 35px;">
															<option value="0" style="font-size: 10px">Select one</option>
															<option value="chec" 
															<?php
															if ( $condition->op == 'chec' ) {
																echo 'selected';
															}
															?>
																					>Checked</option>
															<option value="unc" 
															<?php
															if ( $condition->op == 'unc' ) {
																echo 'selected';
															}
															?>
																				>Unchecked</option>
														</select>
														<select class="third-conditional-step col-3" style="height: 35px;display:none">
															<option value="0" style="font-size: 10px">Select one</option>
															<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
														</select>
														<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
														<div class="btn-group" style="margin-left: 10px;">
															<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
												<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
													<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
												</div>
												<div class="col-xs-11 col-md-11" style="padding:0px;">
													<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
														<div class="item_conditionals">
															<select class="first-conditional-step col-3" style="height: 35px;">
																<option style="font-size: 10px" value="0">Select one</option>
																<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
															</select>
															<select class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="eq" 
																<?php
																if ( $condition->op == 'eq' ) {
																	echo 'selected';
																}
																?>
																					>Equal To</option>
																<option value="ne" 
																<?php
																if ( $condition->op == 'ne' ) {
																	echo 'selected';
																}
																?>
																					>Not Equal To</option>
																<option value="gr" 
																<?php
																if ( $condition->op == 'gr' ) {
																	echo 'selected';
																}
																?>
																					>Greater than</option>
																<option value="les" 
																<?php
																if ( $condition->op == 'les' ) {
																	echo 'selected';
																}
																?>
																					>Less than</option>
															</select>
															<select class="third-conditional-step col-3" style="height: 35px;display:none">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
															<div class="btn-group" style="margin-left: 10px;">
																<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
								<div class="row col-xs-12 col-md-12 conditional-selection  
																				<?php
																				if ( count( $conditionCollection ) ) {
																					echo 'hidden';
																				}
																				?>
																				" style="padding: 0px; margin-bottom: 5px;">
									<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
										<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
									</div>
									<div class="col-xs-11 col-md-11" style="padding:0px;">
										<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
											<div class="item_conditionals">
												<select class="first-conditional-step col-3" style="height: 35px;">
													<option style="font-size: 10px" value="0">Select an element</option>
												</select>
												<select class="second-conditional-step col-3" style="height: 35px;display:none">
													<option value="0" style="font-size: 10px">Select one</option>
												</select>
												<select class="third-conditional-step col-3" style="height: 35px;display:none">
													<option value="0" style="font-size: 10px">Select one</option>
												</select>
												<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
												<div class="btn-group" style="margin-left: 10px;display:none">
													<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
						<?php endforeach; ?>
						<div style="width: 28%">
							<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	public function checkbox_setup_checkbox_item( $count, $elit, $is_image_checkbox ) {
		$defaults = array(
			'id'                    => null,
			'order'                 => '0',
			'name'                  => 'Name',
			'price'                 => '10',
			'description'           => 'Description',
			'value1'                => '',
			'value2'                => '',
			'value3'                => '',
			'value4'                => '',
			'uniqueId'              => 'ozkYrfNw9j',
			'woocomerce_product_id' => '',
			'opt_default'           => '0',
		);
		$elit     = (object) wp_parse_args( $elit, $defaults );
		ob_start();
		?>
		<div class="row m-0 selopt3 col-md-12 col-xs-12" style="margin-bottom:5px;padding:0px;width: 110%">
					<div class="row" style="margin:0; padding: 0; width: 91%;">
						<div class="row p-0 m-0 mt-2 col-md-12 col-xs-12">
							<input class="666 swichoptionitem_id" type="text" value="<?php echo intval( $elit->id ); ?>" hidden>
							<div class="scc-input 123 el_1 col-xs-1 col-md-1 tool-premium  
																			<?php
																			if ( $elit->opt_default == '1' ) {
																				echo 'is-set-default';
																			}
																			?>
																			" onclick="setDefaultOption(this)" id="dropdownOpt" style="padding:0px;">
								<label class="scc-elm-num-lbl"><?php echo intval( $count ); ?></label>
							</div>
							<div class="col-md-6 col-xs-6 el_2" style="padding: 0px 5px 0px 1px;">
								<input type="text" onkeyup="changeNameElementItem(this)" class="input_pad inputoption scc-input" style="width:100%;" value="<?php echo stripslashes( wp_kses( $elit->name, SCC_ALLOWTAGS ) ); ?>" placeholder="Product or service name">
							</div>
							<div class="col-md-3 d-flex scc-input-icon scc-input" style="padding:0px;">
								<span class="input-group-text"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
								<input type="number" onchange="changePriceElementItem(this)" onkeyup="changePriceElementItem(this)" class="input_pad inputoption_2" style="width:100%;text-align:center;height:35px;" placeholder="Price" value="<?php echo floatval( $elit->price ); ?>">
							</div>
						</div>
						<!-- Added for image button -->
						<div class="col-md-2 col-xs-2 checkbox-image image_container mt-1" style="
																		<?php
																		if ( ! $is_image_checkbox ) {
																			echo 'display:none';
																		}
																		?>
																		">
							<img class="scc-image-picker" style="margin-bottom: -15px;height: 75px;width:75px;object-fit:contain;" onclick="choseImageElementItem(this)" src="<?php echo ( $elit->value1 == null || $elit->value1 == '0' ) ? SCC_ASSETS_URL . '/images/image.png' : $elit->value1; ?>" title="Pick an image. Please choose an image with a 1:1 aspect ratio for best results.">
							<span class="scc-dropdown-image-remove" onclick="removeDropdownImage(this)">x</span>
						</div>
						<!-- < -->

							<!-- START WooCommerce for Image Buttons & Checkboxes Element-->
							<?php if ( isset( $this->woo_commerce_products ) ) : ?>
								<div class="dd-woocommerce 
								<?php
								echo $is_image_checkbox ? 'scc-col-md-10' : 'scc-col-md-12';
								if ( ! empty( $this->combine_checkout_woocommerce_product_id ) ) {
									echo 'd-none';}
								?>
								" style="padding:0px;">
									<div class="scc-col-xs-0 scc-col-md-2" style="padding:0px;background: #f8f9ff;height: 35px;"><img class="scc-woo-logo" src="<?php echo esc_url_raw( SCC_ASSETS_URL . '/images/logo-woocommerce.svg' ); ?>" title="Pick an item from your WooCommerce products to link to."></div>
									<div class="woo-product-dd scc-col-xs-6 scc-col-md-10" style="padding:0px;">
										<select class="scc_woo_commerce_product_id" data-target="elements_added" onchange="attachProductId(this, <?php echo intval( $elit->id ); ?>)" style="float:left;height:35px;margin-bottom:20px;max-width: 100%;">
											<option style="font-size: 10px" value=0>Select a product..</option>
											<?php
											foreach ( $this->woo_commerce_products as $product ) {
												?>
												<?php
												if ( $product->is_type( 'variable' ) ) {
													$available_variations = $product->get_available_variations();
													foreach ( $available_variations as $product_variable ) {
														$attributes = array();
														foreach ( $product_variable['attributes'] as $key => $value ) {
															$attributes[] = $product->get_name() . ': ' . $value;
														}
														?>
														<option value=<?php echo esc_html( $product_variable['variation_id'] ); ?> <?php echo selected( $product->get_id() == intval( $elit->woocomerce_product_id ) ); ?>><?php echo esc_html( implode( ' | ', $attributes ) ) . ' | Price: ' . get_woocommerce_currency_symbol() . '' . esc_html( $product_variable['display_regular_price'] ); ?></option>
														<?php
													}
												} else {
													?>
													<option value=<?php echo esc_html( $product->get_id() ); ?> <?php echo selected( $product->get_id() == intval( $elit->woocomerce_product_id ) ); ?>><?php echo esc_html( $product->get_name() ) . ' | Price: ' . get_woocommerce_currency_symbol() . '' . esc_html( $product->get_price() ); ?></option>
													<?php
												}
											}
											?>
										</select>
									</div>
								</div>
							<?php endif; ?>
							<!-- END woocommerce dropdown -->
					</div>
					<div class="col-md-1 col-xs-1" style="padding-left: 0;">
						<button onclick="removeSwitchOptionDropdown(this)" class="deleteBackendElmnt"><i class="fa fa-trash"></i></button>
					</div>
				</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	public function renderCommentBoxSetupBody2( $el, $conditionsBySet ) {
		if ( $this->is_from_ajax ) {
			$el->value1 = 'default';
		}
		$defaults = array(
			'orden'                         => 0,
			'titleElement'                  => 'Title',
			'type'                          => '',
			'subsection_id'                 => 0,
			'value1'                        => 'default',
			'value4'                        => null,
			'value3'                        => null,
			'value2'                        => '',
			'length'                        => '',
			'uniqueId'                      => '',
			'mandatory'                     => 0,
			'showTitlePdf'                  => 0,
			'titleColumnDesktop'            => '4',
			'titleColumnMobile'             => '12',
			'showPriceHint'                 => 0,
			'displayFrontend'               => 1,
			'displayDetailList'             => 1,
			'subsection_id'                 => 0,
			'element_woocomerce_product_id' => 0,
			'elementitems'                  => array(
				(object) array(
					'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
					'order'                 => '0',
					'name'                  => 'Name',
					'price'                 => '10',
					'description'           => 'Description',
					'value1'                => '',
					'value2'                => '',
					'value3'                => '',
					'value4'                => '',
					'uniqueId'              => 'ozkYrfNw9j',
					'woocomerce_product_id' => '',
					'opt_default'           => '0',
					'element_id'            => '0',
				),
			),
			'conditions'                    => array( 1 => array() ),
		);
		$el       = (object) meks_wp_parse_args( $el, $defaults );
		ob_start();
		?>
		<div class="scc-element-content" value="selectoption" style="
		<?php
		if ( ! $this->is_from_ajax ) {
			echo 'display:none;';
		}
		?>
		 height:auto;">
			<div class="slider-setup-body" style="border:0px none!">
													<!-- CONTENIDO DE CADA ELEMENTO -->
													<!-- ELEMENT -->
													<label class="form-label fw-bold">Title</label>
													<div class="input-group mb-3">
													<input type="text" class="input_pad inputoption_title" onkeyup="clickedTitleElement(this)" style="height:35px;width:100%;" placeholder="Title" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>">
													</div>
													<div class="row g-3 edit-field">
														<div class="col">
															<label class="form-label fw-bold use-tooltip" title="Define the size of the comment input box height" style="width: 100%;">Height</label>
															<input onkeyup="changeValue2(this)" onchange="changeValue2(this)" type="number" class="input_pad inputoption_2" style="text-align:center;width:80px;height:35px" placeholder="3" value="<?php echo esc_attr( $el->value2 ); ?>">
													</div>
													</div>
													<div class="row m-0 mt-2 col-xs-12 col-md-12" style="padding: 0;">
													<label class="form-label fw-bold" style="width: 100%;padding: 0;">Placeholder</label>
															<div class="col-xs-12 col-md-12" style="padding:0px;">
																<textarea onkeyup="changeValue3(this)" class="input_pad inputoption_desc" style="width:100%;max-width: 100%;" rows="4" placeholder=""><?php echo esc_attr( $el->value3 ); ?></textarea>
															</div>
														</div>
														</div>
													<div class="scc-element-content" value="selectoption" style="display:none; height:auto">
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
																<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional ">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
													   </div>
													</div>
													<!-- ADVANCE -->
												</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	public function renderQuantityBoxSetupBody2( $el, $conditionsBySet ) {
		if ( $this->is_from_ajax ) {
			$el->value1 = 'default';
		}
		$defaults = array(
			'orden'                         => 0,
			'titleElement'                  => 'Title',
			'type'                          => '',
			'subsection_id'                 => 0,
			'value1'                        => 'default',
			'value4'                        => null,
			'value3'                        => null,
			'value2'                        => '',
			'length'                        => '',
			'uniqueId'                      => '',
			'mandatory'                     => 0,
			'showTitlePdf'                  => 0,
			'titleColumnDesktop'            => '4',
			'titleColumnMobile'             => '12',
			'showPriceHint'                 => 0,
			'displayFrontend'               => 1,
			'displayDetailList'             => 1,
			'subsection_id'                 => 0,
			'element_woocomerce_product_id' => 0,
			'elementitems'                  => array(
				(object) array(
					'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
					'order'                 => '0',
					'name'                  => 'Name',
					'price'                 => '10',
					'description'           => 'Description',
					'value1'                => '',
					'value2'                => '',
					'value3'                => '',
					'value4'                => '',
					'uniqueId'              => 'ozkYrfNw9j',
					'woocomerce_product_id' => '',
					'opt_default'           => '0',
					'element_id'            => '0',
				),
			),
			'conditions'                    => array( 1 => array() ),
		);
		$el       = (object) meks_wp_parse_args( $el, $defaults );
		ob_start();
		?>
		<div class="scc-element-content" value="selectoption" style="
		<?php
		if ( ! $this->is_from_ajax ) {
			echo 'display:none;';
		}
		?>
		 height:auto;">
			<div class="slider-setup-body">
													<!-- CONTENIDO DE CADA ELEMENTO -->
													<!-- ELEMENT -->
													<label class="form-label fw-bold">Title</label>
													<div class="input-group mb-3">
														<input type="text" class="form-control" onkeyup="clickedTitleElement(this)" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>">
													</div>
													<div class="row g-3 edit-field">
														<div class="col">
															<label class="form-label fw-bold">Input Box Style</label>
															 <select onchange="changeValue1(this)" type="select-one" name="quantity-input--style-selection" style="width:100%;max-width:100%;float:left;height:35px;">'
															<option value="default" selected="">Default</option>
															<option value="compact">Compact</option>'
															</select>'
														</div>
													
													<div class="row g-3 edit-field" style="margin-top: 3%;">
														<!-- <div class="col">
															<label class="form-label fw-bold" style="width: 100%;">Price</label>
																<input onkeyup="changeValue2(this)" onchange="changeValue2(this)" type="number" class="input_pad inputoption_2" style="text-align:center;width:80px;height:35px" placeholder="Price" value="<?php echo esc_attr( $el->value2 ); ?>">
														</div> -->
														<div class="col-md-3 d-flex scc-input-icon scc-input">
															<span class="input-group-text"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
															<input type="number" onchange="changeValue2(this)" onkeyup="changeValue2(this)" class="input_pad inputoption_2" style="width:100%;text-align:center;height:35px;" placeholder="Price" value="<?php echo floatval( $el->price ); ?>">
														</div>
													</div>
													</div>
													</div>
													<div class="scc-element-content" value="selectoption" style="display:none; height:auto">
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
																<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional ">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															 <div class="scc-content" style="display: none;">
																<div class="scc-transition">
																	<?php
																	foreach ( $conditionsBySet as $key => $conditionCollection ) :
																		?>
																		<?php if ( $key > 1 ) : ?>
																			<div style="margin: 10px 0px 10px -10px;">OR</div>
																		<?php endif; ?>
																		<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
																			<?php
																			foreach ( $conditionCollection as $index => $condition ) {
																				if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
																					?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="eq" 
																										<?php
																										if ( $condition->op == 'eq' ) {
																											echo 'selected';
																										}
																										?>
																										>Equal To</option>
																										<option value="ne" 
																										<?php
																										if ( $condition->op == 'ne' ) {
																											echo 'selected';
																										}
																										?>
																										>Not Equal To</option>
																										<option value="any" 
																										<?php
																										if ( $condition->op == 'any' ) {
																											echo 'selected';
																										}
																										?>
																										>Any</option>
																									</select>
																									<select class="third-conditional-step col-3" style="height: 35px;
																									<?php
																									if ( $condition->op == 'any' ) {
																										echo 'display:none';
																									}
																									?>
																									 ">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="chec" 
																										<?php
																										if ( $condition->op == 'chec' ) {
																											echo 'selected';
																										}
																										?>
																										>Checked</option>
																										<option value="unc" 
																										<?php
																										if ( $condition->op == 'unc' ) {
																											echo 'selected';
																										}
																										?>
																										>Unchecked</option>
																									</select>
																									<select class="third-conditional-step col-3" style="height: 35px;display:none">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																							<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																								<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																							</div>
																							<div class="col-xs-11 col-md-11" style="padding:0px;">
																								<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																									<div class="item_conditionals">
																										<select class="first-conditional-step col-3" style="height: 35px;">
																											<option style="font-size: 10px" value="0">Select one</option>
																											<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																										</select>
																										<select class="second-conditional-step col-3" style="height: 35px;">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="eq" 
																											<?php
																											if ( $condition->op == 'eq' ) {
																												echo 'selected';
																											}
																											?>
																											>Equal To</option>
																											<option value="ne" 
																											<?php
																											if ( $condition->op == 'ne' ) {
																												echo 'selected';
																											}
																											?>
																											>Not Equal To</option>
																											<option value="gr" 
																											<?php
																											if ( $condition->op == 'gr' ) {
																												echo 'selected';
																											}
																											?>
																											>Greater than</option>
																											<option value="les" 
																											<?php
																											if ( $condition->op == 'les' ) {
																												echo 'selected';
																											}
																											?>
																											>Less than</option>
																										</select>
																										<select class="third-conditional-step col-3" style="height: 35px;display:none">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																										</select>
																										<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
																										<div class="btn-group" style="margin-left: 10px;">
																											<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																			<div class="row col-xs-12 col-md-12 conditional-selection  
																			<?php
																			if ( count( $conditionCollection ) ) {
																				echo 'hidden';
																			}
																			?>
																			" style="padding: 0px; margin-bottom: 5px;">
																				<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																					<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
																				</div>
																				<div class="col-xs-11 col-md-11" style="padding:0px;">
																					<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																						<div class="item_conditionals">
																							<select class="first-conditional-step col-3" style="height: 35px;">
																								<option style="font-size: 10px" value="0">Select an element</option>
																							</select>
																							<select class="second-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<select class="third-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																							<div class="btn-group" style="margin-left: 10px;display:none">
																								<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																	<?php endforeach; ?>
																	<div style="width: 28%">
																		<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
																	</div>
																</div>
															 </div>
														</div>
													</div>
													</div>
													<!-- ADVANCE -->
												</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}
	public function renderSliderSetupBody2( $el, $conditionsBySet ) {
		if ( $this->is_from_ajax ) {
			$el->value1 = 'default';
		}
		$defaults = array(
			'orden'              => 0,
			'titleElement'       => 'Title',
			'type'               => '',
			'subsection_id'      => 0,
			'value1'             => 'default',
			'value4'             => null,
			'value2'             => '',
			'mandatory'          => 0,
			'showPriceHint'      => 0,
			'titleColumnDesktop' => '4',
			'titleColumnMobile'  => '12',
			'displayFrontend'    => 1,
			'displayDetailList'  => 1,
			'showTitlePdf'       => 0,
			'elementitems'       => array(
				(object) array(
					'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
					'element_id'            => '0',
					'opt_default'           => '0',
					'woocomerce_product_id' => null,
					'uniqueId'              => '8SKrlo73vP',
					'value4'                => '',
					'description'           => '',
					'value1'                => '1',
					'value3'                => '2',
					'value2'                => '10',
					'price'                 => '',
				),
			),
			'conditions'         => array(),
			'showInputBoxSlider' => 00,

		);
		$el                 = (object) wp_parse_args( $el, $defaults );
		$slider_price_title = '';
		switch ( $el->value1 ) {
			case 'default':
				$slider_price_title = 'Price Per Unit';
				break;
			case 'bulk':
				$slider_price_title = 'Price Per Unit';
				break;
			case 'sliding':
				$slider_price_title = 'Price For Range';
				break;
			case 'quantity_mod':
				$slider_price_title = '(not used for this mode)';
				break;
			default:
				# code...
				break;
		}
		ob_start();
		?>
		<div class="scc-element-content" data-element-setup-type="slider" value="selectoption" style="
		<?php
		if ( ! $this->is_from_ajax ) {
			echo 'display:none;';}
		?>
		 height:auto;">
			<div class="slider-setup-body">
													<!-- CONTENIDO DE CADA ELEMENTO -->
													<!-- ELEMENT -->
													<label class="form-label fw-bold">Title</label>
													<div class="input-group mb-3">
														<input data-element-title type="text" class="form-control" onkeyup="clickedTitleElement(this)" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>">
													</div>
													<div class="col-12 mb-3 edit-field">
														<label class="form-label fw-bold">Pricing Structure</label>
														<select data-pricing-structure class="form-select w-100 pricing-structure-dd" onchange="changeValue1(this)">
															<option value="default" <?php selected( $el->value1, 'default' ); ?>>Default Pricing</option>
															<option value="bulk" <?php selected( $el->value1, 'bulk' ); ?>>Bulk Discount</option>
															<option value="sliding" <?php selected( $el->value1, 'sliding' ); ?>>Sliding Scale</option>
															<option value="quantity_mod" <?php selected( $el->value1, 'quantity_mod' ); ?>>Element Quantity Modifier</option>
														</select>
														<i class="material-icons-outlined v-align-middle" data-element-tooltip-type="slider-type-<?php echo esc_attr( $el->value1 ); ?>">help_outline</i>
													</div>
													<div class="col-12 mb-3 edit-field" data-edit-field-type="wc_choices">
													</div>
													<div class="row g-3 price-slider-item-header ">
														<div class="col">
															<label class="form-label fw-bold">From</label>
														</div>
														<div class="col">
															<label class="form-label fw-bold">To</label>
														</div>
														<div class="col">
															<label class="form-label fw-bold"><?php echo $slider_price_title; ?></label>
														</div>
													</div>
														<?php
														foreach ( $el->elementitems as $key => $item ) {
															$hide_range = false;
															if ( in_array( $el->value1, array( 'default', 'quantity_mod' ) ) && $key > 0 ) {
																$hide_range = true;
															}
															?>
															<div data-slider-range-setup class="row g-3 price-slider-item 
															<?php
															if ( $hide_range ) {
																echo 'd-none';}
															?>
																">
																<input data-range-id="<?php echo intval( $item->id ); ?>" type="text" class="id_element_slider_item" value="<?php echo intval( $item->id ); ?>" hidden>
																<div class="col">
																	<input class="form-control scc-input" 
																	<?php
																	if ( $key > 0 ) {
																		echo 'disabled';}
																	?>
																	type="number" min="0" value="<?php echo esc_attr( $item->value1 ); ?>">
																</div>
																<div class="col">
																	<input class="form-control scc-input" value="<?php echo esc_attr( $item->value2 ); ?>" type="number" min="1">
																</div>
																<div class="col d-inline-flex scc-input-icon">
																	<span class="input-group-text" style="height: fit-content;"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
																	<input class="form-control" type="number" min="0" 
																	<?php
																	if ( $key === 0 && $el->value1 == 'quantity_mod' ) {
																		echo 'disabled';}
																	?>
																	 value="<?php echo esc_attr( $item->value3 ); ?>">
																</div>
																<i onclick="deleteSliderItem(this)" class="material-icons-outlined range-close-btn" 
																<?php
																if ( $key === 0 ) {
																	echo 'disabled style="opacity: 0"';}
																?>
																>close</i>
															</div>
														<?php } ?>
													<div class="text-start 
													<?php
													if ( in_array( $el->value1, array( 'quantity_mod', 'default' ) ) || empty( $el->value1 ) ) {
														echo 'd-none';}
													?>
													">
														<p href="#" onclick="addNewRangeSlider(this)" class="link-primary text-decoration-none" role="button">+ Price Range</p>
													</div>
													</div>
													<!-- ADVANCE -->
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
															<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional ">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															<div class="scc-content" style="display: none;">
																<div class="scc-transition">
																	<?php
																	foreach ( $conditionsBySet as $key => $conditionCollection ) :
																		?>
																		<?php if ( $key > 1 ) : ?>
																			<div style="margin: 10px 0px 10px -10px;">OR</div>
																		<?php endif; ?>
																		<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
																		   <?php
																			foreach ( $conditionCollection as $index => $condition ) {
																				if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
																					?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select  class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																									</select>
																									<select  class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="eq" 
																										<?php
																										if ( $condition->op == 'eq' ) {
																											echo 'selected';}
																										?>
																										>Equal To</option>
																										<option value="ne" 
																										<?php
																										if ( $condition->op == 'ne' ) {
																											echo 'selected';}
																										?>
																										>Not Equal To</option>
																										<option value="any" 
																										<?php
																										if ( $condition->op == 'any' ) {
																											echo 'selected';}
																										?>
																										>Any</option>
																									</select>
																									<select  class="third-conditional-step col-3" style="height: 35px;
																									<?php
																									if ( $condition->op == 'any' ) {
																										echo 'display:none';}
																									?>
																									 ">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="chec" 
																										<?php
																										if ( $condition->op == 'chec' ) {
																											echo 'selected';}
																										?>
																										>Checked</option>
																										<option value="unc" 
																										<?php
																										if ( $condition->op == 'unc' ) {
																											echo 'selected';}
																										?>
																										>Unchecked</option>
																									</select>
																									<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																							<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																								<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																							</div>
																							<div class="col-xs-11 col-md-11" style="padding:0px;">
																								<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																									<div class="item_conditionals">
																										<select class="first-conditional-step col-3" style="height: 35px;">
																											<option style="font-size: 10px" value="0">Select one</option>
																											<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																										</select>
																										<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																											<option value="eq" 
																											<?php
																											if ( $condition->op == 'eq' ) {
																												echo 'selected';}
																											?>
																											>Equal To</option>
																											<option value="ne" 
																											<?php
																											if ( $condition->op == 'ne' ) {
																												echo 'selected';}
																											?>
																											>Not Equal To</option>
																											<option value="gr" 
																											<?php
																											if ( $condition->op == 'gr' ) {
																												echo 'selected';}
																											?>
																											>Greater than</option>
																											<option value="les" 
																											<?php
																											if ( $condition->op == 'les' ) {
																												echo 'selected';}
																											?>
																											>Less than</option>
																										</select>
																										<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																										</select>
																										<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
																										<div class="btn-group" style="margin-left: 10px;">
																											<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
																			<div class="row col-xs-12 col-md-12 conditional-selection  
																			<?php
																			if ( count( $conditionCollection ) ) {
																				echo 'hidden';}
																			?>
																			" style="padding: 0px; margin-bottom: 5px;">
																				<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																					<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
																				</div>
																				<div class="col-xs-11 col-md-11" style="padding:0px;">
																					<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																						<div class="item_conditionals">
																							<select class="first-conditional-step col-3" style="height: 35px;">
																								<option style="font-size: 10px" value="0">Select an element</option>
																							</select>
																							<select class="second-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<select class="third-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																							<div class="btn-group" style="margin-left: 10px;display:none">
																								<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																								<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
																			<?php
																			if ( empty( count( $el->conditions ) ) ) {
																				echo 'hidden';}
																			?>
																			">+ AND</button>
																		</div>
																	<?php endforeach; ?>
																	<div style="width: 28%">
																		<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}

	public function renderDropdownSetupBody( $el, $conditionsBySet, $woo_commerce_products = null ) {
		$defaults = array(
			'orden'              => 0,
			'titleElement'       => 'Title',
			'type'               => '',
			'subsection_id'      => 0,
			'value1'             => 'default',
			'value4'             => null,
			'value2'             => '',
			'mandatory'          => 0,
			'showPriceHint'      => 0,
			'titleColumnDesktop' => '4',
			'titleColumnMobile'  => '12',
			'displayFrontend'    => 1,
			'displayDetailList'  => 1,
			'showTitlePdf'       => 0,
			'elementitems'       => array(
				(object) array(
					'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
					'order'                 => '0',
					'name'                  => 'Name',
					'price'                 => '10',
					'description'           => 'Description',
					'value1'                => '',
					'value2'                => '',
					'value3'                => '',
					'value4'                => '',
					'uniqueId'              => 'ozkYrfNw9j',
					'woocomerce_product_id' => '',
					'opt_default'           => '0',
				),
			),
			'conditions'         => array(),
		);
		$el       = (object) wp_parse_args( $el, $defaults );
		ob_start();
		?>
		<div class="scc-element-content dropdown-content" value="selectoption" style="
		<?php
		if ( ! $this->is_from_ajax ) {
			echo 'display:none;';}
		?>
			 height:auto">
			<!-- CONTENIDO DE CADA ELEMENTO -->
			<!-- ELEMENT -->
			<div class="slider-setup-body">
			<label class="form-label fw-bold">Title</label>
			<div class="input-group mb-3"><input type="text" onkeyup="clickedTitleElement(this)" style="height:35px;width:100%;" placeholder="Title" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>"></div>
			<!-- Dropdown Menu Element - ELEMENTS INSIDE ELEMENTS -->
			<div class="col-titles">
				<div class="row g-3 dd-item-edit-field">
				<div class="col-md-7">
					<label class="form-label fw-bold">Items</label>
				</div>
				<div class="col-md-2">
					<label class="form-label fw-bold">Unit Price</label>
				</div>
				</div>
			</div>
			<div class="selectoption_2 col-xs-12 col-md-12">
				<?php foreach ( $el->elementitems as $key => $elit ) { ?>
					<?php echo true ? $this->element_setup_part_dropdown_item_beta( $key, $elit ) : $this->element_setup_part_dropdown_item( $key, $elit ); ?>
					<?php
				}
				?>
			</div>
			<a onclick="addOptiontoSelect(this)" class="crossnadd" style="margin-top:5px;margin-bottom:20px;">+ Item </a>
				</div>
		</div>
		<div class="scc-element-content" value="selectoption" style="
		<?php
		if ( ! $this->is_from_ajax ) {
			echo 'display:none;';
		}
		?>
		 height:auto;">            <div class="scc-new-accordion-container">
				<div class="styled-accordion">
					<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
						<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
					</div>
					<?php echo $this->renderAdvancedOptions( $el ); ?>
				</div>
				<div class="styled-accordion">
				<div class="scc-title scc_accordion_conditional "  >
						<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
					</div>
					<div class="scc-content" style="display: none;">
						<div class="scc-transition">
							<?php
							// echo json_encode($conditionsBySet);
							foreach ( $conditionsBySet as $key => $conditionCollection ) :
								?>
								<?php if ( $key > 1 ) : ?>
									<div style="margin: 10px 0px 10px -10px;">OR</div>
								<?php endif; ?>
								<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
								<?php
								foreach ( $conditionCollection as $index => $condition ) {
									if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
										?>
											<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
												<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
												<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
													<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
												</div>
												<div class="col-xs-11 col-md-11" style="padding:0px;">
													<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
														<div class="item_conditionals">
															<select  class="first-conditional-step col-3" style="height: 35px;">
																<option style="font-size: 10px" value="0">Select one</option>
																<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
															</select>
															<select  class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="eq" 
																<?php
																if ( $condition->op == 'eq' ) {
																	echo 'selected';}
																?>
																	>Equal To</option>
																<option value="ne" 
																<?php
																if ( $condition->op == 'ne' ) {
																	echo 'selected';}
																?>
																	>Not Equal To</option>
																<option value="any" 
																<?php
																if ( $condition->op == 'any' ) {
																	echo 'selected';}
																?>
																	>Any</option>
															</select>
															<select  class="third-conditional-step col-3" style="height: 35px;
															<?php
															if ( $condition->op == 'any' ) {
																echo 'display:none';}
															?>
																 ">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
															<div class="btn-group" style="margin-left: 10px;">
																<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
												<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
													<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
												</div>
												<div class="col-xs-11 col-md-11" style="padding:0px;">
													<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
														<div class="item_conditionals">
															<select class="first-conditional-step col-3" style="height: 35px;">
																<option style="font-size: 10px" value="0">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<select class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="chec" 
																<?php
																if ( $condition->op == 'chec' ) {
																	echo 'selected';}
																?>
																	>Checked</option>
																<option value="unc" 
																<?php
																if ( $condition->op == 'unc' ) {
																	echo 'selected';}
																?>
																	>Unchecked</option>
															</select>
															<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
															<div class="btn-group" style="margin-left: 10px;">
																<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
													<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
														<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
													</div>
													<div class="col-xs-11 col-md-11" style="padding:0px;">
														<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
															<div class="item_conditionals">
																<select class="first-conditional-step col-3" style="height: 35px;">
																	<option style="font-size: 10px" value="0">Select one</option>
																	<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																</select>
																<select class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																	<option value="eq" 
																	<?php
																	if ( $condition->op == 'eq' ) {
																		echo 'selected';}
																	?>
																		>Equal To</option>
																	<option value="ne" 
																	<?php
																	if ( $condition->op == 'ne' ) {
																		echo 'selected';}
																	?>
																		>Not Equal To</option>
																	<option value="gr" 
																	<?php
																	if ( $condition->op == 'gr' ) {
																		echo 'selected';}
																	?>
																		>Greater than</option>
																	<option value="les" 
																	<?php
																	if ( $condition->op == 'les' ) {
																		echo 'selected';}
																	?>
																		>Less than</option>
																</select>
																<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																	<option value="0" style="font-size: 10px">Select one</option>
																	<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																</select>
																<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
																<div class="btn-group" style="margin-left: 10px;">
																	<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
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
									<div class="row col-xs-12 col-md-12 conditional-selection  
									<?php
									if ( count( $conditionCollection ) ) {
										echo 'hidden';}
									?>
										" style="padding: 0px; margin-bottom: 5px;">
										<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
											<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
										</div>
										<div class="col-xs-11 col-md-11" style="padding:0px;">
											<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
												<div class="item_conditionals">
													<select class="first-conditional-step col-3" style="height: 35px;">
														<option style="font-size: 10px" value="0">Select an element</option>
													</select>
													<select class="second-conditional-step col-3" style="height: 35px;display:none">
														<option value="0" style="font-size: 10px">Select one</option>
													</select>
													<select class="third-conditional-step col-3" style="height: 35px;display:none">
														<option value="0" style="font-size: 10px">Select one</option>
													</select>
													<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
													<div class="btn-group" style="margin-left: 10px;display:none">
														<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
														<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
									<?php
									if ( empty( count( $el->conditions ) ) ) {
										echo 'hidden';}
									?>
										">+ AND</button>
								</div>
							<?php endforeach; ?>
							<div style="width: 28%">
								<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		$html = ob_get_clean();
		return $html;
	}

	public function element_setup_part_dropdown_item( $key, $elit ) {
		$defaults = array(
			'id'                    => isset( $elit->elementItem_id ) ? $elit->elementItem_id : null,
			'order'                 => '0',
			'name'                  => 'Name',
			'price'                 => '10',
			'description'           => 'Description',
			'value1'                => '',
			'value2'                => '',
			'value3'                => '',
			'value4'                => '',
			'uniqueId'              => 'ozkYrfNw9j',
			'woocomerce_product_id' => '',
			'opt_default'           => '0',
		);
		$elit     = (object) wp_parse_args( $elit, $defaults );
		if ( $this->is_from_ajax ) {
			$elit = (object) $elit;
			ob_start();
		}
		?>
		<div class="row m-0 selopt3 col-md-12 col-xs-12" style="margin-top:10px;padding:0px">
			<div class="row p-0 m-0 col-md-11 col-xs-11">
				<input class="swichoptionitem_id" type="text" value="<?php echo intval( $elit->id ); ?>" hidden>
				<div class="col-xs-2 col-md-2 tooltipadmin-left 
				<?php
				if ( $elit->opt_default == '1' ) {
					echo 'is-set-default';}
				?>
					" onclick="setDefaultOption(this)" id="dropdownOpt" <?php echo ( $elit->opt_default == '1' ) ? ' data-selected="true"' : 'style="padding:0px;height:35px;" data-tooltip="Click to make this option default."'; ?>>
					<label class="" style="float: none;margin-top:10px;font-size:14px;font-weight: normal;"><?php echo intval( $key ) + 1; ?></label>
				</div>
				<div class="col-md-6 col-xs-6" style="padding: 0px 5px 0px 1px;">
				<input type="text" onkeyup="changeNameElementItem(this)" class="input_pad inputoption scc-input" style="width:100%;" value="<?php echo esc_html( $elit->name ); ?>" placeholder="Product or service name">
				</div>
				<div class="col-md-2 col-xs-2 d-flex scc-input-icon" style="padding:0px">
					<span class="input-group-text"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
					<input type="number" onchange="changePriceElementItem(this)" onkeyup="changePriceElementItem(this)" class="input_pad inputoption_2" style="width:100%;text-align:center;" placeholder="Price" value="<?php echo floatval( $elit->price ); ?>">
				</div>
				<div class="col-md-2 col-xs-0" style="padding:0px"></div>
				<span class="col-xs-6 col-md-2">
					<img class="scc-image-picker" style="height: 80px;width:80px" onclick="choseImageElementItem(this)" src="<?php echo empty( $elit->value1 ) ? esc_url( SCC_ASSETS_URL . '/images/image.png' ) : esc_url( $elit->value1 ); ?> " title="Pick an image. Please choose an image with a 1:1 aspect ratio for best results.">
					<span class="scc-dropdown-image-remove" onclick="removeDropdownImage(this)">x</span>
				</span>
				<div class="col-md-8 col-xs-8" style="padding:0px; padding-right:5px; margin-top:5px;">
				<textarea onkeyup="changeDescriptionElementItem(this)" class="input_pad inputoption_desc" style="width:100%;height:75px;" placeholder="Description"><?php echo esc_html( $elit->description ); ?></textarea>
				</div>
				<div class="col-md-1 col-xs-1" style="padding:0px;"></div>
			</div>
			<div class="col-md-1 col-xs-1" style="padding-left: 0;">
				<button onclick="removeSwitchOptionDropdown(this)" class="deleteBackendElmnt"><i class="fa fa-trash"></i></button>
			</div>
		</div>
		<?php
		if ( $this->is_from_ajax ) {
			$html = ob_get_clean();
			return $html;
		}
	}
	public function element_setup_part_dropdown_item_beta( $key, $elit ) {
		$defaults = array(
			'id'                    => isset( $elit->elementItem_id ) ? $elit->elementItem_id : null,
			'order'                 => '0',
			'name'                  => 'Name',
			'price'                 => '10',
			'description'           => 'Description',
			'value1'                => '',
			'value2'                => '',
			'value3'                => '',
			'value4'                => '',
			'uniqueId'              => 'ozkYrfNw9j',
			'woocomerce_product_id' => '',
			'opt_default'           => '0',
		);
		$elit     = (object) wp_parse_args( $elit, $defaults );
		if ( $this->is_from_ajax ) {
			$elit = (object) $elit;
			ob_start();
		}
		?>
		<div class="dd-item-field-container" data-element-item-id="<?php echo intval( $elit->id ); ?>">
			<div class="row g-3 dd-item-edit-field">
				<div class="col-md-1">
					<label class="scc-accordion_switch_button tool-premium">
					<input type="checkbox" class="dd-item-def-checkbox" onchange="setDefaultOption(this,false,true)" disabled>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
				</div>
				<div class="col-md-6">
					<input type="text" class="form-control scc-input" onkeyup="changeNameElementItem(this, true)" value="<?php echo stripslashes( wp_kses( $elit->name, SCC_ALLOWTAGS ) ); ?>">
				</div>
				<div class="col-md-2 d-inline-flex scc-input-icon">
					<span class="input-group-text" style="height: fit-content;"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
					<input type="number" onchange="changePriceElementItem(this, true)" class="form-control" value="<?php echo floatval( $elit->price ); ?>">
				</div>
				<i onclick="removeSwitchOptionDropdown(this, true)" class="material-icons-outlined range-close-btn">close</i>
			</div>
			<div class="row g-3">
				<span class="col-xs-0 col-md-2 image_container">
					<img class="scc-image-picker" style="height: 80px;width:80px" onclick="choseImageElementItem(this)" src="<?php echo ( $elit->value1 == null || $elit->value1 == '0' ) ? SCC_ASSETS_URL . '/images/image.png' : $elit->value1; ?>" title="Pick an image. Please choose an image with a 1:1 aspect ratio for best results.">
					<span class="scc-dropdown-image-remove" style="" onclick="removeDropdownImage(this, true)">x</span>
				</span>
				<div class="col-md-10 col-xs-6">
					<textarea onkeyup="changeDescriptionElementItem(this, true)" class="input_pad inputoption_desc" style="width:100%;height:75px;" placeholder="Description"><?php echo stripslashes( wp_kses( $elit->description, SCC_ALLOWTAGS ) ); ?></textarea>
				</div>
			</div>
			<div class="col-12 mb-3 edit-field" data-edit-field-type="wc_choices" style="width: 105%;">
														<?php if ( ! empty( $this->woo_commerce_products ) ) : ?>
															<label class="form-label fw-bold"><img class="scc-woo-logo" src="<?php echo esc_url_raw( SCC_ASSETS_URL . '/images/logo-woocommerce.svg' ); ?>" title="Pick an item from your WooCommerce products to link to."></label>
															<!--WooCommerce for Slider Element-->
															<select class="form-select w-100" data-target="elements_added" onchange="attachProductId(this, null, null, true)">
																<option style="font-size: 10px" value=0>Select a product..</option>
																<?php
																foreach ( $this->woo_commerce_products as $product ) {
																	?>
																	<?php
																	if ( $product->is_type( 'variable' ) ) {
																		$available_variations = $product->get_available_variations();
																		foreach ( $available_variations as $product_variable ) {
																			$attributes = array();
																			foreach ( $product_variable['attributes'] as $key => $value ) {
																				$attributes[] = $product->get_name() . ': ' . $value;
																			}
																			?>
																			<option value=<?php echo esc_html( $product_variable['variation_id'] ); ?> <?php echo selected( $product->get_id() == intval( $elit->woocomerce_product_id ) ); ?>><?php echo esc_html( implode( ' | ', $attributes ) ) . ' | Price: ' . get_woocommerce_currency_symbol() . '' . esc_html( $product_variable['display_regular_price'] ); ?></option>
																			<?php
																		}
																	} else {
																		?>
																		<option value=<?php echo esc_html( $product->get_id() ); ?> <?php echo selected( $product->get_id() == intval( $elit->woocomerce_product_id ) ); ?>><?php echo esc_html( $product->get_name() ) . ' | Price: ' . get_woocommerce_currency_symbol() . '' . esc_html( $product->get_price() ); ?></option>
																		<?php
																	}
																}
																?>
															</select>
															<i class="material-icons-outlined v-align-middle">info</i>
														<?php endif; ?>
													</div>
		</div>
		<?php
		if ( $this->is_from_ajax ) {
			$html = ob_get_clean();
			return $html;
		}
	}

	public function renderElementLoader() {
		ob_start();?>
			<span class="scc-saving-element-msg scc-visibility-hidden"></span>
		<?php
		$html = ob_get_clean();
		return $html;
	}
}

