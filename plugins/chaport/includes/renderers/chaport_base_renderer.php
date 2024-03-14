<?php
abstract class ChaportBaseRenderer {
    private $user_еmail;
    private $user_name;

    abstract function render();

    public function setUserEmail($email) {
        if (is_string($email)) {
            // throw new Exception('Email should be a string');
            $this->user_email = $email;
        }
        // $this->userEmail = $email;
    }

    public function setUserName($name) {
        if (is_string($name)) {
            // throw new Exception('Name should be a string');
            $this->user_name = $name;
        }
    }

    protected function renderUserDetails() {
        require(dirname(dirname(__FILE__)) . '/snippets/chaport_user_data_snippet.php');
    }
}
