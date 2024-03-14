<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_CommentUtils
{

    /**
     * @return array
     */
    protected static function getCommentAttributes(){
        return array('comment_post_ID', 'comment_content',
            'comment_author', 'comment_author_email',
            'comment_approved', 'comment_karma', 'comment_author_url',
            'comment_date', 'comment_date_gmt', 'comment_type', 'comment_parent',
            'user_id', 'comment_agent', 'comment_author_IP');
    }

    /**
     * @param WP_Comment $currentComment
     * @param WP_Comment|object $priorComment
     * @return array<array>
     */
    public static function getCommentChanges($currentComment, $priorComment){
        $attributes2Check = self::getCommentAttributes();
        return WADA_CompUtils::getChangedAttributes($priorComment, $currentComment, $attributes2Check);
    }

}
