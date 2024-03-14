<?php

namespace WpLHLAdminUi\Pages;

class UptimeGhost {

    public static function uptimeGhostPage(){
        echo "<div>";
            echo "<div>";
                echo "<h1>";
                    echo "UpTime Ghost 👻";
                echo "</h1>";
            echo "</div>";

            echo "<div class='lhl__pb_10'>";
            echo "<p class='lhl__lead'>";
                echo __("<b><u>Get 120 Days free</u></b> Website Uptime monitoring. Pay only $15/year afterwards. Cancel any time.");
            echo "</p>";
                echo "<p class='lhl__lead'>";
                    echo __("Uptime Ghost is a Website uptime monitoring service provided by <a href='https://lehelmatyus.com/uptime-ghost' target='_blank'>LehelMatyus.com</a>.");
                echo "</p>";
                echo "<ul class='lhl__pl_20 lhl__lead'>";
                    echo "<li>";
                        echo __("→ Get instant email notification if your website goes down. ");
                    echo "</li>";
                    echo "<li>";
                        echo __("→ No technical skill required, we set it up for you. Just turn it on and Complete Sign up.");
                    echo "</li>";
                    echo "<li>";
                    echo __("→ Get a website uptime monitoring dashboard for your website.");
                    echo "</li>";
                    echo "<li>";
                        echo __("→ Direct support at: ") . '<a href="mailto:support@uptimeghost.com">support@uptimeghost.com</a>';
                        ;
                    echo "</li>";
            echo "</ul>";
            echo "</div>";

            echo "<div>";
                echo "<h2>";
                    echo "Get Started for free in less than 2 minutes";
                echo "</h2>";
                echo "<ul class='lhl__pl_20 lhl__lead '>";
                    echo "<li class='lhl__pb_10'>";
                        echo __("Step 1: Enable \"Website Uptime Detection\" for Uptime Ghost below and save changes.");
                    echo "</li>";
                    echo "<li class='lhl__pb_10'>";
                        echo __("Step 2: Signup for Sevice here: ") . "<a href='https://buy.stripe.com/28odR19hu1rj5pK9AB' target='_blank'>" . __("Get Uptime Ghost") . "</a>.";
                    echo "</li>";
                    echo "<li class='lhl__pb_10'>";
                        echo __("Step 3: We will add your site to our monitoring service annd send you the link to your uptime dashboard.");
                    echo "</li>";
                echo "</ul>";
            echo "</div>";

            echo "<div>";
            echo "</div>";
        echo "</div>";


        settings_fields( 'uptime_ghost_options' );
        do_settings_sections( 'uptime_ghost_options' );
        submit_button();

        // echo "<div>";
        //     echo "<h2>";
        //         echo "Useful Links";
        //     echo "</h2>";
        //     echo "<ul class='lhl__pl_20 lhl__lead'>";
        //         echo "<li>";
        //             echo "<a href='https://billing.stripe.com/p/login/eVa5m8biz5xX2A0cMM' target='_blank'>" . __("Your Subscription Dashboard") . "</a>.";
        //         echo "</li>";
        //     echo "</ul>";
        // echo "</div>";



    }

}