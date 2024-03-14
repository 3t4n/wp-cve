<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Ajax;

use WPRemoteMediaExt\WPCore\WPajaxCall;
use WPRemoteMediaExt\RemoteMediaExt\Accounts\RemoteMediaFactory;

class AjaxSendRemoteToEditor extends WPajaxCall
{
    public function __construct($onAdmin = true, $mustBeLoggedIn = true)
    {
        parent::__construct('send-remote-attachment-to-editor', 'media-remote-ext', $onAdmin, $mustBeLoggedIn);
        $this->jsvar = 'rmlSendToEditorParams';
        $this->nonceQueryVar = 'nonce';
    }

    public function callback($data)
    {
        $jsattachment = wp_unslash($_POST['attachment']);
        $posthtml = stripslashes_deep($_POST['html']);

        if (empty($jsattachment['accountId']) ||
            empty($jsattachment['remotedata'])
        ) {
            wp_send_json_error();
        }

        $accountId = absint($jsattachment['accountId']);
        
        $media = RemoteMediaFactory::createFromAccountid($accountId, $jsattachment['remotedata']);

        if (is_null($media)) {
            if (empty($jsattachment['url'])) {
                wp_send_json_error();
            }

            $html = '[embed]'.$jsattachment['url'].'[/embed]';
            wp_send_json_success($html);
        }

        unset($jsattachment['remotedata']);

        try {
            $html = $media->toEditorHtml($jsattachment, $posthtml);

            $html = apply_filters('ocs_remote_media_send_to_editor', $html, $media, $jsattachment, $posthtml);

            wp_send_json_success($html);
        } catch (\Exception $e) {
            wp_send_json_error(
                array(
                    'msg' => __('Error sending remote media to editor: '.$e->getMessage(), 'remote-medias-lite')
                )
            );
        }
    }
}
