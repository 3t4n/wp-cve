<?php
$limit = !$subscribe?1000:$limit;
$count = !$count?0:$count;
$percent = $count!=0 && $limit>0?number_format( ($count/$limit)*100, 0 ):0;
if ($percent>=80){ ?>
    <?php
    if ($is_wp){?>
        <div class="limit__wrapper"><div class="limit_notice"><i class="fa fa-exclamation" aria-hidden="true"></i></div></div>
        <div class="limit__notice_tooltip pro__limit">
    <?php
        if ($percent<=99){
            ?>
            <p>This page has reached <b><?php echo $percent?>%</b> of the annual send limit.</p>
            <span>Bots will shut down soon! Upgrade to MobileMonkey PRO plan now to increase your send limit.</span>
            <?php
        }else{
            ?>
            <p>This page has exceeded the annual send limit.</p>
            <span>Bots will shut down! Upgrade to MobileMonkey PRO now to continue sending messages.</span>
            <?php
        }
        ?>
        <a target="_blank" href="<?php echo $app_domain ?>settings/billing" class="limit__button">Learn more</a>
            <div class="limit-notify-close"></div>
        </div>
    <?php
    }elseif(!$subscribe){?>
        <div class="limit__wrapper"><div class="limit_notice"><i class="fa fa-exclamation" aria-hidden="true"></i></div></div>
        <div class="limit__notice_tooltip free__limit">
            <?php
            if ($percent<=99){
                ?>
                <p>This page has reached <b><?php echo $percent?>%</b> of the monthly send limit.</p>
                <span>Bots will shut down soon! Upgrade to WP-Chatbot PRO plan now to increase your send limit.</span>
                <?php
            }else{
                ?>
                <p>This page has exceeded the annual send limit.</p>
                <span>Bots will shut down! Upgrade to WP-Chatbot PRO plan now to increase your send limit.</span>
                <?php
            }
            ?>
            <div id="button_update" class="limit__button">Upgrade now</div>
            <div class="limit-notify-close"></div>
        </div>
        <?php

    }
}
?>
