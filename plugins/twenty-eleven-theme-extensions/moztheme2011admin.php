<?php
/*  Copyright 2011  Michael J. Walker (email: mike@moztools.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once 'moztools/mozadmin.php';

class MozTheme2011Admin extends MozAdminBaseClass_MozTheme2011 {

    // This array contains all the definitions of the 
    // colors that can be modified on the Theme Options page.
    private static $cssMapping = array('text_color' => array('title' => 'Post Text Color', 'styles' => array('color' => 'body'), 'defaults' => array('light' => '#373737', 'dark' => '#bbb')),
                                       'title_color' => array('title' => 'Post Title Color', 'styles' => array('color' => '.entry-title, .entry-title a'), 'defaults' => array('light' => '#222222', 'dark' => '#dddddd')), 
                                       'metadata_color' => array('title' => 'Post Metadata Text Color', 'styles' => array('color' => '.entry-meta'), 'defaults' => array('light' => '#666666', 'dark' => '#999999')), 
                                       'page_color' => array('title' => 'Page Background Color',  'styles' => array('background-color' => '#page'), 'defaults' => array('light' => '#fff', 'dark' => '#0f0f0f')),
    								   'window_color' => array('title' => 'Page Border Color',  'styles' => array('background-color' => 'body'), 'defaults' => array('light' => '#e2e2e2', 'dark' => '#1d1d1d')),
    								   'background_contrast_color' => array('title' => 'Background Contrast Color', 'styles' => array('background-color' => '.widget_calendar #wp-calendar tfoot td, .widget_calendar #wp-calendar th, .entry-header .comments-link a, .entry-meta .edit-link a, .commentlist .edit-link a, pre'), 'defaults' => array('light' => '#f1f1f1', 'dark' => '#222222')),
                                       'blog_title_color' => array('title' => 'Blog Title Color',  'styles' => array('color' => '#site-title a'), 'defaults' => array('light' => '#111', 'dark' => '#eee')),
                                       'blog_description_color' => array('title' => 'Blog Description Color',  'styles' => array('color' => '#site-description'), 'defaults' => array('light' => '#7a7a7a', 'dark' => '#858585')),
                                       'header_background_color' => array('title' => 'Header Background Color',  'styles' => array('background-color' => '#branding'), 'defaults' => array('light' => '#fff', 'dark' => '#0f0f0f')),
                                       'menu_background_color' => array('title' => 'Menu Background Color',  'styles' => array('background' => '#access, #access ul ul a'), 'defaults' => array('light' => '#181818', 'dark' => '#363636')),
                                       'menu_highlight_color' => array('title' => 'Menu Highlight Color',  'styles' => array('background' => '#access li:hover > a, #access a:focus, #access ul ul *:hover > a', 'border-bottom-color' => '#access ul ul a'), 'defaults' => array('light' => '#383838', 'dark' => '#505050')),                                       
                                       'menu_text_color' => array('title' => 'Menu Text Color',  'styles' => array('color' => '#access a, #access li:hover > a, #access a:focus, #access ul ul a, #access ul ul *:hover > a'), 'defaults' => array('light' => '#eeeeee', 'dark' => '#eeeeee')));
    
    protected $adminMenu = 'appearance';
    protected $adminTitle = 'Twenty Eleven Theme Extensions';
    protected $adminMenuTitle = 'Theme Extensions';
    protected $adminMenuSlug = 'moztheme2011';
    
    protected $pluginOptions = MOZTHEME2011_OPTIONS;
    
    private $themeOptionsSlug = 'theme_options'; 
    
    public static function create() {
        if (get_template() == MOZTHEME2011_TEMPLATE || strpos(get_current_theme(), MOZTHEME2011_NAME) !== false) {
            new MozTheme2011Admin();
        }
    }

    protected function __construct() {
        parent::__construct();
        if ($this->isAdminPage()) {
            $this->registerScripts('moztheme2011admin.min', array('jquery-ui-widget'));
            $this->registerStyles('moztheme2011admin');
            $this->addHelp('<p>Each option has its own context help -- just click the question mark icon next to its title.</p>'
                          .'<p>For further assistance, please use one of the following links:</p>'
                          .'<p><a href="http://moztools.com/wordpress/theme2011-plugin/">Theme Extensions Plugin Home Page</a><br/>'
                          .'<p><a href="http://moztools.com/wordpress/theme2011-plugin/support-forum">Theme Extensions Support Forum</a><br/>');
            
        } else if ($this->isThemeOptionsPage() && $this->isOptionSet('enable-custom-colors')) {
            $this->registerScripts('moztheme2011options.min', array('jquery'));
            $this->registerStyles('moztheme2011options');
            $this->registerAction('admin_footer', 'outputCustomColorValues');
        } else {
            if ($this->isOptionSet('image-size')) {
                $this->registerFilter('twentyeleven_header_image_height', 'getHeaderImageHeight');
            }
            if ($this->isOptionSet('enable-custom-colors')) {
                $this->registerFilter('twentyeleven_theme_options_validate', 'processThemeOptions', 10, 2);
            }
        }
    }

    protected function init() {
        parent::init();
    }

    public function getHeaderImageHeight($height) {
        return $this->isOptionSet('image-size') ? $this->getOption('image-height') : $height;
    }
    
    public function displayAdminPage() {
                
        $this->setFormData($this->getOptions());
        $row = $this->checkBox('sidebar-page', 'Enable the widget sidebar on pages').$this->crlf();
        $row .= $this->checkBox('sidebar-post', 'Enable the widget sidebar on single-post pages').$this->crlf();
        $indent = $this->checkBox('adjust-nav', 'Center the navigation links at the top of single-post pages', true);
        $row .= $this->wrapIndent($indent); 
        $content = $this->wrapRow($row, 'Widget Sidebar', 'moz-sidebars');

        $row = $this->checkBox('align-widget-titles', 'Left align the widget titles with the contents of the widgets.');
        $content .= $this->wrapRow($row, 'Widget Titles', 'moz-widget-titles');

        $row = $this->checkBox('headline', 'Enable sticky post as headline').$this->crlf();
        $indent = $this->textInput('headline-text', 'Enter text to display with the sticky post\'s title', '',
                                   '<em>(Note: place the string <strong>%title%</strong> where you want the post title to appear in the text)</em>', true);
        $row .= $this->wrapIndent($indent);
        $content .= $this->wrapRow($row, 'Headline Post', 'moz-headlines');

        $row = $this->checkBox('image-size', 'Change the height of new images created on the <strong>Header</strong> admin page').$this->crlf();
        $indent = $this->textInput('image-height', 'Height (in pixels)', '288', '', true).$this->crlf();
        $indent .= $this->checkBox('force-image-size', 'Force all existing header images to be this height.'.$this->crlf()
                                  .'<em class="moz-indent">(Warning: requires Javascript, and will stretch or compress images of with different heights)</em>', true).$this->crlf();
        $row .= $this->wrapIndent($indent);
        $content .= $this->wrapRow($row, 'Header Image Size', 'moz-image-size');
        
        $row = $this->checkBox('enable-custom-colors', 'Enable custom colors <em>(you can change the color values on the <a href="'.$this->getAdminUrl($this->themeOptionsSlug).'">Theme Options</a> page.)</em>').$this->crlf();
        $content .= $this->wrapRow($row, 'Custom Colors', 'moz-custom-colors');
        
        $row = $this->checkBox('embed-custom-css', 'Include custom CSS styles for the blog').$this->crlf();
        $indent = $this->textArea('custom-css', 10, 'Enter one or more CSS styles to use with your blog:', '', true);
        $row .= $this->wrapIndent($indent);
        $content .= $this->wrapRow($row, 'Custom CSS', 'moz-custom-css');

        $content = $this->wrapTable($content);
        $content .= $this->helpText('moztheme2011help');
        $content .= $this->hiddenInput('action', 'save');
        $content .= $this->submitButton('submit', 'Save Changes'); 
        $content = $this->wrapForm($content, $this->getAdminUrl());
        $content = $this->title('icon-themes', 'Twenty Eleven Theme Extensions <em>(by MozTools)</em>').$content;
        $content .= '<div id="settings-container" />';
        $content = $this->wrapClass($content, 'wrap');
        echo $content;
    }
    
    /**
     * Now that the options come from two different places, make
     * sure to merge the color settings options in with the new
     * settings from the Extension page before saving.
     * 
     * @see MozAdminBaseClass_MozTheme2011::processAdminRequest()
     */
    protected function processAdminRequest($action, $params) {
        $options = $this->getOptions();
        $params['headline-text'] = stripslashes($params['headline-text']);
        $params['custom-css'] = stripslashes($params['custom-css']);
        $params['custom-colors'] = $options['custom-colors'];
        $params['custom-colors-css'] = $options['custom-colors-css'];
        $this->updateOptions($params);
    }
    
    /**
     * Test to see if we're on the Theme Options page.
     * @return boolean true if on Theme Options page
     */
    private function isThemeOptionsPage() {
        return $_REQUEST['page'] == $this->themeOptionsSlug;
    }
    
    /**
     * This callback function is called after the user has changed the
     * options in the Theme Options page. This function saves the values
     * the user has set for the custom values.
     * 
     * @param array $output array of scheme values (ignored)
     * @param array $input array of scheme settings submitted by user
     * @return array the output array, unmodified 
     */
    public function processThemeOptions($output, $input) {
        $options = $this->getOptions();
        $css = '';
        unset($options['custom-colors']);
        $styles = apply_filters('moz_theme_custom_colors', self::$cssMapping);
        foreach($styles as $name => $item) {
            $options['custom-colors'][$name] = $input[$name];
            foreach($item['styles'] as $style => $classes) {
                $css .= $classes.'{'.$style.':'.$input[$name].'}'.PHP_EOL;
            }
        }
        $options['custom-colors-css'] = $css;
        $this->updateOptions($options);
        return $output;
    }

    /**
     * This callback function is called when the footer of the Theme Options
     * page is about to be output. The function embeds all the information
     * about the custom colors, so that the javascript can build the controls
     * on the page.  
     */
    public function outputCustomColorValues() {
        $options = $this->getOptions();
        $styles = apply_filters('moz_theme_custom_colors', self::$cssMapping);
        echo '<table style="display:none"><tbody id="moz-custom-colors">';
        foreach ($styles as $name => $settings) {
            $this->outputCustomColor($name, $options['custom-colors'][$name], $settings['title'], $settings['defaults']);
        }
        echo '</tbody></table>';
    }
    
    private function outputCustomColor($id, $color, $title, $defaults) {
        ?>
        <tr valign="top"><th scope="row"><?php echo $title; ?></th>
          <td>
            <fieldset>
              <legend class="screen-reader-text"><span><?php echo $title; ?></span></legend>
              <input type="text" value="<?php echo $color; ?>" id="<?php echo $id; ?>" name="twentyeleven_theme_options[<?php echo $id; ?>]" class="moz-color-input"/>
              <a id="<?php echo $id; ?>_example" class="hide-if-no-js moz-color-example" href="#" style="background-color:<?php echo $color; ?>;">&nbsp;</a>
              <input type="button" value="<?php esc_attr_e('Select a Color', 'twentyeleven'); ?>" class="button hide-if-no-js"/>
              <div id="colorPickerDiv_<?php echo $id; ?>" class="colorPickerDiv" style="z-index: 100; background: #eeeeee; border: 1px solid #cccccc; position: absolute; display: none;"></div>
              <br />
              <span><?php _e('Default color:', 'twentyeleven'); ?> <span class="moz-default-color"><?php 
                  foreach ($defaults as $scheme => $value) {
                      echo '<a class="def_color_'.$scheme.'" href="#" style="display:none">'.$value.'</a>';
                  } ?>
              </span></span>
            </fieldset>
          </td>
        </tr>    
        <?php 
    }
}