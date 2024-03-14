<?php

namespace Modular\Connector\Http\Controllers;

use Modular\Connector\Facades\Backup;
use Modular\Connector\Facades\Database;
use Modular\Connector\Facades\Manager;
use Modular\Connector\Facades\Server;
use Modular\Connector\Facades\WhiteLabel;
use Modular\Connector\Helper\OauthClient;
use Modular\Connector\Jobs\ManagerUpdateJob;
use Modular\Connector\Jobs\ManagerUpgradeJob;
use Modular\Connector\Services\Backup\BackupOptions;
use Modular\Connector\Services\Helpers\Utils;
use Modular\ConnectorDependencies\Carbon\Carbon;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
use function Modular\ConnectorDependencies\app;
use function Modular\ConnectorDependencies\request;

/**
 * This class receives the requests from API and handles them.
 */
class HandleController
{
    /**
     * Request ID from Modular
     *
     * @var string
     */
    protected string $mrid;

    /**
     * @var bool
     */
    protected bool $debug = false;

    /**
     * @return array
     * @throws \ErrorException
     */
    public function getOauthCallback()
    {
        $client = OauthClient::getClient();

        $token = $client->oauth->confirmAuthorizationCode(request('code'));

        $client->setAccessToken($token->access_token)
            ->setRefreshToken($token->refresh_token)
            ->setExpiresIn($token->expires_in)
            ->setConnectedAt(Carbon::now())
            ->save();

        return [
            'success' => 'OK',
            'version' => Server::connectorVersion(),
        ];
    }

    /**
     * @return mixed
     * @throws \ErrorException
     * @throws \Exception
     */
    public function getHandleRequest()
    {
        $client = OauthClient::getClient();
        $client->validateOrRenewAccessToken();

        $request = request();

        $mrid = $request->get('mrid', $request->get('request'));

        $request = $client->wordpress->handleRequest($mrid);

        $this->mrid = $mrid;
        $this->debug = !empty($request->debug);

        $payload = $request->body;

        if ($this->debug) {
            defined('MODULAR_CONNECTOR_DEBUG') or define('MODULAR_CONNECTOR_DEBUG', false);
            app()->config->set('app.debug', MODULAR_CONNECTOR_DEBUG);
        }

        $method = 'handle' . Str::studly(str_replace('.', '_', $request->type));

        if (method_exists($this, $method)) {
            /**
             * @see HandleController::handleLogin()
             * @see HandleController::handleManagerUpdate()
             * @see HandleController::handleManagerUpgrade()
             * @see HandleController::handleManagerServerInformation()
             * @see HandleController::handleManagerServerHealth()
             * @see HandleController::handleManagerHealth()
             * @see HandleController::handleManagerQueueHealth()
             * @see HandleController::handleManagerDirectoryTree()
             * @see HandleController::handleManagerDatabaseTree()
             * @see HandleController::handleManagerBackupMake()
             * @see HandleController::handleManagerBackupUpload()
             * @see HandleController::handleManagerBackupInformation()
             * @see HandleController::handleManagerBackupRemove()
             * @see HandleController::handleManagerWhiteLabelUpdate()
             */
            return $this->{$method}($payload);
        }

        return ['method' => 'Missing method: ' . $method];
    }

    /**
     * @param $payload
     * @return void
     */
    protected function handleLogin($payload)
    {
        Manager::login();
    }

    /**
     * @param $payload
     * @return void
     */
    protected function handleManagerUpdate($payload)
    {
        ManagerUpdateJob::dispatch($this->mrid);
    }

    /**
     * @param $payload
     * @return void
     */
    protected function handleManagerUpgrade($payload)
    {
        ManagerUpgradeJob::dispatch($this->mrid, $payload);
    }

    /**
     * @param $payload
     * @return mixed
     */
    protected function handleManagerServerInformation($payload)
    {
        return Server::information();
    }

    /**
     * @param $payload
     * @return void
     */
    protected function handleManagerServerHealth($payload)
    {
        return Server::healthCheck();
    }

    /**
     * Get directory tree
     *
     * @param $payload
     * @return mixed
     */
    protected function handleManagerDirectoryTree($payload)
    {
        return Backup::getDirectoryTree($payload);
    }

    /**
     * Get Database tree
     *
     * @param $payload
     * @return mixed
     */
    protected function handleManagerDatabaseTree($payload)
    {
        return Database::tree();
    }

    /**
     * Returns the backup with the provided $payload name content if existing.
     *
     * @param $payload
     * @return array
     */
    protected function handleManagerBackupInformation($payload)
    {
        return Backup::information();
    }

    /**
     * Creates a backup of the WordPress parts provided in $payload,
     * excluding the paths also included in excluded option.
     *
     * @param $payload
     * @return void
     */
    protected function handleManagerBackupMake($payload)
    {
        Utils::forceResponse(null);

        $options = new BackupOptions($this->mrid, $payload);

        Backup::make($options);
    }

    /**
     * Deletes the backup with the provided $payload name if existing.
     *
     * @param $payload
     * @return array
     */
    protected function handleManagerBackupRemove($payload)
    {
        Utils::forceResponse(null);

        Backup::remove($payload->name ?? null, true);
    }

    /**
     * @param $payload
     * @return mixed
     */
    protected function handleManagerQueueHealth($payload)
    {
        Utils::forceResponse(null);

        do_action('modular_queue_start');
    }

    /**
     * @param $payload
     * @return void
     */
    protected function handleManagerWhiteLabelUpdate($payload)
    {
        Utils::forceResponse(null);

        WhiteLabel::update($payload);
    }
}
