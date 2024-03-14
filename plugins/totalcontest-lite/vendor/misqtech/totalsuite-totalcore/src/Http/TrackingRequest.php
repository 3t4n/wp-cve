<?php
namespace TotalContestVendors\TotalCore\Http;


use TotalContestVendors\TotalCore\Application;
use TotalContestVendors\TotalCore\Helpers\Arrays;

/**
 * Class Request
 */
class TrackingRequest
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $method = 'POST';

    /**
     * Request constructor.
     *
     * @param  string  $url
     * @param $data
     * @param  string  $method
     */
    public function __construct($url, $data, $method = 'POST')
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = $method;
    }


    /**
     * @return bool|mixed|void
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @return mixed|void
     */
    protected function execute()
    {
        $onboarding = get_option(Application::getInstance()->env('onboarding-key'), []);
        $userConsent = (bool)Arrays::getDotNotation($onboarding, 'tracking', false);

        if($userConsent) {
            wp_remote_request($this->url,
                [
                    'method'   => strtoupper($this->method),
                    'blocking' => false,
                    'body'     => [
                        'uid'     => Application::getInstance()->uid(),
                        'product' => strtolower(Application::getInstance()->env('name', '')),
                        'date'    => date(DATE_ATOM),
                        'data'    => $this->data
                    ]
                ]);
        }
    }

    /**
     * @param $url
     * @param $data
     * @param  string  $method
     */
    public static function send($url, $data, $method = 'POST') {
        $request  = new static($url, $data, $method);
        $request->execute();
    }
}