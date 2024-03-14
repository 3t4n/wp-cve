<?php
if( ! class_exists( 'BM_Guest_User' ) ):
    /**
     * Single message class.
     */
    class BM_Guest_User {
        /**
         * @var int
         */
        public $id;

        /**
         * @var string
         */
        public $secret;

        /**
         * @var string
         */
        public $name;

        /**
         * @var string
         */
        public $email;

        /**
         * @var string
         */
        public $ip;

        /**
         * @var string
         */
        public $created_at;

        /**
         * @var string
         */
        public $updated_at;
    }
endif;;
