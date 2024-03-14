<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wcpt_settings = wcpt_get_settings_data();
$settings = $wcpt_settings['modals'];

$labels =& $settings['labels'];
$locale = get_locale();

$strings = array();

if( ! empty( $labels ) ){
	foreach( $labels as $key => $translations ){
		$strings[$key] = array();
		$translations = preg_split ('/$\R?^/m', $translations);
		foreach( $translations as $translation ){
			$array = explode( ':', $translation );      
			if( ! empty( $array[1] ) ){
				$strings[$key][ trim( $array[0] ) ] = stripslashes( trim( $array[1] ) );
			}else{
				$strings[$key][ 'default' ] = stripslashes( trim( $array[0] ) );
			}
		}
	}
}

// maybe use defaults
foreach( $strings as $item => &$translations ){
	if( empty( $translations[ $locale ] ) ){
		if( ! empty( $translations[ 'default' ] ) ){
			$translations[ $locale ] = $translations[ 'default' ];			
		}else if( ! empty( $translations[ 'en_US' ] ) ){
			$translations[ $locale ] = $translations[ 'en_US' ];
		}
	}
}

$style =& $settings['style'];
?>
<div class="wcpt-nav-modal-tpl">
  <div class="wcpt-nav-modal" data-wcpt-table-id="<?php echo $GLOBALS['wcpt_table_data']['id']; ?>">
    <div class="wcpt-nm-content wcpt-noselect">
      <div class="wcpt-nm-heading wcpt-nm-heading--sticky">
				<span class="wcpt-nm-close">
					<?php wcpt_icon('x'); ?>
					</svg>
				</span>
        <span class="wcpt-nm-heading-text wcpt-on-filters-show">
          <?php echo ! empty( $strings['filters'][$locale] ) ? $strings['filters'][$locale] : __('Filters', 'wc-product-table'); ?>
        </span>
        <span class="wcpt-nm-heading-text wcpt-on-sort-show">
          <?php echo ! empty( $strings['sort'][$locale] ) ? $strings['sort'][$locale] : __('Sort', 'wc-product-table'); ?>
        </span>
				<div class="wcpt-nm-action">
					<span class="wcpt-nm-reset">
						<?php echo ( ! empty( $strings['reset'] ) && ! empty( $strings['reset'][$locale] ) ) ?  $strings['reset'][$locale] : __('Reset', 'wc-product-table'); ?>
					</span>
	        <span class="wcpt-nm-apply">
						<?php echo ( ! empty( $strings['apply'] ) && ! empty( $strings['apply'][$locale] ) ) ?  $strings['apply'][$locale] : __('Apply', 'wc-product-table'); ?>
					</span>
				</div>
      </div>
      <div class="wcpt-navigation wcpt-left-sidebar">
        <div class="wcpt-nm-filters">
          <span class="wcpt-nm-filters-placeholder"></span>
        </div>
        <div class="wcpt-nm-sort">
          <span class="wcpt-nm-sort-placeholder"></span>
        </div>
      </div>
    </div>
  </div>
</div>
