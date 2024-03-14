<?php

namespace WcMipConnector\View\Module\MinimumRequirements;

defined('ABSPATH') || exit;

use WcMipConnector\View\Assets\Assets;

class CheckStatus
{
    /** @var Assets  */
    protected $assets;

    public function __construct()
    {
        $this->assets = new Assets();
    }

    public function getErrorStatus(): void
    {
        ?>
            <img class="error-status" width="25" height="25" src="<?php echo $this->assets->getImageAsset('error.svg') ?>" alt="">
        <?php
    }

    public function getCorrectStatus(): void
    {
        ?>
             <img class="okey-status" width="25" height="25" src="<?php echo $this->assets->getImageAsset('done_alt.svg') ?>" alt="">
        <?php
    }
}