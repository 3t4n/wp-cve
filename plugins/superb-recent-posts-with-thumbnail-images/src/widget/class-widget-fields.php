<?php

namespace SuperbRecentPosts\Widget;

if (! defined('WPINC')) {
    die;
}

class WidgetConstant
{
    const TITLE = "title";
    const NUMBER_OF_POSTS = "numberofposts";
    const DISPLAY_DATE = "displaydate";
    const ALIGN_TEXT = "aligntext";
    const DISPLAY_THUMBNAILS = "displaythumbnails";
    const ALIGN_THUMBNAILS = "alignthumbnails";
    const EXCLUDE_CURRENT = "excludecurrent";
    const SHOW_HOMEPAGE = "showonhomepage";
    const SHOW_BLOGPAGE = "showonblogpage";
    const SHOW_PAGESPOSTS = "showonpagesposts";
}

class WidgetField
{
    private $ID;
    private $Name;
    private $Instance;
    private $Value;
    private $Label;
    private $Type;
    private $Class;
    private $Options;

    public function __construct($widget, $instance, $constant, $label, $type, $class = "", $options = array())
    {
        $this->Instance = $instance;
        $this->ID = $widget->get_field_id($constant);
        $this->Name = $widget->get_field_name($constant);
        $this->Value = $this->InstanceValueOrDefault($constant);
        $this->Label = $label;
        $this->Type = $type;
        $this->Class = $class;
        $this->Options = $options;
        $this->BuildField();
    }

    private function InstanceValueOrDefault($constant)
    {
        switch ($constant) {
            case WidgetConstant::TITLE:
                if (isset($this->Instance[ WidgetConstant::TITLE ])) {
                    return $this->Instance[ WidgetConstant::TITLE ];
                } else {
                    return __('Recent Posts', 'superbrecentposts');
                }
            break;

            case WidgetConstant::NUMBER_OF_POSTS:
                if (isset($this->Instance[WidgetConstant::NUMBER_OF_POSTS])) {
                    return $this->Instance[ WidgetConstant::NUMBER_OF_POSTS ];
                } else {
                    return 5;
                }
            break;

            case WidgetConstant::DISPLAY_DATE:
                if (isset($this->Instance[WidgetConstant::DISPLAY_DATE])) {
                    return $this->Instance[ WidgetConstant::DISPLAY_DATE ] ? "checked" : "";
                } else {
                    return "checked";
                }
            break;

            case WidgetConstant::DISPLAY_THUMBNAILS:
                if (isset($this->Instance[WidgetConstant::DISPLAY_THUMBNAILS])) {
                    return $this->Instance[ WidgetConstant::DISPLAY_THUMBNAILS ] ? "checked" : "";
                } else {
                    return "checked";
                }
            break;

            case WidgetConstant::ALIGN_THUMBNAILS:
                if (isset($this->Instance[WidgetConstant::ALIGN_THUMBNAILS])) {
                    return $this->Instance[ WidgetConstant::ALIGN_THUMBNAILS ] === "right" ? "right" : "left";
                } else {
                    return "left";
                }
            break;

            case WidgetConstant::ALIGN_TEXT:
                if (isset($this->Instance[WidgetConstant::ALIGN_TEXT])) {
                    return $this->Instance[ WidgetConstant::ALIGN_TEXT ] === "right" ? "right" : "left";
                } else {
                    return "left";
                }
            break;

            case WidgetConstant::EXCLUDE_CURRENT:
                if (isset($this->Instance[WidgetConstant::EXCLUDE_CURRENT])) {
                    return $this->Instance[ WidgetConstant::EXCLUDE_CURRENT ] ? "checked" : "";
                } else {
                    return "checked";
                }
            break;

            case WidgetConstant::SHOW_BLOGPAGE:
                if (isset($this->Instance[WidgetConstant::SHOW_BLOGPAGE])) {
                    return $this->Instance[ WidgetConstant::SHOW_BLOGPAGE ] ? "checked" : "";
                } else {
                    return "checked";
                }
            break;

            case WidgetConstant::SHOW_HOMEPAGE:
                if (isset($this->Instance[WidgetConstant::SHOW_HOMEPAGE])) {
                    return $this->Instance[ WidgetConstant::SHOW_HOMEPAGE ] ? "checked" : "";
                } else {
                    return "checked";
                }
            break;

            case WidgetConstant::SHOW_PAGESPOSTS:
                if (isset($this->Instance[WidgetConstant::SHOW_PAGESPOSTS])) {
                    return $this->Instance[ WidgetConstant::SHOW_PAGESPOSTS ] ? "checked" : "";
                } else {
                    return "checked";
                }
            break;

        }
    }

    private function BuildField()
    {
        ?>
        <p>
            <label for="<?php echo esc_attr($this->ID); ?>"><?php echo esc_html($this->Label); ?></label> 
                    <?php
        switch ($this->Type) {
            case "text":
                ?>
                    <input class="<?php echo esc_attr($this->Class); ?>" id="<?php echo esc_attr($this->ID); ?>" name="<?php echo esc_attr($this->Name); ?>" type="text" value="<?php echo esc_attr($this->Value); ?>" />
                <?php
                break;

            case "number":
                ?>
                    <input class="<?php echo esc_attr($this->Class); ?>" id="<?php echo esc_attr($this->ID); ?>" name="<?php echo esc_attr($this->Name); ?>" type="number" value="<?php echo esc_attr($this->Value); ?>" step="1" min="1" size="3" />
                <?php
                break;

            case "checkbox":
                ?>
                    <input class="<?php echo esc_attr($this->Class); ?>" id="<?php echo esc_attr($this->ID); ?>" name="<?php echo esc_attr($this->Name); ?>" type="checkbox" <?php echo esc_html($this->Value); ?>>
                <?php
                break;
            case "select":
                    ?>
                        <select class="<?php echo esc_attr($this->Class); ?>" id="<?php echo esc_attr($this->ID); ?>" name="<?php echo esc_attr($this->Name); ?>">
                            <?php foreach ($this->Options as &$option) {
                        ?>
                                <option value="<?php echo esc_attr(strtolower($option)); ?>"<?php echo strtolower($this->Value)===strtolower($option)?'selected="selected"':''; ?>><?php echo esc_html($option); ?></option>
                                <?php
                    }
                            unset($option);?>
                        </select>
                    <?php
                break;
        } ?>
        </p>
        <?php
    }

    public static function BuildLink()
    {
        ?>
        <p><a class="button button-large button-primary" target="_blank" style="color: #fff;" href="https://superbthemes.com/plugins/recent-posts/"><?php esc_html_e("View Premium Version", "superbrecentposts"); ?></a></p>
        <?php
    }
}

class FieldSeparator
{
    public function __construct()
    {
        echo '<hr />';
    }
}
