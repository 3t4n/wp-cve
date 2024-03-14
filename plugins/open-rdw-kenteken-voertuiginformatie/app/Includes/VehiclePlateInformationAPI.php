<?php
namespace Tussendoor\OpenRDW\Includes;

/**
 * This class is responsible for our API call and returns vehicle data from open rdw
 * @see       http://www.tussendoor.nl
 * @since      2.0.0
 */
class VehiclePlateInformationAPI
{
    public $dot_config;

    /**
     * The unique identifier of this plugin.
     *
     * @since    2.0.0
     * @var string $url    The url string used to make calls to open rdw.
     */
    protected $url;

    /**
     * Constructor of our class
     *
     * @since    2.0.0
     * @param string $url A string containing our API link.
     */
    public function __construct($url = '')
    {
        global $dot_config;
        $this->dot_config = $dot_config;
        $this->url = ($url != '') ? $url : $this->dot_config['open.api'];
        add_filter('https_ssl_verify', '__return_false');
    }

    /**
     * function that builds our call and returns the response.
     *
     * @since     2.0.0
     * @param  string $kenteken A string containing our license plate.
     * @return array  An array containing our vehicle information.
     */
    public function get_info($kenteken = 0)
    {
        $this->InformBase();
        // Clean our input
        $kenteken = $this->clean_license($kenteken);

        // Call our request and see if we get a response
        $response = $this->call($this->url . '?kenteken=' . $kenteken);

        if(is_array($response)){
            // See if there are any "side calls to make"
            $sidecalls = preg_grep($this->dot_config['open.sidecallexpress'], $response);
            if (!empty($sidecalls)) {
                foreach ($sidecalls as $key => $value) {
                    $sidecall = $this->call($value . '?kenteken=' . $kenteken);
                    $response = array_merge($response, $sidecall);
                    unset($response[$key]);
                }
            }


            if(!empty($response)){
                $response = apply_filters('open_rdw_vehicle_plate_information_format_result', $response);
            }

            return $response;
        }

        return [];
    }

    /**
     * Returns our cleaned license plate.
     * Is public so we can also only clean the license.
     *
     * @since     2.0.0
     * @param  string $license A string containing the filled in license.
     * @return string Returns a cleaned license string.
     */
    public function clean_license($license)
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $license));
    }

    /**
     * The code that initializes and configures our curl call
     * executes the curl and decodes the json string for use.
     *
     * @since    2.0.0
     * @param  string $request Our url request
     * @return array  Our vehicle data (if there is any)
     */
    private function call($request = '')
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $request,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_MAXREDIRS => 10,
        ]);

        $response = json_decode(curl_exec($curl), true);

        // Check for errors
        if (curl_error($curl)) {
            $response = curl_error($curl);
        }
        curl_close($curl);

        // If we have errors set that as response
        if (isset($response[0])) {
            $response = $response[0];
        }
        return $response;
    }

    /**
     * Send some request info to base. If it fails, it fails silently.
     * This way it will not cancel the upstream request.
     *
     * @return void
     */
    public function InformBase()
    {
            $url = 'https://tussendoor.nl/api/v1/rdw';
            $response = wp_remote_post($url);

            if (is_wp_error($response)) {
                // Handle the error silently.
                error_log($response->get_error_message());
            }
    }
}
