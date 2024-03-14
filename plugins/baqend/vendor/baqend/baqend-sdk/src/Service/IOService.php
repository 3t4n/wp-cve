<?php

namespace Baqend\SDK\Service;

/**
 * Class IOService created on 09.08.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Service
 */
class IOService
{

    const DEFAULT_CHUNK_SIZE = 5242880; // 5 * 1024 * 1024

    /**
     * Calculates the ETag for a given file.
     *
     * @param string $filename The filename of the file to calculate the ETag for.
     * @param int $chunkSize The size of a chunk to use. (Defaults to 5 MiB)
     * @param bool $convertToLf If true, normalize file to LF.
     * @return string The calculated ETag.
     */
    public function calculateEntityTag($filename, $chunkSize = self::DEFAULT_CHUNK_SIZE, $convertToLf = false) {
        $md5s = [];

        $fp = fopen($filename, 'rb');
        while (true) {
            $data = fread($fp, $chunkSize);
            if (!$data) {
                break;
            }

            if ($convertToLf) {
                $data = $this->convertLineSeparatorsToLf($data);
            }

            $md5s[] = $data;
        }
        fclose($fp);

        // Return single chunk ETag
        if (count($md5s) == 1) {
            return md5($md5s[0]);
        }

        // Return multiple chunk ETag
        $digests = implode(
            array_map(
                function ($it) {
                    return md5($it, true);
                },
                $md5s
            )
        );

        return sprintf('%s-%d', md5($digests), count($md5s));
    }

    /**
     * Converts the line separators to line feeds (\n).
     *
     * @param string $string The string to convert the line feeds of.
     * @return string The converted string.
     */
    public function convertLineSeparatorsToLf($string) {
        return preg_replace('~\r\n?~', "\n", $string);
    }
}
