<?php


namespace rnpdfimporter\core\Integration\Media;


class MediaIntegration
{
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function EnqueueMedia(){
        \wp_enqueue_media();
    }

}