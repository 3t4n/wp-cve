<?php

namespace Modular\Connector\Jobs\Hooks;

use Modular\Connector\Events\AbstractEvent;
use Modular\Connector\Facades\Server;
use Modular\Connector\Helper\OauthClient;
use Modular\Connector\Jobs\AbstractJob;
use Modular\ConnectorDependencies\Carbon\Carbon;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
use function Modular\ConnectorDependencies\class_basename;

class HookSendEventJob extends AbstractJob
{
    /**
     * @var array
     */
    protected array $event;

    /**
     * @param AbstractEvent $event
     */
    public function __construct(AbstractEvent $event)
    {
        $name = Str::snake(class_basename($event), '.');

        $event = [
            'type' => str_ireplace('.event', '', $name),
            'connector_version' => Server::connectorVersion(),
            'created' => Carbon::now()->timestamp,
            'mrid' => $event->mrid,
            'data' => $event->payload,
        ];

        $this->event = $event;
    }

    /**
     * @return void
     * @throws \ErrorException
     */
    public function handle()
    {
        $client = OauthClient::getClient();
        $client->validateOrRenewAccessToken();

        $client->wordpress->handleHook($this->event);
    }
}
