<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Controllers/TemplateController.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Controllers;

use FelixTzWPModernPolls\Models\Templates;


class TemplateController
{
    public $templates;

    public function __construct()
    {
        $this->templates = new Templates();
    }

    public function get($id)
    {
        return $this->templates->get($id);
    }

    public function getAll()
    {
        return $this->templates->getAll();
    }


    public function add($post, $file)
    {
        $file = $file['templateZip'];

        $zip = new \ZipArchive();
        if ($zip->open($file['tmp_name']) === TRUE) {
            $conf = $zip->getFromName('conf.json');

            if ($conf != false) {
                $conf = json_decode($conf);

                $hash = $this->checkDir(FelixTzWPModernPollsDir . 'resources/views/templates/' . $conf->dir);

                if ($zip->extractTo(FelixTzWPModernPollsDir . 'resources/views/templates/' . $conf->dir . $hash)) {
                    if ($this->templates->insert($conf->name, $conf->dir . $hash)) {

                    } else {
                        echo 'database ?';
                        die();
                    }
                } else {
                    echo 'write rights?';
                    die();
                }
            } else {
                echo 'keine Conf datei.';
                die();
            }
        } else {
            echo 'Fehler';
            die();
        }
    }

    public function delete($id)
    {
        $template = $this->templates->get($id);
        $dir = FelixTzWPModernPollsDir . 'resources/views/templates/' . $template->dir;

        if ($this->templates->delete($id)) {
            if ($this->deleteDir($dir)) {

            } else {
                echo 'rmdir ?';
            }
        } else {
            echo 'database ?';
        }
    }

    public function checkDir($path, $hash = '')
    {
        if (is_dir($path . $hash)) {
            $hash = '_' . uniqid();
            return $this->checkDir($path, $hash);
        } else {
            return $hash;
        }
    }

    public function deleteDir($path)
    {
        if (is_dir($path)) {
            $files = glob($path . '/*', GLOB_MARK);

            foreach ($files as $file) {
                $this->deleteDir($file);
            }

            if (rmdir($path)) {
                return 1;
            } else {
                return 0;
            }
        } elseif (is_file($path)) {
            unlink($path);
        }
    }
}