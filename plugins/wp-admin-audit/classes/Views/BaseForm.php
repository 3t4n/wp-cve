<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

abstract class WADA_View_BaseForm
{
    use WADA_View_BaseView;
    public $parentHeadline;
    public $parentHeadlineLink;
    public $viewHeadline;
    abstract protected function displayForm();
    abstract protected function handleFormSubmissions();

    public function execute(){
        $this->handleFormSubmissions();
        $this->displayMessages();
        $this->displayForm();
    }

    protected function printHeadersAndBreadcrumb(){
        if ($this->parentHeadline): ?>
            <?php if ($this->parentHeadlineLink): ?><a href="<?php echo $this->parentHeadlineLink; ?>" class="wada-parent-link"><?php endif; ?>
            <h1 class="wp-heading-inline"><?php echo esc_html($this->parentHeadline); ?></h1><?php if ($this->parentHeadlineLink): ?></a><?php endif; ?>
            <h1 class="wp-heading-inline wada-breadcrumb-divider">&gt;</h1>
        <?php endif; ?>
        <h1 class="wp-heading-inline"><?php echo esc_html($this->viewHeadline); ?></h1><?php
    }
}