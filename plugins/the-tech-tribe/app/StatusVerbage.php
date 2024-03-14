<?php
namespace TheTribalPlugin;

/**
 * 
 */
class StatusVerbage
{
    /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct(){}

    public function get($key)
    {
		$arrStatus = [
            'api' => [
                'success' => [
                    'header' => 'WOOHOO - IT WORKED!',
                    'msg' => 'Your Tech Tribe plugin is now Activated. Make sure you adjust your Settings below.'
                ],
                'error' => [
                    'header' => 'UH-OH - THERE WAS AN ERROR',
                    'msg' => 'Uh-oh, it looks like the API Key you entered was incorrect. Please try copying again, making sure there are no extra characters coming across in the COPY/PASTE. If it still fails, please email us at help@thetechtribe.com'
                ],
                'notverified' => [
                    'header' => 'Error',
                    'msg' => 'Error API Key.'
                ],
            ],
            'ac_tag' => [
                'error' => [
                    'header' => 'UH-OH - THERE WAS AN ERROR',
                    'msg' => 'Uh-oh, it looks like you have invalid AC tag. If it still fails, please email us at help@thetechtribe.com'
                ],
            ],
            'domain' => [
                'error' => [
                    'header' => 'UH-OH - ACTIVATION ISSUE',
                    'msg'   => 'Your API Key has already been activated on a different domain name to this one. If you are changing the URL of your website, please email help@thetechtribe.com with your new domain and will adjust this in the back-end.'
                ],
            ],
            'settings' => [
                'success' => [
                    'header' => 'Success',
                    'msg' => 'Updated Settings.'
                ]
            ],
            'import' => [
                'success' => [
                    'header' => 'Success',
                    'msg' => 'Successfully imported blog(s).'
                ],
                'error' => [
                    'header' => 'Error',
                    'msg' => 'Un-Successfully imported blog(s).'
                ],
                'nothing' => [
                    'header' => 'CHECK SUCCESSFUL',
                    'msg' => 'There were no available blogs to import. We release new posts monthly and your plugin will automatically check for these every 24 hours.'
                ],
                'imported' => [
                    'header' => 'Success',
                    'msg' => 'Blogs Imported'
                ]
            ],
            'default_dashboard' => [
                'success' => [
                    'header' => 'Success',
                    'msg' => 'Settings updated.'
                ],
                'error' => [
                    'header' => 'Error',
                    'msg' => 'Settings not updated.'
                ]
            ],
            'general_error' => [
                'timeout' => [
                    'header' => 'UH-OH - TIME-OUT ISSUE',
                    'msg' => 'For some reason, there has been a time-out issue trying to run this task. Please try again shortly and if it still continues - please email help@thetechtribe.com.'
                ],
                'error' => [
                    'header' => 'UH-OH - THERE WAS AN ERROR',
                    'msg' => 'Uh-oh, there was an undocumented error. If this continually happens, please let us know via email at help@thetechtribe.com and let us know what you were trying to do at the time.'
                ],
            ],
        ];

        if($key != '' && isset($arrStatus[$key])){
            return $arrStatus[$key];
        }

        return $arrStatus;
		
    }
    
}