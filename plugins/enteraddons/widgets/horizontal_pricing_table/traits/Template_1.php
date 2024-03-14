<?php 
namespace Enteraddons\Widgets\Horizontal_Pricing_Table\Traits;
/**
 * Enteraddons Horizontal Pricing Table template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Template_1 {
	
public static function markup_style_1() {
	$settings   = self::getSettings();
	?>
		<div class="horizontal-pricing-wrapper ea-table-responsive-lg ">
			<div class="ea-product-item-list">
				
					<?php
					if ( 'yes' === $settings['logo_show'] ) {
					 self::logo(); 
					}
					 self::Title(); 
					 
					echo '<ul class="ea-product-feature-list">';
						 if( $settings['pricing_feature_list'] ){
							foreach( $settings['pricing_feature_list'] as $feature ){
						
							echo '<li>';
							 self::product_feature( $feature );  
							echo '</li>';  
							}
						}      
                 	echo '</ul>'; 
				
					self::price(); 
					self::duration();
					if ( 'yes' === $settings['ratting_show'] ) {
				echo '<div class="ea-product-ratting">';
					
					self::ratting_number(); 
					self::ratting_star();                              
					self::review_text();
					
				echo '</div>';
					}
				
					 self::button();
					 ?>
			</div>
		</div>	
	<?php	
 }

}