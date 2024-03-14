<?php

namespace Rublon_WordPress\Libs\Classes\Confirmations\Strategy;

use Rublon_WordPress\Libs\Classes\Confirmations\RublonConfirmStrategyForm;

class RublonConfirmStrategy_UserProfileUpdate extends RublonConfirmStrategyForm
{

    protected $action = 'UserProfileUpdate';
    protected $label = 'Change user\'s or own profile data';
    protected $formSelector = '#your-profile';
    protected $pageNowInit = 'user-edit.php';
    protected $pageNowAction = 'user-edit.php';
    protected $confirmMessage = 'Do you want to update profile data for user: %s?';
    protected $confirmMessageOwn = 'Do you want to update your user profile?';
    protected $user;

    protected static $pageNow = array('profile.php', 'user-edit.php');

    /**
     * @return int
     */
    function checkChanges()
    {
        return 1;
    }

    /**
     * @return bool
     */
    function isConfirmationRequired()
    {
        return parent::isConfirmationRequired();
    }

    /**
     * @return array|string|null
     */
    function getConfirmMessage()
    {
        if ($this->isOwnProfile()) {
            return $this->confirmMessageOwn;
        } else {
            return sprintf($this->confirmMessage, $this->getUserLogin());
        }
    }

    /**
     * @return bool
     */
    function isOwnProfile()
    {
        return ($this->user->ID === get_current_user_id());
    }

    /**
     * @return mixed
     */
    function getUserLogin()
    {
        return $this->user->user_login;
    }

    /**
     * @param $user
     * @return $this
     */
    function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    function checkForAction()
    {
        global $pagenow;
        if ($this->isTheAction() && !empty($_POST) && ($pagenow == 'profile.php' || !empty($_POST['user_id']))) {
            $userId = (empty($_POST['user_id']) ? get_current_user_id() : $_POST['user_id']);
            if ($user = get_userdata($userId)) {
                $this->setUser($user);

                $old = array(
                    'email' => $user->user_email,
                    RublonHelper::FIELD_USER_PROTECTION_TYPE => RublonHelper::userProtectionType($user),
                );

                $new = array(
                    'email' => $_POST['email'],
                    RublonHelper::FIELD_USER_PROTECTION_TYPE => (empty($_POST[RublonHelper::FIELD_USER_PROTECTION_TYPE]) ?
                        null : $_POST[RublonHelper::FIELD_USER_PROTECTION_TYPE])
                );

                RublonConfirmations::handleConfirmation($this->getAction(), $_POST, $old, $new);
            }
        }
    }

    /**
     * @return bool
     */
    function isThePage()
    {
        global $pagenow;
        return (is_admin() && !empty($pagenow) && in_array($pagenow, self::$pageNow));
    }

    /**
     * @return bool
     */
    function isTheAction()
    {
        global $pagenow;
        return (is_admin() && !empty($pagenow) && in_array($pagenow, self::$pageNow));
    }

    function pluginsLoaded()
    {
        parent::pluginsLoaded();

        if ($this->isTheAction() && (RublonConfirmations::$dataRestored) || !$this->isConfirmationRequired()) {
            // Update user protection type
            $current_user = wp_get_current_user();
            if (!empty($_POST[RublonHelper::FIELD_USER_PROTECTION_TYPE])
                && $_POST[RublonHelper::FIELD_USER_PROTECTION_TYPE] !== RublonHelper::userProtectionType($current_user)) {
                RublonHelper::setUserProtectionType(
                    $current_user,
                    $_POST[RublonHelper::FIELD_USER_PROTECTION_TYPE]
                );
            }
            if (!empty($_POST['email']) && $_POST['email'] !== RublonHelper::getUserEmail($current_user)) {
                RublonHelper::clearMobileUserStatus($current_user);
            }
        }
    }
}
