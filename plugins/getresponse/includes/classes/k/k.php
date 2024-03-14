<?php
/**
 * The k framework
 * 
 * @author Nabil Kadimi <nabil@kadimi.com>
 * @version 1.0.4
 * @package k_framework
 */
class K {

	/**
	 * Gets a variable 
	 */
	static function get_var( $name, $array = null, $default = null ) {

		if( is_null( $array ) ) {
			$array = $GLOBALS;
		}
		if( is_array( $array ) && array_key_exists( $name, $array ) ) {
			return $array[ $name ];
		} else {
			return $default;
		}
	}

	/**
	 * Prints or returns an input field
	 */
	static function input( $name ) {

		// $params		
		if( func_num_args() > 1 ) {
			$params = func_get_arg(1);
		}
		if( empty( $params ) ) {
			$params = array();
		}

		// $args
		if( func_num_args() > 2 ) {
			$args = func_get_arg(2);
		}
		if( empty( $args ) ) {
			$args = array();
		}

		// Load defaults		
		$params += array(
			'type' => 'text',
			'id' => '',
			'value' => ''
		);

		// Add name
		$params[ 'name' ] = $name;

		// Build the input field html
		$input = sprintf( '<input %s/>', K::params_str( $params ) );

		// Format
		if( ! empty ( $args[ 'format' ] ) ) {
			$input = str_replace(
				array( ':input', ':name', ':id', ':value' ),
				array( $input, $name, $params[ 'id'], $params[ 'value'] ),
				$args[ 'format' ]
			);
		}

		// Add default color picker
		if(
			! empty ( $args[ 'colorpicker' ] )
			|| (
				'text' === $params[ 'type' ]
				&& preg_match( '/_color\]?$/', $name) 
				&& empty( $args[ 'nocolorpicker' ] ) 
			)
		) {
			ob_start();
			$input .= ob_get_clean();
		}

		// Print or return the input field HTML
		if( ! empty( $args[ 'return' ] ) ) {
			return $input;
		} else {
			echo $input;
		}
	}

	/**
	 * Prints or returns an input field
	 */
	static function textarea( $name ) {

		// $params
		if( func_num_args() > 1 ) {
			$params = func_get_arg(1);
		}
		if( ! is_array( $params ) ) {
			$params = array();
		}

		// $args
		if( func_num_args() > 2 ) {
			$args = func_get_arg(2);
		}
		if( ! is_array( $args ) ) {
			$args = array();
		}

		// Load defaults
		$params += array(
			'id' => '',
		);

		// Add name
		$params[ 'name' ] = $name;

		// Set $value
		$value = empty( $args[ 'value' ] ) ? '' : $args[ 'value' ];

		// Build textarea html
		if( K::get_var( 'editor', $args ) ) {
			// Remove the name since it's attached to the editor
			$params_for_editor = $params;
			$name = ( $params[ 'name' ] );
			$placeholder = 'Get the latest content first.';
			unset( $params_for_editor[ 'name' ] );

			// Build
			ob_start();
			wp_enqueue_style('fca_eoi_wysi_css', FCA_EOI_PLUGIN_URL . '/assets/vendor/wysi/wysi.min.css', array(), FCA_EOI_VER );		
			wp_enqueue_script('fca_eoi_wysi_js_main', FCA_EOI_PLUGIN_URL . '/assets/vendor/wysi/wysihtml.min.js', array(), FCA_EOI_VER, true );		
			wp_enqueue_script('fca_eoi_wysi_js', FCA_EOI_PLUGIN_URL . '/assets/vendor/wysi/wysi.js', array( 'jquery', 'fca_eoi_wysi_js_main' ), FCA_EOI_VER, true );		
			

			$admin_data = array (
				'stylesheet' => FCA_EOI_PLUGIN_URL . '/assets/vendor/wysi/wysi.min.css',
				'editor' => 'full'
			);

			wp_localize_script( 'fca_eoi_wysi_js', 'fcaEoiAdminData', $admin_data );

			$html = '';
			$html .= "<div class='fca-wysiwyg-nav' style='display:none'>";
				$html .= '<div class="fca-wysiwyg-group fca-wysiwyg-text-group">';
					$html .= '<button type="button" data-wysihtml5-command="bold" class="fca-nav-bold fca-nav-rounded-left" ><span class="dashicons dashicons-editor-bold"></span></button>';
					$html .= '<button type="button" data-wysihtml5-command="italic" class="fca-nav-italic fca-nav-no-border" ><span class="dashicons dashicons-editor-italic"></span></button>';
					$html .= '<button type="button" data-wysihtml5-command="underline" class="fca-nav-underline fca-nav-rounded-right" ><span class="dashicons dashicons-editor-underline"></span></button>';
				$html .= "</div>";
				$html .= '<div class="fca-wysiwyg-group fca-wysiwyg-alignment-group">';
					$html .= '<button type="button" data-wysihtml5-command="justifyLeft" class="fca-nav-justifyLeft fca-nav-rounded-left" ><span class="dashicons dashicons-editor-alignleft"></span></button>';
					$html .= '<button type="button" data-wysihtml5-command="justifyCenter" class="fca-nav-justifyCenter fca-nav-no-border" ><span class="dashicons dashicons-editor-aligncenter"></span></button>';
					$html .= '<button type="button" data-wysihtml5-command="justifyRight" class="fca-nav-justifyRight fca-nav-rounded-right" ><span class="dashicons dashicons-editor-alignright"></span></button>';
				$html .= "</div>";
				
				$html .= '<div class="fca-wysiwyg-group fca-wysiwyg-link-group">';
					$html .= '<button type="button" data-wysihtml5-command="createLink" style="border-right: 0;" class="fca-wysiwyg-link-group fca-nav-rounded-left"><span class="dashicons dashicons-admin-links"></span></button>';
					$html .= '<button type="button" data-wysihtml5-command="unlink" class="fca-wysiwyg-link-group fca-nav-rounded-right"><span class="dashicons dashicons-editor-unlink"></span></button>';
				$html .= "</div>";

				$html .= '<div class="fca-wysiwyg-group fca-wysiwyg-image-group">';
					$html .= '<button type="button" class="fca-wysiwyg-insert-image" data-wysihtml5-command="insertImage"><span class="dashicons dashicons-format-image"></span></button>';
				$html .= "</div>";
				
				$html .= '<div class="fca-wysiwyg-url-dialog" data-wysihtml5-dialog="createLink" style="display: none">';
					$html .= '<input data-wysihtml5-dialog-field="href" value="http://">';
					$html .= '<a class="button button-secondary" data-wysihtml5-dialog-action="cancel">' . __('Cancel', 'fca-eoi') . '</a>';
					$html .= '<a class="button button-primary" data-wysihtml5-dialog-action="save">' . __('OK', 'fca-eoi') . '</a>';
				$html .= "</div>";

				$html .= '<button class="fca-wysiwyg-view-html action" type="button" data-wysihtml5-action="change_view">HTML</button>';
		
			$html .= "</div>";
			$html .= "<textarea class='fca-wysiwyg-html fca-eoi-input-wysi $name' name='$name' placeholder='$placeholder'>$value</textarea>";
			echo $html;

			$textarea = ob_get_clean();
			$textarea = sprintf( '<div %s>%s</div>', K::params_str( $params_for_editor ), $textarea );
		} else {
			$textarea = sprintf( '<textarea %s>%s</textarea>', K::params_str( $params ), $value );
		}

		// Format
		if( ! empty ( $args[ 'format' ] ) ) {
			$textarea = str_replace(
				array( ':textarea', ':value', ':name', ':id' ),
				array( $textarea, $value, $name, $params[ 'id' ] ),
				$args[ 'format' ]
			);
		}

		// Print or return the textarea field HTML
		if( ! empty( $args[ 'return' ] ) ) {
			return $textarea;
		} else {
			echo $textarea;
		}
	}

	/**
	 * Prints or returns an dropdown select
	 */
	static function select( $name ) {

		// $params		
		if( func_num_args() > 1 ) {
			$params = func_get_arg(1);
		}
		if( empty( $params ) ) {
			$params = array();
		}
		// Load defaults		
		$params += array(
			'id' => '',
		);

		// Sanitize $params[multiple], and Add brackets if the former is true
		if( ! empty( $params[ 'multiple' ] ) ) {
			$params[ 'multiple' ] = 'multiple';
			$name .= '[]';
		}

		// Add name
		$params[ 'name' ] = $name;

		// $args
		if( func_num_args() > 2 ) {
			$args = func_get_arg(2);
		}
		if( empty( $args ) ) {
			$args = array();
		}
		$args += array(
			'default' => '',
			'options' => array(),
			'html_before' => '',
			'html_after' => '',
			'selected' => '',
		);

		// Make 'selected' an array
		if( $selected = $args[ 'selected' ] ) {
			if( ! is_array( $selected ) ) {
				$selected = array( $selected );
			}
		}

		// Use 'default' if 'selected' is empty
		if( ! $selected ) {
			$selected = array( $args[ 'default' ] );
		}

		// Build options
		$options = '';
		foreach ( $args[ 'options' ] as $value => $label ) {
			$options .= K::wrap(
				$label
				, array(
					'value' => $value,
					'selected' => ( in_array( $value, $selected ) ) 
						? 'selected'
						: null
					,
				)
				, array(
					'in' => 'option',
					'return' => true,
				)
			);
		}

		// Build the input field html
		$select = sprintf( '%s<select %s>%s</select>%s', $args[ 'html_before' ], K::params_str( $params ), $options, $args[ 'html_after' ] );

		// Format
		if( ! empty ( $args[ 'format' ] ) ) {
			$select = str_replace(
				array( ':select', ':name', ':id' ),
				array( $select, $name, $params[ 'id'] ),
				$args[ 'format' ]
			);
		}

		// Print or return the input field HTML
		if( ! empty( $args[ 'return' ] ) ) {
			return $select;
		} else {
			echo $select;
		}
	}

	/**
	 * Prints or returns an input field
	 * 
	 * @param array $controls The array of controls
	 */
	static function fieldset( $legend, $controls = array() ) {

		// $params		
		if( func_num_args() > 2 ) {
			$params = func_get_arg(2);
		}
		if( empty( $params ) ) {
			$params = array();
		}

		// $args
		if( func_num_args() > 3 ) {
			$args = func_get_arg(3);
		}
		if( empty( $args ) ) {
			$args = array();
		}

		// Inner HTML placeholder
		$innerHTML = '';

		// Put controls in placehoder
		foreach( $controls as $control ) {

			// Fill params if needed
			$control[2] = ! empty( $control[2] ) ? $control[2] : array();

			// Set $args['return'] to false
			if( empty( $control[3] ) ) {
				$control[3] = array();
			}
			$control[3][ 'return' ] = true;

			// Get control HTML
			$innerHTML .= call_user_func(
				/* Callback */     'K::' . $control[0],
				/* Name/content */ $control[1],
				/* Params*/        $control[2],
				/* Args */         $control[3]
			);
		}

		// Prepare HTML
		$HTML = str_replace(
			array( ':legend', ':controls', ':parameters' ),
			array( 
				! empty( $legend ) ? $legend : '',
				$innerHTML,
				K::params_str( $params )
			),
			'<fieldset :parameters><legend>:legend</legend>:controls</fieldset>'
		);

		// Print or return the input field HTML
		if( ! empty ( $args[ 'return' ] ) ) {
			return $HTML;
		} else {
			echo $HTML;
		}
	}

	/**
	 * Wraps given input in an html tag
	 */
	static function wrap( $content = '' ) {

		// $params		
		if( func_num_args() > 1 ) {
			$params = func_get_arg(1);
		}
		if( empty( $params ) ) {
			$params = array();
		}

		// $args
		if( func_num_args() > 2 ) {
			$args = func_get_arg(2);
		}
		if( empty( $args ) ) {
			$args = array();
		}
		$args += array(
			'in' => 'div',
			'html_before' => '',
			'html_after' => '',
		);

		// Build the input field html
		$html = sprintf( '%s<%s %s>%s</%s>%s',
			$args[ 'html_before' ],
			$args[ 'in' ],
			K::params_str( $params ),
			$content,
			$args[ 'in' ],
			$args[ 'html_after' ]
		);

		// Print or return the input field HTML
		if( ! empty( $args[ 'return' ] ) ) {
			return $html;
		} else {
			echo $html;
		}
	}

	/**
	 * Prepares an array of params and their values to be added to an html element
	 */
	static function params_str( $params ) {
		$params_str = '';
		foreach( $params as $parameter => $value ) {
			if( $value ) {
				if( strlen( $value ) ) {
					$params_str .= sprintf( ' %s="%s"', $parameter, esc_attr( $value ) );
					
				}
			}
		}
		return $params_str;
	}
}

add_action( 'in_admin_footer', 'k_scripts' );
function k_scripts() {
	?>
	<style>
		fieldset.k {
			border: solid 1px lightgray;
			margin-bottom: 1em;
			padding-left: .5em;
			padding-right: .5em;
		}
		fieldset.k legend {
			background: white;
			padding: .25em .5em;
			box-shadow: 0 0 3px silver;
			transition: all .5s;
		}
		fieldset.k legend.highlighted {
			background: lightgray;
			box-shadow: 0 0 1px black;
		}
		fieldset.k.expanded legend {
			font-weight: bold;
		}
		fieldset.k.collapsible legend {
			cursor: pointer;
		}
		fieldset.k.collapsed :not(legend) {
			display: none;
		}
		fieldset.k.collapsed {
			display: inline;
			border: none;
		}
	</style>
	<script>
		jQuery( document ).ready( function( $ ) {
			$( '.collapsible legend' ).click( function (){
				$this = $( this );
				$fieldset = $this.parent();
				// Collapse all except target
				$fieldset.parent()
					.find( 'fieldset' )
					.not( $fieldset )
					.removeClass( 'expanded' )
					.addClass( 'collapsed' )
					.trigger( 'collapse' );
				// Toggle target
				$fieldset.toggleClass( 'collapsed' );
				// Add expanded class to non-collpased and vice-versa, then trigger events
				if ( $fieldset.hasClass('collapsed') ) {
					$fieldset.removeClass( 'expanded' );
					$fieldset.trigger( 'collapse' );
				} else {
					$fieldset.addClass( 'expanded' );
					$fieldset.trigger( 'expand' );
				}
			} );
		} );
	</script>
	<?php
}

function k_selector( $name, $selected_options = array(), $return = false ) {

	global $post;
	// Dirty fix to restore the global $post
	$post_bak = $post;

	// Get all post types except media
	$post_types = get_post_types( array( 'public' => true ) );
	unset( $post_types[ 'attachment' ] );

	$ret = ob_start();

	// Start ouput
	echo '<select data-placeholder="' . __( 'Type to search for posts, categories or pages.' ) . '" name = "' . $name . '[]" class="select2" multiple="multiple" style="width: 100%;">';

	// Front page
	K::wrap( __( 'Front page' )
		, array(
			'value' => '~',
			'selected' => in_array( '~', $selected_options ),
		)
		, array( 'in' => 'option' )
	);

	foreach ($post_types as $post_type => $post_type_args ) {

		$post_type_obj = get_post_type_object( $post_type );
		$post_type_name = $post_type_obj->labels->singular_name;

		$options = array();

		// Add taxonomy/terms options
		$taxonomies = get_object_taxonomies( $post_type );
		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy_obj = get_taxonomy( $taxonomy );
			$taxonomy_name = $taxonomy_obj->labels->singular_name;
			$terms = get_categories("taxonomy=$taxonomy&type=$post_type"); 
			foreach ($terms as $term) {
				$options[ 'taxonomies' ][ "$post_type:$term->term_id" ] =
					$post_type_name
					. " › $taxonomy_name"
					. " › $term->name"
				;
			}
		}

		// Add posts options
		$the_query = new WP_Query( "post_type=$post_type&posts_per_page=-1" );
		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$options[ 'posts' ][ '#' . get_the_ID() ] = $post_type_name
					. ' ' . __( '›' ) . ' '
					. '#' . get_the_ID() . ' &ndash; '
					. ( get_the_title() ? get_the_title() : __('[Untitled]') )
				;
			}
		}

		// Dirty fix to restore the global $post
		$post = $post_bak;

		// Posts > All
		echo '<optgroup label="' . $post_type_name . '">';
		printf(
			'<option value="%s" %s >%s</option>'
			, $post_type
			, ( in_array( $post_type, $selected_options ) ? 'selected' : '' )
			, $post_type_name . ' ' . ( '›' ) . ' ' . __( 'All' )
		);
		echo '</optgroup>';

		// Posts > Taoxonomies
		if ( ! empty( $options[ 'taxonomies' ] ) ) {
			printf(
				'<optgroup label="%s">'
				, $post_type_name . ' ' . __( '›' ) . ' ' . __( 'Taxonomies' )
			);
			foreach ( $options[ 'taxonomies' ] as $k => $v ) {
				$selected = ( in_array( $k, $selected_options ) ) ? 'selected="selected"' : '';
				printf( '<option value="%s" %s >%s</option>', $k, $selected, $v );
			}
			echo '</optgroup>';
		}

		// Posts > content
		if ( ! empty( $options[ 'posts' ] ) ) {
			printf( '<optgroup label="%s">'
				, $post_type_name . ' ' . __( '›' ) . ' ' . __( 'Content' )
			);
			foreach ( $options[ 'posts' ] as $k => $v ) {
				$selected = ( in_array( $k, $selected_options ) ) ? 'selected="selected"' : '';
				printf( '<option value="%s" %s >%s</option>'
					, $k
					, $selected
					, $v
				);
			}
			echo '</optgroup>';
		}
	}
	echo '</select>';

	$ret = ob_get_clean();

	if ( $return ) {
		return $ret;
	} else {
		echo $ret;
	}
}