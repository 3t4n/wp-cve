<?php

namespace WcMipConnector\Manager;

use WcMipConnector\Repository\ImagesUrlRepository;

defined('ABSPATH') || exit;

class ImagesUrlManager
{
    /** @var ImagesUrlRepository */
    private $imagesUrlRepository;

    /** @var \wpdb */
    protected $woocommerceClass;

    public function __construct()
    {
        $this->imagesUrlRepository = new ImagesUrlRepository();
    }

    /**
     * @param array $productImages
     * @return void
     * @throws \Exception
     */
    public function updateImagesUrl(array $productImages): void
    {
        if (empty($productImages)) {
            return;
        }

        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));

        foreach ($productImages as $productImage) {
            $data = [
                'id_image' => $productImage['ID'],
                'cover' => $productImage['Cover'] === true ? 1 : 0,
                'url' => $productImage['guid'],
                'date_add' => $dateNow->format('Y-m-d H:i:s'),
                'date_update' => $dateNow->format('Y-m-d H:i:s'),
            ];

            $this->imagesUrlRepository->updateImagesUrl($data);
        }
    }

    public function cleanTable(): void
    {
        $this->imagesUrlRepository->cleanTable();
    }
}