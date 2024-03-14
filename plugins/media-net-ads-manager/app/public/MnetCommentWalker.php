<?php
namespace Mnet\PublicViews;

class MnetCommentWalker extends \Walker_Comment
{
    public function comment_callback($comment, $args, $depth)
    {
        if (($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback') && $args ['short_ping']) {
            $this->ping($comment, $depth, $args);
        } elseif ($args['format'] === 'html5') {
            $this->html5_comment($comment, $depth, $args);
        } else {
            $this->comment($comment, $depth, $args);
        }
    }
}
