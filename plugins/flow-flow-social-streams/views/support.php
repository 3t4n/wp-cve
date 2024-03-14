<?php if ( ! defined( 'WPINC' ) ) die;
$plugins_url = plugins_url() . '/' . 'flow-flow';
/** @var array $context */
$options = $context['options'];
?>
<div class="section-content" data-tab="support-tab" id="support-section">
    <div class="section">
        <h1 class="desc-following">Support requests</h1>
        <p class="desc">Here you can manage your support tickets: create new and access previous.</p>
        <p style="margin-top: 20px" id="support-cont">
            Checking status...
        </p>

    </div>
	<?php
	/** @noinspection PhpIncludeInspection */
	include($context['root']  . 'views/footer.php');
	?>
</div>
