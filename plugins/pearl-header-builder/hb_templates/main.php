<?php
$hb = $this->headerName;
$elements = stm_hb_get_option('header_builder', false, $hb);

$parts = stm_hb_parts();
$rows = $parts['rows'];
$cells = $parts['cells'];

$row_wrapper = 'stm-header__row_color stm-header__row_color_';
$row_base = 'stm-header__row stm-header__row_';
$cell_base = 'stm-header__cell stm-header__cell_';
$element_base = 'stm-header__element';

?>

<script type="text/javascript">
    var stm_sticky = '<?php echo esc_js(stm_hb_get_option('header_sticky', '', 'stm_hb_settings')); ?>';
</script>

<div class="stm-header stm-header__hb" id="stm_<?php echo sanitize_title($this->headerName); ?>">
	<?php foreach($rows as $row): ?>
		<?php if(!empty($elements[$row])):
			$count_elements_in_row = 0;
			foreach($elements[$row] as $element_num) {
				$count_elements_in_row++;
			}
			?>

            <div class="<?php echo apply_filters('stm_hb_row_class', sanitize_text_field($row_wrapper . $row), $row); ?> elements_in_row_<?php echo intval($count_elements_in_row); ?>">
                <div class="container">
                    <div class="<?php echo sanitize_text_field($row_base . $row) ?>">
						<?php foreach($cells as $cell):
							if(!empty($elements[$row][$cell])): ?>
                                <div class="<?php echo sanitize_text_field($cell_base . $cell) ?>">
									<?php if(!empty($elements[$row][$cell])):
										foreach($elements[$row][$cell] as $key => $element):
											$custom_css = sanitize_title($element['$$hashKey']);
											$custom_css .= stm_hb_element_style($element);

											$type = !empty($element['view_template']) ? $element['view_template'] : $element['type'];

											$element['pearl_header_builder'] = $hb;
											?>
                                            <div class="<?php echo sanitize_text_field($element_base . ' ' . $custom_css); ?>">
												<?php stm_hb_load_element($type, array('element' => $element)); ?>
                                            </div>
										<?php endforeach;
									endif; ?>
                                </div>
							<?php endif;
						endforeach; ?>
                    </div>
                </div>
            </div>
		<?php endif;
	endforeach; ?>
</div>

<?php
/*Mobile Page*/
stm_hb_load_element('mobile_header', array('hb' => $hb), 'mobile', 'mobile'); ?>