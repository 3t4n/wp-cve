<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Options section
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Section.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Options_Section
{
    /**
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_label;

    /**
     * @var string
     */
    protected $_description = '';

    /**
     * @var string
     */
    protected $docsUrl = '';

    /**
     * @var null|string
     */
    protected $_pageId;

    /**
     * @var array
     */
    protected $_fields = array();

    protected $_sanitizeCallback;


    /**
     * @param $id
     * @param $label
     * @param null $description
     */
    public function __construct($id, $label, $description=null, $_sanitizeCallback=null)
    {
        $this->_id = $id;
        $this->_label = $label;
        if ($description !== null) {
            $this->_description = $description;
        }
        if (is_callable($_sanitizeCallback)) {
            $this->_sanitizeCallback = $_sanitizeCallback;
        }
    }

    /**
     * @param IfwPsn_Wp_Options_Field $field
     * @return $this
     */
    public function addField(IfwPsn_Wp_Options_Field $field)
    {
        $this->_fields[] = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->_label = $label;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @return bool
     */
    public function hasFields()
    {
        return count($this->_fields) > 0;
    }

    /**
     *
     */
    public function render()
    {
        echo '<div class="options_sections_header">';

        printf('<h1 class="section_label">%s</h1>', $this->_label);

        echo '<div class="section_header_links">';

        if (!empty($this->docsUrl)) {
            printf(
                '<p><a href="%s" target="_blank" class="options_section_main_docs_link"><span class="dashicons dashicons-sos"></span><b>%s</b></a></p>',
                $this->docsUrl,
                __('Need some help?', 'ifw')
            );
        }
        echo '</div>';

        echo '</div>';

        printf('<div class="section_description">%s</div>', $this->_description);
    }

    /**
     * @param null|string $pageId
     */
    public function setPageId($pageId)
    {
        $this->_pageId = $pageId;
    }

    /**
     * @return null|string
     */
    public function getPageId()
    {
        return $this->_pageId;
    }

    /**
     * @return null|string
     */
    public function hasPageId()
    {
        return !empty($this->_pageId);
    }

    /**
     * @return bool
     */
    public function hasSanitizeCallback(): bool
    {
        return is_callable($this->_sanitizeCallback);
    }

    /**
     * @return callable
     */
    public function getSanitizeCallback()
    {
        return $this->_sanitizeCallback;
    }

    /**
     * @param callable $sanitizeCallback
     */
    public function setSanitizeCallback(callable $sanitizeCallback): void
    {
        $this->_sanitizeCallback = $sanitizeCallback;
    }

    /**
     * @param $title
     * @param string $description
     * @return void
     */
    public function addSubsectionHeader($title, string $description = '', string $id = '')
    {
        if (empty($id)) {
            $id = uniqid();
        }
        $this->addField(new \IfwPsn_Wp_Options_Field_Html(
            $id,
            '',
            sprintf(
                '<h3 class="sub_section_header" data-id="%s">%s</h3>%s',
                $id,
                $title,
                $description
            )
        ));
    }

    public function addSubsectionFooter()
    {
        $this->addField(new \IfwPsn_Wp_Options_Field_Html(
            uniqid(),
            '',
            '<span class="sub_section_footer"></span>'
        ));
    }

    /**
     * @param string $docsUrl
     */
    public function setDocsUrl(string $docsUrl): void
    {
        $this->docsUrl = $docsUrl;
    }

}
