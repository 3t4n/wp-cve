<?php
class CLP_Customizer_template_Control extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'clp-template';

	/**
	 * parse parameters to JS JSON
	 *
	 * @access public
	 * @since  1.1.7
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$array = $this->get_values();
		$this->json['options']		= $array['options'];
	}

	public function get_values() {
		$arrays = array(
			// 'choices' => array(),
			'options' => array(),
		);

		foreach ( $this->choices as $key => $choice ) {
			$arrays['options'][ $key ] = array();

			foreach ( $choice['options'] as $option_key => $option_value ) {
				$arrays['options'][ $key ][ $option_key ] = array(
					'name' => $option_key,
					'value' => $option_value
				);
			}
		}

		return $arrays;

	}

	public function render_content() {
        foreach ($this->choices as $slug => $settings) { ?>
			<label for="<?php echo $this->id .'-'. $slug;?>">
				<input type="radio" value="<?php echo $slug;?>" id="<?php echo $this->id .'-'. $slug;?>" name="_customize-clp-template" style="display:none" />
				<div class="clp-template-thumbnail">
					<img src="<?php echo $settings['thumbnail'];?>" alt="<?php echo $settings['name'];?>">
					<div class="clp-template-overlay">
						<span class="clp-template-overlay-text"><?php echo $settings['name'];?></span>
					</div>
				</div>
			</label>
            <?php
        }
        
        ?>
        <input type="hidden" value="<?php echo esc_attr( $this->value() ); ?>" id="clp-templates" <?php $this->link(); ?> name ="_customize-<?php echo $this->id;?>"> 
        <?php 

	}


}
