<div>✓ Successfully synced <?= $details['successes']; ?> reviews</div>
<?php foreach ($details['errors'] as $error): ?>
<div class="webwinkelkeur-error"><?= htmlentities($error); ?></div>
<?php endforeach; ?>