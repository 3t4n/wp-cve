<?php 
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class VisitorToken
{
   /**
     *  Class instance
     *
     *  @var  object
     */
    private static $instance;

	/**
	 *  Cookie Name
	 *
	 *  @var  string
	 */
	private $cookieName = 'fpfid';

	/**
	 *  Represents the maximum age of the visitor's cookie in seconds.
	 *
	 *  @var  Integer
	 */
	private $expire = 90000000;

    /**
     *  Cookie Object
     *
     *  @var  object
     */
    private $cookie;

    /**
     * Class constructor
     * 
     * @param   object  $cookie
     * 
     * @return  void
     */
    private function __construct($cookie = null)
    {
        if (!$cookie)
        {
            $cookie = new \FPFramework\Libs\Cookie();
        }
        
        $this->cookie = $cookie;

        $token = $this->cookie->get($this->cookieName, null);

        if ($token === null)
        {
            $this->store($this->create());
        }
    }

    /**
     *  Returns class instance
     *
     *  @return  object
     */
    public static function getInstance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     *  Get a visitor's unique token id, if a token isn't set yet one will be generated.
     *
     *  @param   boolean   $forceNew  If true, force a new token to be created
     *  
     *  @return  string    The session token
     */
    public function get($forceNew = false)
    {
        return $this->cookie->get($this->cookieName);
    }

    /**
     *  Deprecated
     */
    public function getToken($forceNew = false)
    {
        return $this->get($forceNew);
    }

    /**
     *  Create a token-string
     *
     *  @param   integer $length  Length of string
     *
     *  @return  string  Generated token
     */
    private function create($length = 8)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     *  Deprecated
     */
    private function createToken($length = 8)
    {
        return $this->create($length);
    }

    /**
     *  Saves the cookie to the visitor's browser
     *
     *  @param   string  $value  Cookie Value
     *
     *  @return  void
     */
    private function store($value)
    {
        $options = [
            'expires' => time() + $this->expire,
            'path' => '/',
            'secure' => true
        ];

        $this->cookie->set($this->cookieName, $value, $options);
    }

    /**
     *  Deprecated
     */
    private function saveToken($value)
    {
        $this->store($value);
    }
}

?>