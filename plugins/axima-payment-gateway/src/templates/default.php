<?php include __DIR__ . '/includes/menu.php'; ?>

<div class="page-header">
	<h1><?= __('Dashboard', 'axima-payment-gateway') ?></h1>
</div>

<div class="col-sm-4">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b><?= __('Total payments paid/initiated', 'axima-payment-gateway') ?></b></td>
					<td><span class="pull-right"><?= $totalPaid ?> / <?= $totalInitiated ?> (<?= $totalInitiated == 0 ? 100 : number_format(100 * $totalPaid / $totalInitiated) ?>%)</span></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><b><?= __('Last week payments paid/initiated', 'axima-payment-gateway') ?></b></td>
					<td><span class="pull-right"><?= $lastWeekPaid ?> / <?= $lastWeekInitiated ?> (<?= $lastWeekInitiated == 0 ? 100 : number_format(100 * $lastWeekPaid / $lastWeekInitiated) ?>%)</span></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="col-sm-4">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<td><b><?= __('Total errors', 'axima-payment-gateway') ?></b></td>
					<td><span class="pull-right"><?= $totalErrors ?> (<?= ($totalInitiated + $totalErrors) == 0 ? 0 : number_format(100 * $totalErrors / ($totalInitiated + $totalErrors)) ?>%)</span></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><b><?= __('Last week errors', 'axima-payment-gateway') ?></b></td>
					<td><span class="pull-right"><?= $lastWeekErrors ?> (<?= ($lastWeekInitiated + $lastWeekErrors) == 0 ? 0 : number_format(100 * $lastWeekErrors / ($lastWeekInitiated + $lastWeekErrors)) ?>%)</span></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

