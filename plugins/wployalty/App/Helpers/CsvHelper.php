<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Helpers;
defined('ABSPATH') or die;

use Exception;
use ParseCsv\Csv;

class CsvHelper extends Base
{
    public static $instance = null;


    /**
     * instance of class
     * @param array $config
     * @return CsvHelper|null
     * @since 1.0.0
     */
    public static function getInstance(array $config = array())
    {
        if (!self::$instance) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Get total record of csv
     * @param $file_path
     * @return int
     */
    function getTotalRecord($file_path)
    {
        try {
            $csv = new Csv();
            $csv->loadFile($file_path);
            $total = $csv->getTotalDataRowCount();
        } catch (Exception $e) {
            $total = 0;
        }
        return $total;
    }

    /**
     * Get first record in csv
     * @return array
     */
    function getFirstValue($file_path)
    {
        $first_value = $this->getCsvData($file_path, 1, 1);
        return !empty($first_value) && is_array($first_value) ? $first_value[0] : array();
    }

    function getCsvData($file_path, $limit_start = 0, $limit = 5)
    {
        $pair_data = array();
        try {
            $reader_csv = new Csv();
            $reader_csv->offset = $limit_start;
            $reader_csv->limit = $limit;
            $reader_csv->delimiter = ',';
            $reader_csv->titles = $this->getHeader();
            $reader_csv->parseFile($file_path);


            foreach ($reader_csv->data as $data) {
                if (isset($data['email']) && $data['email'] == 'email') {
                    continue;
                }
                if (empty($data)) {
                    continue;
                }
                $pair_data[] = $data;
            }

        } catch (Exception $e) {
        }
        return $pair_data;
    }

    /**
     * Get header of csv
     * @return string[]
     */
    function getHeader()
    {
        return apply_filters('wlr_csv_header_list', array(
            'email',
            'points',
            'referral_code',
            'comment'
        ));
    }

    function setCsvData($file_path, $data, $custom_header = array())
    {
        try {
            $csv_header = $this->getHeader();
            if (!empty($custom_header)) {
                $csv_header = $custom_header;
            }
            $csv = new Csv();
            $csv->loadFile($file_path);
            $count = $csv->getTotalDataRowCount();
            if ($count <= 0) {
                $header = array();
                $header[] = $csv_header;
                $csv->save($file_path, $header, true);
            }
            $csv_hel = new Csv();
            $csv_hel->titles = $csv_header;
            $csv_hel->save($file_path, $data, true);
        } catch (Exception $e) {

        }
    }

    /**
     * File delete
     * @param $filepath
     * @return bool
     */
    function file_delete($filepath)
    {
        @chmod($filepath, 0777);
        $status = false;
        // as long as the owner is either the webserver or the ftp
        if (@unlink($filepath)) {
            $status = true;
        }
        return $status;
    }

    /**
     * File upload
     * @param $src
     * @param $dest
     * @return bool
     */
    function file_upload($src, $dest)
    {
        // Create the destination directory if it does not exist
        $baseDir = \dirname($dest);
        if (is_writable($baseDir) && move_uploaded_file($src, $dest)) {
            // Short circuit to prevent file permission errors
            if (self::setPermissions($dest)) {
                $ret = true;
            } else {
                $ret = false;
            }
        } else {
            $ret = false;
        }
        return $ret;
    }

    /**
     * Set permission for file path
     * @param $path
     * @param string $filemode
     * @param string $foldermode
     * @return bool
     */
    public static function setPermissions($path, $filemode = '0644', $foldermode = '0755')
    {
        // Initialise return value
        $ret = true;

        if (is_dir($path)) {
            $dh = opendir($path);

            while ($file = readdir($dh)) {
                if ($file != '.' && $file != '..') {
                    $fullpath = $path . '/' . $file;

                    if (is_dir($fullpath)) {
                        if (!self::setPermissions($fullpath, $filemode, $foldermode)) {
                            $ret = false;
                        }
                    } else {
                        if (isset($filemode)) {
                            if (!@ chmod($fullpath, octdec($filemode))) {
                                $ret = false;
                            }
                        }
                    }
                }
            }

            closedir($dh);

            if (isset($foldermode)) {
                if (!@ chmod($path, octdec($foldermode))) {
                    $ret = false;
                }
            }
        } else {
            if (isset($filemode)) {
                $ret = @ chmod($path, octdec($filemode));
            }
        }

        return $ret;
    }

    /**
     * Save user data in database
     * @param $file_data
     * @param string $need_update
     * @param string $update_type
     */
    function save_user($file_data, $need_update = 'no', $update_type = 'equal')
    {
        if (!empty($file_data)) {
            foreach ($file_data as $user_data) {
                if (isset($user_data['email']) && !empty($user_data['email']) && $user_data['email'] != 'email') {
                    $user_data['user_email'] = $user_data['email'];
                }
                if (isset($user_data['user_email']) && !empty($user_data['user_email']) && filter_var($user_data['user_email'], \FILTER_VALIDATE_EMAIL)) {
                    $email = sanitize_email($user_data['user_email']);
                    $user_points = $this->getPointUserByEmail($email);
                    $file_points = (int)(isset($user_data['points']) && !empty($user_data['points'])) ? $user_data['points'] : 0;
                    $old_point = 0;
                    if (isset($user_points->points) && !empty($user_points->points)) {
                        $old_point = $user_points->points;
                    }
                    $action_data = array(
                        'user_email' => $email,
                        'points' => 0,
                        'action_type' => 'import',
                        'action_process_type' => '',
                        'customer_note' => '',
                        'note' => '',
                        'customer_command' => isset($user_data['comment']) && !empty($user_data['comment']) ? $user_data['comment'] : ''
                    );
                    if (empty($user_points)) {
                        $action_data['referral_code'] = isset($user_data['referral_code']) && !empty($user_data['referral_code']) ? $user_data['referral_code'] : '';
                        $action_data['points'] = $file_points;
                        $action_data['action_process_type'] = 'new_user';
                        $action_data['customer_note'] = sprintf(__('Added %d %s by site admin', 'wp-loyalty-rules'), $file_points, $this->getPointLabel($file_points));
                        $action_data['note'] = sprintf(__('%s customer imported with %d %s by admin(%s)', 'wp-loyalty-rules'), $email, $file_points, $this->getPointLabel($file_points), self::$woocommerce_helper->get_email_by_id(get_current_user_id()));
                        $this->addExtraPointAction('import', $file_points, $action_data);
                    } elseif (isset($user_points->points)) {
                        if ($need_update == 'yes') {
                            $trans_type = 'credit';
                            if ($update_type == 'equal') {
                                $user_points->points = (int)$file_points;
                                if ($file_points > $old_point) {
                                    $added_point = (int)($file_points - $old_point);
                                    $action_data['points'] = $added_point;
                                    $action_data['action_process_type'] = 'earn_point';
                                } elseif ($file_points < $old_point) {
                                    $reduced_point = (int)($old_point - $file_points);
                                    $action_data['points'] = $reduced_point;
                                    $action_data['action_process_type'] = 'reduce_point';
                                    $trans_type = 'debit';
                                }
                            } elseif ($update_type == "add") {
                                $added_point = (int)$file_points;
                                $action_data['points'] = $added_point;
                                $action_data['action_process_type'] = 'earn_point';
                            } elseif ($update_type == "sub") {
                                $trans_type = 'debit';
                                $reduced_point = (int)$file_points;
                                if ($reduced_point <= 0) {
                                    $reduced_point = 0;
                                }
                                $action_data['points'] = $reduced_point;
                                $action_data['action_process_type'] = 'reduce_point';
                                if ($user_points->points < $file_points) {
                                    $user_points->points = 0;
                                } else {
                                    $user_points->points -= (int)$file_points;
                                }
                            }
                            $action_data['note'] = sprintf(__('%s customer %s changed from %d to %d by admin(%s) via import', 'wp-loyalty-rules'), $email, $this->getPointLabel(3), $old_point, $user_points->points, self::$woocommerce_helper->get_email_by_id(get_current_user_id()));
                            $action_data['customer_note'] = sprintf(__('%s value changed to %d by store administrator(s)', 'wp-loyalty-rules'), $this->getPointLabel($user_points->points), $user_points->points);
                            $this->addExtraPointAction('import', $action_data['points'], $action_data, $trans_type, false, true);
                        }
                    }
                }
            }
        }
    }
}