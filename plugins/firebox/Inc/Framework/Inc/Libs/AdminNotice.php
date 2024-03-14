<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Libs;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class AdminNotice
{
    /**
     * Notice Field prefix
     * 
     * @var  String
     */
    const NOTICE_FIELD = 'FPF_admin_notice_message';

    /**
     * Displays a notice.
     * The notice is displayed once and is destroyed upon page refresh.
     * 
     * @return  void
     */
    public function displayAdminNotice()
    {
        $option  = get_option(self::NOTICE_FIELD);
        $message = isset($option['message']) ? $option['message'] : false;

        if (!$message)
        {
            return;
        }
        
        $noticeLevel = !empty($option['notice-level']) ? $option['notice-level'] : 'error';

        $noticeBgColorClass = '';

        switch ($noticeLevel)
        {
            case 'success':
                $noticeBgColorClass = 'bg-green-100 border-green-600';
                break;
            
            case 'error':
                $noticeBgColorClass = 'bg-red-100 border-red-600';
                break;
            
            case 'info':
                $noticeBgColorClass = 'bg-blue-100 border-blue-600';
                break;
            
            case 'warning':
                $noticeBgColorClass = 'bg-yellow-100 border-yellow-600';
                break;
            
            default:
                $noticeBgColorClass = 'bg-gray-100 border-gray-600';
                break;
        }
        
        echo
        '<div class="relative mb-2 flex items-start text-sm justify-between gap-x-2 p-2 pr-6 ' . $noticeBgColorClass . ' text-gray-900 border border-solid">' .
            esc_html($message) .
            '<a href="#" class="absolute top-[13px] right-1 shadow-none fpf-notice-close-btn text-gray-900 opacity-50 hover:opacity-100">' .
                '<svg width="24" height="24" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path style="transform: translate(25%, 25%);" fill-rule="evenodd" clip-rule="evenodd" d="M24 3.6L20.4 0L11.9998 8.40022L3.59999 0.000423898L0 3.60042L8.39978 12.0002L1.69741e-05 20.4L3.60001 24L11.9998 15.6002L20.4 24.0004L23.9999 20.4004L15.5998 12.0002L24 3.6Z" fill="currentColor"></path></svg>' .
            '</a>' .
        '</div>';
        delete_option(self::NOTICE_FIELD);
    }

    /**
     * Displays an error notice
     * 
     * @param   string  $message
     * 
     * @return  void
     */
    public static function displayError($message)
    {
        self::updateOption($message, 'error');
    }

    /**
     * Displays a warning notice
     * 
     * @param   string  $message
     * 
     * @return  void
     */
    public static function displayWarning($message)
    {
        self::updateOption($message, 'warning');
    }

    /**
     * Displays an info notice
     * 
     * @param   string  $message
     * 
     * @return  void
     */
    public static function displayInfo($message)
    {
        self::updateOption($message, 'info');
    }

    /**
     * Displays a success notice
     * 
     * @param   string  $message
     * 
     * @return  void
     */
    public static function displaySuccess($message)
    {
        self::updateOption($message, 'success');
    }

    /**
     * Updates the notice message and its type
     * 
     * @param   string  $message
     * @param   string  $noticeLevel
     * 
     * @return  void
     */
    protected static function updateOption($message, $noticeLevel)
    {
        update_option(self::NOTICE_FIELD, [
            'message' => $message,
            'notice-level' => $noticeLevel
        ]);
    }
}