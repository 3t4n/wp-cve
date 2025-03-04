<?php use Dev4Press\Plugin\GDPOL\Basic\InstallDB; ?>

<div class="d4p-install-block">
    <h4>
		<?php esc_html_e( 'Additional database tables', 'gd-topic-polls' ); ?>
    </h4>
    <div>
		<?php

		$db = InstallDB::instance();

		$list_db = $db->install();

		if ( ! empty( $list_db ) ) {
			echo '<h5>' . esc_html__( 'Database Upgrade Notices', 'gd-topic-polls' ) . '</h5>';
			echo join( '<br/>', $list_db );
		}

		echo '<h5>' . esc_html__( 'Database Tables Check', 'gd-topic-polls' ) . '</h5>';
		$check = $db->check();

		$msg = array();
		foreach ( $check as $table => $data ) {
			if ( $data['status'] == 'error' ) {
				$_proceed  = false;
				$_error_db = true;
				$msg[]     = '<span class="gdpc-error">[' . esc_html__( 'ERROR', 'gd-topic-polls' ) . '] - <strong>' . $table . '</strong>: ' . $data['msg'] . '</span>';
			} else {
				$msg[] = '<span class="gdpc-ok">[' . esc_html__( 'OK', 'gd-topic-polls' ) . '] - <strong>' . $table . '</strong></span>';
			}
		}

		echo join( '<br/>', $msg );
		?>
    </div>
</div>