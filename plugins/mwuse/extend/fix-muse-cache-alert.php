<?php

if( !function_exists( 'mtw_debug_alert_cache' ) )
{
	function mtw_init_debug_alert_cache()
	{
		?>
		<script type="text/javascript">
		function mtw_no_alert(f)
		{
			
		}
		</script>
		<?php
	}
	add_action( 'wp_head', 'mtw_init_debug_alert_cache' );

	function mtw_debug_alert_cache ( $html )
	{
		$html = str_replace("alert(f)", "mtw_no_alert(f)", $html );
		$html = str_replace("alert(g)", "mtw_no_alert(g)", $html );		
		return $html;
		
	}
	add_action( 'muse_footer_html_filter', 'mtw_debug_alert_cache', 10, 2 );
}

?>