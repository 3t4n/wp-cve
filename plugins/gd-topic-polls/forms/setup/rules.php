<?php

use Dev4Press\v43\Core\Quick\WPR;

?>
<div class="d4p-install-block">
    <h4>
		<?php esc_html_e( 'Permalinks rewrite rules', 'gd-topic-polls' ); ?>
    </h4>
    <div>
		<?php WPR::flush_rewrite_rules(); ?>
		<?php esc_html_e( 'Rewrite rules flushed.', 'gd-topic-polls' ); ?>
    </div>
</div>
