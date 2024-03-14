<script type="text/html" id="tmpl-wps-store-details">
	<div class="wps-store-details">
		<# if ( data.city ) { #>
		<span><?= __('City', 'wc-pickup-store') ?><span class="colon">:</span></span> {{ data.city.value }} <br>
		<# } #>
		<# if ( data.phone ) { #>
		<span><?= __('Phone', 'wc-pickup-store') ?><span class="colon">:</span></span> {{ data.phone.value }} <br>
		<# } #>
		<# if ( data.address ) { #>
		<span><?= __('Address', 'wc-pickup-store') ?><span class="colon">:</span></span> {{ data.address.value }} <br>
		<# } #>
		<# if ( data.map ) { #>
		<iframe src="{{ data.map.value }}" frameborder="0"></iframe>
		<# } #>
	</div>
</script>