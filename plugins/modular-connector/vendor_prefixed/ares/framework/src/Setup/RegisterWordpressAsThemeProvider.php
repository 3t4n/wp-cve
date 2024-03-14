<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Setup;

use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\RegisterWordpressConfigurationSmtpProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\RegisterWordpressScriptsProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\RegisterWordpressStylesProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\RegisterWordPressThemeAsAresProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme\RegisterWordpressThemeBuilderElementsProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme\RegisterWordPressThemeCustomizerProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme\RegisterWordPressThemeLayoutsProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme\RegisterWordPressThemeNavMenusProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme\RegisterWordPressThemePageTypesProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme\RegisterWordpressThemeSecurityProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\Theme\RegisterWordPressThemeSupportsProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\Admin\RegisterWordPressAdminMenusProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\Admin\RegisterWordPressCustomFieldsProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\RegisterWordPressImageSizesProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\RegisterWordPressPostTypesProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\RegisterWordPressTaxonomiesProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\RegisterWordPressThemeSidebarsProvider;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Elements\WordPress\RegisterWordPressWidgetsProvider;
use Modular\ConnectorDependencies\Ares\View\ViewServiceProvider;
use Modular\ConnectorDependencies\Illuminate\Support\AggregateServiceProvider;
/** @internal */
class RegisterWordpressAsThemeProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [RegisterWordpressConfigurationSmtpProvider::class, RegisterWordpressScriptsProvider::class, RegisterWordpressStylesProvider::class, RegisterWordPressThemeAsAresProvider::class, RegisterWordpressThemeBuilderElementsProvider::class, RegisterWordPressThemeCustomizerProvider::class, RegisterWordPressThemeLayoutsProvider::class, RegisterWordPressThemeNavMenusProvider::class, RegisterWordPressThemePageTypesProvider::class, RegisterWordpressThemeSecurityProvider::class, RegisterWordPressThemeSupportsProvider::class, RegisterWordPressAdminMenusProvider::class, RegisterWordPressCustomFieldsProvider::class, RegisterWordPressImageSizesProvider::class, RegisterWordPressPostTypesProvider::class, RegisterWordPressTaxonomiesProvider::class, RegisterWordPressThemeSidebarsProvider::class, RegisterWordPressWidgetsProvider::class, ViewServiceProvider::class];
}
