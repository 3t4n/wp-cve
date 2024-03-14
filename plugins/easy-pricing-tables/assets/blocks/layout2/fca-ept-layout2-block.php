<?php

function fca_ept_render_layout2( $attributes ){
	
	// enqueue frontend style
	wp_enqueue_style( 'fca-ept-layout2-style' );

	$tableID = empty( $attributes['tableID'] ) ? 0 : $attributes['tableID'];

	$columnSettings = empty( $attributes['columnSettings'] ) ? array() : json_decode( $attributes['columnSettings'], true );

	/* COLORS */
	$layoutBGColor = empty( $attributes['layoutBGColor'] ) ? '#f2f2f2' : ( $attributes['layoutBGColor'] );
	$layoutFontColor = empty( $attributes['layoutFontColor'] ) ? '#000' : ( $attributes['layoutFontColor'] );
	$layoutFontColor1 = empty( $attributes['layoutFontColor1'] ) ? '#6236ff' : ( $attributes['layoutFontColor1'] );
	$buttonColor = $toggleColor = empty( $attributes['buttonColor'] ) ? '#6236ff' : ( $attributes['buttonColor'] );
	$buttonFontColor = empty( $attributes['buttonFontColor'] ) ? '#fff' : ( $attributes['buttonFontColor'] );
	$accentColor = empty( $attributes['accentColor'] ) ? '#6236ff' : ( $attributes['accentColor'] );

	/* FONT SIZES */
	$popularFontSize = empty( $attributes['popularFontSize'] ) ? '75%' : ( $attributes['popularFontSize'] );
	$planFontSize = empty( $attributes['planFontSize'] ) ? '300%' : ( $attributes['planFontSize'] );
	$planSubtextFontSize = empty( $attributes['planSubtextFontSize'] ) ? '100%' : ( $attributes['planSubtextFontSize'] );
	$priceFontSize = empty( $attributes['priceFontSize'] ) ? '400%' : ( $attributes['priceFontSize'] );
	$pricePeriodFontSize = empty( $attributes['pricePeriodFontSize'] ) ? '100%' : ( $attributes['pricePeriodFontSize'] );
	$featuresFontSize = empty( $attributes['featuresFontSize'] ) ? '125%' : ( $attributes['featuresFontSize'] );
	$buttonFontSize = empty( $attributes['buttonFontSize'] ) ? '150%' : ( $attributes['buttonFontSize'] );

	/* SETTINGS */
	$fontFamily = empty( $attributes['fontFamily'] ) ? 'sans-serif' : $attributes['fontFamily'];
	
	$paddingBottom = empty( $attributes['showButtons'] ) ? '30px' : ( $attributes['showButtons'] === 'block' ? '30px' : '0px' );
	$align = empty( $attributes['align'] ) ? 'wide' : ( $attributes['align'] );
	$popularTextDefault = empty( $attributes['popularText'] ) ? 'Most Popular' : ( $attributes['popularText'] );
	$showPlanSubtext = empty( $attributes['showPlanSubtextToggle'] ) ? 'none' : 'block';
	$showPriceSubtext = empty( $attributes['showPriceSubtextToggle'] ) ? 'none' : 'block';
	
	$showButtons = empty( $attributes['showButtonsToggle'] ) ? false : true;
	$urlTarget = empty( $attributes['urlTargetToggle'] ) ? '_self' : '_blank';
	$customCSS = empty( $attributes['customCSS'] ) ? '' : esc_attr( $attributes['customCSS'] );

	ob_start(); 

	?>
	<div style="display:contents; font-family: <?php echo $fontFamily ?>" class='fca-ept-main' id=<?php echo 'fca-ept-table-' . $tableID ?>>
		<?php if( function_exists( 'fca_ept_render_toggle' ) ){ 
			echo fca_ept_render_toggle( $attributes, $toggleColor );  
		}?>
		<div class="wp-block-fatcatapps-easy-pricing-tables align<?php echo $align ?> fca-ept-layout2">
		<?php

		forEach ( $columnSettings as $column ){
			
			$columnPopular = empty( $column['columnPopular'] ) ? false : true;
			$showPopular = $columnPopular ? 'block' : 'none';
			$popularClass = $columnPopular ? 'fca-ept-most-popular' : '';
			$popularText = empty( $column['popularText'] ) ? $popularTextDefault : $column['popularText'];
			
			$planText1 = fca_ept_get_product_data( $column, 1, 'plan' );
			$planText2 = fca_ept_get_product_data( $column, 2, 'plan' );
			$hasPlanImage1 = fca_ept_get_product_data( $column, 1, 'image' ) ? 'block' : 'none';
			$hasPlanImage2 = fca_ept_get_product_data( $column, 2, 'image' ) ? 'block' : 'none';
			$priceText1 = fca_ept_get_product_data( $column, 1, 'price' );
			$priceText2 = fca_ept_get_product_data( $column, 2, 'price' );
			$buttonURL1 = fca_ept_get_product_data( $column, 1, 'url' );
			$buttonURL2 = fca_ept_get_product_data( $column, 2, 'url' );

			$planSubText = $column['planSubText'] ? $column['planSubText'] : '';
			$pricePeriod1 = $column['pricePeriod1'] ? $column['pricePeriod1'] : '';
			$pricePeriod2 = empty( $column['pricePeriod2'] ) ? '' : $column['pricePeriod2'];
			$featuresText = $column['featuresText'] ? $column['featuresText'] : '';
			$buttonText = $column['buttonText'] ? $column['buttonText'] : '';
			
			
			?> 
			<div style="background-color:<?php echo $layoutBGColor ?>;" class="fca-ept-column <?php echo $popularClass ?>">				
				<div class="fca-ept-plan-div">
					<div style="display: <?php echo $showPopular ?>; border-color:<?php echo $accentColor ?>" class="fca-ept-popular-div">					
						<span style="font-size:<?php echo $popularFontSize ?>; background-color:<?php echo $accentColor ?>; color:<?php echo $buttonFontColor ?>" class="fca-ept-popular-text"><?php echo $popularText ?></span>					
					</div>
					<div style="display: <?php echo $hasPlanImage1 ?>" class="fca-ept-plan-image"><img class="fca-ept-image1" src="<?php echo fca_ept_get_product_data( $column, 1, 'image' ) ?>"></div>
					<div style="display: <?php echo $hasPlanImage2 ?>" class="fca-ept-plan-image"><img class="fca-ept-image2" src="<?php echo fca_ept_get_product_data( $column, 2, 'image' ) ?>"></div>
					<span style="font-size:<?php echo $planFontSize ?>; color:<?php echo $layoutFontColor1 ?>" class="fca-ept-plan" data-plan1="<?php echo esc_attr( $planText1 ) ?>" data-plan2="<?php echo esc_attr( $planText2 ) ?>"><?php echo $planText1 ?></span>			
					<span style="display: <?php echo $showPlanSubtext ?>; font-size:<?php echo $planSubtextFontSize ?>; color:<?php echo $layoutFontColor ?>;" class="fca-ept-plan-subtext"><?php echo $planSubText ?></span>
				</div>			
				<div class="fca-ept-price-div">			
					<div class="fca-ept-price-container">			
						<span style="font-size: <?php echo $priceFontSize ?>; color:<?php echo $layoutFontColor ?>" class="fca-ept-price" data-price1="<?php echo esc_attr( $priceText1 ) ?>" data-price2="<?php echo esc_attr( $priceText2 ) ?>"><?php echo $priceText1 ?></span>			
						<div style="display: <?php echo $showPriceSubtext ?>" class="fca-ept-price-subtext">			
							<svg class="fca-ept-price-svg" style="background-color:<?php echo $buttonColor ?>"></svg>			
							<span style="font-size:<?php echo $pricePeriodFontSize ?>; color:<?php echo $layoutFontColor ?>" class="fca-ept-price-period" data-period1="<?php echo esc_attr( $pricePeriod1 ) ?>" data-period2="<?php echo esc_attr( $pricePeriod2 ) ?>"><?php echo $pricePeriod1 ?></span>			
						</div>			
					</div>			
				</div>			
				<div class="fca-ept-features-div">			
					<ul style="font-size:<?php echo $featuresFontSize ?>; color:<?php echo $layoutFontColor ?>" class="fca-ept-features">			
						<?php echo $featuresText ?>			
					</ul>			
				</div>	
				<?php if ( $showButtons ) { ?>
					<a style=" 
					font-size:<?php echo $buttonFontSize ?>; 
					color:<?php echo $buttonFontColor ?>; 
					background-color:<?php echo $buttonColor ?>" 
					href="<?php echo $buttonURL1 ?>" 
					class="fca-ept-button" data-url1="<?php echo $buttonURL1 ?>" 
					data-url2="<?php echo $buttonURL2 ?>" 
					target="<?php echo $urlTarget ?>" rel="noopener noreferrer">
					<?php echo $buttonText ?>
					</a>
				<?php } ?>
			</div>		
		<?php } ?>
		</div>
		<?php echo fca_ept_match_heights_js( $attributes ); ?>	
	</div>
	<style>
		#fca-ept-table-<?php echo $tableID ?> div.fca-ept-layout2 div.fca-ept-column.fca-ept-most-popular { border: 2px solid <?php echo $accentColor ?> }
		<?php echo $customCSS ?>
	</style>
	<?php
	
	return ob_get_clean();
	
}
?>