<?php

declare(strict_types=1);

namespace Holded\Woocommerce;

use Holded\Woocommerce\Services\Admin;

/**
 * Define the holded plugin.
 */
class Holded
{
    private const VERSION_NUMBER = '3.4.8-%s';

    /** @var string */
    public $version = '';

    /**
     * @var array<string, string>
     */
    public $manifest;

    public function __construct()
    {
        $this->manifest = $this->readManifest();
        // Version is also in holded-integration.php file. Change in it too.
        $this->version = sprintf(self::VERSION_NUMBER, $this->manifest['BUILD_COMMIT']);
    }

    public function load(): void
    {
        $this->languages();

        (new Admin())->load();
    }

    private function languages(): void
    {
        load_plugin_textdomain(HOLDED_I10N_DOMAIN, false, basename(dirname(__FILE__)).'/lang/');
    }

    /**
     * @return array<string, mixed>
     */
    public function readManifest(): array
    {
        $manifest = file_get_contents(__DIR__.'/../manifest.json');

        if ($manifest === false) {
            throw new \Exception('File manifest not found.');
        }

        $data = json_decode($manifest, true);

        if ($data === null) {
            throw new \Exception('File manifest not able to read.');
        }

        return $data;
    }
}
