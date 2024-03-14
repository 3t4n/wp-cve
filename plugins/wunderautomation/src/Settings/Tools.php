<?php

namespace WunderAuto\Settings;

use WunderAuto\Types\Workflow;

/**
 * Class Tools
 */
class Tools extends BaseSettings
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id        = 'wunderauto-tools';
        $this->caption   = 'Tools';
        $this->sortOrder = 90;
    }

    /**
     * Register settings.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTab();
        $this->addSection('export', __('Export', 'wunderauto'));
        $this->addField('export', 'export', 'Select objects');
        $this->addField('export', 'export-submit', '');

        $this->addSection('import', __('Import', 'wunderauto'));
        $this->addField('import', 'import', 'Select file');
        $this->addField('import', 'import-submit', '');

        $this->addSection('last', '');
        $this->addField('last', 'hidden', '');
    }

    /**
     * Sanitize user input.
     *
     * @return array<string, string|int>
     */
    public function sanitize()
    {
        if (isset($_POST['submit-export'])) {
            $this->doExport();
        }

        if (isset($_POST['submit-import'])) {
            $this->doImport();
        }

        return [];
    }

    /**
     * Render section name
     *
     * @param array<string, string> $section
     *
     * @return void
     */
    public function displaySection($section)
    {
        switch ($section['id']) {
            case 'export':
                esc_html_e('Export workflows and re-triggers to json', 'wunderauto');
                break;
            case 'import':
                esc_html_e('Import worfklows and re-triggers from a json file', 'wunderauto');
                break;
            case 'last':
                echo "<style>#submit { display: none; }</style>";
                break;
        }
    }

    /**
     * Render field
     *
     * @param string $fieldId
     * @param string $field
     *
     * @return void
     */
    public function displayField($fieldId, $field)
    {
        switch ($fieldId) {
            case 'import':
                echo '<input type="file" name="import_file" id="import_file">';
                break;
            case 'export':
                $wunderAuto = wa_wa();
                $workflows  = $wunderAuto->getWorkflowPosts();
                $this->renderCheckboxes('workflow', 'Workflows', $workflows);

                $args = [
                    'post_type'   => 'automation-retrigger',
                    'numberposts' => -1,
                ];

                $retriggers = array_filter(get_posts($args), function ($el) {
                    return $el instanceof \WP_Post;
                });
                $this->renderCheckboxes('retrigger', 'Re-Triggers', $retriggers);

                echo '<p>&nbsp;</p>';
                echo '<table><tr><td colspan="1" style="padding: 5px 5px;">';
                echo '<input type="checkbox" name="export-toggle-all" value="-1" id="export--1">';
                echo sprintf(
                    '<label for="export--1">%s</label></td></tr>',
                    esc_html('Toggle all')
                );
                echo '</td></tr></table>';

                break;
            case 'export-submit':
                echo sprintf(
                    '<input type="submit" name="%s" id="%s" class="button button-primary" value="%s">',
                    esc_attr('submit-export'),
                    esc_attr('submit-export'),
                    esc_attr('Export workflows & re-triggers')
                );
                break;
            case 'import-submit':
                echo sprintf(
                    '<input type="submit" name="%s" id="%s" class="button button-primary" value="%s">',
                    esc_attr('submit-import'),
                    esc_attr('submit-import'),
                    esc_attr('Import workflows & re-triggers')
                );
                break;
        }
    }

    /**
     * Render checkboxes with workflow and retriggers names
     *
     * @param string               $type
     * @param string               $label
     * @param array<int, \WP_Post> $posts
     *
     * @return void
     */
    private function renderCheckboxes($type, $label, $posts)
    {
        echo sprintf(
            '<p><b>%s</b></p>',
            esc_html($label)
        );
        if (count($posts) === 0) {
            echo '<i>';
            esc_html_e('(nothing to export)', 'wunderauto') ;
            echo '</i>';
            return;
        }

        $pos = 0;
        echo "<table><tr>\n";
        foreach ($posts as $post) {
            $pos++;
            $title = empty($post->post_title) ? '(no title)' : $post->post_title;
            echo "<td style=\"padding: 5px 5px\">";
            echo sprintf(
                '<input type="checkbox" class="export-check" name="%s[]" value="%d" id="%s-%s">',
                esc_attr($type),
                $post->ID,
                esc_attr($type),
                $pos
            );
            echo sprintf(
                '<label for="%s-%s">%s</label></td>',
                esc_attr($type),
                $pos,
                esc_html($title)
            );
            echo "\n";
            if ($pos % 3 === 0) {
                echo "</tr><tr>";
            }
        }
        echo "</tr>\n";
        echo "</table>\n";
    }

    /**
     * Prepare export and send a downloadable file.
     *
     * @return void
     */
    private function doExport()
    {
        $wunderAuto = wa_wa();

        $output = (object)[
            'workflows'  => [],
            'retriggers' => [],
        ];

        if (isset($_POST['workflow']) && is_array($_POST['workflow'])) {
            foreach ($_POST['workflow'] as $id) {
                $id       = (int)$id;
                $workflow = $wunderAuto->createWorkflowObject($id);

                $output->workflows[] = $workflow->getState();
            }
        }

        if (isset($_POST['retrigger']) && is_array($_POST['retrigger'])) {
            foreach ($_POST['retrigger'] as $id) {
                $id        = (int)$id;
                $reTrigger = $wunderAuto->createReTriggerObject($id);

                $output->retriggers[] = $reTrigger->getState()->toObject();
            }
        }

        if (count($output->workflows) === 0 && count($output->retriggers) === 0) {
            $message = __('No objects selected', 'wunderauto');
            add_settings_error('general', 'settings_updated', $message, 'error');
            return;
        }

        if (empty($output->workflows)) {
            unset($output->workflows);
        }

        if (empty($output->retriggers)) {
            unset($output->retriggers);
        }

        // headers
        $file_name = 'wunderautomation-export-' . date('Y-m-d') . '.json';
        header('Content-Description: File Transfer');
        header("Content-Disposition: attachment; filename={$file_name}");
        header('Content-Type: application/json; charset=utf-8');

        // return
        echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die;
    }

    /**
     * Import workflows and retriggers
     *
     * @return void
     */
    private function doImport()
    {
        $wunderAuto = wa_wa();

        // Check file size.
        if (empty($_FILES['import_file']['size'])) {
            $message = __('No file selected', 'wunderauto');
            add_settings_error('general', 'settings_updated', $message, 'error');
            return;
        }

        // Get file data.
        $file = $_FILES['import_file'];

        // Check errors.
        if ($file['error']) {
            $message = __('Error uploading file. Please try again', 'wunderauto');
            add_settings_error('general', 'settings_updated', $message, 'error');
            return;
        }

        // Check file type.
        if (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'json') {
            $message = __('Incorrect file type', 'wunderauto');
            add_settings_error('general', 'settings_updated', $message, 'error');
            return;
        }

        // Read JSON.
        $json = file_get_contents($file['tmp_name']);
        if (empty($json)) {
            $message = __('File empty or invalid', 'wunderauto');
            add_settings_error('general', 'settings_updated', $message, 'error');
            return;
        }

        $json = json_decode($json, false);
        if (empty($json)) {
            $message = __('File does not contain valid JSON', 'wunderauto');
            add_settings_error('general', 'settings_updated', $message, 'error');
            return;
        }

        if (isset($json->workflows) && isset($json->workflows)) {
            foreach ($json->workflows as $savedState) {
                $guid     = $savedState->guid;
                $workflow = $wunderAuto->getWorkflowObjectByGuid($guid);
                if (!$workflow) {
                    $workflow = new Workflow();
                }

                $workflow->setState($savedState);
                $result = $workflow->save();
                if ($result === false) {
                    $message = __('Error importing file. Please try again', 'wunderauto');
                    add_settings_error('general', 'settings_updated', $message, 'error');
                }
            }
        }

        $message = __('File imported', 'wunderauto');
        add_settings_error('general', 'settings_updated', $message, 'success');
    }
}
