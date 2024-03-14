<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\StatusTypes;
use WcMipConnector\Manager\CategoryManager;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\FileLogManager;
use WcMipConnector\Manager\ImportProcessProductManager;
use WcMipConnector\Manager\ProductMapManager;
use WcMipConnector\Model\File;

class FileService
{
    private const FILE_BATCH_SIZE = 10;

    /** @var FileLogManager */
    private $fileLogManager;
    /** @var ImportProcessProductManager */
    private $importProcessProductManager;
    /** @var BrandService */
    private $brandService;
    /** @var BrandPluginService */
    private $brandPluginService;
    /** @var TagService  */
    private $tagService;
    /** @var CategoryService  */
    private $categoryService;
    /** @var AttributeService  */
    private $attributeService;
    /** @var ProductService  */
    private $productService;
    /** @var LoggerService */
    protected $logger;
    /** @var UnPublishProductService */
    protected $disableService;
    /** @var CategoryManager  */
    protected $categoryManager;
    /** @var ProductMapManager */
    protected $productMapManager;
    /** @var ImportProcessProductService */
    private $importProcessProductService;
    /** @var SystemService */
    private $systemService;
    /** @var DirectoryService */
    private $directoryService;

    public function __construct()
    {
        $this->fileLogManager = new FileLogManager();
        $this->importProcessProductManager = new ImportProcessProductManager();
        $this->brandService = new BrandService();
        $this->brandPluginService = new BrandPluginService();
        $this->tagService = new TagService();
        $this->categoryService = new CategoryService();
        $this->attributeService = new AttributeService();
        $this->productService = new ProductService();
        $logger = new LoggerService();
        $this->logger = $logger->getInstance();
        $this->disableService = new UnPublishProductService;
        $this->categoryManager = new CategoryManager();
        $this->productMapManager = new ProductMapManager();
        $this->importProcessProductService = new ImportProcessProductService();
        $this->systemService = new SystemService();
        $this->directoryService = new DirectoryService();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function processFile(): void
    {
        $fileInProcess = $this->fileLogManager->getFileInProcess();

        if ($fileInProcess) {
            $this->logger->info('Cannot synchronize a new file because another file is in process');

            return;
        }

        $filesWithoutProcess = $this->fileLogManager->getFileWithoutProcess(self::FILE_BATCH_SIZE);

        if (!$filesWithoutProcess) {
            $this->logger->info('There not exists any file to be processed');

            return;
        }

        foreach ($filesWithoutProcess as $fileWithoutProcess) {
            if (!$this->systemService->checkIfPluginNeedUpgrade($fileWithoutProcess['version'])) {
                $this->logger->error('Error in function checkIfPluginNeedUpgrade() in FileService');

                return;
            }

            $productsArray = $this->getProductsFromFile($fileWithoutProcess);
            $fileIdWithoutProcess = $fileWithoutProcess['file_id'];

            if (empty($productsArray)) {
                $this->fileLogManager->updateFileAsInProcess($fileIdWithoutProcess);
                $this->fileLogManager->updateFileAsProcessed($fileIdWithoutProcess);
                $this->logger->warning('File ID: '.$fileIdWithoutProcess.' synchronization ended because the product array is empty');

                continue;
            }

            $this->systemService->setManageStockOption();
            $this->fileLogManager->updateFileAsInProcess($fileIdWithoutProcess);
            $this->logger->info('Started the synchronization of File ID: '.$fileIdWithoutProcess);
            $productsArrayIndexedByProductId = \array_column($productsArray, 'ProductID');
            $processedProducts = $this->importProcessProductManager->getProcessedProducts($productsArrayIndexedByProductId, $fileIdWithoutProcess);
            $this->processProduct($productsArray, $processedProducts, $fileIdWithoutProcess);
            $this->fileLogManager->updateFileAsProcessed($fileIdWithoutProcess);
            $this->logger->info('Finished the synchronization of File ID: '.$fileIdWithoutProcess);
        }
    }

    /**
     * @param array $productsArray
     * @param array $processedProducts
     * @param int $fileIdWithoutProcess
     *
     * @throws \Exception
     */
    private function processProduct(array $productsArray, array $processedProducts, int $fileIdWithoutProcess): void
    {
        $tags = [];
        $categories = [];
        $brands = [];
        $products = [];
        $brandsPluginIds = [];
        $attributeGroup = [];
        $attribute = [];
        $attributes = [];
        $productsDisabled = [];
        $product = [];

        $batches = array_chunk($productsArray, $this->systemService->getBatchValue(), true);

        foreach ($batches as $productBatch) {
            $productIdIndexedByProductId = \array_column($productBatch, 'ProductID');
            $messageVersionIndexedByProductId = $this->productMapManager->getMessageVersionIndexedByProductId($productIdIndexedByProductId);

            foreach ($productBatch as $product) {
                if (\array_key_exists($product['ProductID'], $processedProducts) && !$product['Variations'] && !$product['Active'] !== 0) {
                    $this->logger->info('The product '.$product['ProductID'].' has already been processed successfully in the fileID '.$fileIdWithoutProcess);
                    continue;
                }

                if ($product['Active'] === 0) {
                    $productsDisabled[$product['ProductID']] = $product;

                    continue;
                }

                if (\array_key_exists($product['ProductID'], $messageVersionIndexedByProductId)) {
                    $messageVersion = $messageVersionIndexedByProductId[$product['ProductID']];
                    if ($messageVersion && !$this->hasToUpdateMessageVersion($product, $messageVersion)) {
                        $this->logger->info('PRODUCT ID: '.$product['ProductID'].' PROCESSED WITH OLD INFORMATION IN THE FILE.');
                        $this->importProcessProductService->setSuccess($product['ProductID'], $fileIdWithoutProcess);
                        continue;
                    }
                }

                if ($product['Tags']) {
                    $tags = $this->getTagsFromFile($product['Tags'], $tags);
                }

                if ($product['Categories']) {
                    $categories = $this->getCategoriesFromFile($product['Categories'], $categories);
                }

                if (isset($product['Brand']['BrandID'])) {
                    $brands = $this->getBrandsFromFile($product['Brand'], $brands);
                }

                if ($product['Variations']) {
                    $attributesResult = $this->getAttributesFromVariationData($product['Variations']);
                    if ($attributesResult['AttributeGroup']) {
                        foreach ($attributesResult['AttributeGroup'] as $attributesParent => $attributeResult) {
                            $attributeGroup[$attributesParent] = $attributeResult;
                        }
                    }

                    if ($attributesResult['Attribute']) {
                        foreach ($attributesResult['Attribute'] as $attributeParent => $attributeResult) {
                            foreach ($attributeResult as $parent => $result) {
                                $attribute[$attributeParent][$parent] = $result;
                            }
                        }
                    }
                }

                $products[] = $this->getProductsAndVariationsFromFile($product);
            }
        }

        if ($attributeGroup && $attribute) {
            $attributes = ['AttributeGroup' => $attributeGroup, 'Attribute' => $attribute];
        }

        $languageIsoCode = '';

        if (\is_array($product['Languages'])) {
            $languageIsoCode = \current($product['Languages']);
        }

        $this->logger->debug('Started the Tag process');
        $tagIds = $this->tagService->processByBatch($tags, $fileIdWithoutProcess, $languageIsoCode);
        $this->logger->debug('Finished the Tag process');

        $this->logger->debug('Started the Category process');
        $categoriesIds = $this->categoryService->processByBatch($categories, $languageIsoCode, $fileIdWithoutProcess);
        $this->logger->debug('Finished the Category process');

        $brandPlugin = ConfigurationOptionManager::isWoocommerceBrandPluginEnable();

        if ($brandPlugin) {
            $this->logger->debug('Started the brand plugin process');
            $brandsPluginIds = $this->brandPluginService->processByBatch($brands, $fileIdWithoutProcess);
            $this->logger->debug('Finished the brand plugin process');
        }

        $this->logger->debug('Started the brand process');
        $brandsIds = $this->brandService->processByBatch($brands, $fileIdWithoutProcess, $languageIsoCode);
        $this->logger->debug('Finished the brand process');

        $this->logger->debug('Started the attribute process');
        $attributesIds = $this->attributeService->process($attributes, $fileIdWithoutProcess, $languageIsoCode);
        $this->logger->debug('Finished the attribute process');

        if ($products) {
            $this->productService->process(
                $products,
                $tagIds,
                $categoriesIds,
                $brandsIds,
                $brandsPluginIds,
                $attributesIds,
                $fileIdWithoutProcess,
                $languageIsoCode
            );
        }

        $this->disableService->disableByBatch($productsDisabled, $fileIdWithoutProcess);
        $this->categoryService->deleteEmptyCategories();
        $this->attributeService->deleteEmptyAttributes();
        $this->tagService->deleteEmptyTags();
        $this->brandService->deleteEmptyBrands();
        $this->brandPluginService->deleteEmptyBrands();
    }

    /**
     * @param array $productTags
     * @param array $tags
     *
     * @return array
     */
    private function getTagsFromFile(array $productTags, array $tags): array
    {
        $tagActive = ConfigurationOptionManager::getActiveTag();
        $tagName = ConfigurationOptionManager::getTagName();

        foreach ($productTags as $productTag) {
            if ($this->isBlackFriday($productTag['TagLangs'], $tagActive, $tagName)) {
                continue;
            }

            $tags[$productTag['TagID']] = $productTag;
        }

        return $tags;
    }

    /**
     * @param array $tagLangs
     * @param bool $tagActive
     * @param string $tagName
     * @return bool
     */
    private function isBlackFriday(array $tagLangs, bool $tagActive, string $tagName): bool
    {
        foreach ($tagLangs as $tagLang) {
            if (\array_key_exists('TagName', $tagLang) && !$tagActive && $tagLang['TagName'] === $tagName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $productCategories
     * @param array $categories
     *
     * @return array
     */
    private function getCategoriesFromFile(array $productCategories, array $categories): array
    {
        foreach ($productCategories as $productCategory) {
            $categories[$productCategory['CategoryID']] = $productCategory;
        }

        return $categories;
    }

    /**
     * @param array $productBrands
     * @param array $brands
     *
     * @return array
     */
    private function getBrandsFromFile(array $productBrands, array $brands): array
    {
        $brands[$productBrands['BrandID']] = $productBrands;

        return $brands;
    }

    /**
     * @param array $productVariations
     *
     * @return array
     */
    private function getAttributesFromVariationData(array $productVariations): array
    {
        $attributeGroupList = [];
        $attributeList = [];

        foreach ($productVariations as $productVariation) {
            foreach ($productVariation['VariationAttributes'] as $productAttribute) {
                $attributeGroupList[$productAttribute['AttributeGroup']['AttributeGroupID']] = $productAttribute['AttributeGroup'];
                $attributeList[$productAttribute['AttributeGroup']['AttributeGroupID']][$productAttribute['Attribute']['AttributeID']] = $productAttribute['Attribute'];
            }
        }

        return ['AttributeGroup' => $attributeGroupList, 'Attribute' => $attributeList];
    }

    /**
     * @param array $product
     *
     * @return array
     */
    private function getProductsAndVariationsFromFile(array $product): array
    {
        $productList = [];
        $variationList = [];

        $productList[$product['ProductID']] = $product;

        if (!isset($product['Variations'])) {
            $productArray = ['Product' => $productList];
            $variationArray = ['Variation' => $variationList];

            return array_merge($productArray, $variationArray);
        }

        foreach ($product['Variations'] as $variationData) {
            $variationList[$product['ProductID']][$variationData['VariationID']] = $variationData;
        }

        $productArray = ['Product' => $productList];
        $variationArray = ['Variation' => $variationList];

        return array_merge($productArray, $variationArray);
    }

    /**
     * @param array
     *
     * @return array
     */
    public function getProductsFromFile(array $infoFile): array
    {
        $importFilesDir = $this->directoryService->getImportFilesDir();

        try {
            $filename = $importFilesDir.'/'.$infoFile['name'].'.json';

            if (!$this->directoryService->fileExist($filename)) {
                $this->logger->error('The file '.$infoFile['name'].'.json does not exist in the directory '.$importFilesDir);
                return [];
            }

            $data = \json_decode($this->directoryService->getFileContent($filename), true);

            if (!$data || empty($data['Products'])) {
                $this->logger->warning('The file '.$infoFile['name'].' doesnt contain a valid array or doesnt contain ProductData');
                return [];
            }

            return $data['Products'];
        } catch (\Exception $exception) {
            $this->logger->error('Error in the function getProductsFromFile(), Exception: '.$exception);
            return [];
        }
    }

    /**
     * @param array $product
     * @param string $messageVersion
     * @return bool
     * @throws \Exception
     */
    public function hasToUpdateMessageVersion(array $product, string $messageVersion): bool
    {
        $messageVersion = \DateTime::createFromFormat('Y-m-d H:i:s', $messageVersion, new \DateTimeZone('UTC'));
        $fileMessageVersion = new \DateTime($product['MessageVersion']);

        if ($messageVersion > $fileMessageVersion) {
            $this->logger->getInstance()->warning('Product ID: '.$product['ProductID'].
                ' information in the file is old. It will not be processed.');

            return false;
        }

        $this->logger->getInstance()->warning('Product ID: '.$product['ProductID'].
            ' is out of date. It will be processed');

        return true;
    }

    /**
     * @param array $fileWithoutProcess
     * @throws \Exception
     */
    public function preProcessFile(array $fileWithoutProcess): void
    {
        $products = $this->getProductsFromFile($fileWithoutProcess);
        $batches = array_chunk($products, $this->systemService->getBatchValue(), true);
        foreach ($batches as $productBatch) {
            $productIds = \array_column($productBatch, 'ProductID');
            $messageVersionIndexedByProductId = $this->productMapManager->getMessageVersionIndexedByProductId($productIds);
            if ($messageVersionIndexedByProductId) {
                foreach ($productBatch as $product) {
                    if (!array_key_exists($product['ProductID'], $messageVersionIndexedByProductId)) {
                        continue;
                    }

                    $messageVersion = $messageVersionIndexedByProductId[$product['ProductID']];
                    if ($messageVersion && $this->hasToUpdateMessageVersion($product, $messageVersion)) {
                        try {
                            $newMessageVersion = new \DateTime($product['MessageVersion']);
                        } catch (\Exception $e) {
                            $this->logger->getInstance()->error(
                                'Product ID: '.$product['ProductID'].' - Create DateTime from: '.$product['MessageVersion'].' - Message: '.$e->getMessage(
                                )
                            );
                            continue;
                        }

                        $this->productMapManager->setMessageVersion($product['ProductID'], $newMessageVersion);
                    }
                }
            }
        }
    }

    /**
     * @param string $submissionId
     * @return File
     */
    public function getStateFileImport(string $submissionId): File
    {
        $fileState = new File();
        $fileState->state = StatusTypes::HTTP_BAD_REQUEST;
        $fileState->message = 'Invalid submission ID';

        if (!$submissionId) {
            return $fileState;
        }

        $file = $this->fileLogManager->getStateFileImport($submissionId);
        if (!$file) {
            $fileState->state = StatusTypes::HTTP_NOT_FOUND;
            $fileState->message = 'File not found';

            return $fileState;
        }

        if (!$file['date_process']) {
            $fileState->state = StatusTypes::HTTP_OK;
            $fileState->message = 'File not processed';

            return $fileState;
        }

        $fileState->state = StatusTypes::HTTP_OK;
        $fileState->message = 'File processed';

        return $fileState;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getProcessState(string $fileName): string
    {
        $fileState = $this->fileLogManager->getDateProcessByFileName($fileName);
        if (!$fileState['date_process']) {
            $this->logger->debug('The submission has been not processed', $fileState);
            return File::STATUS_SUBMITTED;
        }

        return File::STATUS_DONE;
    }
}