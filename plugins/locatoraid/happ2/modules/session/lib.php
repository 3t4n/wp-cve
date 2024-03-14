<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Session Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Sessions
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/sessions.html
 */
class Session_Lib_HC_MVC extends _HC_MVC
{
	protected $init_done = FALSE;

	var $sess_encrypt_cookie		= FALSE;
	var $sess_use_database			= FALSE;
	var $sess_table_name			= '';
	var $sess_expiration			= 7200;
	var $sess_expire_on_close		= FALSE;
	var $sess_match_ip				= FALSE;
	var $sess_match_useragent		= TRUE;
	var $sess_cookie_name			= 'hc_session';
	var $cookie_prefix				= '';
	var $cookie_path				= '';
	var $cookie_domain				= '';
	var $cookie_secure				= FALSE;
	var $sess_time_to_update		= 300;
	var $flashdata_key				= 'flash';
	var $time_reference				= 'time';
	var $gc_probability				= 5;
	var $userdata					= array();
	var $CI;
	var $now;

	protected $encrypt = NULL;
	protected $input = NULL;

	protected $my_prefix = 'nts_';
	protected $builtin_props = array(
		'session_id',
		'ip_address',
		'user_agent',
		'last_activity',
		'user_data'
		);

	public function single_instance()
	{
	}

	/**
	 * Session Constructor
	 *
	 * The constructor runs the session routines automatically
	 * whenever the class is instantiated.
	 */

	public function _init()
	{
		if( $this->init_done ){
			return $this;
		}
		$this->init_done = TRUE;


		$app_short_name = $this->app->app_short_name();
		$this->my_prefix = $app_short_name . '_' . $this->my_prefix;
// echo "INIT SESSION FOR '$app_short_name'<br>";
// echo 'SESSION ID = ' . session_id() . '<br>';

		if( session_id() == '' ){
			$sessionOptions = array();
			$sessionOptions = array( 'read_and_close' => TRUE );
			if( PHP_SESSION_NONE == session_status() ){
				@session_start( $sessionOptions );
			}
		}

		$this->encrypt = $this->app->make('/encrypt/lib');
		$this->input = $this->app->make('/input/lib');

		// Set the "now" time.  Can either be GMT or server time, based on the
		// config prefs.  We use this to set the "last activity" time
		$this->now = $this->_get_time();

		// Set the session length. If the session expiration is
		// set to zero we'll set the expiration two years from now.
		if ($this->sess_expiration == 0){
			$this->sess_expiration = (60*60*24*365*2);
		}

	// Set the cookie name
		$this->sess_cookie_name = $this->cookie_prefix . $this->sess_cookie_name . '_' . $app_short_name;

		// Run the Session routine. If a session doesn't exist we'll
		// create a new one.  If it does, we'll update it.
		if ( ! $this->sess_read()){
			$this->sess_create();
		}
		else {
			$this->sess_update();
		}

		// Delete 'old' flashdata (from last request)
		$this->_flashdata_sweep();

		// Mark all new flashdata as old (data will be deleted before next request)
		$this->_flashdata_mark();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch the current session data if it exists
	 *
	 * @access	public
	 * @return	bool
	 */
	function sess_read()
	{
		// Fetch the cookie
		$session = $this->input->cookie($this->sess_cookie_name);

		// No cookie?  Goodbye cruel world!...
		if ($session === FALSE)
		{
			// log_message('debug', 'A session cookie was not found.');
			return FALSE;
		}

		// Decrypt the cookie data
		if ($this->encrypt == TRUE)
		{
			$session = $this->encrypt->decode($session);
		}
		else
		{
			// encryption was not used, so we need to check the md5 hash
			$hash	 = substr($session, strlen($session)-32); // get last 32 chars
			$session = substr($session, 0, strlen($session)-32);

			// Does the md5 hash match?  This is to prevent manipulation of session data in userspace
			// if ($hash !==  md5($session.$this->encryption_key))
			// {
			// 	hc_show_error('The session cookie data did not match what was expected. This could be a possible malicious attempt.');
			// 	$this->sess_destroy();
			// 	return FALSE;
			// }
		}

		// Unserialize the session array
		$session = $this->_unserialize($session);

		// Is the session data we unserialized an array with the correct format?
		if ( ! is_array($session) OR ! isset($session['session_id']) OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) OR ! isset($session['last_activity']))
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Is the session current?
		if (($session['last_activity'] + $this->sess_expiration) < $this->now)
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Does the IP Match?
		if ($this->sess_match_ip == TRUE AND $session['ip_address'] != $this->input->ip_address())
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Does the User Agent Match?
		if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr($this->input->user_agent(), 0, 120)))
		{
			$this->sess_destroy();
			return FALSE;
		}

		// Session is valid!
		$this->userdata = $session;
		unset($session);

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Write the session data
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_write()
	{
		// Are we saving custom data to the DB?  If not, all we do is update the cookie
		if ($this->sess_use_database === FALSE)
		{
			$this->_set_cookie();
			return;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Create a new session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_create()
	{
		$sessid = '';
		while (strlen($sessid) < 32)
		{
			$sessid .= mt_rand(0, mt_getrandmax());
		}

		// To make the session ID even more secure we'll combine it with the user's IP
		$sessid .= $this->input->ip_address();

		$this->userdata = array(
			'session_id'	=> md5(uniqid($sessid, TRUE)),
			'ip_address'	=> $this->input->ip_address(),
			'user_agent'	=> substr($this->input->user_agent(), 0, 120),
			'last_activity'	=> $this->now,
			'user_data'		=> ''
			);

		// Write the cookie
		$this->_set_cookie();
	}

	// --------------------------------------------------------------------

	/**
	 * Update an existing session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_update()
	{
		// We only update the session every five minutes by default
		if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
		{
			return;
		}

		// Save the old session id so we know which record to
		// update in the database if we need it
		$old_sessid = $this->userdata['session_id'];
		$new_sessid = '';
		while (strlen($new_sessid) < 32)
		{
			$new_sessid .= mt_rand(0, mt_getrandmax());
		}

		// To make the session ID even more secure we'll combine it with the user's IP
		$new_sessid .= $this->input->ip_address();

		// Turn it into a hash
		$new_sessid = md5(uniqid($new_sessid, TRUE));

		// Update the session data in the session data array
		$this->userdata['session_id'] = $new_sessid;
		$this->userdata['last_activity'] = $this->now;

		// _set_cookie() will handle this for us if we aren't using database sessions
		// by pushing all userdata to the cookie.
		$cookie_data = NULL;

		// Write the cookie
		$this->_set_cookie($cookie_data);
	}

	// --------------------------------------------------------------------

	/**
	 * Destroy the current session
	 *
	 * @access	public
	 * @return	void
	 */
	function sess_destroy()
	{
		// Kill the cookie
		@setcookie(
					$this->sess_cookie_name,
					addslashes(serialize(array())),
					($this->now - 31500000),
					$this->cookie_path,
					$this->cookie_domain,
					0
				);

		// Kill session data
		$this->userdata = array();
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a specific item from the session array
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function userdata($item)
	{
		$my_key = $this->my_prefix . $item;
		if( isset($_SESSION[$my_key]) ){
			return $_SESSION[$my_key];
		}
		return ( ! isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch all session data
	 *
	 * @access	public
	 * @return	array
	 */
	function all_userdata()
	{
		$return = array();

		/* get flash data we store in _SESSION */
		if( is_array($_SESSION) ){
			foreach( $_SESSION as $key => $v ){
				if( ! (substr($key, 0, strlen($this->my_prefix)) == $this->my_prefix) )
					continue;
				$my_key = substr($key, strlen($this->my_prefix) );
				$return[ $my_key ] = $v;
			}
		}

		$parent_return = $this->userdata;
		$return = array_merge( $return, $parent_return );
		return $return;
	}

	// --------------------------------------------------------------------

	/**
	 * Add or change data in the "userdata" array
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function set_userdata($newdata = array(), $newval = '', $append = FALSE)
	{
		if( PHP_SESSION_NONE == session_status() ){
			@session_start();
		}
		if (is_string($newdata))
		{
			$newdata = array($newdata => $newval);
		}

		$parent_newdata = array();
		if (count($newdata) > 0)
		{
			$parent_newdata = array();
			foreach ($newdata as $key => $val)
			{
				if( ! in_array($key, $this->builtin_props) )
				{
					$my_key = $this->my_prefix . $key;
					if( $append )
					{
						if( ! isset($_SESSION[$my_key]) )
							$_SESSION[$my_key] = array();
						if( ! is_array($_SESSION[$my_key]) )
							$_SESSION[$my_key] = array( $_SESSION[$my_key] );
						$_SESSION[$my_key][] = $val;
					}
					else
					{
						$_SESSION[$my_key] = $val;
					}
				}
				else
				{
					$parent_newdata[ $key ] = $val;
				}
			}
		}

		if( $parent_newdata )
		{
			if (count($parent_newdata) > 0){
				foreach( $parent_newdata as $key => $val){
					$this->userdata[$key] = $val;
				}
			}
			$this->sess_write();

		}
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a session variable from the "userdata" array
	 *
	 * @access	array
	 * @return	void
	 */
	function unset_userdata($newdata = array())
	{
		if( PHP_SESSION_NONE == session_status() ){
			@session_start();
		}
		if (is_string($newdata)){
			$newdata = array($newdata => '');
		}

		$parent_newdata = array();
		if (count($newdata) > 0){
			foreach ($newdata as $key => $val){
//				if( substr($key, 0, strlen($this->flashdata_key)) == $this->flashdata_key )
				if( ! in_array($key, $this->builtin_props) ){
					$my_key = $this->my_prefix . $key;
					unset($_SESSION[$my_key]);
				}
				else {
					$parent_newdata[ $key ] = $val;
				}
			}
		}

		if( $parent_newdata ){
			if (is_string($parent_newdata)){
				$parent_newdata = array($parent_newdata => '');
			}

			if (count($parent_newdata) > 0){
				foreach ($parent_newdata as $key => $val){
					unset($this->userdata[$key]);
				}
			}
			$this->sess_write();
		}
	}

	function add_flashdata($newdata = array(), $newval = '')
	{
		return $this->set_flashdata( $newdata, $newval, TRUE );
	}

	// ------------------------------------------------------------------------

	/**
	 * Add or change flashdata, only available
	 * until the next request
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function set_flashdata($newdata = array(), $newval = '', $append = FALSE)
	{
		if (is_string($newdata)){
			$newdata = array($newdata => $newval);
		}

		if (count($newdata) > 0){
			foreach ($newdata as $key => $val){
				$flashdata_key = $this->flashdata_key.':new:'.$key;
				$this->set_userdata($flashdata_key, $val, $append);
			}
		}
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Keeps existing flashdata available to next request.
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	function keep_flashdata($key)
	{
		// 'old' flashdata gets removed.  Here we mark all
		// flashdata as 'new' to preserve it from _flashdata_sweep()
		// Note the function will return FALSE if the $key
		// provided cannot be found
		$old_flashdata_key = $this->flashdata_key.':old:'.$key;
		$value = $this->userdata($old_flashdata_key);

		$new_flashdata_key = $this->flashdata_key.':new:'.$key;
		$this->set_userdata($new_flashdata_key, $value);
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch a specific flashdata item from the session array
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	function flashdata($key)
	{
		$flashdata_key = $this->flashdata_key.':old:'.$key;
		return $this->userdata($flashdata_key);
	}

	// ------------------------------------------------------------------------

	/**
	 * Identifies flashdata as 'old' for removal
	 * when _flashdata_sweep() runs.
	 *
	 * @access	private
	 * @return	void
	 */
	function _flashdata_mark()
	{
		$userdata = $this->all_userdata();
		foreach ($userdata as $name => $value)
		{
			$parts = explode(':new:', $name);
			if (is_array($parts) && count($parts) === 2)
			{
				$new_name = $this->flashdata_key.':old:'.$parts[1];
				$this->set_userdata($new_name, $value);
				$this->unset_userdata($name);
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Removes all flashdata marked as 'old'
	 *
	 * @access	private
	 * @return	void
	 */

	function _flashdata_sweep()
	{
		$userdata = $this->all_userdata();
		foreach ($userdata as $key => $value){
			if (strpos($key, ':old:')){
				$this->unset_userdata($key);
			}
		}

	}

	// --------------------------------------------------------------------

	/**
	 * Get the "now" time
	 *
	 * @access	private
	 * @return	string
	 */
	function _get_time()
	{
		if (strtolower($this->time_reference) == 'gmt')
		{
			$now = time();
			$time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));
		}
		else
		{
			$time = time();
		}

		return $time;
	}

	// --------------------------------------------------------------------

	/**
	 * Write the session cookie
	 *
	 * @access	public
	 * @return	void
	 */
	function _set_cookie($cookie_data = NULL)
	{
		if (is_null($cookie_data))
		{
			$cookie_data = $this->userdata;
		}

		// Serialize the userdata for the cookie
		$cookie_data = $this->_serialize($cookie_data);

		if ($this->encrypt == TRUE)
		{
			$cookie_data = $this->encrypt->encode($cookie_data);
		}
		else
		{
			// if encryption is not used, we provide an md5 hash to prevent userside tampering
			$cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
		}

		$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();
		// Set the cookie
		@setcookie(
			$this->sess_cookie_name,
			$cookie_data,
			$expire,
			$this->cookie_path,
			$this->cookie_domain,
			$this->cookie_secure
			);
	}

	// --------------------------------------------------------------------

	/**
	 * Serialize an array
	 *
	 * This function first converts any slashes found in the array to a temporary
	 * marker, so when it gets unserialized the slashes will be preserved
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
	function _serialize($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		}
		else
		{
			if (is_string($data))
			{
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}

		return serialize($data);
	}

	// --------------------------------------------------------------------

	/**
	 * Unserialize
	 *
	 * This function unserializes a data string, then converts any
	 * temporary slash markers back to actual slashes
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
	function _unserialize($data)
	{
		$data = @unserialize( $this->strip_slashes($data) );

		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('{{slash}}', '\\', $val);
				}
			}

			return $data;
		}

		return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
	}

	function strip_slashes($str)
	{
		if (is_array($str)){
			foreach ($str as $key => $val){
				$str[$key] = $this->strip_slashes($val);
			}
		}
		else {
			$str = stripslashes($str);
		}
		return $str;
	}

}
// END Session Class

/* End of file Session.php */
/* Location: ./system/libraries/Session.php */