<?php
	$name = WTBP_WP_PLUGIN_NAME;
?>
<html>
	<head>
		<title><?php esc_html_e( $name ); ?></title>
		<style type="text/css">
			.wtbpDeletePage {
				position: fixed;
				margin-left: 40%;
				margin-right: auto;
				text-align: center;
				background-color: #fdf5ce;
				padding: 10px;
				margin-top: 10%;
			}
		</style>
	</head>
	<body>
	<div class="wtbpDeletePage">
	<div><?php esc_html_e( $name ); ?></div>
	<?php HtmlWtbp::formStart('deactivatePlugin', array('action' => $this->REQUEST_URI, 'method' => $this->REQUEST_METHOD)); ?>
	<?php
	$formData = array();
	switch ($this->REQUEST_METHOD) {
		case 'GET':
			$formData = $this->GET;
			break;
		case 'POST':
			$formData = $this->POST;
			break;
	}
	foreach ($formData as $key => $val) {
		if (is_array($val)) {
			foreach ($val as $subKey => $subVal) {
				HtmlWtbp::hidden($key . '[' . $subKey . ']', array('value' => $subVal));
			}
		} else {
			HtmlWtbp::hidden($key, array('value' => $val));
		}
	}
	?>
		<table width="100%">
			<tr>
				<td><?php esc_html_e('Delete Plugin Data (options, setup data, database tables, etc.)', 'woo-product-tables'); ?>:</td>
				<td><?php HtmlWtbp::radiobuttons('deleteOptions', array('options' => array('No', 'Yes'))); ?></td>
			</tr>
		</table>
	<?php HtmlWtbp::submit('toeGo', array('value' => __('Done', 'woo-product-tables'))); ?>
	<?php HtmlWtbp::formEnd(); ?>
	</div>
</body>
</html>
