<?php
/**
 * Author: Vitaly Kukin
 * Date: 23.05.2017
 * Time: 12:45
 */

namespace dm;


class dmTemplateRender {

	/**
	 * Render panel
	 * @param array $args <p>
	 * panel-title-icon, panel-title, panel-description, panel-class, panel-content
	 * </p>
	 *
	 * @return string
	 */
	public function panel( $args = [] ) {

		$defaults = [
			'panel-title-icon'  => false,
			'panel-title'       => false,
			'panel-heading'     => false,
			'panel-help'        => false,
			'panel-description' => false,
			'panel-class'       => 'default',
			'panel-content'     => '',
		];

		$args = dm_parse_args( $defaults, $args );

		if( $args[ 'panel-help' ] ) {
			$help = sprintf(
			'<a href="%s" class="help_import" target="_blank"></a>',
				esc_url($args[ 'panel-help' ])
				);
			if( $args[ 'panel-heading' ] )
				$args[ 'panel-heading' ] .= $help;
			else
				$args[ 'panel-heading' ] = $help;
		}

		$content = sprintf(
			'<div class="card card-%s"><div class="card-body">%s %s %s</div></div>',
			$args[ 'panel-class' ],
			$args[ 'panel-title' ] || $args[ 'panel-heading' ] ?
				sprintf(
					'<div class="card-title">%s%s</div>',
					$args[ 'panel-title' ] ? sprintf( '<h5>%s%s</h5>',
						$args[ 'panel-title-icon' ] ? '<i class="fa fa-' . $args[ 'panel-title-icon' ] . '"></i>&nbsp;' : '',
						$args[ 'panel-title' ] ) : '',
					$args[ 'panel-heading' ] ? sprintf( '<div class="heading-elements">%s</div>', $args[ 'panel-heading' ] ) : ''
				) : '',
			$args[ 'panel-description' ] ? '<p class="card-text">' . $args[ 'panel-description' ] . '</p>'  : '',
			$args[ 'panel-content' ]
		);

		return $content;
	}

	/**
	 * Render One element
	 *
	 * @param string $type <p>
	 * Type of element which will render by template
	 * </p>
	 * @param array $args <p>
	 * list attributes for rendering element
	 * </p>
	 *
	 * @return null|string
	 */
	public function item( $type, $args ) {

		if( method_exists( $this, $type ) ) {
			return $this->$type( $args );
		}

		return null;
	}

	/**
	 * Render group
	 * @param array $args <p>
	 * class, id, value
	 * </p>
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function group( $args = [] ) {

		$layout = '';
		foreach( $args[ 'values' ] as $key => $val ) {
			$layout .= sprintf( '<div class="%s">%s</div>', $args[ 'rules' ][ $val[ 'name' ] ], $this->item( $val['type'], $val ) );
		}

		$content = sprintf( '%s<div class="group-items"><div class="row">%s</div>%s</div>',
			$args[ 'label' ] ? '<label>' . $args[ 'label' ] . '</label>' : '',
			$layout,
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
		);

		return $content;
	}

	/**
	 * Render custom element
	 * @param array $args <p>
	 * class, id, value
	 * </p>
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function custom( $args = [] ) {

		$defaults = [
			'class' => false,
			'id'    => false,
			'value' => false,
		];

		$args = dm_parse_args( $defaults, $args );

		$content = sprintf( '<div class="%s" id="%s">%s</div>', $args[ 'class' ], $args[ 'id' ], $args[ 'value' ] );

		return $content;
	}

	/**
	 * Render text element
	 * @param array $args <p>
	 * label, class, id, name, value, placeholder, disabled, help
	 * </p>
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function text( $args = [] ) {

		$defaults = [
			'label'       => false,
			'class'       => 'form-control',
			'id'          => false,
			'name'        => false,
			'value'       => false,
			'placeholder' => false,
			'disabled'    => false,
			'help'        => false,
			'wrap'        => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

		if( ! $args[ 'value' ] && $args[ 'name' ] ) {
			$args[ 'value' ] = '{{' . $args[ 'name' ] .'}}';
		}

		if( $args[ 'value' ] == 'null' ) $args[ 'value' ] = '';

		$content = sprintf(
			'<div class="form-group %s">%s<input type="text" class="%s" id="%s" name="%s" value="%s" placeholder="%s" %s>%s</div>',
			$args[ 'wrap' ] ? $args[ 'wrap' ] : '',
			$args[ 'label' ] ? '<label for="' . $args[ 'id' ] . '">' . $args[ 'label' ] . '</label>' : '',
			$args[ 'class' ],
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'value' ],
			$args[ 'placeholder' ],
			$args[ 'disabled' ] ? 'disabled="disabled"' : '',
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
		);

		return $content;
	}
	/**
	 * Render number element
	 * @param array $args <p>
	 * label, class, id, name, value, placeholder, disabled, help
	 * </p>
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function number( $args = [] ) {

		$defaults = [
			'label'       => false,
			'class'       => 'form-control',
			'id'          => false,
			'name'        => false,
			'value'       => false,
			'placeholder' => false,
			'disabled'    => false,
			'help'        => false,
			'wrap'        => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

		if( ! $args[ 'value' ] && $args[ 'name' ] ) {
			$args[ 'value' ] = '{{' . $args[ 'name' ] .'}}';
		}

		if( $args[ 'value' ] == 'null' ) $args[ 'value' ] = '';

		$content = sprintf(
			'<div class="form-group %s">%s<input type="number" class="%s" id="%s" name="%s" value="%s" placeholder="%s" %s>%s</div>',
			$args[ 'wrap' ] ? $args[ 'wrap' ] : '',
			$args[ 'label' ] ? '<label for="' . $args[ 'id' ] . '">' . $args[ 'label' ] . '</label>' : '',
			$args[ 'class' ],
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'value' ],
			$args[ 'placeholder' ],
			$args[ 'disabled' ] ? 'disabled="disabled"' : '',
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
		);

		return $content;
	}
	/**
	 * Render textarea element
	 * @param array $args <p>
	 * label, class, id, name, value, placeholder, disabled, help
	 * </p>
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function textarea( $args = [] ) {

		$defaults = [
			'label'       => false,
			'class'       => 'elastic elastic-destroy',
			'id'          => false,
			'name'        => false,
			'value'       => false,
			'placeholder' => false,
			'disabled'    => false,
			'help'        => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

		if( ! $args[ 'value' ] && $args[ 'name' ] ) {
			$args[ 'value' ] = '{{' . $args[ 'name' ] .'}}';
		}

		if( $args[ 'value' ] == 'null' ) $args[ 'value' ] = '';

		$content = sprintf(
			'<div class="form-group">%s
				<textarea rows="4" cols="4" class="form-control %s" id="%s" name="%s" placeholder="%s" %s>%s</textarea>
				%s
			</div>',
			$args[ 'label' ] ? '<label for="' . $args[ 'id' ] . '">' . $args[ 'label' ] . '</label>' : '',
			$args[ 'class' ],
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'placeholder' ],
			$args[ 'disabled' ] ? 'disabled="disabled"' : '',
			$args[ 'value' ],
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
		);

		return $content;
	}



	/**
	 * Render textarea element
	 * @param array $args <p>
	 * label, class, id, name, value, placeholder, disabled, help
	 * </p>
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function editor( $args = [] ) {

	    static $editor = false;

	    if( ! $editor ) {
            $editor = true;
            ob_start();
            \wp_editor( '', 'editor_settings', [ 'teeny' => false ] );
            ob_get_clean();
        }

		$defaults = [
			'label'       => false,
			'class'       => 'elastic elastic-destroy',
			'id'          => false,
			'name'        => false,
			'value'       => false,
			'placeholder' => false,
			'disabled'    => false,
			'help'        => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

		if( ! $args[ 'value' ] && $args[ 'name' ] ) {
			$args[ 'value' ] = '{{' . $args[ 'name' ] .'}}';
		}

		if( $args[ 'value' ] == 'null' ) $args[ 'value' ] = '';

		$content = sprintf(
			'<div class="form-group">%s
				<textarea rows="4" cols="4" class="form-control editor %s" id="%s" name="%s" placeholder="%s" %s>%s</textarea>
				%s
			</div>',
			$args[ 'label' ] ? '<label for="' . $args[ 'id' ] . '">' . $args[ 'label' ] . '</label>' : '',
			$args[ 'class' ],
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'placeholder' ],
			$args[ 'disabled' ] ? 'disabled="disabled"' : '',
			$args[ 'value' ],
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
		);

		return $content;
	}

	/**
	 * Render switcher element
	 * @param array $args <p>
	 * label, class, id, name, value, placeholder, disabled, checked, help
	 * </p>
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function switcher( $args = [] ) {

		$defaults = [
			'label'    => false,
			'id'       => false,
			'class'    => 'switchery-small',
			'name'     => false,
			'checked'  => false,
			'value'    => false,
			'disabled' => false,
			'help'     => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

		$checkedIfName = str_replace( [ '{{', '}}' ], '', $args[ 'name' ], $c );
        $checkedIfName = $c == 2 ? 'checked' : $checkedIfName;

        if( preg_match( '/\[(\w+)\]/', $args[ 'name' ], $match ) ) {
            $checkedIfName = $match[1];
        }

		$content = sprintf(
			'<div class="checkbox checkbox-switchery"><label>
				<input type="checkbox" id="%s" name="%s" value="%s" class="switchery %s" %s %s>%s
			</label>%s</div>',
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'value' ],
			$args[ 'class' ],
            ($args[ 'checked' ] && is_bool($args[ 'checked' ])) ?
				'checked="checked"' : ($args[ 'checked' ] ? '' : '{{checkedIf ' . $checkedIfName . '}}'),
			$args[ 'disabled' ] ? 'disabled="disabled"' : '',
			sprintf( '<span class="switch-label">%s</span>', $args[ 'label' ] ? $args[ 'label' ] : __( 'Status', 'dm' ) ),
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
		);

		return $content;
	}

	/**
	 * Render checkbox element
	 * @param array $args <p>
	 * label, class, id, name, value, placeholder, disabled, checked, help
	 * </p>
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function checkbox( $args = [] ) {

		$defaults = [
			'label'    => false,
			'id'       => false,
			'class'    => false,
			'name'     => false,
			'checked'  => false,
			'value'    => false,
			'disabled' => false,
			'help'     => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

        $checkedIfName = str_replace( [ '{{', '}}' ], '', $args[ 'name' ], $c );
        $checkedIfName = $c == 2 ? 'checked' : $checkedIfName;

		$content = sprintf(
			'<div class="checkbox"><label>%s
				<input type="checkbox" id="%s" name="%s" value="%s" class="uniform-checkbox %s" %s %s> <span class="filter-option">%s</span>
			</label></div>',
			$args[ 'label' ] ? '&nbsp;&nbsp;<span class="checkbox-label">' . $args[ 'label' ] . '</span>' : '',
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'value' ],
			$args[ 'class' ],
			$args[ 'checked' ] && is_bool($args[ 'checked' ]) ?
				'checked="checked"' : ( $args[ 'checked' ] ? '' : '{{checkedIf ' . $checkedIfName . '}}'),
			$args[ 'disabled' ] ? 'disabled="disabled"' : '',
			$args[ 'help' ]
		);

		return $content;
	}
	
	public function radio( $args = [] ) {

		$defaults = [
			'label'    => false,
			'id'       => false,
			'class'    => false,
			'name'     => false,
			'checked'  => false,
			'value'    => false,
			'disabled' => false,
			'help'     => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

        $checkedIfName = str_replace( [ '{{', '}}' ], '', $args[ 'name' ], $c );
        $checkedIfName = $c == 2 ? 'checked' : $checkedIfName;

		$content = sprintf(
			'<div class="radio"><label>%s
				<input type="radio" id="%s" name="%s" value="%s" class="uniform-radio %s" %s %s>%s
			</label></div>',
			$args[ 'label' ] ? '&nbsp;&nbsp;<span class="radio-label">' . $args[ 'label' ]  . '</span>' : '',
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'value' ],
			$args[ 'class' ],
			$args[ 'checked' ] && is_bool($args[ 'checked' ]) ?
				'checked="checked"' : ( $args[ 'checked' ] ? '' : '{{checkedIf ' . $checkedIfName . '}}'),
			$args[ 'disabled' ] ? 'disabled="disabled"' : '',
			$args[ 'help' ]
		);

		return $content;
	}

	public function _select( $args = [] ) {

		$defaults = [
			'label'          => false,
			'id'             => false,
			'name'           => false,
			'multiple'       => false,
			'class'          => false,
			'value'          => false,
			'values'         => false,
			'disabled'       => false,
			'help'           => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

		$values = '';

		if( is_array( $args[ 'values' ] ) ) {

			foreach( $args[ 'values' ] as $key => $val ) {

				if( $args[ 'multiple' ] )
					$selected = in_array( $key, $args[ 'value' ] ) ? 'selected="selected"' : '';
				else
					$selected = $key == $args[ 'value' ] ? 'selected="selected"' : '';

				$values .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
			}
		} else {

		    $name  = str_replace( [ '{{', '}}' ], '', $args[ 'name' ] );
		    $value = str_replace( [ '{{', '}}' ], '', $args[ 'value' ] );


			$values = sprintf(
				'{{#each values_%1$s}}
					<option value="{{this.value}}" {{#ifIn this.value ../%2$s }}selected{{/ifIn}}>
						{{this.title}}
					</option>
				{{/each}}',
                $name,
                $value ? $value : $name
			);
		}

		$disabled = '';
		if( $args[ 'disabled' ] === true ) {
			$disabled = 'disabled="disabled"';
		} elseif( ! is_bool( $args[ 'disabled' ] ) ) {
			$disabled = '{{#if ' . $args[ 'disabled' ] . '}}disabled="disabled"{{/if}}';
		}

		$content = sprintf(
			'<div class="%s">%s<select id="%s" name="%s" class="%s %s" data-width="%s" %s %s>%s</select>%s</div>',
			$args[ 'multiple' ] ? 'multi-select-full' : 'form-group',
			$args[ 'label' ] ? '<label ><span class="select-label">' . $args[ 'label' ]  . '</span></label>' : '',
			$args[ 'id' ],
			$args[ 'multiple' ] ? $args[ 'name' ] . '[]' : $args[ 'name' ],
			$args[ 'multiple' ] ? 'multiselect-full-featured' : 'bootstrap-select',
			$args[ 'class' ],
			'100%',
			$disabled,
			$args[ 'multiple' ] ? 'multiple="multiple"' : '',
			$values,
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
		);

		return $content;
	}

	public function select( $args = [] ) {

		$defaults = [
			'label'       => false,
			'label_class' => false,
			'id'          => false,
			'icon'        => false,
			'name'        => false,
			'multiple'    => false,
			'class'       => false,
			'wrap'        => false,
			'value'       => false,
			'values'      => false,
			'disabled'    => false,
			'help'        => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

		$values = '';

		if( is_array( $args[ 'values' ] ) ) {

			foreach( $args[ 'values' ] as $key => $val ) {

				if( $args[ 'multiple' ] )
					$selected = in_array( $key, $args[ 'value' ] ) ? 'selected="selected"' : '';
				else
					$selected = ( $key == $args[ 'value' ] ) ? 'selected="selected"' : '';
				
				$icon = $args[ 'icon' ] ? 'data-icon="flag flag-' . strtolower( $key ). '"' : '';

				$values .= '<option value="' . $key . '" ' . $selected . ' ' . $icon . '>' . $val . '</option>';
			}
		} else {

		    $name  = str_replace( [ '{{', '}}' ], '', $args[ 'name' ] );
		    $value = str_replace( [ '{{', '}}' ], '', $args[ 'value' ] );
			
			$icon = $args[ 'icon' ] ? 'data-icon="flag flag-{{lovercase this.value}}"' : '';

			$values = sprintf(
				'{{#each values_%1$s}}' .
					'<option value="{{this.value}}" %3$s {{#ifIn this.value ../%2$s }}selected{{/ifIn}}>{{this.title}}</option>' .
				'{{/each}}',
                $name,
                $value ? $value : $name,
				$icon
			);
		}

		$disabled = '';
		if( $args[ 'disabled' ] === true ) {
			$disabled = 'disabled="disabled"';
		} elseif( ! is_bool( $args[ 'disabled' ] ) ) {
			$disabled = '{{#if ' . $args[ 'disabled' ] . '}}disabled="disabled"{{/if}}';
		}
		
		$label_class = $args[ 'label_class' ] ? $args[ 'label_class' ] : '';
		
		$content = sprintf(
			'<div class="form-group %s">
				%s
				<div class="bootstrap-select %s">
					<select id="%s" name="%s" class="selectpicker" data-width="%s" %s %s>%s</select>
				</div>
				%s
			</div>',
			$args[ 'wrap' ],
//			$args[ 'label' ] ? '<label><span class="select-label">' . $args[ 'label' ]  . '</span></label>' : '',
			$args[ 'label' ] ? '<label class="' . $label_class . '" ><span class="select-label">' . $args[ 'label' ]  . '</span></label>' : '',
			$args[ 'class' ],
			$args[ 'id' ],
			$args[ 'multiple' ] ? $args[ 'name' ] . '[]' : $args[ 'name' ],
			'100%',
			$disabled,
			$args[ 'multiple' ] ? 'multiple="multiple"' : '',
			$values,
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
		);

		return $content;
	}

	public function hidden( $args = [] ) {

		$defaults = [
			'id'    => false,
			'name'  => false,
			'value' => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'value' ] && $args[ 'name' ] ) {
			$args[ 'value' ] = '{{' . $args[ 'name' ] .'}}';
		}

		if( $args[ 'value' ] == 'null' ) $args[ 'value' ] = '';

		$content = sprintf(
			'<input type="hidden" class="ads-field not-hash" id="%s" name="%s" value="%s" />',
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'value' ]
		);

		return $content;
	}

	/**
	 * Render nonce element
	 * @param array $args <p>
	 * name, value (if value not empty will calling wp_create_nonce())
	 * </p>
	 *
	 * @return string
	 */
	public function nonce( $args = [] ) {

		$defaults = [
			'name'  => false,
			'value' => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( $args[ 'value' ] ) {
			$args[ 'value' ] = wp_create_nonce( $args[ 'value' ] );
		} elseif( $args[ 'name' ] ) {
			$args[ 'value' ] = '{{' . $args[ 'name' ] .'}}';
		}

		$content = sprintf(
			'<input type="hidden" id="%1$s" name="%1$s" value="%2$s" />',
			$args[ 'name' ],
			$args[ 'value' ]
		);

		return $content;
	}

	/**
	 * Render button
	 * @param array $args <p>
	 * class, id, name, value
	 * </p>
	 *
	 * @return string
	 */
	public function button( $args = [] ) {

		$defaults = [
			'class'      => 'btn btn-blue',
			'value'      => __( 'Submit', 'dm' ),
			'name'       => false,
			'id'         => false,
			'style'      => false,
			'form_group' => false,
			'title'      => false,
			'wrap'       => false,
		];

		$args = dm_parse_args( $defaults, $args );

		return sprintf(
			'<div class="%s %s"><button class="ads-button %s" id="%s" style="%s" name="%s" %s>%s</button></div>',
			$args[ 'wrap' ] ? $args[ 'wrap' ] : '',
			$args[ 'form_group' ] ? 'form-group' : 'text-right',
			$args[ 'class' ],
			$args[ 'id' ],
			$args[ 'style' ],
			$args[ 'name' ],
            $args[ 'title' ] ? 'title="' . $args[ 'title' ] . '"' : '',
			$args[ 'value' ]
		);
	}

	/**
	 * Render button
	 * @param array $args <p>
	 * class, id, name, value
	 * </p>
	 *
	 * @return string
	 */
	public function linkBtn( $args = [] ) {

		$defaults = [
			'class'      => 'btn btn-blue',
			'value'      => __( 'Submit', 'dm' ),
			'id'         => false,
			'form_group' => false,
			'title'      => false,
			'href'       => false
		];

		$args = dm_parse_args( $defaults, $args );
		
		return sprintf(
			'<div class="%s"><a href="%s" class="ads-button %s" id="%s" %s>%s</a></div>',
			$args[ 'form_group' ] ? 'form-group' : 'text-right',
			$args[ 'href' ],
			$args[ 'class' ],
			$args[ 'id' ],
			$args[ 'title' ] ? 'title="'.$args[ 'title' ].'"':'',
			$args[ 'value' ]
		);

	}

	/**
	 * Render buttons
	 * @param array $args <p>
	 * class, id, name, value
	 * </p>
	 *
	 * @return string
	 */
	public function buttons( $args = [] ) {

		$defaults = [
			'class' => 'btn btn-blue',
			'value' => __( 'Submit', 'dm' ),
			'name'  => false,
			'id'    => false,
			'style' => false
		];

		$foo = [];

		if( isset( $args[ 'data' ] ) && count( $args[ 'data' ] ) ) foreach( $args[ 'data' ] as $arg ) {
			$foo[] = dm_parse_args( $defaults, $arg );
		}

		$content = '';

		if( count($foo) ) {

			$content = '<div class="text-right">';

			foreach ( $foo as $item ) {
				$content .= sprintf(
					'&nbsp;<button class="ads-button %s" id="%s" name="%s" style="%s">%s</button>',
					$item[ 'class' ],
					$item[ 'id' ],
					$item[ 'name' ],
					$item[ 'style' ],
					$item[ 'value' ]
				);
			}

			$content .= '</div>';
		}

		return $content;
	}

	public function daterangepicker( $args = [] ) {

		$defaults = [
			'class' => false,
			'id'    => false
		];

		$args = dm_parse_args( $defaults, $args );

		return sprintf(
			'<button type="button" class="btn btn-default daterange-predefined %s" 
				data-ads_from="#date-from" data-ads_to="#date-to" id="%s">
                <i class="fa fa-calendar position-left"></i> <span></span> <b class="caret"></b>
            </button>',
			$args[ 'class' ], $args[ 'id' ]
		);
	}

	public function progressbar( $args = [] ) {

		$defaults = [
			'id'    => false,
			'class' => false,
			'label' => false
		];

		$args = dm_parse_args( $defaults, $args );

		return sprintf(
			'<div id="%s" class="progress-container %s">
				<div class="progress-label text-center">%s <span class="progress-percent">0</span>&percnt;</div>
				<div class="progress"><div class="progress-bar"></div></div>
			</div>',
			$args[ 'id' ],
			$args[ 'class' ],
			$args[ 'label' ] ? $args[ 'label' ] : __( 'Progress', 'dm' )
		);
	}

	/**
	 * Render color element
	 * @param array $args <p>
	 * label, class, id, name, value, disabled, help
	 * </p>
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function colorpicker( $args = [] ) {

		$defaults = [
			'label'       => false,
			'class'       => 'form-control',
			'id'          => false,
			'name'        => false,
			'value'       => false,
			'placeholder' => false,
			'disabled'    => false,
			'help'        => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

		if( ! $args[ 'value' ] && $args[ 'name' ] ) {
			$args[ 'value' ] = '{{' . $args[ 'name' ] .'}}';
		}

		if( $args[ 'value' ] == 'null' ) $args[ 'value' ] = '';

		$content = sprintf(
			'<div class="form-group colorpicker-box"><input type="text" class="colorpicker %s" id="%s" name="%s" value="%s" %s>%s %s</div>',
			$args[ 'class' ],
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'value' ],
			$args[ 'disabled' ] ? 'disabled="disabled"' : '',
			$args[ 'label' ] ? '<label for="' . $args[ 'id' ] . '">' . $args[ 'label' ] . '</label>' : '',
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
		);

		return $content;
	}
	
	public function uploadImg( $args = [], $crop = false ) {

		$defaults = [
			'label'       => false,
			'class'       => 'form-control',
			'id'          => false,
			'name'        => false,
			'value'       => false,
			'placeholder' => false,
			'disabled'    => false,
			'help'        => false,
			'width'       => false,
			'height'      => false,
			'crop_name'   => false,
		];

		$args = dm_parse_args( $defaults, $args );

		if( ! $args[ 'id' ] && $args[ 'name' ] )
			$args[ 'id' ] = $args[ 'name' ];

		if( ! $args[ 'value' ] && $args[ 'name' ] ) {
			$args[ 'value' ] = '{{' . $args[ 'name' ] .'}}';
		}

		if( $args[ 'value' ] == 'null' ) $args[ 'value' ] = '';

		$crop_name = $args['crop_name'] ? $args['crop_name'] : '';

		$content = sprintf(
			'<div class="form-group uploadImg-box" data-width="%9$s" data-height="%10$s">%1$s
				<div class="form-group image-cropper-container content-group %15$s">
					<img src="%5$s" alt="" class="%14$s preview-upload">
				</div>
				<div class="form-group">
				%8$s
				<button type="button" class="btn btn-green upload_file"><i class="icon-file-upload2"></i><span class="hidden-xs">%11$s</span></button>
				<button type="button" class="btn btn-default remove_file"><i class="icon-cross3"></i><span class="hidden-xs">%12$s</span></button>
				<button type="button" class="btn btn-blue %16$s" style="display: none;"><i class="fa fa-crop"></i><span class="hidden-xs">%13$s</span></button>
			</div>
			<input type="hidden" class="file_url %2$s" id="%3$s" data-crop_name="%17$s" name="%4$s" value="%5$s" placeholder="%6$s" %7$s/></div>',
			$args[ 'label' ] ? '<label for="' . $args[ 'id' ] . '">' . $args[ 'label' ] .'</label>' : '',
			$args[ 'class' ],
			$args[ 'id' ],
			$args[ 'name' ],
			$args[ 'value' ],
			$args[ 'placeholder' ],
			$args[ 'disabled' ] ? 'disabled="disabled"' : '',
			$args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : '',
			$args['width'],
			$args['height'],
			__( 'Upload', 'dm' ),
			__( 'Remove', 'dm' ),
			__( 'Crop', 'dm' ),
			$crop ? 'cropper' : '',
			$args[ 'value' ] ? 'active': '',
			$crop ? 'crop_file' : '',
			$crop_name
		);

		return $content;
	}

	public function uploadImgCrop( $args = [] ) {
		
		return $this->uploadImg( $args, true );
	}

    public function icon( $args = [] ) {

        $defaults = [
            'label'       => false,
            'class'       => 'form-control',
            'id'          => false,
            'name'        => false,
            'value'       => false,
            'placeholder' => false,
            'disabled'    => false,
            'help'        => false,
        ];

        $args = dm_parse_args( $defaults, $args );

        if( ! $args[ 'id' ] && $args[ 'name' ] )
            $args[ 'id' ] = $args[ 'name' ];

        if( ! $args[ 'value' ] && $args[ 'name' ] ) {
            $args[ 'value' ] = '{{' . $args[ 'name' ] .'}}';
        }

        if( $args[ 'value' ] == 'null' ) $args[ 'value' ] = '';

        $content = sprintf(
            '<div class="form-group select-icon">%6$s
                <div class="input-group">
                    <input data-placement="bottomRight" class="%4$s icp icp-auto" value="%2$s" name="%3$s" type="text" />
                    <span class="input-group-addon"></span>
                </div>%7$s
			</div>',
            $args[ 'id' ],
            $args[ 'value' ],
            $args[ 'name' ],
            $args[ 'class' ],
            $args[ 'disabled' ] ? 'disabled="disabled"' : '',
            $args[ 'label' ] ? '<label for="' . $args[ 'id' ] . '">' . $args[ 'label' ] . '</label>' : '',
            $args[ 'help' ] ? '<span class="help-block">' . $args[ 'help' ] . '</span>' : ''
        );

        return $content;
    }

    public function tooltip( $text = '' ) {
		
        return sprintf( '<i style="margin-left:5px" class="fa fa-question-circle" data-placement="auto" data-toggle="tooltip" data-original-title="%1$s" ></i>', $text );
    }
}