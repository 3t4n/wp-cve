<?php

namespace Memsource\Registry;

use Memsource\Controller\ContentController;
use Memsource\Controller\UserController;
use Memsource\Dao\ContentSettingsDao;
use Memsource\Dao\MetaDao;
use Memsource\Page\AdvancedPage;
use Memsource\Page\BlockPage;
use Memsource\Page\ConnectorPage;
use Memsource\Page\CustomFieldsPage;
use Memsource\Page\LanguageMappingPage;
use Memsource\Page\ShortcodePage;
use Memsource\Parser\BlockParser;
use Memsource\Parser\ExcerptParser;
use Memsource\Parser\ShortcodeParser;
use Memsource\Service\AuthService;
use Memsource\Service\BlockService;
use Memsource\Service\Content\CategoryService;
use Memsource\Service\Content\CustomPostService;
use Memsource\Service\Content\CustomTaxonomyService;
use Memsource\Service\Content\IContentService;
use Memsource\Service\Content\PageService;
use Memsource\Service\Content\PostService;
use Memsource\Service\Content\ReusableBlockService;
use Memsource\Service\Content\TagService;
use Memsource\Service\CustomFields\CustomFieldsDecodeService;
use Memsource\Service\CustomFields\CustomFieldsEncodeService;
use Memsource\Service\CustomFields\CustomFieldsService;
use Memsource\Service\CustomFields\CustomFieldsSettingsService;
use Memsource\Service\DatabaseService;
use Memsource\Service\ExternalPlugin\AcfPlugin;
use Memsource\Service\ExternalPlugin\ElementorPlugin;
use Memsource\Service\ExternalPlugin\SeoPlugin;
use Memsource\Service\FilterService;
use Memsource\Service\LanguageService;
use Memsource\Service\Migrate\MigrateService;
use Memsource\Service\Migrate\SchemaService;
use Memsource\Service\Migrate\UpdateService;
use Memsource\Service\OptionsService;
use Memsource\Service\PlaceholderService;
use Memsource\Service\ShortcodeService;
use Memsource\Service\TransformService;
use Memsource\Service\TranslationPlugin\ITranslationPlugin;
use Memsource\Service\TranslationPlugin\MultilingualpressPlugin;
use Memsource\Service\TranslationPlugin\NonExistingPlugin;
use Memsource\Service\TranslationPlugin\TranslationPluginProvider;
use Memsource\Service\TranslationPlugin\WPMLPlugin;
use Memsource\Service\TranslationWorkflowService;
use Memsource\Utils\ArrayUtils;
use Memsource\Utils\AuthUtils;
use Memsource\Utils\PreviewUtils;

class AppRegistry
{
    /** @var OptionsService */
    private $optionsService;

    /** @var MigrateService */
    private $migrateService;

    /** @var DatabaseService */
    private $databaseService;

    /** @var LanguageService */
    private $languageService;

    /** @var FilterService */
    private $filterService;

    /** @var ShortcodeService */
    private $shortcodeService;

    /** @var TranslationWorkflowService */
    private $translationWorkflowService;

    /** @var TransformService */
    private $transformService;

    /** @var BlockService */
    private $blockService;

    /** @var CustomFieldsDecodeService */
    private $customFieldsDecodeService;

    /** @var CustomFieldsService */
    private $customFieldsService;

    /** @var ITranslationPlugin */
    private $translationPlugin;

    /** @var UserController */
    private $userController;

    /** @var ContentController */
    private $contentController;

    /** @var BlockPage */
    private $blockPage;

    /** @var ConnectorPage */
    private $connectorPage;

    /** @var CustomFieldsPage */
    private $customFieldsPage;

    /** @var AdvancedPage */
    private $advancedPage;

    /** @var LanguageMappingPage */
    private $languageMappingPage;

    /** @var ShortcodePage */
    private $shortcodePage;

    /** @var MetaDao */
    private $metaDao;

    public function __construct()
    {
        // Exception
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Exception/NotFoundException.php';

        // Utils
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Utils/ActionUtils.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Utils/ArrayUtils.php';
        $arrayUtils = new ArrayUtils;
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Utils/AuthUtils.php';
        $authUtils = new AuthUtils;
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Utils/DatabaseUtils.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Utils/LogUtils.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Utils/PreviewUtils.php';
        PreviewUtils::register();
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Utils/StringUtils.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Utils/SystemUtils.php';

        // Dto
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Dto/AbstractDto.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Dto/ContentSettingsDto.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Dto/MetaKeyDto.php';

        // Dao
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Dao/AbstractDao.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Dao/ContentSettingsDao.php';
        $contentSettingsDao = new ContentSettingsDao;
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Dao/MetaDao.php';
        $this->metaDao = new MetaDao;

        // Services
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/OptionsService.php';
        $this->optionsService = new OptionsService;
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Migrate/SchemaService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Migrate/UpdateService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Migrate/MigrateService.php';
        $this->migrateService = new MigrateService(new SchemaService, new UpdateService, $this->optionsService);

        // Translation plugins
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/TranslationPlugin/ITranslationPlugin.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/TranslationPlugin/TranslationPluginProvider.php';
        $translationPluginProvider = new TranslationPluginProvider($this->optionsService);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/TranslationPlugin/NonExistingPlugin.php';
        $translationPluginProvider->addDefaultTranslationPlugin(new NonExistingPlugin);

        // 1) WPML plugin
        $wpmlApiFile = WP_PLUGIN_DIR . '/sitepress-multilingual-cms/inc/wpml-api.php';
        if (file_exists($wpmlApiFile)) {
            require_once $wpmlApiFile;
            require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/TranslationPlugin/WPMLPlugin.php';
            $translationPluginProvider->addTranslationPlugin(OptionsService::MULTILINGUAL_PLUGIN_WPML, new WPMLPlugin);
        }

        // 2) Multilingualpress plugin
        $mlpApiFile = WP_PLUGIN_DIR . '/multilingualpress/src/inc/api.php';
        if (file_exists($mlpApiFile)) {
            require_once $mlpApiFile;
            require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/TranslationPlugin/MultilingualpressPlugin.php';
            $translationPluginProvider->addTranslationPlugin(OptionsService::MULTILINGUAL_PLUGIN_MLP, new MultilingualpressPlugin);
        }

        $this->translationPlugin = $translationPluginProvider->getTranslationPlugin();

        // Services
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/ExternalPlugin/AcfPlugin.php';
        $acfPlugin = new AcfPlugin;
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/ExternalPlugin/ElementorPlugin.php';
        $elementorPlugin = new ElementorPlugin;
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/ExternalPlugin/SeoPlugin.php';
        $seoPlugin = new SeoPlugin();
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/DatabaseService.php';
        $this->databaseService = new DatabaseService;
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/AuthService.php';
        $authService = new AuthService($this->optionsService, $this->translationPlugin);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/LanguageService.php';
        $this->languageService = new LanguageService($this->databaseService, $this->translationPlugin);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/BlockService.php';
        $this->blockService = new BlockService;
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/ShortcodeService.php';
        $this->shortcodeService = new ShortcodeService;
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/TranslationWorkflowService.php';
        $this->translationWorkflowService = new TranslationWorkflowService($this->optionsService, $this->translationPlugin);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/PlaceholderService.php';
        $placeholderService = new PlaceholderService($authUtils);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Parser/ParserResult.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Parser/ShortcodeParser.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Parser/BlockParser.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Parser/ExcerptParser.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/CustomFields/CustomFieldsSettingsService.php';
        $customFieldsSettingsService = new CustomFieldsSettingsService($contentSettingsDao);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/CustomFields/CustomFieldsDecodeService.php';
        $this->customFieldsDecodeService = new CustomFieldsDecodeService($acfPlugin, $elementorPlugin, $seoPlugin);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/CustomFields/CustomFieldsService.php';
        $this->customFieldsService = new CustomFieldsService(
            $seoPlugin,
            $this->translationWorkflowService,
            $customFieldsSettingsService,
            $this->metaDao
        );
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/CustomFields/CustomFieldsEncodeService.php';
        $customFieldsEncodeService = new CustomFieldsEncodeService(
            $acfPlugin,
            $elementorPlugin,
            $seoPlugin,
            $authUtils,
            $placeholderService,
            $customFieldsSettingsService,
            $this->customFieldsService,
            $this->metaDao
        );
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/TransformService.php';
        $this->transformService = new TransformService(
            $this->shortcodeService,
            new ShortcodeParser($authUtils, $placeholderService),
            new BlockParser($arrayUtils, $authUtils, $this->blockService),
            $elementorPlugin,
            $this->customFieldsDecodeService,
            $customFieldsEncodeService,
            $authUtils,
            $placeholderService,
            new ExcerptParser()
        );
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/FilterService.php';
        $this->filterService = new FilterService($this->translationPlugin);

        // Controllers
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Controller/UserController.php';
        $this->userController = new UserController($this->optionsService, $authService, $this->languageService);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Controller/ContentController.php';
        $this->contentController = new ContentController($this->optionsService, $authService, $this->databaseService, $this->metaDao);

        // Content services
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/CustomTypeTrait.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/IContentService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/AbstractContentService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/AbstractPostService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/AbstractTermService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/PostService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/CategoryService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/TagService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/PageService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/CustomPostService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/CustomTaxonomyService.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Service/Content/ReusableBlockService.php';
        $this->contentController->addContentServices([
            new PostService($this->optionsService, $this->transformService, $this->filterService, $this->languageService, $this->translationPlugin, $this->customFieldsService, $this->customFieldsDecodeService, $this->translationWorkflowService, $this->metaDao),
            new PageService($this->optionsService, $this->transformService, $this->filterService, $this->languageService, $this->translationPlugin, $this->customFieldsService, $this->customFieldsDecodeService, $this->translationWorkflowService, $this->metaDao),
            new CategoryService($this->languageService, $this->translationPlugin, $this->transformService, $this->customFieldsDecodeService),
            new TagService($this->languageService, $this->translationPlugin, $this->transformService, $this->customFieldsDecodeService),
            new ReusableBlockService($this->optionsService, $this->transformService, $this->filterService, $this->languageService, $this->translationPlugin, $this->customFieldsService, $this->customFieldsDecodeService, $this->translationWorkflowService, $this->metaDao),
        ]);

        // Pages
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Page/AbstractPage.php';
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Page/ConnectorPage.php';
        $this->connectorPage = new ConnectorPage($this->optionsService, $this->translationWorkflowService, $acfPlugin);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Page/LanguageMappingPage.php';
        $this->languageMappingPage = new LanguageMappingPage($this->databaseService, $this->translationPlugin);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Page/AdvancedPage.php';
        $this->advancedPage = new AdvancedPage($this->optionsService);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Page/CustomFieldsPage.php';
        $this->customFieldsPage = new CustomFieldsPage($customFieldsSettingsService, $this->metaDao);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Page/ShortcodePage.php';
        $this->shortcodePage = new ShortcodePage($this->shortcodeService);
        require_once MEMSOURCE_PLUGIN_PATH . '/src/Page/BlockPage.php';
        $this->blockPage = new BlockPage($this->blockService);
    }

    /**
     * Create CustomPostService object.
     *
     * @param $type \WP_Post_Type
     * @return CustomPostService
     */
    public function createCustomPostService(\WP_Post_Type $type): CustomPostService
    {
        $customTypeService = new CustomPostService($this->optionsService, $this->transformService, $this->filterService, $this->languageService, $this->translationPlugin, $this->customFieldsService, $this->customFieldsDecodeService, $this->translationWorkflowService, $this->metaDao);
        $customTypeService->setType($type->name);
        $customTypeService->setLabel($type->label);

        return $customTypeService;
    }

    /**
     * Create CustomTaxonomyService object.
     *
     * @param $taxonomy \WP_Taxonomy
     * @return CustomTaxonomyService
     */
    public function createCustomTaxonomyService(\WP_Taxonomy $taxonomy): CustomTaxonomyService
    {
        $service = new CustomTaxonomyService($this->languageService, $this->translationPlugin, $this->transformService, $this->customFieldsDecodeService);
        $service->setType($taxonomy->name);
        $service->setLabel($taxonomy->label);

        return $service;
    }

    public function initOptions($multilingualPlugin)
    {
        $this->optionsService->initOptions($multilingualPlugin, false);
    }

    public function forceInitOptions($multilingualPlugin)
    {
        $oldTranslationPlugin = $this->optionsService->getActiveTranslationPluginKey();
        $this->optionsService->initOptions($multilingualPlugin, true);
        $newTranslationPlugin = $this->optionsService->getActiveTranslationPluginKey();

        if ($oldTranslationPlugin !== $newTranslationPlugin) {
            $this->databaseService->truncateLanguageMapping();
        }
    }

    public function initPages()
    {
        $this->connectorPage->initPage();
        $this->languageMappingPage->initPage();
        $this->advancedPage->initPage();
        $this->customFieldsPage->initPage();
        $this->shortcodePage->initPage();
        $this->blockPage->initPage();
    }

    public function initRestRoutes()
    {
        $this->userController->registerRestRoutes();
        $this->contentController->registerRestRoutes();
    }

    public function getOptionsService(): OptionsService
    {
        return $this->optionsService;
    }

    public function getMigrateService(): MigrateService
    {
        return $this->migrateService;
    }

    public function getDatabaseService(): DatabaseService
    {
        return $this->databaseService;
    }

    public function getCustomFieldsService(): CustomFieldsService
    {
        return $this->customFieldsService;
    }

    public function getShortcodeService(): ShortcodeService
    {
        return $this->shortcodeService;
    }

    public function getTranslationWorkflowService(): TranslationWorkflowService
    {
        return $this->translationWorkflowService;
    }

    public function getBlockService(): BlockService
    {
        return $this->blockService;
    }

    public function getTranslationPlugin(): ITranslationPlugin
    {
        return $this->translationPlugin;
    }

    public function getLanguageMappingPage(): LanguageMappingPage
    {
        return $this->languageMappingPage;
    }

    public function getCustomFieldsPage(): CustomFieldsPage
    {
        return $this->customFieldsPage;
    }

    private function getContentController(): ContentController
    {
        return $this->contentController;
    }

    /**
     * Add a content service object to content controller class.
     *
     * @param $service IContentService
     * @param $throw bool throw exception if content exist already
     *
     * @return IContentService
     * @throws \Exception
    */
    public function addContentServiceToContentController(IContentService $service, bool $throw = true): IContentService
    {
        try {
            $contentController = $this->getContentController();
            $contentController->addContentService($service);
        } catch (\Exception $exception) {
            if ($throw === true) {
                throw $exception;
            }
        }

        return $service;
    }
}
