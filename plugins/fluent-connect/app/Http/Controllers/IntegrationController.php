<?php

namespace FluentConnect\App\Http\Controllers;

use FluentConnect\App\Models\Feed;
use FluentConnect\App\Models\Integration;
use FluentConnect\App\Models\Trigger;
use FluentConnect\App\Services\ThriveCart\ThriveCart;
use FluentConnect\Framework\Request\Request;
use FluentConnect\Framework\Support\Arr;

class IntegrationController extends Controller
{

    public function index(Request $request)
    {
        return [
            'integrations' => Integration::paginate()
        ];
    }

    public function getInfo(Request $request, $id)
    {
        $integration = Integration::findOrFail($id);

        $accountInfo = (new ThriveCart)->getAccountInfo(Arr::get($integration->settings, 'api_key'));

        return [
            'integration' => $integration,
            'html_info'   => '',
            'other_data'  => [
                'webhook_url' => site_url('index.php?fcon_webhook=1&provider=' . $integration->provider . '&hook_id=' . $integration->id . '&sign=' . Arr::get($integration->settings, 'webhook_signature')),
                'account' => $accountInfo
            ]
        ];
    }

    public function save(Request $request, $integrationKey)
    {
        $this->validate($request->all(), [
            'api_key' => 'required'
        ]);

        $apiKey = sanitize_text_field($request->get('api_key'));
        $accountInfo = (new ThriveCart())->getAccountInfo($apiKey);

        if (is_wp_error($accountInfo)) {
            return $this->sendError([
                'message' => 'API Key is invalid. Error: ' . $accountInfo->get_error_message()
            ]);
        }

        if (Integration::where('remote_id', $accountInfo['account_id'])->where('provider', 'thrivecart')->first()) {
            return $this->sendError([
                'message' => 'Provided Account is already exist in the database'
            ]);
        }

        $integrationData = [
            'provider'  => 'thrivecart',
            'title'     => 'ThriveCart - ' . $accountInfo['account_name'],
            'status'    => 'draft',
            'remote_id' => $accountInfo['account_name'],
            'settings'  => [
                'api_key'           => $apiKey,
                'webhook_verified'  => false,
                'webhook_signature' => substr(md5(wp_generate_uuid4() . $accountInfo['account_name']), 0, 10)
            ]
        ];

        $integration = Integration::create($integrationData);

        return [
            'message'     => 'API Key has been validated. Please setup webhook now!',
            'integration' => $integration
        ];
    }

    public function update(Request $request, $id)
    {
        $integration = Integration::findOrFail($id);

        $updateData = $request->all();

        if(isset($updateData['status']) && $updateData['status'] == 'published') {
            $updateData['settings'] = $integration->settings;
            $updateData['settings']['webhook_verified'] = true;
        }

        $integration->fill($updateData)->save();

        return $this->getInfo($request, $id);
    }

    public function delete(Request $request, $id)
    {
        Integration::where('id', $id)->delete();
        Trigger::where('integration_id')->delete();

        return [
            'message' => 'Selected API Integrations and connected triggers has been deleted'
        ];
    }
}


