<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Check for permissions
if ( !current_user_can( 'edit_shop_orders' ) ) {
  wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title><?php _e('Invoice preview', 'wc-szamlazz'); ?></title>
		<link rel="stylesheet" href="<?php echo esc_url(WC_Szamlazz()::$plugin_url); ?>assets/css/preview.css" media="all">
	</head>
	<body>

		<div class="sheet">

			<?php if($invoice_xml['error']): ?>
				<?php if(!empty($invoice_xml['messages'])): ?>
					<?php foreach ($invoice_xml['messages'] as $message): ?>
						<p><?php echo esc_html($message); ?></p>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php else: ?>

				<div class="header">
					<div class="logo">logo</div>
					<div class="seller">
						<strong><?php _e('Seller Name', 'wc-szamlazz'); ?></strong><br>
						1234 Város<br>
						Minta u. 1.<br>
						<strong><?php _e('VAT Number', 'wc-szamlazz'); ?>:</strong> XXXXXXXX-X-XX
					</div>
					<div class="seller">
						<strong><?php _e('Bank name:', 'wc-szamlazz'); ?>:</strong> <?php if($invoice['elado']['bank']): ?><?php echo $invoice['elado']['bank']; ?><?php else: ?>Teszt bank<?php endif; ?><br>
						<strong><?php _e('Bank account number', 'wc-szamlazz'); ?>:</strong> <?php if($invoice['elado']['bankszamlaszam']): ?><?php echo $invoice['elado']['bankszamlaszam']; ?><?php else: ?>XXXXXXXX-XXXXXXXX-XXXXXXXX<?php endif; ?>
					</div>
				</div>

				<h1 class="title"><?php echo esc_html_x('Preview', 'Invoice preview title', 'wc-szamlazz'); ?></h1>
				<div class="subtitle"><?php _e('Invoice number', 'wc-szamlazz'); ?>: <?php if(isset($invoice['fejlec']['szamlaszamElotag'])): ?><?php echo $invoice['fejlec']['szamlaszamElotag']; ?>-<?php endif; ?>1234</div>

				<div class="info">

					<div class="buyer">
						<h2>Vevő:</h2>
						<?php echo $invoice['vevo']['nev']; ?><br>
						<?php echo $invoice['vevo']['orszag']; ?>, <?php echo $invoice['vevo']['irsz']; ?> <?php echo $invoice['vevo']['telepules']; ?><br>
						<?php echo $invoice['vevo']['cim']; ?>
						<?php if($invoice['vevo']['adoszam']): ?><br><?php _e('VAT Number', 'wc-szamlazz'); ?>: <?php echo $invoice['vevo']['adoszam']; ?><?php endif; ?>
						<?php if($invoice['vevo']['adoszamEU']): ?><br><?php _e('EU VAT Number', 'wc-szamlazz'); ?>: <?php echo $invoice['vevo']['adoszamEU']; ?><?php endif; ?>
					</div>

					<table class="shop-details">
						<tr>
							<td><?php _e('Payment method', 'wc-szamlazz'); ?>:</td>
							<td><?php echo $invoice['fejlec']['fizmod']; ?></td>
						</tr>
						<tr>
							<td><?php _e('Order number', 'wc-szamlazz'); ?>:</td>
							<td><?php echo $invoice['fejlec']['rendelesSzam']; ?></td>
						</tr>
						<tr>
							<td><?php _e('Completion date', 'wc-szamlazz'); ?>:</td>
							<td><?php echo $invoice['fejlec']['teljesitesDatum']; ?></td>
						</tr>
						<tr>
							<td><?php _e('Issue date', 'wc-szamlazz'); ?>:</td>
							<td><?php echo $invoice['fejlec']['keltDatum']; ?></td>
						</tr>
						<tr>
							<td><?php _e('Due date', 'wc-szamlazz'); ?>:</td>
							<td><?php echo $invoice['fejlec']['fizetesiHataridoDatum']; ?></td>
						</tr>
					</table>
				</div>

				<table class="line-items">
					<thead>
						<tr>
							<th><?php _e('Description', 'wc-szamlazz'); ?></th>
							<th><?php _e('Qty', 'wc-szamlazz'); ?></th>
							<th><?php _e('Unit price', 'wc-szamlazz'); ?></th>
							<th><?php _e('Net price', 'wc-szamlazz'); ?></th>
							<th><?php _e('VAT', 'wc-szamlazz'); ?></th>
							<th><?php _e('VAT value', 'wc-szamlazz'); ?></th>
							<th><?php _e('Gross price', 'wc-szamlazz'); ?></th>
						</tr>
					</thead>
					<?php
					$sum_netto = 0;
					$sum_afa = 0;
					$sum_brutto = 0;
					$sum_total = 0;
					$items = $invoice['tetelek']['tetel'];
					if(!isset($invoice['tetelek']['tetel'][0])) {
						$items = array($invoice['tetelek']['tetel']);
					}
					?>
					<?php foreach ($items as $tetel): ?>
						<tr>
							<td>
								<span><?php echo $tetel['megnevezes']; ?> <?php if(isset($tetel['azonosito']) && !empty($tetel['azonosito'])): ?>(<?php echo $tetel['azonosito']; ?>)<?php endif; ?></span>
								<?php if(!empty($tetel['megjegyzes'])): ?>
									<div class="note"><?php echo wpautop($tetel['megjegyzes']); ?></div>
								<?php endif; ?>
							</td>
							<td><?php echo $tetel['mennyiseg']; ?> <?php echo $tetel['mennyisegiEgyseg']; ?></td>
							<td><?php echo $tetel['nettoEgysegar']; ?></td>
							<td><?php echo round($tetel['nettoErtek'], 2); ?></td>
							<td><?php echo $tetel['afakulcs']; ?> <?php if(intval($tetel['afakulcs']) > 0): ?>%<?php endif; ?></td>
							<td><?php echo round($tetel['afaErtek'], 2); ?></td>
							<td><?php echo round($tetel['bruttoErtek'], 2); ?></td>
						</tr>
						<?php $sum_netto += $tetel['nettoEgysegar']; ?>
						<?php $sum_afa += $tetel['afaErtek']; ?>
						<?php $sum_brutto += $tetel['bruttoErtek']; ?>
					<?php endforeach; ?>
					<tfoot>
						<tr>
							<td colspan="2"><?php _e('Total', 'wc-szamlazz'); ?>:</td>
							<td colspan="2"><?php echo round($sum_netto, 2); ?></td>
							<td></td>
							<td><?php echo round($sum_afa, 2); ?></td>
							<td><?php echo round($sum_brutto, 2); ?></td>
						</tr>
					</tfoot>
				</table>

				<div class="total">
					<h2><?php _e('Total', 'wc-szamlazz'); ?>:</h2>
					<h3><?php echo round($sum_brutto, 2); ?> <?php echo $invoice['fejlec']['penznem']; ?></h3>
				</div>

				<?php if($invoice['fejlec']['megjegyzes']): ?>
					<div class="invoice-note"><?php echo wpautop($invoice['fejlec']['megjegyzes']); ?></div>
				<?php endif; ?>

			<?php endif; ?>

		</div>

		<p class="footer"><?php _e('This is just a preview of the invoice and not all parameters will match the real invoice, like the colors, logo, language, and seller details.', 'wc-szamlazz'); ?></p>
		<?php if(WC_Szamlazz()->get_option('debug', 'no') == 'yes'): ?>
			<div class="debug"><textarea><?php _e('Development mode details:', 'wc-szamlazz'); ?> <?php echo json_encode($invoice, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?></textarea></div>
		<?php endif; ?>

	</body>
</html>
