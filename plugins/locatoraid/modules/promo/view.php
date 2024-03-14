<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Promo_View_LC_HC_MVC extends _HC_MVC
{
	public function render()
	{
		ob_start();
?>

<style>
.locatoraid-box { padding: 1em; border: #ccc 1px solid; background-color: #fff; }
.locatoraid-inline-list { margin: 0 0 .5em 0; padding: 0 0 0 0; }
.locatoraid-inline-list > * { vertical-align: middle; position: relative; display: inline-block; line-height: normal; margin-right: .25em; }
</style>

<div class="hc-clearfix hc-mxn2">
	<div class="hc-col hc-col-6 hc-mb2">
		<div class="hc-mx2 locatoraid-box">
			<h3 class="locatoraid-inline-list">
				<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M7 9H5l3-3 3 3H9v5H7V9zm5-4c0-.44-.91-3-4.5-3C5.08 2 3 3.92 3 6 1.02 6 0 7.52 0 9c0 1.53 1 3 3 3h3v-1.3H3c-1.62 0-1.7-1.42-1.7-1.7 0-.17.05-1.7 1.7-1.7h1.3V6c0-1.39 1.56-2.7 3.2-2.7 2.55 0 3.13 1.55 3.2 1.8v1.2H12c.81 0 2.7.22 2.7 2.2 0 2.09-2.25 2.2-2.7 2.2h-2V12h2c2.08 0 4-1.16 4-3.5C16 6.06 14.08 5 12 5z"/></svg><a target="_blank" href="https://www.locatoraid.com/upload-export/">CSV Manager</a>
			</h3>
			<p>
			Import, export and update your locations with a CSV file.
			</p>
		</div>
	</div>

	<div class="hc-col hc-col-6 hc-mb2">
		<div class="hc-mx2 locatoraid-box">
			<h3 class="locatoraid-inline-list">
				<svg xmlns="http://www.w3.org/2000/svg" width="0.88em" height="1em" viewBox="0 0 14 16"><path fill-rule="evenodd" d="M7.73 1.73C7.26 1.26 6.62 1 5.96 1H3.5C2.13 1 1 2.13 1 3.5v2.47c0 .66.27 1.3.73 1.77l6.06 6.06c.39.39 1.02.39 1.41 0l4.59-4.59a.996.996 0 0 0 0-1.41L7.73 1.73zM2.38 7.09c-.31-.3-.47-.7-.47-1.13V3.5c0-.88.72-1.59 1.59-1.59h2.47c.42 0 .83.16 1.13.47l6.14 6.13-4.73 4.73-6.13-6.15zM3.01 3h2v2H3V3h.01z"/></svg><a target="_blank" href="https://www.locatoraid.com/products/">Products / Categories</a>
			</h3>
			<p>
			Assign products to categorize your locations.
			</p>
		</div>
	</div>
</div>

<div class="hc-clearfix hc-mxn2">
	<div class="hc-col hc-col-6 hc-mb2">
		<div class="hc-mx2 locatoraid-box">
			<h3 class="locatoraid-inline-list">
				<svg xmlns="http://www.w3.org/2000/svg" width="0.75em" height="1em" viewBox="0 0 12 16"><path fill-rule="evenodd" d="M2 13c0 .59 0 1-.59 1H.59C0 14 0 13.59 0 13c0-.59 0-1 .59-1h.81c.59 0 .59.41.59 1H2zm2.59-9h6.81c.59 0 .59-.41.59-1 0-.59 0-1-.59-1H4.59C4 2 4 2.41 4 3c0 .59 0 1 .59 1zM1.41 7H.59C0 7 0 7.41 0 8c0 .59 0 1 .59 1h.81c.59 0 .59-.41.59-1 0-.59 0-1-.59-1h.01zm0-5H.59C0 2 0 2.41 0 3c0 .59 0 1 .59 1h.81c.59 0 .59-.41.59-1 0-.59 0-1-.59-1h.01zm10 5H4.59C4 7 4 7.41 4 8c0 .59 0 1 .59 1h6.81c.59 0 .59-.41.59-1 0-.59 0-1-.59-1h.01zm0 5H4.59C4 12 4 12.41 4 13c0 .59 0 1 .59 1h6.81c.59 0 .59-.41.59-1 0-.59 0-1-.59-1h.01z"/></svg><a target="_blank" href="https://www.locatoraid.com/custom-fields/">Custom Fields</a>
			</h3>
			<p>
			Up to 10 additional fields to keep custom information about your locations.
			</p>
		</div>
	</div>

	<div class="hc-col hc-col-6 hc-mb2">
		<div class="hc-mx2 locatoraid-box">
			<h3 class="locatoraid-inline-list">
				<svg xmlns="http://www.w3.org/2000/svg" width="0.75em" height="1em" viewBox="0 0 12 16"><path fill-rule="evenodd" d="M6 0C2.69 0 0 2.5 0 5.5 0 10.02 6 16 6 16s6-5.98 6-10.5C12 2.5 9.31 0 6 0zm0 14.55C4.14 12.52 1 8.44 1 5.5 1 3.02 3.25 1 6 1c1.34 0 2.61.48 3.56 1.36.92.86 1.44 1.97 1.44 3.14 0 2.94-3.14 7.02-5 9.05zM8 5.5c0 1.11-.89 2-2 2-1.11 0-2-.89-2-2 0-1.11.89-2 2-2 1.11 0 2 .89 2 2z"/></svg><a target="_blank" href="https://www.locatoraid.com/custom-map-icons/">Custom Map Icons</a>
			</h3>
			<p>
			Set a custom icon for any of your locations.
			</p>
		</div>
	</div>
</div>


<div style="text-align: center; margin: 0.5em 0;">
Get the Pro version for all of these nice features!
</div>

<div style="text-align: center; margin: 0.5em 0 2em 0;">
<a class="button-primary" style="display: block; text-align: center; font-size: 1.5em;" target="_blank" href="https://www.locatoraid.com/order/">Order Now</a>
</div>


<?php
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}
}