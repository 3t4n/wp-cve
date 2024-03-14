<!doctype html>
	<html>
	<head>
		<title><?php esc_html_e( 'Last response - Stock Sync Pro', 'woo-stock-sync' ); ?></title>
		<style>
			* {
				box-sizing: border-box;
			}

			table {
				max-width: 100%;
				width: 100%;
			}

			th, td {
				padding: 5px 10px;
				text-align: left;
			}

			th {
				white-space: nowrap;
				vertical-align: text-top;
				width: 1%;
			}

			td {
				overflow-wrap: break-word;
			}

			td.no-padding {
				padding: 0;

			}

			td.no-padding table td {
				overflow-wrap: anywhere;
				white-space: pre-wrap;
			}

			pre {
				width: 100%;
				background: #f1f3f5;
				border: 1px solid #dee2e6;
				padding: 20px;
				white-space: pre-wrap;
				margin: 0;
				overflow-wrap: anywhere;
			}
		</style>
	</head>
	<body>
		<table>
			<tr>
				<th><?php esc_html_e( 'Response code', 'woo-stock-sync' ); ?></th>
				<td><?php echo esc_html( $code ); ?></td>
			</tr>
			<?php if ( $headers ) { ?>
				<tr>
					<th><?php esc_html_e( 'Headers', 'woo-stock-sync' ); ?></th>
					<td class="no-padding">
						<table>
							<?php foreach ( $headers as $key => $value ) { ?>
								<tr>
									<th><?php echo esc_html( $key ); ?></th>
									<td><?php echo esc_html( $value ); ?></td>
								</tr>
							<?php } ?>
						</table>
					</td>
				</tr>
			<?php } ?>
			<tr>
				<th><?php esc_html_e( 'Response body', 'woo-stock-sync' ); ?></th>
				<td><pre><?php echo esc_html( $body ); ?></pre></td>
			</tr>
		</table>
	</body>
</html>
