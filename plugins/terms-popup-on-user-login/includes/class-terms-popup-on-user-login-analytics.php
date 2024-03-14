<?php

use WpLHLAdminUi\LicenseKeys\LicenseKeyHandler;

class TPUL_Analytics {

    public static $instance = null;

    public function __construct() {
    }

    /**
     * Analytics Functions
     */
    public function getUserState() {
        // Define an array to store the counts with descriptions as keys
        $value_counts = array(
            'never_accepted' => 0,              // -2
            'accepted_then_declined' => 0,     // -1
            'never_seen' => 0,                 // 0
            'accepted_at_some_point' => 0,     // 1
            'accepted_latest_terms' => 0       // 2
        );

        // Define the arguments for the user query
        $args = array(
            'role' => '', // Replace with the desired user role or leave empty to include all users
            'number' => -1, // -1 to retrieve all users
        );

        // Create a new user query
        $user_query = new WP_User_Query($args);

        // Check if users were found
        if (!empty($user_query->results)) {
            // Loop through each user
            foreach ($user_query->results as $user) {
                // Instantiate the TPUL_User_State class
                $user_state_manager = new TPUL_User_State($user->ID);

                // Get user accepted check
                $user_accepted_check = $user_state_manager->has_accepted_terms(true);

                // Count the values
                switch ($user_accepted_check) {
                    case -2:
                        $value_counts['never_accepted']++;
                        break;
                    case -1:
                        $value_counts['accepted_then_declined']++;
                        break;
                    case 0:
                        $value_counts['never_seen']++;
                        break;
                    case 1:
                        $value_counts['accepted_at_some_point']++;
                        break;
                    case 2:
                        $value_counts['accepted_latest_terms']++;
                        break;
                }
            }

            // Return an array of two arrays with descriptions as keys
            return array(
                'keys' => array_keys($value_counts),
                'values' => array_values($value_counts)
            );
        } else {
            // No users found
            return array(
                'keys' => array(),
                'values' => array()
            );
        }
    }

    function getUserStateUnified() {
        // Define an array to store the counts with descriptions as keys
        $value_counts = array(
            'never_accepted' => 0,              // -2 or -1
            'never_seen' => 0,                 // 0
            'accepted' => 0                    // 1 or 2
        );

        // Define the arguments for the user query
        $args = array(
            'role' => '', // Replace with the desired user role or leave empty to include all users
            'number' => -1, // -1 to retrieve all users
        );

        // Create a new user query
        $user_query = new WP_User_Query($args);

        // Check if users were found
        if (!empty($user_query->results)) {
            // Loop through each user
            foreach ($user_query->results as $user) {
                // Instantiate the TPUL_User_State class
                $user_state_manager = new TPUL_User_State($user->ID);

                // Get user accepted check
                $user_accepted_check = $user_state_manager->has_accepted_terms(true);

                // Unify the values
                if ($user_accepted_check == -2 || $user_accepted_check == -1) {
                    $value_counts['declined']++;
                } elseif ($user_accepted_check == 0) {
                    $value_counts['never_seen']++;
                } elseif ($user_accepted_check == 1 || $user_accepted_check == 2) {
                    $value_counts['accepted']++;
                }
            }

            // Return an array of two arrays with descriptions as keys
            return array(
                'keys' => array_keys($value_counts),
                'values' => array_values($value_counts)
            );
        } else {
            // No users found
            return array(
                'keys' => array(),
                'values' => array()
            );
        }
    }

    function getAcceptedDatesByMonth() {
        // Initialize an array to store the values for all the last 12 months
        $values = array();

        // Get the current timestamp
        $current_timestamp = time();

        // Create an array of the last 12 months with keys in the format 'YYYY-MM'
        $last_12_months = array();
        for ($i = 11; $i >= 0; $i--) {
            $last_12_months[date('Y-m', strtotime("-$i months", $current_timestamp))] = 0;
        }

        // Get user data for all users
        $user_query = new WP_User_Query(array('number' => -1));

        if (!empty($user_query->results)) {
            foreach ($user_query->results as $user) {
                // Initialize the TPUL_User_State class
                $user_state = new TPUL_User_State($user->ID);
                // Get the accepted date for each user
                $accepted_date = $user_state->get_user_accepted_date_raw($user->ID);

                // Check if the date is within the last 12 months
                if ($accepted_date >= strtotime('-12 months', $current_timestamp)) {
                    // Extract the year and month from the accepted date
                    $year_month = date('Y-m', $accepted_date);

                    // Increment the count for the respective month
                    $last_12_months[$year_month]++;
                }
            }
        }

        return array(
            'keys' => array_keys($last_12_months),
            'values' => array_values($last_12_months)
        );
    }


    /**
     * Helper functions
     */
    function phpArrayToJavaScriptArray($phpArray) {
        $javascriptArray = '[';
        $first = true;

        foreach ($phpArray as $item) {
            if (!$first) {
                $javascriptArray .= ', ';
            } else {
                $first = false;
            }

            // Escape single quotes in the item
            $item = str_replace("'", "\\'", $item);

            $javascriptArray .= "'$item'";
        }

        $javascriptArray .= ']';

        return $javascriptArray;
    }

    /**
     * DASHBOARD
     */

    public function print_dashboard() {
        $analytics = new TPUL_Analytics();
        $user_states = $analytics->getUserStateUnified();
        $accepted_dates_by_month = $analytics->getAcceptedDatesByMonth();

        $license_key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());
        $license_is_active = $license_key_handler->is_active();


?>

        <?php if (!$license_is_active) : ?>
            <h2><?php _e("The Analytics Feature is temporarily available to you, but it will be discontinued if a License Key is not obtained.", 'terms-popup-on-user-login'); ?></h2>
            <div>
                <a href="https://www.lehelmatyus.com/question/question-category/terms-popup-on-user-login" target="_blank" title="terms popup on user login review">
                    <?php _e("Purchase a License Key", 'terms-popup-on-user-login'); ?>
                </a>
                <br />
                <br />
                <br />
            </div>
        <?php else : ?>
            <h2><?php _e("Analytics - for logged in users"); ?></h2>
        <?php endif; ?>

        <div class="chart-container">
            <div class="chart" style="height: 300px; width:300px;">
                <canvas id="userChart" width="400" height="400"></canvas>
            </div>
            <div class="chart" style="height: 300px; width:600px;">
                <canvas id="myChart" width="600" height="300"></canvas>
            </div>
        </div>

        <style>
            /* Default styles for divs */
            .chart-container {
                display: flex;
            }

            .chart {
                margin-right: 20px;
                /* Add some spacing between divs */
            }

            /* Media query for tablet and smaller screens */
            @media (max-width: 768px) {
                .chart-container {
                    flex-direction: column;
                    /* Stack divs on top of each other */
                }

                .chart {
                    margin-right: 0;
                    /* Remove margin for spacing */
                }
            }
        </style>

        <script>
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo $analytics->phpArrayToJavaScriptArray($accepted_dates_by_month['keys']) ?>,
                    datasets: [{
                        label: '# of accepts',
                        data: <?php echo $analytics->phpArrayToJavaScriptArray($accepted_dates_by_month['values']) ?>,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });


            new Chart(document.getElementById("userChart"), {
                type: 'pie',
                data: {
                    labels: <?php echo $analytics->phpArrayToJavaScriptArray($user_states['keys']) ?>,
                    datasets: [{
                        label: "Users",
                        backgroundColor: ["#FF6B6B", "#AEC6CF", "#87CEEB"],
                        data: <?php echo $analytics->phpArrayToJavaScriptArray($user_states['values']) ?>
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'User Actions'
                    }
                }
            });
        </script>
<?php
    }
}
