<?php

class WPPP_Disable_Widgets_Advanced extends WPPP_Admin_Renderer {

	private $dberror = null;
	private $dberrortype = null;
	private $dbstate = null;

	public function add_help_tab () {
		$screen = get_current_screen();
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_disable_widgets',
			'title'	=>	__( 'Overview', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "Disable WordPress' default widgets.", 'wp-performance-pack' ) . '</p>',
		) );
	}

	public function e_switchButtonDsableWidget( $opt_name, $value, $disabled = false, $label = '' ) {
	?>
		<label for="<?php echo $opt_name . $value . '_id'; ?>">
			<input id="<?php echo $opt_name . $value . '_id'; ?>" type="checkbox" value="<?php echo $value; ?>" <?php echo ( !in_array( $value, $this->wppp->options[ $opt_name ] ) ) ? 'checked="checked"' : ''; ?> class="switchButton" <?php $this->e_array_opt_name( $opt_name ); ?> <?php echo ( $disabled === true ) ? 'disabled="true"' : ''; ?> /><?php if ( !empty( $label) ) echo $label; else _e( 'enabled', 'wp-performance-pack' );?>
		</label>
	<?php
	}


	public function render_options () {
	?>
		<style>.form-table th, .form-table td { padding : 10px 10px }</style>
		<h3 class="title"><?php _e( "WordPress' default widgets", 'wp-performance-pack' );?></h3>
		<p><strong>Really</strong> disable WordPress' default widgets. This feature is still in beta so be careful. Please report any issues in the support forums.</p>

		<table class="form-table" style="clear:none">
			<?php
			// First get all possible widget classes from files inside "wp-include/widgets"
			$files = glob( ABSPATH . WPINC . '/widgets/*.php' );					
			$classes = array();
			foreach ( $files as $file ) {					
				$classes[] = $this->get_first_class_from_file( $file );
			}
					
			// Now sort classes into a tree with classes extending "WP_Widget" as root					
			// Leafs of the tree are assumed widgets that can be enabled/disabled
			$refs = array();
			$tree = array();
			foreach ( $classes as &$c ) {
				if ( !isset( $refs[ $c[ 'name' ] ] ) ) {
					$refs[ $c[ 'name' ] ] = &$c;
				} else {
					$refs[ $c[ 'name' ] ] = array_merge( $refs[ $c[ 'name' ] ], $c );
					$c = &$refs[ $c[ 'name' ] ];
				}
				
				if ( $c[ 'extends' ] === 'WP_Widget' ) {
					$tree[ $c[ 'name' ] ] = &$c;
				} else {
					$refs[ $c[ 'extends' ] ][ 'children' ][ $c[ 'name' ] ] = &$c;
				}
			}
					
			// Build list of widgets 'widget name' => 'file1', 'file2', ...
			// Files are to be included in the given order
			$widgets = $this->build_widget_list( $tree );
			$this->wppp->update_option( 'wppp_known_default_widgets', $widgets );
			foreach( $widgets as $classname => $files ) {
				foreach( $files as $file ) {
					include_once( ABSPATH . WPINC . '/widgets/' . $file );
				}
				$w = new $classname;
				echo '<tr><th>' . $w->name . '</th><td>';
				$this->e_switchButtonDsableWidget( 'disabled_widgets', $classname );
				echo '</td>';
			}
			?>
		</table>
	<?php
	}
	
	private function build_widget_list( $widgets ) {
		$res = array();
		foreach( $widgets as $widget => $data ) {
			if ( isset( $data[ 'children' ] ) ) {
				$res2 = $this->build_widget_list( $data[ 'children' ] );
				foreach ( $res2 as $w => &$f ) {
					array_unshift( $f, $data[ 'file' ] );
				}
				$res = array_merge( $res2, $res );
			} else {
				$res[ $widget ] = array( $data[ 'file' ] );
			}
		}
		return $res;
	}
	
	private function get_first_class_from_file( $file ) {
		// based on https://stackoverflow.com/questions/7153000/get-class-name-from-file/44654073
		$fp = fopen($file, 'r');
		$class = $namespace = $buffer = $extends = '';
		$i = 0;
		while (!$class) {
			if (feof($fp)) break;

			$buffer .= fread($fp, 512);
			$tokens = token_get_all($buffer);

			if (strpos($buffer, '{') === false) continue;

			for (;$i<count($tokens);$i++) {
				if ($tokens[$i][0] === T_NAMESPACE) {
					for ($j=$i+1;$j<count($tokens); $j++) {
						if ($tokens[$j][0] === T_STRING) {
							$namespace .= '\\'.$tokens[$j][1];
						} else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
							break;
						}
					}
				}

				if ( $tokens[ $i ][ 0 ] === T_CLASS) {
					for ( $j = $i + 1; $j < count( $tokens ); $j++ ) {
						if ( $tokens[ $j ][ 0 ] === T_EXTENDS ) {
							for ( $k = $j + 1; $k < count( $tokens ); $k++ ) {
								if ( $tokens[ $k ] === '{') {
									$extends = $tokens[ $j + 2 ][ 1 ];
								}
							}
						}
						if ( $tokens[ $j ] === '{') {
							$class = $tokens[ $i + 2 ][ 1 ];
						}
					}
				}
			}
		}
		return( array( 'name' => $class, 'namespace' => $namespace, 'extends' => $extends, 'file' => wp_basename( $file ) ) );
	}
}