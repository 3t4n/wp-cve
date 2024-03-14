<?php

class CQtrMimetype
{
    private $mimes;
    
    public function __construct()
    {
        $this->mimes = array (
            array("binary", "MZ", 0, ".exe"),
            array("binary", "\x7f\x45\x4c\x46", 0, ".elf"),
            array("document", "\x25\x50\x44\x46", 0, ".pdf"),
            array("compress", "\x50\x4b", 0, ".zip"),
            array("compress", "\x37\x7a", 0, ".7zip"),
            array("compress", "\x42\x5a", 0, ".bz"),
            array("compress", "\x1f\x8b", 0, ".gz"),
            array("compress", "\x52\x61\x72\x21\x1a\x07\x01", 0, ".rar"),
            array("compress", "MSCF", 0, ".cab"),
            array("image", "\x89\x50\x4e\x47", 0, ".png"),
            array("image", "GIF", 0, ".gif"),
            array("image", "VIEW", 0, ".pm"),
            array("image", "\xff\xd8\xff\xe0", 0, ".jpg"),
            array("image", "\x6a\x50\x20\x20", 4, ".jp2"),
            array("image", "\x4d\x4d\x00\x2a", 0, ".tif"),
            array("image", "\x49\x49\x2a\x00", 0, ".tif"),
            array("image", "BM", 0, ".bmp"),
            array("image", "CDR9", 8, ".cdr"),
            array("woff",  "wOFF", 0, ".woff"),
            array("woff",  "wOF2", 0, ".wof2"),
            array("audio",  "OggS", 0, ".opus"),
            array("video", "\x14\x66\x74\x79\x70", 3, ".mp4"),
            array("video", "\x46\x4c\x56\x01", 0, ".flv"),
            array("video", "\x46\x57\x53", 0, ".swf"),
            array("video", "\x43\x57\x53", 0, ".swf"),
            array("audio", "\x49\x44\x33", 0, ".mp3"),
            array("audio", "\x66\x74\x79\x70", 4, ".m4a"),
            array("helpfile", "\x49\x6e\x6e\x6f\x20\x53\x65\x74\x75\x70\x20\x55\x6e\x69\x6e\x73\x74\x61\x6c\x6c\x20\x4c\x6f\x67", 0, ".chm")
        );
    }

    public function CheckMimeType( $filename )
    {
        $full_test = TRUE;

        if( !$full_test )
        {
            /*
             * room to keep old code
             */
            foreach($this->mimes as $mime)
            {
                $pos = strpos($this->getHeader($filename), $mime[1]);
                if ($pos === FALSE)
                {
                    continue;
                }
                else if ($pos == $mime[2])
                {
                    return $mime[0];
                }
            }
            return "textfile";
        }


        $buf = $this->getHeader($filename);
        /*
         * Check if file contains ASCII characters.
         */
        $char_count = preg_match_all("/[^\x01-\x7e]/", $buf);
        if ($char_count < 5)
        {
            return "textfile";
        }
        else
        {
            /*
             * Check for mime type since there are files contains chinese characters.
             */
            foreach($this->mimes as $mime)
            {
                $pos = strpos($buf, $mime[1]);
                if ($pos === FALSE)
                {
                    continue;
                }
                else if ($pos == $mime[2])
                {
                    return $mime[0];
                }
            }
            /*
             * if can't find via Mime type, check if first 32 bytes is non ASCII
             */
            if ($char_count > 32)
            {
                return "binary";
            }
            else
            {
               return "textfile"; 
            }
        }

        return "textfile";
    }

    private function getHeader( $filename )
    {
        $handle = fopen($filename, "rb");
        $contents = fread($handle, 100);
        fclose($handle);
        return $contents;
    }
}

?>
