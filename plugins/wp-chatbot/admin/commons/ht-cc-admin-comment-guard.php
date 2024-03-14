<?php

/**
 *  Show notify if user don't have Comment Guard.
 *
 * @uses at class-htcc-admin.php
 */

if (!defined('ABSPATH')) exit;
?>

<style>
   .comment-guard-notify {
       background: linear-gradient(0, #A7F18C -41.44%, #19C9B7 121.33%);
       box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.25);
       border-radius: 0 5px 5px;
       min-width: 200px;
       top: 105%;
       left: 7%;
       padding: 10px 20px;
       color: #444444;
       font-weight: normal;
       font-size: 13px;
       position: absolute;
       z-index: 10;
       box-sizing: border-box;
       opacity: 1;
       transition: all 0.5s ease;
    }

    .comment-guard-notify p {
        font-family: 'Open Sans', sans-serif;
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 15px;
        color: #165F47;
    }
    .comment-guard-notify .trn {
        position:absolute;
        bottom: 100%;
        left: 0;
        border-bottom: 14px solid #2DCFB1;
        border-right: 14px solid transparent;
    }
   .close-guard-notify {
       position: absolute;
       right: 5px;
       top: 5px;
       width: 15px;
       height: 14px;
       opacity: 0.5;
       cursor: pointer;
   }
   .close-guard-notify:hover {
       opacity: 1;
   }
   .close-guard-notify:before, .close-guard-notify:after {
       position: absolute;
       content: ' ';
       right: 6px;
       height: 15px;
       width: 2px;
       background-color: #333;
   }
   .close-guard-notify:before {
       transform: rotate(45deg);
   }
   .close-guard-notify:after {
       transform: rotate(-45deg);
   }
    .btn-lets_go{
        margin-left: auto;
        margin-right: auto;
        justify-content: center;
        align-items: center;
        font-size: 13px;
        font-family: 'Open Sans', sans-serif;
        line-height: 18px;
        color: #525252;
        width: 187px;
        display: flex;
        height: 32px;
        background: #FFFFFF;
        box-shadow: 0px 1px 4px rgba(74, 84, 234, 0.6);
        border-radius: 5px;
        cursor: pointer;
    }
   .btn-lets_go:hover,.btn-lets_go:active,.btn-lets_go:focus{
       box-shadow: none;
   }


   .comment-guard-notify{
       opacity: 0;
       animation: fadeIn 0.5s;
       animation-delay: 2s;
       animation-fill-mode: forwards;
   }
   @keyframes fadeIn {
       from { opacity: 0; }
       to { opacity: 1; }
   }
</style>
<div class="comment-guard-notify">
    <div class="close-guard-notify"></div>
    <p>Looking for more ways to generate leads? Try putting a Comment Guard on your Facebook post. We'll automatically message any user who comments on your posts to get their opt-ins.</p>
    <div class="trn"></div>
    <a class="btn-lets_go" target="_blank" href="<?php echo $app_domain ?>chatbot-editor/<?php echo $bot_id ?>/comment-guards">Let's Go</a>
</div>



