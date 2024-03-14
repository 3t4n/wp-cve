<div class="totalcontest-box-actions">
	<?php foreach ( $actions as $actionId => $action ): ?>
        <a href="<?php echo esc_attr( $action['url'] ); ?>" class="totalcontest-box-action">
            <div class="totalcontest-box-action-icon">
                <span class="dashicons dashicons-<?php echo esc_attr( $action['icon'] ); ?>"></span>
            </div>
            <div class="totalcontest-box-action-name">
				<?php echo esc_html( $action['label'] ); ?>
            </div>
        </a>
	<?php endforeach; ?>
</div>