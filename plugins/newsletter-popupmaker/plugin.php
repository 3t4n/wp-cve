<?php

class NewsletterPopupMaker extends NewsletterAddon {

    static $instance;

    function __construct($version) {
        self::$instance = $this;
        parent::__construct('popupmaker', $version);
        $this->setup_options();
    }

    function init() {
        parent::init();

        // No admin side required by now
    }

}
