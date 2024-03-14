<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCCategory;
use WcMipConnector\Service\DirectoryService;

class CategoryFactory
{
    public const CATEGORY_DEFAULT_IMAGE = 'https://cdnbigbuy.com/images/category/default_small.jpg';

    /** @var DirectoryService|null  */
    protected $directoryService;

    /**
     * CategoryFactory constructor.
     * @param DirectoryService|null $directoryService
     */
    public function __construct(
        ?DirectoryService $directoryService = null
    ) {
        $this->directoryService = $directoryService;

        if (!$directoryService) {
            $this->directoryService = new DirectoryService();
        }
    }

    /**
     * @param array $categoryData
     * @param bool $overrideCategoryTree
     * @param string $languageIsoCode
     * @param int $categoryParentId
     * @param bool $imageCategoryPostExists
     * @return WCCategory
     */
    public function create(array $categoryData, bool $overrideCategoryTree, string $languageIsoCode, $categoryParentId = 0, bool $imageCategoryPostExists = false): WCCategory
    {
        $categoryModel = new WCCategory();
        $categoryLangFactory = new CategoryLangFactory();
        $categoryLangModel = $categoryLangFactory->create($categoryData['CategoryLangs'], $languageIsoCode);
        $categoryModel->name = $categoryLangModel->name;
        $categoryModel->slug = $categoryLangModel->slug;

        if ($overrideCategoryTree || !$imageCategoryPostExists) {
            $imageResult = $this->directoryService->fileRemoteExist((string)$categoryData['ImageURL']);
            $image = $imageResult ? $categoryData['ImageURL'] : self::CATEGORY_DEFAULT_IMAGE;
            $categoryModel->image =  ['src' => $image];
            $categoryModel->parent = $categoryParentId;
        }

        $categoryModel->id = null;

        return $categoryModel;
    }
}