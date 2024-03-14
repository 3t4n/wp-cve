<?php
/**
 * OntraportConn
 *
 * @package		LetAProDoIT.Helpers
 * @filename	OntraportConn.php
 * @version		2.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2016 Let A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Ontraport API functionality
 *
 */	
class LAPDI_OntraportConn
{
	public $id = null;
	public $key = null;
	public $secret = null;
	public $URL = null;
	public $user = null;
	public $pass = null;

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @param object params - the variables to set
     *
     * @return void
     *
     */
    function __construct($params = null) 
    {
        if (!empty($params))
        {
            foreach ($params as $key => $value)
            {
                if (property_exists($this, $key))
                {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Deconstructor
     *
     * @since 1.0.0
     *
     * @param void
     *
     * @return void
     *
     */
    function __destruct() 
    {
    }

	/**
	 * Function to get OAP contact information from the database
	 *
	 * @since 1.0.0
	 *
	 * @param string $fname  - The first name
	 * @param string $lname - The last name
	 * @param string $email Optional - The email address
	 * @param string $contact_id Optional - The OAP contact ID
	 * @param boolean $count_only Optional - Return the number of results
	 *
	 * @return string $response - The curl response (XML)
	 */
    public function getContactInfo($fname, $lname, $email = null, $contact_id = null, $count_only = false)
    {
        $data = "";
        $reqType= "search";
    
        //Multiple contacts can be fetched by sending separate contact IDs
        if (!empty($contact_id))
        {
            $reqType= "fetch";
            $data = <<<STRING
    <contact_id>$contact_id</contact_id>
STRING;
        }
        else
        {
            $equations = <<<STRING
        <equation>
            <field>First Name</field>
            <op>e</op>
            <value>$fname</value>
        </equation>
        <equation>
            <field>Last Name</field>
            <op>e</op>
            <value>$lname</value>
        </equation>
STRING;

            if (!empty($email))
            {
                $equations .= <<<STRING
            <equation>
                <field>E-mail</field>
                <op>e</op>
                <value>$email</value>
            </equation>
STRING;
            }

            $data = <<<STRING
    <search>
        $equations
    </search>
STRING;
        }
        
        $data = urlencode($data);
        
        $postargs = array(
            'appid'     => $this->id,
            'key'       => $this->key,
            'return_id' => '1',
            'reqType'   => $reqType,
            'data'      => $data,
        );

		if ($count_only)
			$postargs['count'] = '1';

        if (LAPDI_Config::get('app.debug'))
            LAPDI_Log::info("Sending post args for contact info to OAP: " . @json_encode($postargs));

        $response = LAPDI_Helper::getCurlResults($this->URL, null, $postargs, true);
    
        return $response;
    }

	/**
	 * Function to get OAP contact information from the database
	 *
	 * @since 1.0.0
	 *
	 * @param string $fname  - The first name
	 * @param string $lname - The last name
	 * @param string $email Optional - The email address
	 * @param string $contact_id Optional - The OAP contact ID
	 *
	 * @return string $owner - The contact owner
	 */
    public function getContactOwner($fname, $lname, $email = null, $contact_id = null)
    {
        $response = $this->getContactInfo($fname, $lname, $email, $contact_id);
        
        $xml = simplexml_load_string($response);
    
        $owner = null;

        if (isset($xml->contact->Group_Tag))
        {
            foreach($xml->contact->Group_Tag as $group_tag) 
        	{
        		if ($group_tag['name'] == "Lead Information")
        		{
                    foreach($group_tag->field as $field)
                    {
                        if ($field['name'] == "Owner")
                        {
                            $owner = $field;
                            break;
                        }
                    }
        		}
            }
        }

        return $owner;
    }

	/**
	 * Function to get OAP contact ID from the database
	 *
	 * @since 1.0.0
	 *
	 * @param string $fname  - The first name
	 * @param string $lname - The last name
	 * @param string $email Optional - The email address
	 *
	 * @return string $contact_id - The contact ID
	 */
    public function getContactID($fname, $lname, $email = null)
    {
        $response = $this->getContactInfo($fname, $lname, $email);
        
        $xml = simplexml_load_string($response);
    
        $contact_id = null;

        if (isset($xml->contact->Group_Tag))
        {
            foreach($xml->contact->Group_Tag as $group_tag) 
        	{
        		if ($group_tag['name'] == "System Information")
        		{
                    foreach($group_tag->field as $field)
                    {
                        if ($field['name'] == "Contact ID")
                        {
                            $contact_id = $field;
                            break;
                        }
                    }
        		}
            }
        }

        return $contact_id;
    }

	/**
	 * Function to get OAP contact information from the database
	 *
	 * @since 1.0.0
	 *
	 * @param string $fname  - The first name
	 * @param string $lname - The last name
	 * @param string $email Optional - The email address
	 * @param string $contact_id Optional - The OAP contact ID
	 *
	 * @return array $tags - The contact tags in OAP
	 */
    public function getContactTags($fname, $lname, $email = null, $contact_id = null)
    {
        $response = $this->getContactInfo($fname, $lname, $email, $contact_id);
        
        $xml = simplexml_load_string($response);
    
        $tags = array();

        $tag_str = "";

        if (isset($xml->contact->Group_Tag))
        {
            foreach($xml->contact->Group_Tag as $group_tag) 
        	{
        		if ($group_tag['name'] == "Sequences and Tags")
        		{
                    foreach($group_tag->field as $field)
                    {
                        if ($field['name'] == "Contact Tags")
                        {
                            $tag_str = $field;
                            break;
                        }
                    }
        		}
            }
    
            $tags = preg_split("/\*\/\*/", $tag_str);
        }

        return $tags;
    }

	/**
	 * Function to get OAP contact information from the database
	 *
	 * @since 1.0.1
	 *
	 * @param various $tags  - The tags to be parsed
	 *
	 * @return array $tags - The array Object
	 */
    public function formatTags($tags)
    {
    	if (preg_match("/\&lt\;\/li\&gt\;\&lt\;li\&gt\;/", $tags))
    	{
    		$tags = preg_replace("/^(\&lt\;li\&gt\;)/", "", $tags);
    		$tags = preg_replace("/(\&lt\;\/li\&gt\;)$/", "", $tags);
    		$tags = explode("&lt;/li&gt;&lt;li&gt;", $tags);
    	}	
    	elseif (preg_match("/\<\/li\>\<li\>/", $tags))
    	{
    		$tags = preg_replace("/^(\<li\>)/", "", $tags);
    		$tags = preg_replace("/(\<\/li\>)$/", "", $tags);
    		$tags = explode("</li><li>", $tags);
    	}	
    	else {
    		$tags = explode(", ", $tags);
    	}

        return $tags;
    }

	/**
	 * Function to add OAP tags to a contact
	 *
	 * @since 1.0.0
	 *
	 * @param string $contact_id - The OAP contact ID
	 * @param array $tags - a list of tags to add
	 *
	 * @return string $response - The curl response (XML)
	 */
    public function addContactTag($contact_id, $tags)
    {
        $data = "";
        $tag_xml = "";
        $reqType= "add_tag";
    
		$valid_tags = array();
        $response = null;

		// determine tags present that need to be added in OAP
		foreach ($tags as $tag)
		{
			if (strpos($tag, "PDID") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "UplineID") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "Contract") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "SqzPgOwner") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "MyPD") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "On file") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "Topline PD") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "DirectUpline") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "Bonusto") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
		}

        if (!empty($valid_tags))
        {
            foreach ($valid_tags as $tag)
            {
                $tag_xml .= "<tag>$tag</tag>" . PHP_EOL;
            }
    
            
            $data = <<<STRING
    <contact id='{$contact_id}'>
    {$tag_xml}
    </contact>
STRING;
    
            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("Tags to add in OAP: " . @json_encode($data));
    
            $data = urlencode($data);
            
            $postargs = array(
                'appid'     => $this->id,
                'key'       => $this->key,
                'return_id' => '1',
                'reqType'   => $reqType,
                'data'      => $data,
            );
    
            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("Sending post args to add tags to OAP: " . @json_encode($postargs));
    
            $response = LAPDI_Helper::getCurlResults($this->URL, null, $postargs, true);
        }
        else
        {
            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("No valid tags found to add to OAP");
        }
    
        return $response;
    }

	/**
	 * Function to add a new contact to OAP
	 *
	 * @since 1.0.3
	 *
	 * @param array $params - a list of parameters to add
	 *
	 * @return string $contact_id - The curl response (XML)
	 */
    public function addContact($params)
    {
        $reqType = "add";
        $data = <<<STRING
<contact>
    <Group_Tag name="Contact Information">
        <field name="First Name">{$params['fname']}</field>
        <field name="Last Name">{$params['lname']}</field>
        <field name="Email">{$params['email']}</field>
    </Group_Tag>
    <Group_Tag name="Sequences and Tags">
        <field name="Contact Tags">{$params['tags']}</field>
        <field name="Sequences">{$params['sequences']}</field>
    </Group_Tag>
</contact>
STRING;

        $postargs = array(
            'appid'     => $this->id,
            'key'       => $this->key,
            'return_id' => '2',
            'reqType'   => $reqType,
            'data'      => $data,
        );

        if (LAPDI_Config::get('app.debug'))
            LAPDI_Log::info("Sending post args to add tags to OAP: " . @json_encode($postargs));

        $response = LAPDI_Helper::getCurlResults($this->URL, null, $postargs, true);
    
        $xml = simplexml_load_string($response);

        $contact_id = null;

        if (LAPDI_Config::get('app.debug'))
            LAPDI_Log::info("Results of OAP Post: " . @json_encode($xml));

        if (isset($xml->contact))
            $contact_id = (string)$xml->contact->attributes()->id;

        return $contact_id;
    }

	/**
	 * Function to remove OAP tags from a contact
	 *
	 * @since 1.0.0
	 *
	 * @param string $contact_id - The OAP contact ID
	 * @param array $tags - a list of tags to add
	 *
	 * @return string $response - The curl response (XML)
	 */
    public function removeContactTag($contact_id, $tags)
    {
        $data = "";
        $tag_xml = "";
        $reqType= "remove_tag";
        $response = null;

		$valid_tags = array();

		// determine tags present that need to be removed in OAP
		foreach ($tags as $tag)
		{
			if (strpos($tag, "PDID") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "UplineID") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "Contract") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "SqzPgOwner") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "MyPD") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "On file") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "Topline PD") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "DirectUpline") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
			if (strpos($tag, "Bonusto") === 0)
			{
				$valid_tags[] = $tag;
				continue;
			}
		}

        if (!empty($valid_tags))
        {
            foreach ($valid_tags as $tag)
            {
                $tag_xml .= "<tag>$tag</tag>" . PHP_EOL;
            }
    
            $data = <<<STRING
    <contact id='{$contact_id}'>
    {$tag_xml}
    </contact>
STRING;
    
            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("Tags to remove in OAP: " . @json_encode($data));
    
            $data = urlencode($data);
            
            $postargs = array(
                'appid'     => $this->id,
                'key'       => $this->key,
                'return_id' => '1',
                'reqType'   => $reqType,
                'data'      => $data,
            );
    
            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("Sending post args to remove tags in OAP: " . @json_encode($postargs));
    
            $response = LAPDI_Helper::getCurlResults($this->URL, null, $postargs, true);
        }
        else
        {
            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("No valid tags found to remove from OAP");
        }
    
        return $response;
    }

	/**
	 * Function to add a new contact to OAP
	 *
	 * @since 1.0.4
	 *
	 * @param int $id - the contact ID
	 * @param array $params - a list of parameters to add
     * @param string $tag - The default tag for contact
	 *
	 * @return string $response - The curl response (XML)
	 */
    public function updateContact($id, $params, $tag = "Contact Information")
    {
        $reqType = "update";
        $data = <<<STRING
<contact id="{$id}">
    <Group_Tag name="{$tag}">

STRING;
        foreach ($params as $key => $value)
        {
            $data .= <<<STRING
            <field name="{$key}">{$value}</field>
STRING;
        }

        $data .= <<<STRING

    </Group_Tag>
    <Group_Tag name="Sequences and Tags">
        <field name="Contact Tags">{$params['tags']}</field>
        <field name="Sequences">{$params['sequences']}</field>
    </Group_Tag>
</contact>
STRING;

        $postargs = array(
            'appid'     => $this->id,
            'key'       => $this->key,
            'return_id' => '1',
            'reqType'   => $reqType,
            'data'      => $data,
        );

        if (LAPDI_Config::get('app.debug'))
            LAPDI_Log::info("Sending post args to update info to OAP: " . @json_encode($postargs));

        $response = LAPDI_Helper::getCurlResults($this->URL, null, $postargs, true);
    
        return $response;
    }
}

/**
 * TSP_OntraportConn
 *
 * @since 1.0.0
 *
 * @deprecated 2.0.0 Please use LAPDI_OntraportConn instead
 *
 * @return void
 *
 */
class TSP_OntraportConn extends LAPDI_OntraportConn
{

}// end class