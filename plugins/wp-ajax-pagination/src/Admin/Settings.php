<?php namespace AjaxPagination\Admin;

use Premmerce\SDK\V2\FileManager\FileManager;

class Settings
{

    const OPTIONS = 'ajax_pagination';

    const SETTINGS_PAGE = 'ajax_pagination_page';

    private $fileManager;

    private $options;

//plugin default options

    const POSTS_SELECTOR = '#main';
    const NAVIGATION_SELECTOR = '.navigation';
    const JS_CODE = '';



    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
        $this->options = get_option(self::OPTIONS);

    }


    public function registerSettings()
    {
        register_setting(self::OPTIONS, self::OPTIONS, array(
            'sanitize_callback' => array($this, 'updateSettings'),
        ));

        add_settings_section('main_settings', __('', 'wp-ajax-pagination'), array(
            $this,
            'mainSection',
        ), self::SETTINGS_PAGE);

    }

    public function mainSection()
    {
        $this->fileManager->includeTemplate('admin/section/main-settings.php', array(
            'paginationType' => $this->getOption('paginationType'),
            'postsSelector' => $this->getOption('postsSelector'),
            'navigationSelector' => $this->getOption('navigationSelector'),
            'jsCode' => $this->getOption('jsCode'),
            'pagingUrl' => $this->getOption('pagingUrl')
        ));
    }

    public function showSettings()
    {
        print('<form action="' . admin_url('options.php') . '" method="post">');

        //settings_errors();

        settings_fields(self::OPTIONS);

        do_settings_sections(self::SETTINGS_PAGE);

        submit_button();
        print('</form>');
    }


    public function updateSettings($settings)
    {

        $settings['postsSelector'] = array_map( 'sanitize_text_field', $settings['postsSelector']);
        $settings['navigationSelector'] = array_map( 'sanitize_text_field', $settings['navigationSelector']);
        //$settings['postsSelector'] = sanitize_text_field($settings['postsSelector']);
        //$settings['navigationSelector'] = sanitize_text_field($settings['navigationSelector']);

        return $settings;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getOption($key, $default = null)
    {
        return isset($this->options[ $key ])? $this->options[ $key ] : $default;
    }
}
