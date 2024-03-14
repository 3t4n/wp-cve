<?php

#[\AllowDynamicProperties]
class FMC_Account {

	function __construct($data) {

		if( ! empty( $data ) && is_array( $data ) ){
			foreach ($data as $property => $value) {
				$this->$property = $value;
			}
		}
	}

	function primary_email() {
		if( count( $this->Emails ) ){
			foreach ($this->Emails as $email) {
				if(array_key_exists("Primary", $email)) {
					return $email["Address"];
				}
			}
			if(sizeof($this->Emails) > 0) {
				return $this->Emails[0]["Address"];
			}
		}
		return false;
	}


}
