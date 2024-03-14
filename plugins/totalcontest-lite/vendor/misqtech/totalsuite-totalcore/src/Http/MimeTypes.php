<?php

namespace TotalContestVendors\TotalCore\Http;

/**
 * Class MimeTypes
 * @package TotalContestVendors\TotalCore\Http
 */
class MimeTypes
{
    /**
     * @var array $list
     */
    public static $list = [
        // Audio
        'audio/mpeg'                                                                => 'mp3',
        'audio/ogg'                                                                 => 'ogg',
        'audio/webm'                                                                => 'webm',
        'audio/wav'                                                                 => 'wav',
        'audio/aac'                                                                 => 'aac',
        'audio/aacp'                                                                => 'aac',
        // Images
        'image/bmp'                                                                 => 'bmp',
        'image/gif'                                                                 => 'gif',
        'image/jpeg'                                                                => 'jpeg',
        'image/pjpeg'                                                               => 'jpeg',
        'image/png'                                                                 => 'png',
        'image/webp'                                                                => 'webp',
        // Video
        'video/3gpp'                                                                => '3gp',
        'video/3gpp2'                                                               => '3gp',
        'video/mp4'                                                                 => 'mp4',
        'video/avi'                                                                 => 'avi',
        'video/mpeg'                                                                => 'mpeg',
        'video/webm'                                                                => 'webm',
        'video/quicktime'                                                           => 'mov',
        // Documents
        'text/plain'                                                                => 'txt',
        'text/csv'                                                                  => 'csv',
        'text/tab-separated-values'                                                 => 'tsv',
        'application/pdf'                                                           => 'pdf',
        'application/zip'                                                           => 'zip',
        'application/x-zip-compressed'                                              => 'zip',
        'application/msword'                                                        => 'doc',
        'application/vnd.ms-powerpoint'                                             => 'ppt',
        'application/vnd.ms-excel'                                                  => 'xls',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
    ];

}