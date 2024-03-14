<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
if(empty($element['data']['id'])) {
	$element['data']['id'] = 'primary';
}

if (!empty($element['data']['id'])):


    if(!empty($element['data']['innerColor']) or !empty($element['data']['innerHoverColor']) ) {
        $element_hash = sanitize_title($element['$$hashKey']);
        $style = '';

		if(!empty($element['data']['hoverColor'])) {
			$text_color = $element['data']['hoverColor'];

			$style .= "body .stm-header__hb .stm-header__element.{$element_hash} .stm-navigation > ul > li:hover > a,
			 body .stm-header__hb .stm-header__element.{$element_hash} .stm-navigation > ul > li > a:hover {
                color: {$text_color} !important;
            }";

		};

		if(!empty($element['data']['innerColor'])) {
            $text_color = $element['data']['innerColor'];

            $style .= ".stm-header__hb .stm-header__element.{$element_hash} .stm-navigation > ul > li .sub-menu li a {
                color: {$text_color} !important;
            }"; ?>
        <?php }

        if(!empty($element['data']['lineHeight'])) {
            $lineHeight = $element['data']['lineHeight'];

            $style .= ".stm-header__hb .stm-header__element.{$element_hash} .stm-navigation > ul > li > a {
                line-height: {$lineHeight}px !important;
            }"; ?>
        <?php }


        if(!empty($element['data']['innerHoverColor'])) {
            $text_color = $element['data']['innerHoverColor'];

            $style .= ".stm-header__hb .stm-header__element.{$element_hash} .stm-navigation > ul > li .sub-menu li a:hover {
                color: {$text_color} !important;
            }";

		};

		?>

        <style type="text/css"><?php echo sanitize_text_field($style); ?></style>
    <?php }

	$classes = array(
		'stm-navigation'
	);
	$style_string = array();

	$item_classes = array();

	$divider = '';
	$style = 'default';
	$line = 'none';
	$fwn = '';

	if (!empty($element['data'])) {

		$data = $element['data'];

		/*Divider*/
		if (!empty($data['divider'])) {
			$divider = $data['divider'];
		}

		/*Style*/
		if (!empty($data['style'])) {
			$style = $data['style'];
			if ($data['style'] == 'fullwidth') {
				$classes[] = 'stm_hb_tbc';
			}
		}

		/*Line*/
		if (!empty($data['line'])) {
			$line = $data['line'];
		}

		/*Font*/
		$font = 'hf';

		if(!empty($data['font'])) {
			$font = $data['font'];
		}

		if ($font === 'mf') {
			$classes[] = 'main_font';
		} else if ($font === 'hf') {
			$classes[] = 'heading_font';
		}

		/*Font size*/
		if (!empty($data['fsz'])) {
			$classes[] = 'fsz_' . intval($data['fsz']);
		}

		/*Line height*/
		if (!empty($data['lh'])) {
			$style_string['line-height'] = intval($data['lh']) . 'px';
		}

		/*FWN*/
		if (!empty($data['fwn'])) {
			$fwn = $data['fwn'];
		}
	}


	$classes[] = 'stm-navigation__default';
	$classes[] = 'stm-navigation__' . $style;
	$classes[] = 'stm-navigation__' . $line;
	$classes[] = 'stm-navigation__' . $fwn;

	if (!empty($element['data'])) {
		if(!empty($element['data']['divider'])) {
			if (!empty($element['data']['divider']))
			{
				$classes[] = 'stm-navigation__divider';

				if ($element['data']['divider'] === 'icon' && !empty($element['data']['dividerIcon'])) {
					$divider = "<i class='divider " . esc_attr($element['data']['dividerIcon']) . "'></i>";
				}
				if ($element['data']['divider'] === 'symbol' && !empty($element['data']['dividerSymbol'])) {
					$divider = '<span class="divider">' . esc_html($element['data']['dividerSymbol']) . '</span>';
				}
			}

		}
	}

	if (!empty($style_string)) {
		$style_string = 'style="' . stm_hb_array_to_style_string($style_string) . '"';
	} else {
		$style_string = '';
	}

	?>

    <div class="<?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo sanitize_text_field($style_string) ?>>
        <?php if ($data['style'] == 'vertical_left') : ?>
            <div class="stm_mobile__switcher stm_flex_last js_trigger__click" data-toggle="false">
                <span class="stm_hb_mbc"></span>
                <span class="stm_hb_mbc second"></span>
                <span class="stm_hb_mbc"></span>
            </div>
        <?php endif; ?>

        <ul <?php if ($data['style'] == 'vertical_left') : ?>class="stm-navigation__vertical"<?php endif; ?>>
			<?php

			$menu_args = array(
				'depth' => 3,
				'container' => false,
				'items_wrap' => '%3$s',
				'link_after' => $divider,
				'fallback_cb' => false,
				'stm_megamenu' => true
			);

			if(!empty(intval($element['data']['id']))) {
				if(is_nav_menu($element['data']['id'])) {
					$menu_args['menu'] = intval($element['data']['id']);
				} else {
					$menu_args['theme_location'] = 'primary';
				}
			} else {
				$menu_args['theme_location'] = $element['data']['id'];
			}

			wp_nav_menu($menu_args); ?>
        </ul>


		<?php if (in_array('stm-navigation__fullwidth', $classes)) {
			/*Iconbox*/
			if (!empty($element['data'])) {
				$iconbox = array(
					'data' => array()
				);
				$iconbox['data']['icon'] = (!empty($element['data']['icon'])) ? $element['data']['icon'] : '';
				$iconbox['data']['title'] = (!empty($element['data']['title'])) ? $element['data']['title'] : '';
				$iconbox['data']['description'] = (!empty($element['data']['description'])) ? $element['data']['description'] : '';
				$iconbox['data']['iconBoxColor'] = (!empty($element['data']['iconBoxColor'])) ? $element['data']['iconBoxColor'] : '';

				stm_hb_load_element('iconbox', array('element' => $iconbox));
			}
		} ?>

    </div>

<?php endif; ?>
