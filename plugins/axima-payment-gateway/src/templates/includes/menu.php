<ul class="nav nav-tabs" style="margin-top: 25px;">
	<li<?php if ($page === 'default'): ?> class="active"<?php endif; ?>>
		<a href="?page=<?= $domain ?>"><?= __('Dashboard', 'axima-payment-gateway') ?></a>
	</li>
	<li<?php if ($page === 'logs'): ?> class="active"<?php endif; ?>>
		<a href="?page=<?= $domain ?>&payspage=logs"><?= __('Logs', 'axima-payment-gateway') ?></a>
	</li>
	<li<?php if ($page === 'settings'): ?> class="active"<?php endif; ?>>
		<a href="?page=<?= $domain ?>&payspage=settings"><?= __('Settings', 'axima-payment-gateway') ?></a>
	</li>
</ul>
