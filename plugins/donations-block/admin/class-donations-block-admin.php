<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://about.me/bharatkambariya
 * @since      2.1.0
 *
 * @package    Donations_Block
 * @subpackage Donations_Block/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Donations_Block
 * @subpackage Donations_Block/admin
 * @author     bharatkambariya <bharatkambariya@gmail.com>
 */
class Donations_Block_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
    public $paypal_doantion_table_name;

	public function __construct( $plugin_name, $version ) {
        global $wpdb;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->paypal_doantion_table_name = $wpdb->prefix . 'pdb_paypal_doantion_block';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.1.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/donations-block-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'data-table', plugin_dir_url( __FILE__ ) . 'css/dataTables.min.css' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.1.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'jQuery', plugin_dir_url( __FILE__ ) . 'js/jquery.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'donation-block-build', plugin_dir_url( __FILE__ ) . 'js/block.build.js', array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components') );
		wp_enqueue_script( 'dataTables', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/donations-block-admin.js', '', $this->version, true);

	}

    /**
     * Add admin shortcode
     **/
    public function add_admin_shortcode() {

        add_shortcode('pdb_receipt', array($this, 'pdb_receipt'));
        add_shortcode('pdb_payment_failed', array($this, 'pdb_payment_failed'));
        add_shortcode('paypal_donation_block', array($this, 'create_donation_block_shortcode'));

    }

    public function create_donation_block_shortcode($atts) {
        $admin_email = get_option('admin_email');
        $cy_arr = ['AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','MXN','NOK','NZD','PHP','PLN','GBP','RUB','SGD','SEK','CHF','TWD','THB','TRY','USD'];
        if (!filter_var($atts['email'], FILTER_VALIDATE_EMAIL)) { return '<p class="text-danger">Something went wrong!</p>'; }
        if (empty($atts['amount']) || $atts['amount'] <= 0 || empty($atts['currency'])) { return '<p class="text-danger">Something went wrong!</p>'; }
        if (!in_array($atts['currency'], $cy_arr)) {return '<p class="text-danger">Something went wrong!</p>';}
        switch ($atts['mode']) {
            case 'sandbox':
                $md = 'sandbox';
                break;
            case 'live':
                $md = 'live';
                break;
            default:
                $md = 'live';
        }
        $atts = shortcode_atts(
            array( 'email' => $admin_email, 'currency' => 'USD', 'purpose' => '', 'amount' => '', 'size' => 'large', 'mode' => '', 'suggestion' => '', ), $atts, 'bartag');

        switch ($atts['size']) {
            case 'small':
                $imgurl = 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif';
                $sz = 'small';
                break;
            case 'medium':
                $imgurl = 'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif';
                $sz = 'medium';
                break;
            case 'large':
                $imgurl = 'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif';
                $sz = 'large';
                break;
            default:
                $imgurl = 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif';
                $sz = 'medium';
        }
        $paypal_form_url = ('sandbox' == $md) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

        $popup_icon_url = plugins_url( 'donations-block/public/images/hand-money-rupee-coin.svg');
        $suggestion_html = '';
        if (empty($atts['suggestion'])) {
            // If suggestion amount is not available.
            $suggestion_html .= '<li class="donation-amount suggested-donation-amount amount-checked">
                                <label for="field-1">
                                    <input id="field-1" type="radio"  name="donation_amount" value="' . $atts['amount'] . '" checked>
                                    <span class="amount">' . $atts['currency'] . ' '. $atts['amount'] . '</span> <span class="description"></span>			
                                </label>
                            </li>';
        } else {
            // If suggestion amount is available.
            $suggestion_amnt = explode(',', $atts['suggestion']);
            foreach ($suggestion_amnt as $amt) {
                if (empty($amt) || $amt <= 0) { continue; }
                $condition = $atts['amount']==$amt?'checked':'';
                $suggestion_html .= '<li class="donation-amount pdb-sa suggested-donation-amount amount-'.$condition.'">
                            <label for="field-' . $amt . '">
                                <input id="field-' . $amt . '" type="radio"  name="donation_amount" value="' . $amt . '" ' . $condition . '>
                                <span class="amount">' . $atts['currency'] . ' ' . $amt . '</span> <span class="description"></span>			
                            </label>
                        </li>';
            }
        }

        return '<div id="overlay"></div>
                <button class="btn paypal-donation-btn btn-' . $sz . '" onclick="togglePopup()" >Donate</button>
                <div class="content-modal content">
                <div onclick="togglePopup()" class="close-btn">
                    ×
                </div>
                <div class="model paypal-donation-model">
                <form  target="_blank" name="wp_paypal_donation_form" id="pdb_paypal_donation_form" action="' . $paypal_form_url . '" method="post">
    			<div class="paypal_donation_block">
                    <img src="' . $popup_icon_url . '" alt="img-icon" class="donate-icon">
                    <div class="form-header">Your Donation</div>
                        <div class="donation-options">
                        <ul class="donation-amounts">
                            ' . $suggestion_html . '
                             <li class="donation-amount suggested-donation-amount custom-donation-amount">
                                <span class="custom-donation-amount-wrapper">
                                    <label for="form-field-custom-amount">
                                        <input id="form-field-custom-amount" type="radio" name="donation_amount" value="custome_amount">
                                        <span class="description">Custom amount</span>
                                    </label>
                                    <input type="number" class="custom-donation-input" name="custom_donation_amount" value="" min="1" disabled>
                                </span>
                            </li>
                            <li class="disabled-option" disabled>
                                <span class="custom-donation-amount-wrapper">
                                    <label for="form-field-custom-amount">
                                        <span class="description">Selected amount:</span>
                                    </label>
                                    <span class="selected_amount" >'.$atts['amount'].'</span>
                                </span>
                            </li>
                            </ul>
                    </div>
                    <div class="form-input">
                       <label for="donner_name">Full Name</label>
                        <input type="text" name="donner_name" id="donner_name" required>
                        <label for="donner_email">Email</label>
                        <input type="email" name="donner_email" id="donner_email" required>
                        <label for="donner_phone">Phone Number</label>
                        <input type="phone" name="donner_phone" id="donner_phone" required>
                    </div>
			        <input type="hidden" name="cmd" value="_donations">
                    <input type="hidden" name="item_number" id="pdb_item_number" value="">
                    <input type="hidden" name="item_name" value="' . $atts['purpose'] . '">
                    <input type="hidden" min="1" name="amount" value="' . $atts['amount'] . '">
			        <input type="hidden" name="business" value="' . $atts['email'] . '">
			        <input type="hidden" name="rm" value="0">
			        <input type="hidden" name="currency_code" value="' . $atts['currency'] . '">
			        <button type="button" class="donation_data_submit">Submit</button>
			        <input type="image" src="' . $imgurl . '" name="submit" alt="PayPal - The safer, easier way to pay online." hidden>
			        <img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			        <input type="hidden" name="cancel_return" value="'. get_permalink( get_page_by_path( 'Donation Failed' ) ) . '">
                    <input type="hidden" name="return" value="'. get_permalink( get_page_by_path( 'Donation Confirmation' ) ) . ' ">
			    </div>
			</form></div>
			</div>';
    }

    /**
     * On doantion success update payment details
     */
    public function pdb_receipt() {
        ob_start();
        global $wpdb;
        $wpdb->query($wpdb->prepare("UPDATE ".$this->paypal_doantion_table_name." SET transection_id='" . $_REQUEST['tx'] . "', donation_amount='" . $_REQUEST['amt'] . "', donation_currency='" . $_REQUEST['cc'] . "', transection_status='" . $_REQUEST['st'] . "', donation_purpose='" . $_REQUEST['item_name'] . "' WHERE id='" . $_REQUEST['item_number'] . "'"));

        $html = '<div class="donation-confirmation">
                    <div class="confirmation-box">
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Transection ID: </strong></td>
                                <td data-label="Due Date">'.$_REQUEST['tx'].'</td>
                            </tr>
                            <tr>
                                <td><strong>Amount: </strong></td>
                                <td data-label="Due Date">'.$_REQUEST['amt'].' '.$_REQUEST['cc'].'</td>
                            </tr>
                            <tr>
                                <td><strong>Transection Status: </strong></td>
                                <td data-label="Due Date">'.$_REQUEST['st'].'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="successful">
                    <img src="'.plugins_url('donations-block/public/images/correct.svg').'">
                    <h4>payment successful</h4> 
                </div>  
            </div>';
        $html .= ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * oN donation transection failed update transection details
     */
    public function pdb_payment_failed() {
        ob_start();
        global $wpdb;
        $wpdb->query($wpdb->prepare('UPDATE "'.$this->paypal_doantion_table_name.'" SET transection_id="'.$_REQUEST['tx'].'", donation_amount="'.$_REQUEST['amt'].'", donation_currency="'.$_REQUEST['cc'].'", transection_status="'.$_REQUEST['st'].'", donation_purpose="'.$_REQUEST['item_name'].'", WHERE id="'.$_REQUEST['item_number'].'"'));

        $html = '<div class="donation-failed">
                    <div class="failed-box">
                    <table>
                        <tbody>
                            <tr>
                                <td><strong>Transection ID: </strong></td>
                                <td data-label="Due Date">'.$_REQUEST['tx'].'</td>
                            </tr>
                            <tr>
                                <td><strong>Amount: </strong></td>
                                <td data-label="Due Date">'.$_REQUEST['amt'].' '.$_REQUEST['cc'].'</td>
                            </tr>
                            <tr>
                                <td><strong>Transection Status: </strong></td>  
                                <td data-label="Due Date">'.$_REQUEST['st'].'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="failed">
                    <img src="'.plugins_url('donations-block/public/images/failed.svg').'">
                    <h4>Failed</h4> 
                </div>  
            </div>';
        $html .= ob_get_contents();
        ob_end_clean();


        // return
        return $html;
    }

    /**
     * This function will install pdb related page which is not created already.
     *
     * @return void
     *@since 1.8.11
     *
     */
    function pdb_create_pages() {
        $options = [];

        // Checks if the Success Page option exists AND that the page exists.
        if (get_page_by_title('Donation Confirmation') == NULL || get_page_by_title('Donation Confirmation')->post_status != 'publish') {
            // Donation Confirmation (Success) Page
            $success = wp_insert_post(
                [
                    'post_title' => esc_html__('Donation Confirmation', 'pdb'),
                    'post_content' => '[pdb_receipt]',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_type' => 'page',
                    'comment_status' => 'closed',
                ]
            );

            // Store our page IDs
            $options['success_page'] = $success;
        }

        // Checks if the Failure Page option exists AND that the page exists.
        if (get_page_by_title('Donation Failed') == NULL || get_page_by_title('Donation Failed')->post_status != 'publish') {
            // Failed Donation Page
            $failed = wp_insert_post(
                [
                    'post_title' => esc_html__('Donation Failed', 'pdb'),
                    'post_content' => '[pdb_payment_failed]',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_type' => 'page',
                    'comment_status' => 'closed',
                ]
            );

            $options['failure_page'] = $failed;
        }
        add_option('pdb_install_pages_created', 1, '', false);
    }



}

/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page(){
    add_menu_page(
        __( 'PayPal Donation Block' ),
        'PayPal Donation',
        'manage_options',
        'pdb_setting',
        'pdb_setting_callback',
        plugins_url( 'donations-block/public/images/paypal-color.svg' ),
        80
    );
    add_submenu_page(
        'pdb_setting',
        'PayPal Donatione',
        'Setting',
        'manage_options',
        'pdb_setting',
        'pdb_setting_callback' );
    add_submenu_page(
        'pdb_setting',
        'PayPal Donatione Record',
        'Donatione Record',
        'manage_options',
        'pdb_list',
        'pdb_list_callback' );
}


/**
 * Display a custom menu page
 */
function pdb_list_callback(){
    ?>
    <div class="wrap">
    <div class="paypal-record">
        <div class="paypal-title">
            <h1>PayPal Donation Record</h1>
        </div>
        <div class="paypal-record-data">
            <?php
            global $wpdb;
            $tbl_name = $wpdb->prefix.'pdb_paypal_doantion_block';

            // Total Donation Sum
            $pdb_donation_sum = $wpdb->get_results( "SELECT sum(donation_amount) AS donation_amount_sum, donation_currency FROM ".$tbl_name." GROUP BY donation_currency" );

            foreach ($pdb_donation_sum as $pdb_sum) {
                if ($pdb_sum->donation_currency == NULL || empty($pdb_sum->donation_currency)) {
                    continue;
                }
                ?>
                <div class="total-box">
                    <div class="white-box">
                        <!--          <div class="paypal-currency">-->
                        <div class="paypal-currency">
                            <?php if($pdb_sum->donation_currency == 'USD' || $pdb_sum->donation_currency == 'AUD' || $pdb_sum->donation_currency == 'CAD' || $pdb_sum->donation_currency == 'HKD' || $pdb_sum->donation_currency == 'MXN' || $pdb_sum->donation_currency == 'NZD' || $pdb_sum->donation_currency == 'SGD'){?>
                                <h3>$</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'INR'){?>
                                <h3>₹</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'CZK'){?>
                                <h3>Kč</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'DKK' || $pdb_sum->donation_currency == 'NOK' || $pdb_sum->donation_currency == 'SEK'){?>
                                <h3>kr</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'EUR' ){?>
                                <h3>€</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'HUF' ){?>
                                <h3>Ft</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'ILS' ){?>
                                <h3>₪</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'JPY' ){?>
                                <h3>¥</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'MYR' ){?>
                                <h3>RM</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'PHP' ){?>
                                <h3>₱</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'PLN' ){?>
                                <h3>zł</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'GBP' ){?>
                                <h3>£</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'RUB' ){?>
                                <h3>₽</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'CHF' ){?>
                                <h3>CHF</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'TWD' ){?>
                                <h3>NT$</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'THB' ){?>
                                <h3>฿</h3>
                            <?php } else if ($pdb_sum->donation_currency == 'TRY' ){?>
                                <h3>₺</h3>
                            <?php } else {?>
                                <h3>$</h3>
                            <?php }?>
                        </div>
                        <div class="paypal-donation">
                            <h4>Total donation in <?= $pdb_sum->donation_currency; ?></h4>
                            <p><?= $pdb_sum->donation_amount_sum.' '.$pdb_sum->donation_currency; ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }

            // Current Month donation
            $pdb_month_donation_sum = $wpdb->get_results( "SELECT sum(donation_amount) AS donation_amount_sum, donation_currency FROM ".$tbl_name." WHERE created_at_time >= '".date('Y-m-01 00:00:00')."' GROUP BY donation_currency" );

            foreach ($pdb_month_donation_sum as $pdb_month_sum) {
                if ($pdb_month_sum->donation_currency == NULL || empty($pdb_month_sum->donation_currency)) {
                    continue;
                }
                ?>
                <div class="total-box">
                    <div class="white-box">
                        <div class="paypal-currency">
                            <img src="/wp-content/plugins/donations-block/public/images/schedule-calendar.svg">
                        </div>
                        <div class="paypal-donation">
                            <h4>Current month donation in <?= $pdb_month_sum->donation_currency; ?></h4>
                            <p><?= $pdb_month_sum->donation_amount_sum.' '.$pdb_month_sum->donation_currency; ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }

            // Total Donner Count
            $donner_count = $wpdb->get_results( "SELECT id FROM ".$tbl_name." GROUP BY donner_email" );
            ?>
            <div class="total-box">
                <div class="white-box">
                    <div class="paypal-currency">
                        <img src="/wp-content/plugins/donations-block/public/images/total-donation.png">
                    </div>
                    <div class="paypal-donation">
                        <h4>Total Donners</h4>
                        <p><?= count($donner_count); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php

        // Get list of donation
        $result = $wpdb->get_results ( "SELECT * FROM ".$tbl_name);
        ?>

        <table id="pdb_list">
            <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Transection ID</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Time</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            foreach($result as $data){
                ?>
                <tr>
                    <td><?=$i++;?></td>
                    <td><?=$data->donner_name;?></td>
                    <td><?=$data->donner_email;?></td>
                    <td><?=$data->donner_phone;?></td>
                    <td><?=$data->transection_id;?></td>
                    <td><?=$data->donation_amount;?></td>
                    <td><?=$data->donation_currency;?></td>
                    <td><?=$data->donation_purpose;?></td>
                    <td><?=$data->transection_status;?></td>
                    <td><?=$data->created_at_time;?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>

    <?php
}

function register_my_setting() {
    register_setting('pdb_settings_group', 'pdb_mode');
    register_setting('pdb_settings_group', 'pdb_email');
    register_setting('pdb_settings_group', 'pdb_amount');
    register_setting('pdb_settings_group', 'pdb_currency');
    register_setting('pdb_settings_group', 'pdb_size');
    register_setting('pdb_settings_group', 'pdb_purpose');
    register_setting('pdb_settings_group', 'pdb_SuggestionAmount');
}

function pdb_setting_callback() {
    ?>
    <div class="wrap">
        <form method="post" action="options.php" class="pdb-admin-row">
            <div class="full-admin-row">
                <h1>PayPal Donation Setting</h1>
            </div>
            <?php settings_fields('pdb_settings_group'); ?>
            <?php do_settings_sections('pdb_settings_group'); ?>
            <div class="admin-data-row">
                <div class="admin-data-row-box">
                    <ul class="admin-row">
                        <li>
                            <h5>Paypal Mode:</h5>
                            <select class="pdb_form_data" name="pdb_mode" id="pdb_mode">
                                <option value="">Select PayPal Mode</option>
                                <option value="live" <?= (get_option('pdb_mode')=='live'?'selected':''); ?>>Live</option>
                                <option value="sandbox" <?= (get_option('pdb_mode')=='sandbox'?'selected':''); ?>>Sandbox</option>
                            </select>
                        </li>
                        <li>
                            <h5>Email:</h5>
                           <input type="email" class="pdb_form_data" name="pdb_email" id="pdb_email" value="<?= (get_option('pdb_email')); ?>" placeholder="youremail@gmail.com">
                        </li>
                        <li>
                            <h5>Amount:</h5>
                            <input type="number" class="pdb_form_data" name="pdb_amount" id="pdb_amount" value="<?= (get_option('pdb_amount')); ?>" placeholder="10">
                        </li>
                        <li>
                            <h5>Select Currency:</h5>
                            <select class="pdb_form_data" name="pdb_currency" id="pdb_currency">
                                <option value="">Select Currency</option>
                                <option value="AUD" <?= (get_option('pdb_currency')=='AUD'?'selected':''); ?> >Australian Dollars (A $)</option>
                                <option value="BRL" <?= (get_option('pdb_currency')=='BRL'?'selected':''); ?> >Brazilian Real</option>
                                <option value="CAD" <?= (get_option('pdb_currency')=='CAD'?'selected':''); ?> >Canadian Dollars (C $)</option>
                                <option value="CZK" <?= (get_option('pdb_currency')=='CZK'?'selected':''); ?> >Czech Koruna</option>
                                <option value="DKK" <?= (get_option('pdb_currency')=='DKK'?'selected':''); ?> >Danish Krone</option>
                                <option value="EUR" <?= (get_option('pdb_currency')=='EUR'?'selected':''); ?> >Euros (€)</option>
                                <option value="HKD" <?= (get_option('pdb_currency')=='HKD'?'selected':''); ?> >Hong Kong Dollar ($)</option>
                                <option value="HUF" <?= (get_option('pdb_currency')=='HUF'?'selected':''); ?> >Hungarian Forint</option>
                                <option value="ILS" <?= (get_option('pdb_currency')=='ILS'?'selected':''); ?> >Israeli New Shekel</option>
                                <option value="JPY" <?= (get_option('pdb_currency')=='JPY'?'selected':''); ?> >Yen (¥)</option>
                                <option value="MYR" <?= (get_option('pdb_currency')=='MYR'?'selected':''); ?> >Malaysian Ringgit</option>
                                <option value="MXN" <?= (get_option('pdb_currency')=='MXN'?'selected':''); ?> >Mexican Peso</option>
                                <option value="NOK" <?= (get_option('pdb_currency')=='NOK'?'selected':''); ?> >Norwegian Krone</option>
                                <option value="NZD" <?= (get_option('pdb_currency')=='NZD'?'selected':''); ?> >New Zealand Dollar ($)</option>
                                <option value="PHP" <?= (get_option('pdb_currency')=='PHP'?'selected':''); ?> >Philippine Peso</option>
                                <option value="PLN" <?= (get_option('pdb_currency')=='PLN'?'selected':''); ?> >Polish Zloty</option>
                                <option value="GBP" <?= (get_option('pdb_currency')=='GBP'?'selected':''); ?> >Pounds Sterling (£)</option>
                                <option value="RUB" <?= (get_option('pdb_currency')=='RUB'?'selected':''); ?> >Russian Ruble</option>
                                <option value="SGD" <?= (get_option('pdb_currency')=='SGD'?'selected':''); ?> >Singapore Dollar ($)</option>
                                <option value="SEK" <?= (get_option('pdb_currency')=='SEK'?'selected':''); ?> >Swedish Krona</option>
                                <option value="CHF" <?= (get_option('pdb_currency')=='CHF'?'selected':''); ?> >Swiss Franc</option>
                                <option value="TWD" <?= (get_option('pdb_currency')=='TWD'?'selected':''); ?> >Taiwan New Dollar</option>
                                <option value="THB" <?= (get_option('pdb_currency')=='THB'?'selected':''); ?> >Thai Baht</option>
                                <option value="TRY" <?= (get_option('pdb_currency')=='TRY'?'selected':''); ?> >Turkish Lira</option>
                                <option value="USD" <?= (get_option('pdb_currency')=='USD'?'selected':''); ?> >US Dollars</option>
                            </select>
                        </li>
                        <li>
                            <h5>Select Button Size:</h5>
                            <select id="pdb_size" class="pdb_form_data" name="pdb_size">
                                <option value="">Select Size</option>
                                <option value="small" <?= (get_option('pdb_size')=='small'?'selected':''); ?>>Samll</option>
                                <option value="medium" <?= (get_option('pdb_size')=='medium'?'selected':''); ?>>Medium</option>
                                <option value="large" <?= (get_option('pdb_size')=='large'?'selected':''); ?>>Large</option>
                            </select>
                        </li>
                        <li>
                            <h5>Purpose:</h5>
                            <input type="text" class="pdb_form_data" name="pdb_purpose" id="pdb_purpose" value="<?= (get_option('pdb_purpose')); ?>" placeholder="Education">
                        </li>
                        <li>
                            <h5>Suggestion Amount:</h5>
                            <input type="text" class="pdb_form_data" name="pdb_SuggestionAmount" id="pdb_SuggestionAmount" value="<?= (get_option('pdb_SuggestionAmount')); ?>" placeholder="5,10,20,50,100">
                        </li>
                    </ul>
                    <?php submit_button(); ?>
                </div>
            </div>
            <div class="admin-data-row copy-box" onclick="copy_text_fun()">
                <div class="admin-data-row-box">
                    <i class="copy-icon" title="Click to copy!"><img src="<?= plugins_url( 'donations-block/public/images/copy-icon.svg' ); ?>" alt="Click to copy!"></i>
                    <p id="copy_txt" title="Click to copy!">[paypal_donation_block email='<?= (get_option('pdb_email')); ?>' amount='<?= (get_option('pdb_amount')); ?>' currency='<?= (get_option('pdb_currency')); ?>' size='<?= (get_option('pdb_size')); ?>' purpose='<?= (get_option('pdb_purpose')); ?> ' mode='<?= (get_option('pdb_mode')); ?>' suggestion='<?= (get_option('pdb_SuggestionAmount')); ?>']</p>
                    <span class="copy-msg hidden">Copied!</span>
                </div>
            </div>
        </form>
    </div>
    <?php
}
add_action('admin_init', 'register_my_setting');
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );
