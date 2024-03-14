<div class="bookit_addons">
	<div class="container">
		<div class="addon-header">
			<div class="bookit-icon"></div>
			<div class="title">
				<?php echo esc_html( $translations['addons_title'] ); ?>
			</div>
			<div class="description">

				<?php echo esc_html( $translations['addons_description'] ); ?>
			</div>
		</div>
		<div class="addon-content">
			<div class="pricing">
				<div class="left annual active"><?php esc_html_e( 'Annual', 'bookit' ); ?></div>
				<div class="switch">
					<input type="checkbox" id="addonPriceType">
					<label for="addonPriceType"></label>
				</div>
				<div class="right lifetime"><?php esc_html_e( 'Lifetime', 'bookit' ); ?></div>
			</div>
			<div class="addon-list">

				<?php foreach ( $freemius_info as $addon => $data ) : ?>
					<div class="addon <?php echo esc_attr( $addon ); ?>">
						<?php
						$activePlan = current(
							array_filter(
								$data['plan'],
								function( $p ) {
									return true == $p->active;
								}
							)
						);
						$activePlan = $activePlan ? clone( $activePlan ) : $activePlan;
						?>
						<?php if ( 'bookit-all-addons' == $addon ) : ?>
						<span class="popular">
							<i class="popular-icon"></i>
							<?php echo esc_html( $translations['popular'] ); ?>
						</span>
						<?php endif; ?>

						<div class="icons">
							<i class="icon"></i>
						</div>
						<h2 class="title"><?php echo esc_html( $data['title'] ); ?></h2>
						<div class="price <?php echo esc_html( $addon ); ?>" >

							<?php if ( $activePlan ) : ?>
								<?php
									$existPlans = array(
										'lifetime' => array(),
										'annual'   => array(),
									);
									$price = ( $activePlan->is_lifetime ) ? $activePlan->lifetime_price : $activePlan->annual_price;
									if ( false == $activePlan->is_lifetime ) :
										$existPlans['lifetime'] = $data['plan'];
										$existPlans['annual']   = array_map(
											function ( $plan ) use ( $activePlan ) {
												if ( (int) $plan->licenses >= (int) $activePlan->licenses ) {
													return $plan;
												} else {
													return;
												}
											},
											$data['plan']
										);
									endif;
									if ( true == $activePlan->is_lifetime ) :
										$existPlans['annual']   = array();
										$existPlans['lifetime'] = array_map(
											function ( $plan ) use ( $activePlan ) {
												if ( (int) $plan->licenses >= $activePlan->licenses ) {
													return $plan;
												} else {
													return;
												}
											},
											$data['plan']
										);
									endif;
									?>
									<?php foreach ( $data['plan'] as $plan ) : ?>
										<?php
										$pCls       = 'hidden';
										$pDataPrice = $activePlan->annual_price;
										if ( $plan->licenses == $activePlan->licenses && $plan->is_lifetime == $activePlan->is_lifetime ) {
											$pCls = 'active active-plan current-plan';
										}
										if ( $activePlan->is_lifetime ) {
											$pDataPrice = $activePlan->lifetime_price;
										}
										?>
									<p class="<?php echo esc_attr( $plan->licenses ); ?> <?php echo esc_attr( $pCls ); ?>"
										data-lifetime="<?php echo esc_attr( $activePlan->is_lifetime ); ?>"
										data-licenses="<?php echo esc_attr( $activePlan->licenses ); ?>"
										data-price="<?php echo esc_attr( $pDataPrice ); ?>"
										data-license-text="<?php echo esc_attr( $activePlan->data['text'] ); ?>">
										<span class="plan-price"
											data-annual="<?php echo esc_attr( $plan->annual_price ); ?>"
											data-lifetime="<?php echo esc_attr( $plan->lifetime_price ); ?>"
											data-url="<?php echo esc_attr( $plan->url ); ?>">
											$<?php echo esc_html( $price ); ?>
										</span>
										<?php if ( ! $activePlan->is_lifetime ) : ?>
										<span class="plan-period">
											<?php esc_html_e( '/per year', 'bookit' ); ?>
										</span>
										<?php endif; ?>
									</p>
								<?php endforeach; ?>
							<?php endif; ?>

							<?php if ( isset( $data['plan'] ) && ! $activePlan ) : ?>
								<?php foreach ( $data['plan'] as $plan ) : ?>
									<?php
									$pCls = 'hidden';
									if ( 1 == $plan->licenses ) {
										$pCls = 'active';
									}
									?>
									<p class="<?php echo esc_attr( $plan->licenses ); ?> <?php echo esc_attr( $pCls ); ?>">
										<span class="plan-price"
											data-annual="<?php echo esc_attr( $plan->annual_price ); ?>"
											data-lifetime="<?php echo esc_attr( $plan->lifetime_price ); ?>"
											data-url="<?php echo esc_attr( $plan->url ); ?>">
											$<?php echo esc_html( $plan->annual_price ); ?>
										</span>
										<span class="plan-period">
											<?php esc_html_e( '/per year', 'bookit' ); ?>
										</span>
									</p>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<p class="info">
							<?php echo esc_html( $descriptions[ $addon ] ); ?>
						</p>
						<?php if ( ! empty( $data['latest'] ) ) : ?>
							<div class="developer-info">
								<span class="version-label">
									<?php echo esc_html( $translations['version'] ); ?>
								</span>
								<span>
									<?php echo esc_html( $data['latest']['version'] ); ?>
									<a href="https://docs.stylemixthemes.com/bookit-calendar/changelog/" target="_blank">
										<?php echo esc_html( $translations['view_changelog'] ); ?>
									</a>
								</span>
							</div>
						<?php endif; ?>

						<?php $firstPlan = reset( $data['plan'] ); ?>
						<?php if ( $activePlan && 25 == $activePlan->licenses && $activePlan->is_lifetime ) : ?>
							<div class="exist-license">
								<span><?php echo esc_html( sprintf( $translations['active_license'], $activePlan->data['text'] ) ); ?></span>
							</div>
						<?php elseif ( $activePlan ) : ?>
							<div class="exist-license">
								<span><?php echo esc_html( sprintf( $translations['license_purchased'], $activePlan->data['text'], ( $activePlan->is_lifetime ) ? $translations['lifetime'] : '' ) ); ?></span>
							</div>
						<?php endif; ?>

						<?php $customSelectCls = $addon; ?>
						<?php if ( $activePlan && 25 == $activePlan->licenses && $activePlan->is_lifetime ) : ?>
							<div class="action">
								<button class="active-addon"><?php echo esc_html( $translations['active'] ); ?></button>
							</div>
						<?php elseif ( $activePlan ) : ?>

							<div class="action">
								<?php
								if ( $activePlan->is_lifetime || ( ! $activePlan->is_lifetime && 25 == $activePlan->licenses ) ) :
									$customSelectCls .= ' active-annual hidden';
									?>
									<button class="active-addon active-annual-btn"><?php echo esc_html( $translations['active'] ); ?></button>
								<?php endif; ?>

								<div class="custom-select <?php echo esc_attr( $customSelectCls ); ?>">
									<div class="value" data-value="<?php echo esc_attr( $activePlan->licenses ); ?>">
										<?php echo esc_html( $activePlan->data['text'] ); ?>
									</div>
									<div class="custom-options">
										<?php foreach ( $existPlans['annual'] as $plan ) : ?>
											<?php if ( null !== $plan ) : ?>
											<span class="annual custom-option <?php echo ( $activePlan && $activePlan->id == $plan->id && ! $activePlan->is_lifetime ) ? 'disable' : ''; ?>" data-value="<?php echo esc_attr( $plan->licenses ); ?>">
												<?php echo esc_html( $plan->data['text'] ); ?>
											</span>
											<?php endif; ?>
										<?php endforeach; ?>

										<?php foreach ( $existPlans['lifetime'] as $plan ) : ?>
											<?php if ( null !== $plan ) : ?>
												<?php
												$spanCls = '';
												if ( $activePlan && $activePlan->id == $plan->id && $activePlan->is_lifetime ) {
													$spanCls = 'disable';
												}
												?>
												<span class="lifetime hidden custom-option <?php echo esc_attr( $spanCls ); ?>" data-value="<?php echo esc_attr( $plan->licenses ); ?>">
												<?php echo esc_html( $plan->data['text'] ); ?>
												</span>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
								</div>
								<a data-license-url="<?php echo esc_url( $activePlan->url ); ?>" target="_blank" class="buy <?php echo esc_attr( $addon . ' ' . $customSelectCls ); ?>" href="<?php echo esc_url( $activePlan->url ); ?>">
									<?php
									if ( false == $activePlan ) {
										echo esc_html( $translations['buy'] );
									} else {
										echo esc_html( $translations['upgrade'] );
									}
									?>
								</a>
							</div>
						<?php else : ?>
							<div class="action">
								<div class="custom-select">
									<div class="value" data-value="<?php echo esc_attr( $firstPlan->licenses ); ?>">
										<?php echo esc_html( $firstPlan->data['text'] ); ?>
									</div>
									<div class="custom-options">
										<?php foreach ( $data['plan'] as $plan ) : ?>
											<?php
											$spanCls = '';
											if ( $activePlan && $activePlan->id == $plan->id ) {
												$spanCls = 'disable';
											}
											?>
											<span class="custom-option <?php echo esc_attr( $spanCls ); ?>" data-value="<?php echo esc_attr( $plan->licenses ); ?>">
											<?php echo esc_html( $plan->data['text'] ); ?>
											</span>
										<?php endforeach; ?>
									</div>
								</div>
								<a target="_blank" class="buy <?php echo esc_attr( $customSelectCls ); ?>" href="<?php echo esc_url( $firstPlan->url ); ?>">
									<?php
									if ( false == $activePlan ) {
										echo esc_html( $translations['buy'] );
									} else {
										echo esc_html( $translations['upgrade'] );
									}
									?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="addon-footer">
			<p class="terms_content">
				<?php echo esc_html__( 'You get', 'bookit' ); ?>
				<a href="<?php echo esc_url( 'https://stylemixthemes.com/subscription-policy/' ); ?>">
					<span class="<?php echo esc_attr( 'stm_terms_content_support' ); ?>" data-support-lifetime="<?php echo esc_attr__( 'Lifetime', 'bookit' ); ?>" data-support-annual="<?php echo esc_attr__( '1 year', 'bookit' ); ?>">
						<?php echo esc_html__( '1 year', 'bookit' ); ?>
					</span>
					<?php echo esc_html__( ' updates and support ', 'bookit' ); ?>
				</a>
				<?php echo esc_html__( 'from the date of purchase. We offer 30 days Money Back Guarantee based on', 'bookit' ); ?>
				<a href="<?php echo esc_url( 'https://stylemixthemes.com/subscription-policy/' ); ?>">
					<?php echo esc_html__( 'Refund Policy', 'bookit' ); ?>
				</a>
			</p>
		</div>
	</div>
</div>

<script>
jQuery(document).ready(function ( $) {
	function replaceUrlParam( url, paramName, paramValue ) {
		if (paramValue == null) {
			paramValue = '';
		}
		var pattern = new RegExp('\\b('+paramName+'=).*?(&|#|$)');
		if (url.search(pattern)>=0) {
			return url.replace(pattern,'$1' + paramValue + '$2');
		}
		url = url.replace(/[?#]$/,'');
		return url + (url.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue;
	}

	function hasParentClass( child, classList ) {
		for (var i = 0; i < classList.length; i ++ ) {
			if(child.className.split(' ').indexOf(classList[i]) >= 0) return true;
		}
		//Throws TypeError if no parent
		try{
			return child.parentNode && hasParentClass(child.parentNode, classList);
		}catch(TypeError){
			return false;
		}
	}

	const onClickOutside = (e) => {
		if (e.target.className.includes('custom-select') || e.target.className.includes('custom-option') || hasParentClass(e.target, ['action'])) {
			return;
		}
		$('.custom-select').each(function () {
			$(this).removeClass('open');
			$(this).children('.custom-options').removeClass('open');
		})
		window.removeEventListener("click", onClickOutside);
	};

	$('#addonPriceType').attr('checked', false);

	$('.custom-select').on('click', function( event ) {

		var activePlan = $(this).parents('.addon').find('.price > .active-plan');
		var priceType  = ( $('#addonPriceType').is(':checked')) ? 'lifetime': 'annual';
		/** Not show price drop down for lower licencses than exist **/
		if ( activePlan.length > 0 &&  $.trim( $(this).find('.' + priceType).html() ) == "" ) {
			return;
		}

		window.addEventListener("click", onClickOutside);
		let selectClass = $(this).attr('class');
		if ( selectClass.includes('open') ) {
			$(this).removeClass('open');
			$(this).children('.custom-options').removeClass('open');
		}else{
			$(this).addClass('open');
			$(this).children('.custom-options').addClass('open');
		}
	});

	$('.custom-option').on('click', function( event ) {
		if ( $(this).hasClass('disable')) {
			return;
		}
		var license   = $(this).attr('data-value');
		var title     = $(this).text();
		var priceType = ( $('#addonPriceType').is(':checked')) ? 'lifetime': 'annual';

		let valueEl =  $(this).parents('.custom-select').find('.value');
		valueEl.text(title);
		valueEl.attr('data-value', license);

		var parent = $(this).parents('.addon');
		parent.find('.price > p').each(function () {
			if ( $(this).hasClass(license) ) {
				var price        = $(this).children('.plan-price');
				var priceForType = price.data(priceType);
				var url          = replaceUrlParam(price.data('url'), 'period', priceType);
				/** set new price */
				price.text('$' + priceForType);

				var activeLicense  = $(this).attr('data-licenses');
				var activeLifetime = $(this).attr('data-lifetime') == 1 ? true: false;
				if ( !activeLifetime && priceType == 'lifetime' && license == activeLicense ) {
					$(this).removeClass('active-plan');
				}else if ( license == activeLicense ){
					$(this).addClass('active-plan');
				}

				/** hide 'per year' if lifetime */
				if ( priceType == 'lifetime') {
					$(this).children('.plan-period').addClass('hidden');
				}else{
					$(this).children('.plan-period').removeClass('hidden');
				}

				/** set new url */
				$(this).parents('.addon').children('.action').find('a[class*="buy"]').attr('href', url);

				$(this).addClass('active');
				$(this).removeClass('hidden');

			}else{
				$(this).addClass('hidden');
				$(this).removeClass('active');
			}
		})
	});

	$('#addonPriceType').on('change', function () {

		var priceType = 'annual';
		if (this.checked) priceType = 'lifetime';

		let parent      = $(this).closest('.pricing');
		let annual      = parent.find('.annual');
		let lifetime    = parent.find('.lifetime');
		annual.toggleClass('active', !this.checked);
		lifetime.toggleClass('active', this.checked);

		$('.addon').each(function () {
			var addonObj     = $(this);
			var price        = addonObj.find('.price > .active .plan-price');
			var priceForType = price.data(priceType);
			var url          = replaceUrlParam(price.data('url'), 'period', priceType);

			/** set new price */
			price.text('$' + priceForType);

			/** hide 'per year' if lifetime */
			if ( priceType == 'lifetime') {
				addonObj.find('.price > .active .plan-period').addClass('hidden');
				addonObj.find('.active-annual').each(function () {
					$(this).removeClass('hidden');
				});
				addonObj.find('.active-annual-btn').addClass('hidden');
			}else{
				addonObj.find('.price > .active .plan-period').removeClass('hidden');
				addonObj.find('.active-annual').each(function () {
					$(this).addClass('hidden');
				});
				addonObj.find('.active-annual-btn').removeClass('hidden');
			}

			/** set new url */
			var action     = addonObj.children('.action');
			var activePlan = addonObj.find('.price > .current-plan');

			if ( activePlan.length > 0 ) {
				var activeLicense  = activePlan.attr('data-licenses');
				var activeLifetime = activePlan.attr('data-lifetime') == 1 ? true: false;

				/** self url if lower url params than exist license **/
				var choosenLicense = action.find('.value').attr('data-value');
				if ( !activeLifetime && priceType == 'lifetime' && choosenLicense == activeLicense) {
					activePlan.removeClass('active-plan');
				}else if (choosenLicense == activeLicense){
					activePlan.addClass('active-plan');
				}

				if ( ( activeLifetime && priceType == 'annual' ) || ( !activeLifetime && priceType == 'annual' && parseInt( choosenLicense ) < parseInt ( activeLicense) ) ) {
					var url = action.find('a[class*="buy"]').attr('data-license-url');
					action.find('.value').text(activePlan.attr('data-license-text'));
					action.find('.value').attr('data-value', activeLicense);
					/** set new price */
					price.text('$' + activePlan.attr('data-price'));
				}

				action.find('.custom-option').each(function () {
					if ( $(this).attr('class').split(' ').includes(priceType) ) {
						$(this).addClass('show');
						$(this).removeClass('hidden');
					}else{
						$(this).addClass('hidden');
						$(this).removeClass('show');
					}
				});
			}
			action.find('a[class*="buy"]').attr('href', url);
		})
	});
});
</script>
