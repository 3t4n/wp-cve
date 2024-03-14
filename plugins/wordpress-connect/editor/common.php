<?php

function get_default( $key, $default ){

	if ( !isset( $_GET[ $key ] ) ){ return $default; }

	if ( !empty( $_GET[ $key ] ) ){
        $value = htmlspecialchars( $_GET[ $key ], ENT_QUOTES, 'UTF-8' );
        return filter_var( $value, FILTER_SANITIZE_STRING );
	}
	return $default;
}

function print_select( $name, $options, $selected_value ){
?>
				<select name="<?php echo $name; ?>" id="param_<?php echo $name; ?>">
<?php
					foreach( $options as $value ) :
						$selected_class = ( $value == $selected_value ) ? ' selected="selectd"' : '';
					?>
						<option value="<?php echo $value; ?>"<?php echo $selected_class; ?>><?php echo $value; ?></option>
					<?php endforeach;
?>
				</select>
<?php
}

function media_send_to_editor( $html ) {
?>
		<script type="text/javascript">
			/* <![CDATA[ */
			var win = window.dialogArguments || opener || parent || top;
			win.send_to_editor('<?php echo addslashes( $html ); ?>');
			/* ]]> */
		</script>
<?php
}