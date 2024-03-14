<?php

namespace Modular\Connector\Jobs;

use Modular\Connector\Events\ManagerItemsUpgraded;
use Modular\Connector\Facades\Core;
use Modular\Connector\Facades\Database;
use Modular\Connector\Facades\Plugin;
use Modular\Connector\Facades\Theme;
use Modular\Connector\Facades\Translation;

class ManagerUpgradeJob extends AbstractJob
{
    /**
     * @var string
     */
    protected string $mrid;

    /**
     * @var mixed
     */
    protected $payload;

    /**
     * @param string $mrid
     */
    public function __construct(string $mrid, $payload)
    {
        $this->mrid = $mrid;
        $this->payload = $payload;
    }

    public function handle()
    {
        $payload = $this->payload;

        if (isset($payload->plugins)) {
            $result['plugins'] = Plugin::upgrade($payload->plugins);
        } else if (isset($payload->themes)) {
            $result['themes'] = Theme::upgrade($payload->themes);
        } else if (isset($payload->core)) {
            $result['core'] = Core::upgrade();
        } else if (isset($payload->translations)) {
            $result['translations'] = Translation::upgrade();
        } else if (isset($payload->database)) {
            $result['database'] = Database::upgrade();
        }

        ManagerItemsUpgraded::dispatch($this->mrid, $result);

        return $result;
    }
}
