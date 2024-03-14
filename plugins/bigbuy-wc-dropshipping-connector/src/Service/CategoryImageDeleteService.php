<?php

namespace WcMipConnector\Service;

use WcMipConnector\Manager\CategoryManager;
use WcMipConnector\Manager\CategoryMapManager;
use WcMipConnector\Manager\ConfigurationOptionManager;

defined('ABSPATH') || exit;

class CategoryImageDeleteService
{
    private const MAX_DATE_TO_DELETE_PROCESS = 7;

    /** @var CategoryService */
    protected $categoryService;
    /** @var CategoryMapManager */
    protected $categoryMapManager;
    /** @var CategoryManager */
    protected $categoryManager;
    /** @var DirectoryService */
    protected $directoryService;
    /** @var SystemService */
    protected $systemService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->categoryMapManager = new CategoryMapManager();
        $this->categoryManager = new CategoryManager();
        $this->directoryService = new DirectoryService();
        $this->systemService = new SystemService();
    }

    /**
     * @return bool
     */
    public function deleteImageCategoryProcess(): bool
    {
        $lastCategoryImageDeleteDate = ConfigurationOptionManager::getCategoryImageDeleteDate();

        $currentTime = \date('Y-m-d H:i:s');

        if (!$lastCategoryImageDeleteDate) {
            ConfigurationOptionManager::setCategoryImageDeleteDate($currentTime);

            return false;
        }

        $lastCategoryImageDeleteDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $lastCategoryImageDeleteDate);
        $currentDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $currentTime);
        $dateDiff = \date_diff($lastCategoryImageDeleteDateTime, $currentDateTime);

        if ($dateDiff->days < self::MAX_DATE_TO_DELETE_PROCESS) {
            return true;
        }

        ConfigurationOptionManager::setCategoryImageDeleteDate($currentTime);

        $categoryShopIndexedByCategoryId = $this->categoryMapManager->findCategoryShopIndexedByCategoryId();

        if (empty($categoryShopIndexedByCategoryId)) {
            return false;
        }

        $this->deleteCategoryImageData(\array_values($categoryShopIndexedByCategoryId));

        return true;
    }

    /**
     * @param array $categoryShopIndexedByCategoryId
     */
    public function deleteCategoryImageData(array $categoryShopIndexedByCategoryId): void
    {
        if (empty($categoryShopIndexedByCategoryId)) {
            return;
        }

        $categoryImagePostIdIndexedByCategoryId = $this->categoryManager->findCategoryImagePostIdIndexedByCategoryShopId($categoryShopIndexedByCategoryId);

        if (empty($categoryImagePostIdIndexedByCategoryId)) {
            return;
        }

        $imageCategoryPost = $this->categoryManager->findImagePostByPostIds($categoryImagePostIdIndexedByCategoryId);

        if (empty($imageCategoryPost)) {
            return;
        }

        $this->deleteCategoryImageAttachment($imageCategoryPost);
    }

    /**
     * @param array $imageCategoryPostIndexedByGuid
     */
    public function deleteCategoryImageAttachment(array $imageCategoryPostIndexedByGuid): void
    {
        if (!$imageCategoryPostIndexedByGuid) {
            return;
        }

        foreach ($imageCategoryPostIndexedByGuid as $imageCategoryPost) {
            $imagePostTitle = \pathinfo($imageCategoryPost['post_title']);
            $imagePostNameCleaned = $imagePostTitle['filename'];

            if (\strpos($imagePostTitle['filename'], "-")) {
                $imagePostNameCleaned = \substr($imagePostTitle['filename'], 0, \strpos($imagePostTitle['filename'], "-"));
            }

            $duplicatedPostIds = $this->categoryManager->findImagePostIdsIndexedByIds($imageCategoryPost['ID'], $imagePostNameCleaned);

            if(!empty($duplicatedPostIds)) {
                $this->deleteCategoryMetaData($duplicatedPostIds);
                $this->categoryManager->deleteImagePostById($duplicatedPostIds);
            }
        }
    }

    /**
     * @param array $duplicatedPostIds
     */
    private function deleteCategoryMetaData(array $duplicatedPostIds): void
    {
        if (empty($duplicatedPostIds)) {
            return;
        }

        $imagesMetaData = $this->categoryManager->findImagePostMetaByPostId($duplicatedPostIds);

        if (empty($imagesMetaData)) {
            return;
        }

        $metaDataFilesToDelete = [];
        $metaDataIds = \array_column($imagesMetaData, 'meta_id');


        foreach ($imagesMetaData as $imageMetaData) {
            if ($imageMetaData['meta_key'] !== '_wp_attachment_metadata') {
                continue;
            }

            $metaValue = \unserialize($imageMetaData['meta_value'], [true]);
            $fileInfo = \pathinfo($metaValue['file']);
            $dirFileName = $this->directoryService->getUploadDir() .'/'. $fileInfo['dirname'] .'/';
            $metaDataFilesToDelete[$fileInfo['basename']] = $dirFileName. $fileInfo['basename'];

            foreach($metaValue['sizes'] as $metaValuesSizes) {
                $metaDataFilesToDelete[$metaValuesSizes['file']] = $dirFileName . $metaValuesSizes['file'];
            }
        }

        $this->categoryService->deleteFiles($metaDataFilesToDelete);
        $this->categoryManager->deleteImageMetaDataByMetaDataId($metaDataIds);
    }

    /**
     * @param string $imageGuid
     * @param string $imagePostTitle
     */
    private function deleteCategoryAttachment(string $imageGuid, string $imagePostTitle): void
    {
        $pastYear = ((int)\date('Y')) - 1;

        if (is_dir($this->directoryService->getUploadDirByYear($pastYear))) {
            $this->deleteImages($imageGuid, $imagePostTitle, $pastYear);
        }

        if (is_dir($this->directoryService->getUploadDirByCurrentYear())) {
            $this->deleteImages($imageGuid, $imagePostTitle);
        }
    }

    /**
     * @param string $imageGuid
     * @param string $imagePostName
     * @param int|null $year
     */
    private function deleteImages(string $imageGuid, string $imagePostName, int $year = null): void
    {
        $yearDir = $year !== null ? $this->directoryService->getUploadDirByYear($year): $this->directoryService->getUploadDirByCurrentYear();

        if ($this->deleteImagesByGlob($imageGuid, $yearDir, $imagePostName)) {
            return;
        }

        $this->deleteImagesByDirectoryIterator($imageGuid, $yearDir, $imagePostName);
    }

    /**
     * @param string $yearDir
     * @param string $imagePostName
     * @return bool
     */
    private function deleteImagesByShellExec(string $yearDir, string $imagePostName): bool
    {
        if (!\is_callable('shell_exec') || false !== \stripos(\ini_get('disable_functions'), 'shell_exec')) {
            return false;
        }

        \shell_exec('find '.$yearDir.'/ -type f -name "'.$imagePostName.'*" -exec rm "{}" \;');

        return true;
    }

    /**
     * @param string $categoryGuidUrl
     * @param string $yearDir
     * @param string $imagePostName
     * @return bool
     */
    private function deleteImagesByGlob(string $categoryGuidUrl, string $yearDir, string $imagePostName): bool
    {
        if (!\is_callable('glob') || false !== \stripos(\ini_get('disable_functions'), 'glob')) {
            return false;
        }

        $monthsDir = @glob($yearDir . '/*' , GLOB_ONLYDIR);

        if (empty($monthsDir)) {
            return false;
        }

        foreach ($monthsDir as $monthDir) {
            $fileDirName = $monthDir.'/*'.$imagePostName.'*';
            $imagesToDelete = @glob($fileDirName);

            if (empty($imagesToDelete)) {
                continue;
            }

            $categoryImagePath = $this->systemService->convertUrlToDirectory($categoryGuidUrl);
            $imagePathInfo = \pathinfo($categoryImagePath);

            if ($this->checkIfMonthDirectoryMustBeDelete($monthDir, $imagePathInfo['dirname'])) {
                continue;
            }

            $this->categoryService->deleteFiles($imagesToDelete);
        }

        return true;
    }

    /**
     * @param string $categoryImagePath
     * @param string $imagePathToDelete
     * @return bool
     */
    private function checkIfMonthDirectoryMustBeDelete(string $categoryImagePath, string $imagePathToDelete): bool
    {
        return $categoryImagePath === $imagePathToDelete;
    }

    /**
     * @param string $categoryGuidUrl
     * @param string $yearDir
     * @param string $imagePostName
     * @return void
     */
    private function deleteImagesByDirectoryIterator(string $categoryGuidUrl, string $yearDir, string $imagePostName): void
    {
        $monthsDir = new \DirectoryIterator($yearDir);
        $filesToDelete = [];

        if (empty($monthsDir)) {
            return;
        }

        foreach ($monthsDir->current() as $monthDir) {
            if ($monthDir->isDot()) {
                continue;
            }

            $categoryImagePath = $this->systemService->convertUrlToDirectory($categoryGuidUrl);
            $imagePathInfo = \pathinfo($categoryImagePath);

            if ($this->checkIfMonthDirectoryMustBeDelete($monthDir->getPathname(), $imagePathInfo['dirname'])) {
                continue;
            }

            $monthDirFiles =  new \DirectoryIterator($monthDir->getPathname());

            foreach ($monthDirFiles->current() as $fileDir) {
                if (\stripos($fileDir->getFilename(), $imagePathInfo['filename']) === false) {
                    continue;
                }

                $filesToDelete[] = $fileDir->getPathname();
            }
        }

        if (empty($filesToDelete)) {
            return;
        }

        $this->categoryService->deleteFiles($filesToDelete);
    }
}