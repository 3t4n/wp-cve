<div class="acf-box">
	<div class="inner">
		<h2><?php echo esc_html(ACF_CT_PLUGIN_NAME); ?></h2>
		<p>An extension for Advanced Custom Fields plugin which lets you save custom fields data in a custom database table.</p>
		<h3>Resources</h3>
		<ul>
            <?php if (ACF_CT_FREE_PLUGIN === true): ?>
            <li>
                <a href="https://acf-custom-tables.abhisheksatre.com/pro/?ref=plugin-sb" target="_blank">Upgrade to PRO</a>
            </li>
            <?php endif; ?>
			<li>
				<a href="https://acf-custom-tables.abhisheksatre.com/docs/create-custom-table/?ref=plugin-sb" target="_blank">Documentation</a>
			</li>
			<li>
				<a href="https://acf-custom-tables.abhisheksatre.com/?ref=plugin-sb" target="_blank">Website</a>
			</li>
			<li>
				<a href="<?php echo esc_url('mailto:hi@abhisheksatre.com?Subject=Report Bug - ' . ACF_CT_PLUGIN_NAME); ?>" target="_blank">Report a bug</a>
			</li>
		</ul>
	</div>
    <div class="footer">
        <i style="color: #666;">Version: <?php echo esc_html(ACF_CT_VERSION); ?></i>
    </div>
</div>
