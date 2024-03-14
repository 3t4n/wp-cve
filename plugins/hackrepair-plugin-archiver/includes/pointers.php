<?php 

class HackRepair_Plugin_Archiver_Pointer {
	public static $pointers = array();
	public static function enqueue_scripts( $hook_suffix ) {
		self::$pointers = apply_filters( 'hackrepair_plugin_archiver_pointers',     self::$pointers );
		// Check if screen related pointer is registered
		if ( empty( self::$pointers[ $hook_suffix ] ) )
			return;
		$pointers = (array) self::$pointers[ $hook_suffix ];
		// Get dismissed pointers
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		$got_pointers = false;
		foreach ( $pointers as $key => $pointer ) {
			if ( in_array( $key, $dismissed ) )
				continue;
			if ( isset( $pointer['caps'] ) ) {
				foreach ( $pointer['caps'] as $cap ) {
					if ( ! current_user_can( $cap ) )
						continue 2;
				}
			}
			$callback = is_callable( $pointer['callback'] ) ? $pointer['callback'] : array( 'HackRepair_Plugin_Archiver_Pointer', $pointer['callback'] );
			add_action( 'admin_print_footer_scripts', $callback );
			$got_pointers = true;
		}
		if ( ! $got_pointers )
			return;
		// Add pointers script and style to queue
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
	public static function print_js( $pointer_id, $selector, $args ) {
		if ( empty( $pointer_id ) || empty( $selector ) || empty( $args ) || empty( $args['content'] ) )
			return;

		?>
		<script type="text/javascript">
		(function($){
			var options = <?php echo wp_json_encode( $args ); ?>, setup;

			if ( ! options )
				return;

			options = $.extend( options, {
				close: function() {
					$.post( ajaxurl, {
						pointer: '<?php echo $pointer_id; ?>',
						action: 'dismiss-wp-pointer'
					});
				}
			});

			setup = function() {
				$('<?php echo $selector; ?>').first().pointer( options ).pointer('open');
			};

			if ( options.position && options.position.defer_loading )
				$(window).bind( 'load.wp-pointers', setup );
			else
				$(document).ready( setup );

		})( jQuery );
		</script>
		<?php
	}
}