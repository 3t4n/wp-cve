<?php 
define('CACERT_CONFIG_PATH', __DIR__ . "/cert/");
class StdPayPal
{
    /**
     * @var bool $use_sandbox     Indicates if the sandbox endpoint is used.
     */
    private $use_sandbox = false;
    
    private $paypal_business_email = '';
    /**
     * @var bool $use_local_certs Indicates if the local certificates are used.
     */
    private $use_local_certs = true;

    /** Production Postback URL */
    const VERIFY_URI = 'https://ipnpb.paypal.com/cgi-bin/webscr';
    /** Sandbox Postback URL */
    const SANDBOX_VERIFY_URI = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
    
    /** Production Post URL */
    const PAYPAL_POST_LIVE_URI = 'https://www.paypal.com/cgi-bin/webscr';
    /** Sandbox Post URL */
    const PAYPAL_POST_SANDBOX_URI = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    /** Response from PayPal indicating validation was successful */
    const VALID = 'VERIFIED';
    /** Response from PayPal indicating validation failed */
    const INVALID = 'INVALID';
    function __construct($config) {
		$this->use_sandbox = $config['paypal_use_sandbox'];
        $this->paypal_business_email = $config['paypal_business_email'];
	}
    /**
     * Determine endpoint to post the verification data to.
     * @return string
     */
    public function getPaypalUri()
    {
        if ($this->use_sandbox) {
            return self::SANDBOX_VERIFY_URI;
        } else {
            return self::VERIFY_URI;
        }
    }
    /**
     * Determine endpoint to post the form data to paypal site.
     * @return string
     */
    public function getPaypalPostUri()
    {        
        if ($this->use_sandbox) {
            return self::PAYPAL_POST_SANDBOX_URI;
        } else {
            return self::PAYPAL_POST_LIVE_URI;
        }
    }
    /**
     * prepare post value in a single array and post it to paypal.
     * @return string
     */
    public function purchase($params = array())
    {    
        $post_data = array();
        $message = 'Please wait!! we are redirecting the website on PayPal to pay your payment amount.';
        $post_data['business'] = $this->paypal_business_email;
        $post_data['cmd'] = '_xclick';
        $post_data['no_note'] = 1;
        $post_data['no_shipping'] = 1;
        $post_data['lc'] = 'EN';
        $post_data['bn'] = 'PP-BuyNowBF';
        $url = $this->getPaypalPostUri();
        if(!empty($params)){
            foreach($params as $key => $value){
                $post_data[$key] = $value;
            }
        }
        $this->post_redirect($url, $post_data, $message);
    }
    public function subscribe($params = array())
    {    
        $post_data = array();
        $message = 'Please wait!! we are redirecting the website on PayPal to pay your payment amount.';
        $post_data['business'] = $this->paypal_business_email;
        $post_data['cmd'] = '_xclick-subscriptions';
        $post_data['no_note'] = 1;
        $post_data['no_shipping'] = 1;
        $post_data['lc'] = 'EN';
        $post_data['rm'] = 2;
        $post_data['bn'] = 'PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest';
        $url = $this->getPaypalPostUri();
        if(!empty($params)){
            foreach($params as $key => $value){
                $post_data[$key] = $value;
            }
        }
       $check =  $this->post_redirect($url, $post_data, $message);
       print_r($check);
    }
    /**
	 * Redirect the user's browser to a URL using a POST request.
	 */
	protected function post_redirect($url, $data, $message = NULL)
	{
		return self::post_payapl_redirect($url, $data, $message);
	}
    	/**
	 * Redirect the user's browser to a URL using a POST request.
	 *
	 * @param string $url
	 * @param array $data
	 * @param string $message
	 */
	public static function post_payapl_redirect($url, $data, $message = NULL)
	{
		?>
        	<form name="payment" action="<?php echo htmlspecialchars($url); ?>" method="post">
        		<p><?php echo !empty($message)? htmlspecialchars($message):''; ?></p>
        		<p>
        			<?php foreach ($data as $key => $value): ?>
        				<input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>" />
        			<?php endforeach ?>
        			<input type="submit" value="Continue" id="modal" />
        		</p>
        	</form>

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#modal").click(); 
                });
            </script>
        <!-- </body>
        </html> -->
        <?php
		exit();
 	}
    /**
     * Verification Function
     * Sends the incoming post data back to PayPal using the cURL library.
     *
     * @return bool
     * @throws Exception
     */
    public function verifyIPN(){
        if ( ! count($_POST)) {
            throw new Exception("Missing POST Data");
        }
		
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                // Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
                if ($keyval[0] === 'payment_date') {
                    if (substr_count($keyval[1], '+') === 1) {
                        $keyval[1] = str_replace('+', '%2B', $keyval[1]);
                    }
                }
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }

        // Build the body of the verification post request, adding the _notify-validate command.
        $req = 'cmd=_notify-validate';
        $get_magic_quotes_exists = false;
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }
		
        // Post the data back to PayPal, using curl. Throw exceptions if errors occur.
        $ch = curl_init($this->getPaypalUri());
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        // This is often required if the server is missing a global cert bundle, or is using an outdated one.
        if ($this->use_local_certs) {
            curl_setopt($ch, CURLOPT_CAINFO, CACERT_CONFIG_PATH.'/cacert.pem');
        }
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        $res = curl_exec($ch);		
        if ( ! ($res)) {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: [$errno] $errstr");
        }		
        $info = curl_getinfo($ch);
        var_dump($info);		
        $http_code = $info['http_code'];
        if ($http_code != 200) {
            throw new Exception("PayPal responded with http code $http_code");
        }
        curl_close($ch);
        // Check if PayPal verifies the IPN data, and if so, return true.
        if ($res == self::VALID) {
            return $myPost;
        } else {
            return false;
        }
    }
}