<?php
if ( ! function_exists('eli_btn_edit'))
{
    function eli_btn_edit($uri)
    {
        return eli_anchor($uri, '<i class="glyphicon glyphicon-pencil"></i>', array('class'=>'btn btn-success btn-xs'));
    }
}

if ( ! function_exists('eli_btn_read'))
{
    function eli_btn_read($uri, $title=NULL)
    {
        if(empty($title))$title=__('Read', 'eli_win');
        
        return eli_anchor($uri, '<i class="glyphicon glyphicon-search"></i> '.$title, array('class'=>'btn btn-primary btn-xs'));
    }
}

if ( ! function_exists('eli_btn_open'))
{
    function eli_btn_open($uri, $target=NULL)
    {
        if($target === NULL)
            $target = '_blank';

        return eli_anchor($uri, '<i class="glyphicon glyphicon-search"></i>', array('class'=>'btn btn-primary btn-xs', 'target'=>$target, 'title'=>__('Open details', 'eli_win')));
    }
}

if ( ! function_exists('eli_btn_open_ajax'))
{
    function eli_btn_open_ajax($uri, $target=NULL)
    {
        if($target === NULL)
            $target = '_blank';

        return eli_anchor($uri, '<i class="glyphicon glyphicon-search"></i>', array('class'=>'btn btn-primary btn-xs popup-with-form-ajax', 'target'=>$target, 'title'=>__('Open details', 'eli_win')));
    }
}

if ( ! function_exists('eli_btn_delete_noconfirm'))
{
    function eli_btn_delete_noconfirm($uri)
    {
        return eli_anchor($uri, '<i class="glyphicon glyphicon-remove"></i> ', array('class'=>'btn btn-danger btn-xs delete_button'));
    }
}

if ( ! function_exists('eli_btn_delete'))
{
    function eli_btn_delete($uri, $confirm_question = TRUE, $title='')
    {
        $target = '';
        if(isset($_GET['popup']))
        {
            $target = '';
        }

        if($confirm_question)
        {
            return eli_anchor($uri, '<i class="glyphicon glyphicon-remove"></i> ', array( 'target' => $target,  'title' => $title, 'onclick' => 'return confirm(\''.__('Are you sure?', 'eli_win').'\')', 'class'=>'btn btn-danger btn-xs delete_button'));
        }
        else
        {
            return eli_anchor($uri, '<i class="glyphicon glyphicon-remove"></i> ', array( 'target' => $target,  'title' => $title, 'class'=>'btn btn-danger btn-xs delete_button'));
        }
    }
}

if ( ! function_exists('eli_btn_save'))
{
    function eli_btn_save($uri, $empty = '-empty')
    {
        $target = '';
        if(isset($_GET['popup']))
        {
            $target = '';
        }

        return eli_anchor($uri, '<i class="glyphicon glyphicon-heart'.$empty.'"></i> ', array( 'target' => $target, 'class'=>'btn btn-danger btn-xs save_button', 'title'=>__('Save as Favourite for further analysis', 'eli_win')));
    }
}

if ( ! function_exists('eli_btn_block'))
{
    function eli_btn_block($uri, $confirm_question = FALSE, $title='')
    {
        $target = '';
        if(isset($_GET['popup']))
        {
            $target = '_blank';
        }

        if($confirm_question)
        {
            return eli_anchor($uri, '<i class="glyphicon glyphicon-lock"></i> ', array( 'target' => $target, 'title' => $title, 'onclick' => 'return confirm(\''.__('Are you sure?', 'eli_win').'\')', 'class'=>'btn btn-warning btn-xs block_button'));
        }
        else
        {
            return eli_anchor($uri, '<i class="glyphicon glyphicon-lock"></i> ', array( 'target' => $target, 'title' => $title, 'class'=>'btn btn-warning btn-xs block_button'));
        }
    }
}


if ( ! function_exists('eli_btn_view'))
{
    function eli_btn_view($uri, $confirm_question = FALSE, $title='')
    {
        if($confirm_question)
        {
            return eli_anchor($uri, '<i class="glyphicon glyphicon-search"></i> ', array( 'title' => $title, 'onclick' => 'return confirm(\''.__('Are you sure?', 'eli_win').'\')', 'class'=>'btn btn-info btn-xs'));
        }
        else
        {
            return eli_anchor($uri, '<i class="glyphicon glyphicon-search"></i> ', array( 'title' => $title, 'class'=>'btn btn-info btn-xs'));
        }
    }
}

if ( ! function_exists('eli_btn_hide'))
{
    function eli_btn_hide($uri)
    {
        $target = '';
        if(isset($_GET['popup']))
        {
            $target = '_blank';
        }

        return eli_anchor($uri, '<i class="glyphicon glyphicon-eye-close"></i> ', array( 'target' => $target, 'class'=>'btn btn-default btn-xs', 'title'=>__('Define hide rules', 'eli_win')));
    }
}

if ( ! function_exists('eli_anchor'))
{
	/**
	 * Anchor Link
	 *
	 * Creates an eli_anchor based on the local URL.
	 *
	 * @param	string	the URL
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function eli_anchor($uri = '', $title = '', $attributes = '')
	{
		$title = (string) $title;

		$site_url = is_array($uri)
			? site_url($uri)
			: (preg_match('#^(\w+:)?//#i', $uri) ? $uri : site_url($uri));

		if ($title === '')
		{
			$title = $site_url;
		}

		if ($attributes !== '')
		{
			$attributes = stringify_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
	}
}

if(!function_exists('eli_xss_clean')){
	function eli_xss_clean($data)
	{
		//if($data == '0000-00-00 00:00:00')return '';
		if(is_array($data))
			return eli_xss_clean_array($data);

		if(is_object($data))
			return eli_xss_clean_object($data);

		if($data === NULL)
			return '';

		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:framXe|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);

		$data = sanitize_text_field($data);

		// we are done...
		return $data;
	}
}
if(!function_exists('eli_xss_clean_array')){
function eli_xss_clean_array($array)
{
    if(!is_array($array))return array();

    $arr_cleaned = array();
    foreach($array as $key=>$val)
    {
        if($key == 'newcontent')
        {
            $arr_cleaned[eli_xss_clean($key)] = $val;
        }
        else
        {
            $arr_cleaned[eli_xss_clean($key)] = eli_xss_clean($val);
        }
        
    }

    //dump($arr_cleaned);

    return $arr_cleaned;
}
}
if(!function_exists('eli_xss_clean_object')){
function eli_xss_clean_object($object)
{
    if(!is_object($object))return NULL;

    $array = get_object_vars($object);
    foreach($array as $key=>$val)
    {
        $object->{$key} = eli_xss_clean($object->{$key});
    }

    return $object;
}
}
if(!function_exists('eli_ch')){
        
    function eli_ch(&$var, $empty = '')
    {
        if(empty($var))
            return $empty;

        return $var;
    }
}
if(!function_exists('eli_count')){
        

    function eli_count($mixed='') {
        $count = 0;

        if(!empty($mixed) && (is_array($mixed))) {
            $count = count($mixed);
        } else if(!empty($mixed) && function_exists('is_countable') && version_compare(PHP_VERSION, '7.3', '<') && is_countable($mixed)) {
            $count = count($mixed);
        }
        else if(!empty($mixed) && is_object($mixed)) {
            $count = 1;
        }
        return $count;
    }
}

if(!function_exists('eli_is_user_in_role')){
    function eli_is_user_in_role( $user, $role  ) {
        return in_array( $role, $user->roles );
    }
}

if(!function_exists('eli_is_logged_user')){

    function eli_is_logged_user()
    {
        $current_user = wp_get_current_user();
        if ( 0 == $current_user->ID ) {
            return false;
        } else {
            // Logged in.
            return true;
        }
    }
}

if(!function_exists('eli_get_current_user_role')){

function eli_get_current_user_role() {
    if( is_user_logged_in() ) {
      $user = wp_get_current_user();
      $role = ( array ) $user->roles;
      return $role[0];
    } else {
      return '';
    }
   }
}

if(!function_exists('eli_user_in_role')){

    function eli_user_in_role($role)
    {
        if(!eli_is_logged_user())
            return false;
        
        $current_user = wp_get_current_user();
        
        return eli_is_user_in_role( $current_user, $role  );
    }
}