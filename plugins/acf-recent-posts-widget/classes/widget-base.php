<?php

/**
 * Base Widget Class
 */
class Widget_Base extends WP_Widget {

	/**
	 * @var Array of string
	 */
	public $text_fields = array();

	/**
	 * @var Array of string
	 */
	public $text_areas = array();

	/**
	 * @var Array of string
	 */
	public $checkboxes = array();

	/**
	 * @var Array of string
	 */
	public $select_fields = array();

	/**
	 * @var form instance object
	 */
	public $form_instance = '';

	/**
	 * Register widget with WordPress.
	 */
	function __construct($id, $name, $args, $controls) {

		parent::__construct(
				$id, // Base ID
				$name, // Name
				$args, // Args
				$controls
		);
	}

	/**
	 * Function to quick create form input field
	 * 
	 * @param string $field widget field name
	 * @param string $label
	 * @param string $note field note to appear below
	 */
	public function gti($field, $label, $note = '', $class = '') {
		?>
		<p>
			<label for="<?php echo $this->get_field_id( $field ); ?>">
				<?php _e( $label, 'acf-recent-posts-widget' ); ?>
			</label>
			<input class="widefat <?php echo $class; ?>" 
				   id="<?php echo $this->get_field_id( $field ); ?>" 
				   name="<?php echo $this->get_field_name( $field ); ?>" type="text" 
				   value="<?php echo esc_attr( isset( $this->form_instance[$field] ) ? $this->form_instance[$field] : ''  ); ?>" />
				   <?php if ( !empty( $note ) ): ?>
				<small><?php echo $note; ?></small>
			<?php endif; ?>
		</p>
		<?php
	}

	/**
	 * Function to quick create form input field
	 * 
	 * @param string $field widget field name
	 * @param string $label
	 * @param string $note field note to appear below
	 */
	public function gt($field, $label, $note = '') {
		?>
		<p>
			<label for="<?php echo $this->get_field_id( $field ); ?>">
				<?php _e( $label, 'acf-recent-posts-widget' ); ?>
			</label>
			<textarea class="widefat" 
					  id="<?php echo $this->get_field_id( $field ); ?>" 
					  name="<?php echo $this->get_field_name( $field ); ?>"><?php echo esc_attr( isset( $this->form_instance[$field] ) ? $this->form_instance[$field] : ''  ); ?></textarea>
					  <?php if ( !empty( $note ) ): ?>
				<small><?php echo $note; ?></small>
			<?php endif; ?>
		</p>
		<?php
	}

	/**
	 * Generate checkbox input
	 * 
	 * @param string $field widget field name
	 * @param string $label
	 * @param string $note field note to appear below
	 * @param Object $instance widget instance
	 * @param Array_A $elements
	 */
	public function gtc($field, $label, $elements, $note = '') {
		?>
		<div class="acf-rpw-multiple-check-form">
			<p>
				<label for="<?php echo $this->get_field_id( $field ); ?>">
					<?php _e( $label, 'acf-recent-posts-widget' ); ?>
				</label>
			</p>
			<ul>
				<?php foreach ( $elements as $key => $elem ) : ?>
					<li>
						<input type="checkbox" value="<?php echo esc_attr( $key ); ?>" id="<?php echo $this->get_field_id( $field ) . '-' . $elem; ?>" name="<?php echo $this->get_field_name( $field ); ?>[]" <?php checked( isset( $this->form_instance[$field] ) && is_array( $this->form_instance[$field] ) && in_array( $key, $this->form_instance[$field] ) ); ?> />
						<label for="<?php echo $this->get_field_id( $field ) . '-' . $elem; ?>">
							<?php echo esc_attr( ucfirst( $elem ) ); ?>
						</label>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( !empty( $note ) ): ?>
				<p class="note">
					<small><?php echo $note; ?></small>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Generate select input
	 * 
	 * @param string $field widget field name
	 * @param string $label
	 * @param string $note field note to appear below
	 * @param Array_A $elements
	 */
	public function gts($field, $label, $elements, $note = '') {
		?>
		<p>
			<label for="<?php echo $this->get_field_id( $field ); ?>">
				<?php _e( $label, 'acf-recent-posts-widget' ); ?>
			</label>
			<select class="widefat" id="<?php echo $this->get_field_id( $field ); ?>" name="<?php echo $this->get_field_name( $field ); ?>" style="width:100%;">
				<?php foreach ( $elements as $key => $elem ) : ?>
					<option value="<?php echo $key; ?>" <?php isset( $this->form_instance[$field] ) ? selected( $this->form_instance[$field], $key ) : ''; ?>><?php echo ucfirst( $elem ); ?></option>
				</li>
			<?php endforeach; ?>
		</select>
		<?php if ( !empty( $note ) ): ?>
			<small><?php echo $note; ?></small>
		<?php endif; ?>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance = $this->_sanitize_data( $instance, $new_instance );
		return $instance;
	}

	public function _sanitize_data($instance, $new_instance) {

		if ( is_array( $this->text_fields ) ) {
			// update the text fields values
			foreach ( $this->text_fields as $field ) {
				$instance = array_merge( $instance, $this->_update_text( $field, $new_instance ) );
			}
		}

		if ( is_array( $this->text_areas ) ) {
			//update the textarea_values
			foreach ( $this->text_areas as $field ) {
				$instance = array_merge( $instance, $this->_update_textarea( $field, $new_instance ) );
			}
		}

		if ( is_array( $this->checkboxes ) ) {
			// update the checkbox fields values
			foreach ( $this->checkboxes as $field ) {
				$instance = array_merge( $instance, $this->_update_checkbox( $field, $new_instance ) );
			}
		}

		if ( is_array( $this->select_fields ) ) {
			// update the select fields values
			foreach ( $this->select_fields as $field ) {
				$instance = array_merge( $instance, $this->_update_select( $field, $new_instance ) );
			}
		}

		return $instance;
	}

	/**
	 * Update and sanitize backend value of the text field
	 * 
	 * @param string $name
	 * @param object $new_instance
	 * @return object validate new instance
	 */
	public function _update_text($name, $new_instance) {
		$instance = array();
		$instance[$name] = (!empty( $new_instance[$name] )) ? sanitize_text_field( $new_instance[$name] ) : '';
		return $instance;
	}

	/**
	 * Update and sanitize backend value of the textarea
	 * 
	 * @param string $name
	 * @param object $new_instance
	 * @return object validate new instance
	 */
	public function _update_textarea($name, $new_instance) {
		$instance = array();
		$instance[$name] = (!empty( $new_instance[$name] )) ? esc_textarea( htmlspecialchars_decode( $new_instance[$name] ) ) : '';
		return $instance;
	}

	/**
	 * Update and sanitize backend value of the checkbox field
	 * 
	 * @param string $name
	 * @param object $new_instance
	 * @return object validate new instance
	 */
	public function _update_checkbox($name, $new_instance) {
		$instance = array();
		// make sure any checkbox has been checked
		if ( !empty( $new_instance[$name] ) ) {
			// if multiple checkboxes has been checked
			if ( is_array( $new_instance[$name] ) ) {
				// iterate over multiple checkboxes
				foreach ( $new_instance[$name] as $key => $value ) {
					$instance[$name][$key] = (!empty( $new_instance[$name][$key] )) ? esc_attr( $value ) : '';
				}
			} else {
				$instance[$name] = esc_attr( $new_instance[$name] );
			}
		}
		return $instance;
	}

	/**
	 * Update and sanitize backend value of the select field
	 * 
	 * @param string $name
	 * @param object $new_instance
	 * @return object validate new instance
	 */
	public function _update_select($name, $new_instance) {
		$instance = array();
		$instance[$name] = (!empty( $new_instance[$name] )) ? esc_attr( $new_instance[$name] ) : '';
		return $instance;
	}

}
