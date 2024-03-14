<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Ajax;

use WPRemoteMediaExt\Guzzle\Http\Exception\ClientErrorResponseException;
use WPRemoteMediaExt\Guzzle\Http\Exception\CurlException;

use WPRemoteMediaExt\RemoteMediaExt\Accounts\RemoteAccountFactory;
use WPRemoteMediaExt\WPCore\WPajaxCall;

class AjaxQueryAttachments extends WPajaxCall
{
    public function __construct($onAdmin = true, $mustBeLoggedIn = true)
    {
        parent::__construct('query-remote-attachments', 'media-remote-ext', $onAdmin, $mustBeLoggedIn);
        $this->jsvar = 'rmlQueryAttachmentsParams';
    }

    public function callback($data)
    {
        $accountID = 0;
        if (isset($_REQUEST['query']['account_id'])) {
            $accountID = esc_attr($_REQUEST['query']['account_id']);
        }

        $account = RemoteAccountFactory::create($accountID);
        $service = $account->getService();

        $return = array();
        $return['data'] = array();
        try {
            if (is_null($service)) {
                $return['success'] = false;
                $return['statuscode'] = 0;
                $return['msg']        = 'account service not found';
                wp_send_json($return);
            }
            $return['data'] = $service->getUserAttachments();
        } catch (ClientErrorResponseException $e) {
            $return['statuscode'] = $e->getResponse()->getStatusCode();
            $return['msg']        = $e->getResponse()->getMessage();
            wp_send_json_error($return);
        } catch (CurlException $e) {
            $return['statuscode'] = $e->getErrorNo();
            $return['msg']        = $e->getError();
            wp_send_json_error($return);
        } catch (\Exception $e) {
            $return['statuscode'] = $e->getCode();
            $return['msg']        = $e->getMessage();
            wp_send_json_error($return);
        }

        wp_send_json_success($return['data']);
    }
}
