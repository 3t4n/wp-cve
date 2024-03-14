<?php namespace Hurrytimer; ?>

<div id="hurrytimer-tabcontent-styling" class="hurrytimer-tabcontent">

    <!-- Preview container -->
    <div id="hurrytimer-campaign-preview" <?php echo $campaign->enableSticky === C::YES
        ? 'class="hurrytimer-sticky"' : '' ?>>
            <button class="hurrytimer-sticky-close" <?php echo $campaign->enableSticky === C::YES && $campaign->stickyBarDismissible === C::YES ? 'style="display:flex"' : 'style="display:none"' ?>>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 357 357">
                    <polygon points="357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3
			214.2,178.5 		"/>
                </svg>
            </button>
        <?php echo $campaign->enableSticky === C::YES ? '<div class="hurrytimer-sticky-inner">'
            : ''; ?>
        <div class="hurrytimer-campaign">
            <div class="hurrytimer-headline"><?php echo $campaign->headline; ?></div>
            <div class="hurrytimer-timer">
            <div class="hurrytimer-timer-block" data-block="months">
                    <div class="hurrytimer-timer-digit">01</div>
                    <div class="hurrytimer-timer-label">months</div>
                </div>
                <div class="hurrytimer-timer-sep" data-is="separator">:</div>
                <div class="hurrytimer-timer-block" data-block="days">
                    <div class="hurrytimer-timer-digit">10</div>
                    <div class="hurrytimer-timer-label">days</div>
                </div>
                <div class="hurrytimer-timer-sep" data-is="separator">:</div>
                <div class="hurrytimer-timer-block" data-block="hours" data-is="block">
                    <div class="hurrytimer-timer-digit" data-is="digit">20</div>
                    <div class="hurrytimer-timer-label" data-is="label">hours</div>
                </div>
                <div class="hurrytimer-timer-sep" data-is="separator">:</div>
                <div class="hurrytimer-timer-block" data-block="minutes">
                    <div class="hurrytimer-timer-digit">30</div>
                    <div class="hurrytimer-timer-label">minutes</div>
                </div>
                <div class="hurrytimer-timer-sep" data-is="separator">:</div>
                <div class="hurrytimer-timer-block" data-block="seconds">
                    <div class="hurrytimer-timer-digit">40</div>
                    <div class="hurrytimer-timer-label">seconds</div>
                </div>


            </div>
            <div class="hurrytimer-button-wrap">
                <a href="#" class="hurrytimer-button">
                    Buy Now
                </a>
            </div>
        </div>
        <?php echo $campaign->enableSticky === C::YES ? '</div >' : ''; ?>
    </div>
    <!-- Sub tab bar -->
    <ul class="hurrytimer-subtabbar">
       
        <li class="active">
            <a href="#hurrytimer-styling-general-tab">Elements</a>
        </li>
        <li><a href="#hurrytimer-customcss-tab">Custom CSS</a></li>
    </ul>

    <!-- ! Preview container -->
    <?php include 'appearance-style-tabcontent.php' ?>
    <?php include 'customcss-tab-content.php' ?>


</div>