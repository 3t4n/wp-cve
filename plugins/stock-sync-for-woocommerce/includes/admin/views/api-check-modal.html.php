<div id="wss-api-check-dialog" style="display:none;">
	<div id="wss-api-check-app">

		<table class="wss-credentials-table">
			<tr>
				<th><?php esc_html_e( 'URL', 'woo-stock-sync' ); ?></th>
				<td>{{ url }}</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'API Key', 'woo-stock-sync' ); ?></th>
				<td>{{ apiKey }}</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'API Secret', 'woo-stock-sync' ); ?></th>
				<td>{{ apiSecret }}</td>
			</tr>
		</table>

		<table class="wss-checks-table">
			<template v-for="check in checks">
				<tr>
					<th>{{ check.title }}</th>
					<td><span class="wss-check-status" :class="statuses[check.id]"></span></td>
				</tr>
				<tr v-if="errors[check.id].length > 0">
					<td colspan="2" class="error">
						<div v-for="error in errors[check.id]" v-html="error"></div>
					</td>
				</tr>
			</template>
		</table>

		<p v-if="allGood" class="wss-api-check-ok"><?php esc_html_e( 'All tests passed and API connection is good to go. Happy syncing!', 'woo-stock-sync' ); ?></p>
	</div>
</div>