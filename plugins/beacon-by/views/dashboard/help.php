
<h1>Frequently Asked Questions</h1>


<p class="large">
Please check out help center to get answers to frequently asked questions: 
<a href="https://help.beacon.by" target="_blank">Help Center</a>
</p>

<hr />

<?php if (!$data['has_connected']): ?>
<h1 id="connect">Having trouble connecting?</h1>

<p class="large">A small number of Wordpress sites have trouble connecting. If you are unable to connect please try the following:
</p>

<p class="large">
<b>1. </b> First, we're going to need your unique Beacon name. 
<a href="<?php echo BEACONBY_CREATE_TARGET; ?>/dashboard/publication-name" target="_blank">
Click this link to get it
</a>

</p>

<p class="large">
<b>2. </b> Now we need to save it. Paste the name into the text field below:

<form method="post" action="?page=beaconby-help">
<input type="text" name="beacon" />
<button type="submit" class="text-button">Complete Manual Connection &raquo;</button>
</form>

</p>
<?php endif; ?>
