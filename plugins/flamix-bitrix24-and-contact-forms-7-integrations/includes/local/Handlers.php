<?php

namespace FlamixLocal\CF7;

use Flamix\Bitrix24\Trace;
use Exception;

class Handlers
{
    /**
     * For develop purpose.
     *
     * @return string
     */
    public static function getSubDomain(): string
    {
        return ($_SERVER['SERVER_NAME'] === 'wp.test.chosten.com') ? 'devlead' : 'leadwp';
    }

    /**
     * Visited pages.
     *
     * @return void
     */
    public static function trace(): void
    {
        $title = @wp_title('', false);

        if (empty($title))
            $title = false;

        Trace::init($title);
    }

    public static function forms($contact_form): void
    {
        $submission = \WPCF7_Submission::get_instance();
        $posted_data = $submission->get_posted_data();
        $files = $submission->uploaded_files();

        // File
        try {
            $files = self::prepareFiles($files);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            Helpers::sendError($e->getMessage());
        }

        flamix_log('All fields value:', $posted_data ?? [], 'cf7'); // Debug
        $bitrix24 = array_merge($posted_data, $files, ['TITLE' => $contact_form->title]);
        flamix_log('Formatted:', $bitrix24, 'cf7'); // Debug

        try {
            Helpers::send(['FIELDS' => $bitrix24]);
        } catch (\Exception $e) {
            // dd('Error: ',  $e->getMessage()); // Debug
            Helpers::sendError($e->getMessage());
        }
    }

    /**
     * Prepare CF7 Files to send to Bitrix24
     *
     * @param array $files
     * @return array
     * @throws \Exception
     */
    public static function prepareFiles(array $files)
    {
        $return = [];
        foreach ($files as $input_key => $input_value) {
            if (is_array($input_value))
                foreach ($input_value as $file_path) {
                    $name = explode('/', $file_path);
                    if (!is_array($name)) {
                        throw new \Exception('Flamix-Bitrix24: Bad file name!');
                    }

                    $name = end($name);
                    $content = @file_get_contents($file_path);
                    if (empty($content)) {
                        throw new \Exception('Flamix-Bitrix24: Empty file content!');
                    }

                    $return[$input_key][] = [
                        'content' => base64_encode($content),
                        'file_name' => $name
                    ];

                    unset($content);
                }
        }

        return $return;
    }
}