<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_CommentOutline implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-comment-outline';
    public $comment;
    public $commentId;
    public $event;

    /**
     * @param WP_Comment $comment
     * @param object $event
     */
    public function __construct($comment, $event){
        $this->comment = $comment;
        $this->event = $event;
        $this->commentId = $event->object_id;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        if($this->comment):
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <?php
            $author = $this->comment->comment_author;
            if($this->comment->comment_author_email && strlen($this->comment->comment_author_email)>3){
                $author .= ' <'.$this->comment->comment_author_email.'>';
            }
            $content = $this->comment->comment_content;
            if(strlen($content) > 30){
                $content = substr($content, 0, 30).' ...';
            }
            ?>
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->commentId); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Date', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->comment->comment_date); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Author', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($author); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Comment', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($content); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
        else: ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->commentId); ?></td>
                </tr>
                <tr>
                    <td class="value" colspan="2">
                        <strong>
                            <?php _e('Comment no longer existing', 'wp-admin-audit'); ?>
                        </strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
        endif;

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}