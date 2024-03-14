<?php

namespace ShopWP;

class Filesystem
{
    public $wp_filesystem;
    public $credentials;
    public $use_filesystem = false;
    public $chmod_dir;
    public $chmod_file;
    public $container;

    public function __construct()
    {
    }

    public function is_using_wp_filesystem()
    {
        return $this->use_filesystem;
    }

    /*
    
    TODO: We need to show the user a form to enter their FTP credentials if the hosting provider doesn't allow WP to change files. 

    */
    public function maybe_init_wp_filesystem()
    {
        ob_start();
        $this->credentials = \request_filesystem_credentials(
            '',
            '',
            false,
            false,
            null
        );
        ob_end_clean();

        // If no credentials available, Use PHP fallbacks instead
        if ($this->credentials === false) {
            return $this;
        }

        $wp_filesystem_result = \WP_Filesystem($this->credentials);

        // This global should be availabe here
        global $wp_filesystem;

        // If has credentials but they can't be verified. Use PHP fallbacks instead
        if (!$wp_filesystem_result) {
            return $this;
        }

        $this->wp_filesystem = $wp_filesystem;
        $this->use_filesystem = true;

        return $this;
    }

    public function filesystem_method()
    {
        return true;
    }

    public function set_request_filesystem_credentials()
    {
        return true;
    }

    public function bootstrap_filesystem()
    {
        if (!function_exists('request_filesystem_credentials')) {
            return $this;
        }

        return $this->maybe_init_wp_filesystem();
    }

    public function file_exists($abs_path)
    {
        $return = file_exists($abs_path);

        if (!$return && $this->use_filesystem) {
            $abs_path = $this->sanitized_path($abs_path);
            $return = $this->wp_filesystem->exists($abs_path);
        }

        return (bool) $return;
    }

    public function sanitized_path($abs_path)
    {
        if ($this->is_using_wp_filesystem()) {
            return str_replace(
                ABSPATH,
                $this->wp_filesystem->abspath(),
                $abs_path
            );
        }

        return $abs_path;
    }

    public function get_sanitized_path($abs_path)
    {
        if ($this->is_using_wp_filesystem()) {
            return str_replace(
                ABSPATH,
                $this->wp_filesystem->abspath(),
                $abs_path
            );
        }

        return $abs_path;
    }

    public function delete_file($abs_path)
    {
        $return = @unlink($abs_path);

        if (!$return && $this->use_filesystem) {
            $abs_path = $this->get_sanitized_path($abs_path);
            $return = $this->wp_filesystem->delete($abs_path, false, false);
        }

        return $return;
    }

    public function mkdir($path)
    {
        if (!$this->wp_filesystem) {
            return @mkdir($source, $dest);
        }

        return $this->wp_filesystem->mkdir($path);
    }

    public function copy_file($source, $dest)
    {
        if (!$this->wp_filesystem) {
            return @copy($source, $dest);
        }

        return $this->wp_filesystem->copy($source, $dest);
    }
}
