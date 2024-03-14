<?php
$data = array();
$data['trigger'] 	= ( ! empty( $trigger ) ? 'true' : 'false' );
$data['typespeed'] 	= ( ! empty( $typespeed ) ? $typespeed : 0 );
$data['startdelay'] = ( ! empty( $startdelay ) ? $startdelay : 0 );
$data['backspeed'] 	= ( ! empty( $backspeed ) ? $backspeed : 0 );
$data['backdelay'] 	= ( ! empty( $backdelay ) ? $backdelay : 0 );
$data['loop'] 		= ( ! empty( $loop ) ? 'true' : 'false' );
$data['loopcount'] 	= ( ! empty( $loopcount ) ? 'true' : 'false' );
$data['showcursor'] = ( ! empty( $showcursor ) ? 'true' : 'false' );
$data['cursorchar'] = ( ! empty( $cursorchar ) ? $cursorchar : '|' );
$data['cursortime'] = ( ! empty( $cursortime ) ? $cursortime : '|' );
?>
<div class="lrw-word-typed">
	<div class="lrw-word-typed-wrapper typed-align-<?php echo $align; ?>" <?php foreach( $data as $name => $val ) echo 'data-' . $name . '="' . $val . '" ' ?>>
        <<?php echo $tag; ?> class="typed-tag"><span id="lrw-typed" data-strings="<?php echo esc_attr( preg_replace( '/\r|\n/', ',' , $strings ) ); ?>" style="white-space:pre;"></span></<?php echo $tag; ?>>
	</div>
</div>