<?php
/**
 * Rule model
 *
 * @author      Timo Reith <timo@ifeelweb.de>
 * @copyright   Copyright (c) ifeelweb.de
 * @version     $Id: Rule.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package     Psn_Model
 */
class Psn_Model_Rule extends IfwPsn_Wp_ORM_Model
{
    /**
     * @var string
     */
    public static $_table = 'psn_rules';

    /**
     * @var bool
     */
    protected $_ignoreInherit = false;

    /**
     * @var string|null
     */
    protected $_notificationBody;

    /**
     * @var string|null
     */
    protected $_notificationSubject;

    /**
     * @var null|array
     */
    protected $_recipient;

    /**
     * @var string
     */
    protected $_dynamicRecipient;

    /**
     * @var string
     */
    protected $_dynamicMatching;

    /**
     * @var null|array
     */
    protected $_ccSelect;

    /**
     * @var null|array
     */
    protected $_bccSelect;

    /**
     * @var null|array
     */
    protected $_editorRestriction;

    /**
     * @var null|array
     */
    protected $_postWhitelist;

    /**
     * @var null|array
     */
    protected $_postBlacklist;

    /**
     * @var null|array
     */
    protected $_excludeRecipients;

    /**
     * @var Psn_Notification_Placeholders
     */
    protected $_replacer;

    /**
     * Custom data storage
     * @var array
     */
    protected $_data = array();



    /**
     * @param $orm
     * @return mixed
     */
    public static function active($orm) {
        return $orm->where('active', 1);
    }

    /**
     * @param $subject
     */
    public function setNotificationSubject($subject)
    {
        $this->set('notification_subject', $subject);
    }

    /**
     * @return string
     */
    public function getRawSubject()
    {
        return $this->get('notification_subject');
    }

    /**
     * Retrieves the prepared notification subject
     *
     * @return string
     */
    public function getNotificationSubject()
    {
        if ($this->_notificationSubject === null) {

            $subject = $this->get('notification_subject');
            $subject = apply_filters('psn_rule_notification_subject', $this->_replacer->replace($subject), $this);

            $this->_notificationSubject = $subject;
        }

        return $this->_notificationSubject;
    }

    /**
     * @return string
     */
    public function getRawBody()
    {
        return html_entity_decode($this->get('notification_body'));
    }

    /**
     * @param $body
     */
    public function setNotificationBody($body)
    {
        $this->set('notification_body', $body);
    }

    /**
     * Retrieves the prepared notification body text
     *
     * @return string
     */
    public function getNotificationBody()
    {
        if ($this->_notificationBody === null) {

            // see: http://stackoverflow.com/questions/6275380/does-html-entity-decode-replaces-nbsp-also-if-not-how-to-replace-it
            if (IfwPsn_Wp_Proxy_Blog::getCharset() == 'UTF-8') {
                $body = str_replace("\xC2\xA0", ' ', html_entity_decode($this->get('notification_body'), ENT_COMPAT, IfwPsn_Wp_Proxy_Blog::getCharset()));
            } else {
                $body = str_replace("\xA0", ' ', html_entity_decode($this->get('notification_body'), ENT_COMPAT, IfwPsn_Wp_Proxy_Blog::getCharset()));
            }

            $body = apply_filters('psn_rule_notification_body', $this->_replacer->replace($body), $this);

            $this->_notificationBody = $body;
        }

        return $this->_notificationBody;
    }

    /**
     * @return string
     */
    public function getPostType()
    {
        return $this->get('posttype');
    }

    /**
     * @return string
     */
    public function getStatusBefore()
    {
        return $this->get('status_before');
    }

    /**
     * @return string
     */
    public function getStatusAfter()
    {
        return $this->get('status_after');
    }

    /**
     * @return bool
     */
    public function isLoopTo()
    {
        return (int)$this->get('to_loop') === 1;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        $categories = $this->get('categories');

        if (!empty($categories)) {
            return unserialize($categories);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getRecipient()
    {
        if ($this->_recipient === null) {

            $this->_recipient = array();

            $value = $this->get('recipient');

            if (!empty($value)) {

                $value = @unserialize($value);

                if ($value !== false) {
                    // unserialize worked
                    if (!is_array($value)) {
                        $value = array($value);
                    }
                    $this->_recipient = $value;

                } else {
                    // for backwards compat put string in array to work on multiselect
                    $this->_recipient = array($this->get('recipient'));
                }
            }
        }

        return $this->_recipient;
    }

    /**
     * @return string
     */
    public function getDynamicRecipient()
    {
        if ($this->_dynamicRecipient === null) {
            $this->_dynamicRecipient = $this->get('to_dyn');
        }
        return $this->_dynamicRecipient;
    }

    /**
     * @return string
     */
    public function getDynamicMatching()
    {
        if ($this->_dynamicMatching === null) {
            $dynMatch = $this->get('dyn_match');
            $this->_dynamicMatching = trim($dynMatch);
        }
        return $this->_dynamicMatching;
    }

    /**
     * @return bool
     */
    public function hasDynamicMatching()
    {
        $value = $this->getDynamicMatching();
        return !empty($value);
    }

    /**
     * @return array
     */
    public function getCcSelect()
    {
        if ($this->_ccSelect === null) {

            $this->_ccSelect = array();

            $value = $this->get('cc_select');

            if (!empty($value)) {

                $value = @unserialize($value);

                if ($value !== false) {
                    // unserialize worked
                    if (!is_array($value)) {
                        $value = array($value);
                    }
                    $this->_ccSelect = $value;
                }
            }
        }

        return $this->_ccSelect;
    }

    /**
     * @return array
     */
    public function getBccSelect()
    {
        if ($this->_bccSelect === null) {

            $this->_bccSelect = array();

            $value = $this->get('bcc_select');

            if (!empty($value)) {

                $value = @unserialize($value);

                if ($value !== false) {
                    // unserialize worked
                    if (!is_array($value)) {
                        $value = array($value);
                    }
                    $this->_bccSelect = $value;
                }
            }
        }

        return $this->_bccSelect;
    }

    /**
     * @return array
     */
    public function getEditorRestriction()
    {
        if ($this->_editorRestriction === null) {

            $this->_editorRestriction = array();

            $value = $this->get('editor_restriction');

            if (!empty($value)) {

                $value = @unserialize($value);

                if ($value !== false) {
                    // unserialize worked
                    if (!is_array($value)) {
                        $value = array($value);
                    }
                    $this->_editorRestriction = $value;
                }
            }
        }

        return $this->_editorRestriction;
    }

    /**
     * @return array|null
     */
    public function getPostWhitelist()
    {
        if ($this->_postWhitelist === null) {
            $value = $this->get('post_whitelist');
            $this->_postWhitelist = array_filter(array_unique(array_map('intval', explode(',',$value))));
        }

        return $this->_postWhitelist;
    }

    /**
     * @return array|null
     */
    public function getPostBlacklist()
    {
        if ($this->_postBlacklist === null) {
            $value = $this->get('post_blacklist');
            $this->_postBlacklist = array_filter(array_unique(array_map('intval', explode(',',$value))));
        }

        return $this->_postBlacklist;
    }

    /**
     * @return array|null
     */
    public function getExcludeRecipients()
    {
        if ($this->_excludeRecipients === null) {
            $value = $this->get('exclude_recipients');
            $this->_excludeRecipients = array_filter(array_unique(array_map('trim', explode(',',$value))));
        }

        return $this->_excludeRecipients;
    }

    /**
     * The main match method. Determines a post status transition matches a notification rule's settings
     *
     * @param $post
     * @param $before
     * @param $after
     * @return bool
     */
    public function matches($post, $before, $after)
    {
        $result = $this->matchesPostType($post->post_type);

        if ($result === true) {
            $result = $this->matchesStatus($before, $after);
        }
        if ($result === true) {
            $result = $this->matchesCategories($post);
        }
        if ($result === true) {
            $result = $this->matchesPostBlackAndWhitelist($post);
        }
        if ($result === true) {
            $result = $this->matchesSpecialCases($post, $before, $after);
        }
        if ($result === true) {
            $result = $this->matchesDynamicCondition($post);
        }


//        $result =
//            $this->matchesPostType($post->post_type) and
//            $this->matchesStatus($before, $after) and
//            $this->matchesCategories($post) and
//            $this->matchesPostBlackAndWhitelist($post) and
//            $this->matchesSpecialCases($post, $before, $after)
//        ;
//
//        if (!$this->matchesDynamicCondition($post)) {
//            $result = false;
//        }

        $result = apply_filters('psn_rule_matches', $result, $this, $post, $before, $after);

        return $result;
    }

    /**
     * Checks if the rule matches the post's type
     *
     * @param string $postType
     * @return bool
     */
    public function matchesPostType($postType)
    {
        return $this->getPostType() == 'all' or $this->getPostType() == $postType;
    }

    /**
     * Checks if the rule matches the post's status transitions
     *
     * @param string $before
     * @param string $after
     * @return bool
     */
    public function matchesStatus($before, $after)
    {
        return
            $this->_matchesBeforeStatus($before) and
            $this->_matchesAfterStatus($after)
            ;
    }

    /**
     * Checks if before status matches
     *
     * @param $before
     * @return bool
     */
    protected function _matchesBeforeStatus($before)
    {
        $result = false;

        $ruleStatus = $this->getStatusBefore();

        if (is_serialized($ruleStatus)) {
            $ruleStatuses = unserialize($ruleStatus);
            if (is_array($ruleStatuses)) {
                foreach ($ruleStatuses as $ruleStatus) {
                    $result = $this->_matchesStatus($ruleStatus, $before);
                    if ($result === true) {
                        break;
                    }
                }
            }
        } else {
            $result = $this->_matchesStatus($ruleStatus, $before);
        }

        return $result;


//        if (
//            // exact match:
//            $this->getStatusBefore() == $before or
//
//            // "anything" matches always:
//            $this->getStatusBefore() == 'anything' or
//
//            // "not_published" validation:
//            ($this->getStatusBefore() == 'not_published' && $before != 'publish') or
//
//            // "not_private" validation:
//            ($this->getStatusBefore() == 'not_private' && $before != 'private') or
//
//            // "not_pending" validation:
//            ($this->getStatusBefore() == 'not_pending' && $before != 'pending') or
//
//            // "not_scheduled" validation:
//            ($this->getStatusBefore() == 'not_scheduled' && $before != 'future') or
//
//            // "not_trash" validation:
//            ($this->getStatusBefore() == 'not_trash' && $before != 'trash')
//
//            ) {
//
//            return true;
//        }
//
//        return false;
    }

    /**
     * Checks if after status matches
     *
     * @param $after
     * @return bool
     */
    protected function _matchesAfterStatus($after)
    {
        $result = false;

        $ruleStatus = $this->getStatusAfter();

        if (is_serialized($ruleStatus)) {
            $ruleStatuses = unserialize($ruleStatus);
            if (is_array($ruleStatuses)) {
                foreach ($ruleStatuses as $ruleStatus) {
                    $result = $this->_matchesStatus($ruleStatus, $after);
                    if ($result === true) {
                        break;
                    }
                }
            }
        } else {
            $result = $this->_matchesStatus($ruleStatus, $after);
        }

        return $result;

//        if (
//            // exact match:
//            $this->getStatusAfter() == $after or
//
//            // "anything" matches always:
//            $this->getStatusAfter() == 'anything' or
//
//            // "not_published" validation:
//            ($this->getStatusAfter() == 'not_published' && $after != 'publish') or
//
//            // "not_private" validation:
//            ($this->getStatusAfter() == 'not_private' && $after != 'private') or
//
//            // "not_pending" validation:
//            ($this->getStatusAfter() == 'not_pending' && $after != 'pending') or
//
//            // "not_scheduled" validation:
//            ($this->getStatusAfter() == 'not_scheduled' && $after != 'future') or
//
//            // "not_trash" validation:
//            ($this->getStatusAfter() == 'not_trash' && $after != 'trash')
//
//            ) {
//
//            return true;
//        }
//
//        return false;
    }

    /**
     * Checks if status matches
     *
     * @param $ruleStatus
     * @param $postStatus
     * @return bool
     */
    protected function _matchesStatus($ruleStatus, $postStatus)
    {
        if (
            // exact match:
            $ruleStatus == $postStatus or

            // "anything" matches always:
            $ruleStatus == 'anything' or

            // "not_published" validation:
            ($ruleStatus == 'not_published' && $postStatus != 'publish') or

            // "not_private" validation:
            ($ruleStatus == 'not_private' && $postStatus != 'private') or

            // "not_pending" validation:
            ($ruleStatus == 'not_pending' && $postStatus != 'pending') or

            // "not_scheduled" validation:
            ($ruleStatus == 'not_scheduled' && $postStatus != 'future') or

            // "not_trash" validation:
            ($ruleStatus == 'not_trash' && $postStatus != 'trash')

            ) {

            return true;
        }

        return false;
    }

    /**
     * Checks for special matching cases
     *
     * @param $post
     * @param $before
     * @param $after
     * @return bool
     */
    public function matchesSpecialCases($post, $before, $after)
    {
        return
            $this->_matchesInheritanceSettings($before, $after) and
            $this->_matchesEditorRestriction()
            ;
    }

    /**
     * @param $post
     * @return mixed|void
     */
    public function matchesDynamicCondition($post)
    {
        return apply_filters('psn_rule_matches_dynamic_condition', true, $this, $post);
    }

    /**
     * Checks if inheritance settings match
     *
     * @param $before
     * @param $after
     * @return bool
     */
    protected function _matchesInheritanceSettings($before, $after)
    {
        if (
            $this->isIgnoreInherit() === false or
            ($this->isIgnoreInherit() === true && $before != 'inherit' && $after != 'inherit') ) {

            return true;
        }

        return false;
    }

    /**
     * Checks if editor restriction matches
     *
     * @return bool
     */
    protected function _matchesEditorRestriction()
    {
        $editorRestriction = $this->getEditorRestriction();

        if (
            // no restriction set:
            empty($editorRestriction) or
            // determine if user is member of restricted roles:
            IfwPsn_Wp_Proxy_User::isCurrentUserMemberOfRoles($editorRestriction)

            ) {

            return true;
        }

        return false;
    }

    /**
     * @param $post
     * @return bool
     */
    public function matchesCategories($post)
    {
        $categories = $this->getCategories();

        if ($categories === null) {
            // no categories filter set
            return true;
        }

        $postCategories = IfwPsn_Wp_Proxy_Post::getAttachedCategoriesIds($post);

        if (isset($categories['include'])) {
            $include = $categories['include'];
        } else {
            // no include set, get all
            $include = IfwPsn_Wp_Proxy_Post::getAllCategoryIds(IfwPsn_Wp_Proxy_Post::getType($post));
        }

        $exclude = array();
        if (isset($categories['exclude'])) {
            $exclude = $categories['exclude'];
        }

        // the includes which are not dominated by excludes
        $includeDiff = array_diff($include, $exclude);

        if (is_array($postCategories)) {
            if (is_array($exclude)) {
                if (count(array_intersect($postCategories, $exclude)) > 0) {
                    // #191: post has cats that should be excluded
                    return false;
                }
            }

            if (count(array_intersect($postCategories, $includeDiff)) > 0) {
                // post has cats that should be included
                return true;
            }
        }

        return false;
    }

    /**
     * @param $post
     * @return bool
     */
    public function matchesPostBlackAndWhitelist($post)
    {
        $whitelist = $this->getPostWhitelist();
        $blacklist = $this->getPostBlacklist();

        if ((!empty($whitelist) || !empty($blacklist)) && $post instanceof WP_Post) {
            if (!empty($blacklist) && in_array($post->ID, $blacklist)) {
                // blacklist match
                return false;
            }
            if (!empty($whitelist) && !in_array($post->ID, $whitelist)) {
                // whitelist match
                return false;
            }
        }

        return true;
    }

    /**
     * @param bool $ignore
     */
    public function setIgnoreInherit($ignore = true)
    {
        if (is_bool($ignore)) {
            $this->_ignoreInherit = $ignore;
        }
    }

    /**
     * @return bool
     */
    public function isIgnoreInherit()
    {
        return $this->_ignoreInherit;
    }

    /**
     * @return bool
     */
    public function isExcludeCurrentUser()
    {
        return (int)$this->get('exclude_current_user') === 1;
    }

    public static function getMax()
    {
        // please respect my work for this plugin and buy the premium version
        // at http://codecanyon.net/item/post-status-notifier/4809420?ref=ifeelweb
        // otherwise I can not continue updating this plugin with new features
        return apply_filters('psn_max_rules', 2);
    }

    public static function hasMax()
    {
        return self::getMax() > 0;
    }

    public static function reachedMax()
    {
        return IfwPsn_Wp_ORM_Model::factory('Psn_Model_Rule')->count() >= self::getMax();
    }

    /**
     * @param Psn_Notification_Placeholders $replacer
     */
    public function setReplacer($replacer)
    {
        $this->_replacer = $replacer;
    }

    /**
     * @return Psn_Notification_Placeholders
     */
    public function getReplacer()
    {
        return $this->_replacer;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setData($key, $value)
    {
        $this->_data[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasData($key)
    {
        return isset($this->_data[$key]);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getData($key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        return null;
    }

    /**
     * @return string
     */
    public function getAttachment()
    {
        return sanitize_textarea_field( $this->get('attachment') );
    }

    /**
     * @return bool
     */
    public function hasAttachment(): bool
    {
        $attachment = $this->getAttachment();
        return !empty($attachment);
    }

}
