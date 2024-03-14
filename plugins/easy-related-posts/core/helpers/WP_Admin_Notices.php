<?php

/*
 * Copyright (C) 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


if (!class_exists('WP_Admin_Notices')) {
    /**
     * Description of WP_Admin_Notices
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     */
    class WP_Admin_Notices {

        /**
         * Instance of this class.
         *
         * @since 1.0.0
         * @var WP_Admin_Notices
         */
        protected static $instance = null;

        /**
         * Name of the array that will be stored in DB
         * @var string 
         * @since 1.0.0
         */
        protected $noticesArrayName = 'WPAdminNotices';

        /**
         * Notices array as loaded from DB
         * @var array
         * @since 1.0.0 
         */
        protected $notices = array();

        /**
         * Costructor
         */
        private function __construct() {
            $this->loadNotices();
            add_action('admin_notices', array($this, 'displayNotices'));
        }

        /**
         * Return an instance of this class.
         *
         * @since 1.0.0
         * @return WP_Admin_Notices
         */
        public static function getInstance() {
            if (null == self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Loads notices from DB
         */
        private function loadNotices() {
            $notices = get_option($this->noticesArrayName);
            if (is_array($notices)) {
                $this->notices = $notices;
            }
        }

        /**
         * Action hook to display notices. 
         * Just echoes notices that should be displayed.
         */
        public function displayNotices() {
            foreach ($this->notices as $key => $notice) {
                if ($this->isTimeToDisplay($notice)) {
                    echo $notice->getContentFormated();
                    $notice->incrementDisplayedTimes();
                }
                if ($notice->isTimeToDie()) {
                    unset($this->notices[$key]);
                }
            }
            $this->storeNotices();
        }

        /**
         * Stores notices in DB
         */
        private function storeNotices() {
            update_option($this->noticesArrayName, $this->notices);
        }

        /**
         * Deletes a notice
         * @param int $notId The notice unique id
         */
        public function deleteNotice($notId) {
            foreach ($this->notices as $key => $notice) {
                if ($notice->getId() === $notId) {
                    unset($this->notices[$key]);
                    break;
                }
            }
            $this->storeNotices();
        }

        /**
         * Adds a notice to be displayed
         * @param erpAdminMessage $notice
         */
        public function addNotice(WP_Notice $notice) {
            $this->notices[] = $notice;
            $this->storeNotices();
        }

        /**
         * Checks if is time to display a notice
         * @param WP_Notice $notice
         * @return bool 
         */
        private function isTimeToDisplay(WP_Notice $notice) {
            $screens = $notice->getScreen();
            if (!empty($screens)) {
                $curScreen = get_current_screen();
                if (!is_array($screens) || !in_array($curScreen->id, $screens)) {
                    return false;
                }
            }

            $usersArray = $notice->getUsers();
            if (!empty($usersArray)) {
                $curUser = get_current_user_id();
                if (!is_array($usersArray) || !in_array($curUser, $usersArray) || $usersArray[$curUser] >= $notice->getTimes()) {
                    return false;
                }
            } elseif ($notice->getTimes() <= $notice->getDisplayedTimes()) {
                return false;
            }

            return true;
        }

    }

}


if (!class_exists('WP_Notice')) {
    /**
     * Description of WP_Notice
     *
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     */
    abstract class WP_Notice {

        /**
         * Notice message to be displayed
         * @var string 
         */
        protected $content;

        /**
         * Notice type 
         * @var string 
         */
        protected $type;

        /**
         * In which screens the notice to be displayed
         * @var array 
         */
        protected $screen;

        /**
         * Unique identifier for notice
         * @var int
         */
        protected $id;

        /**
         * Number of times to be displayed
         * @var int
         */
        protected $times = 1;

        /**
         * User ids this notice should be displayed
         * @var array
         */
        protected $users = array();

        /**
         * Number of times this message is displayed
         * @var int
         */
        protected $displayedTimes = 0;

        /**
         * Keeps track of how many times and to
         * which users this notice is displayed
         * @var array
         */
        protected $displayedToUsers = array();

        public function __construct($content, $times = 1, Array $screen = array()) {
            $this->content = $content;
            $this->screen = $screen;
            $this->id = uniqid();
            $this->times = $times;
        }

        public function getContentFormated($wrapInParTag = true) {
            $before = '<div class="' . $this->type . '">';
            $before .= $wrapInParTag ? '<p>' : '';
            $after = $wrapInParTag ? '</p>' : '';
            $after .= '</div>';
            return $before . $this->getContent() . $after;
        }

        public function incrementDisplayedTimes() {
            $this->displayedTimes++;

            if (array_key_exists(get_current_user_id(), $this->displayedToUsers)) {
                $this->displayedToUsers[get_current_user_id()] ++;
            } else {
                $this->displayedToUsers[get_current_user_id()] = 1;
            }
            return $this;
        }

        public function isTimeToDie() {
            if (empty($this->users)) {
                return $this->displayedTimes >= $this->times;
            } else {
                $i = 0;
                foreach ($this->users as $key => $value) {
                    if (isset($this->displayedToUsers[$value]) && $this->displayedToUsers[$value] >= $this->times) {
                        $i++;
                    }
                }
                if ($i >= count($this->users)) {
                    return true;
                }
            }
            return false;
        }

        public function getScreen() {
            return $this->screen;
        }

        public function setScreen($screen) {
            $this->screen = $screen;
            return $this;
        }

        public function getContent() {
            return $this->content;
        }

        public function setContent($content) {
            $this->content = $content;
            return $this;
        }

        public function setScreenAnywhere() {
            $this->setScreen('anywhere');
            return $this;
        }

        public function getId() {
            return $this->id;
        }

        public function getTimes() {
            return $this->times;
        }

        public function getUsers() {
            return $this->users;
        }

        public function setTimes($times) {
            $this->times = $times;
            return $this;
        }

        public function setUsers(Array $users) {
            $this->users = $users;
            return $this;
        }

        public function getDisplayedTimes() {
            return $this->displayedTimes;
        }

        public function getDisplayedToUsers() {
            return $this->displayedToUsers;
        }

        public function setDisplayedTimes($displayedTimes) {
            $this->displayedTimes = $displayedTimes;
            return $this;
        }

        public function setDisplayedToUsers(Array $displayedToUsers) {
            $this->displayedToUsers = $displayedToUsers;
            return $this;
        }

    }

    class WP_Error_Notice extends WP_Notice {

        protected $type = 'error';

    }

    class WP_Updated_Notice extends WP_Notice {

        protected $type = 'updated';

    }

    class WP_UpdateNag_Notice extends WP_Notice {

        protected $type = 'update-nag';

    }

}

if(!has_action('admin_init', array('WP_Admin_Notices', 'getInstance'))){
    add_action('admin_init', array('WP_Admin_Notices', 'getInstance'));
}