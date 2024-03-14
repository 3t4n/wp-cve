<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Report{
    public static function generateFailedImagesHTMLReport($data, $error_id){
        $log_path = realpath(dirname(__FILE__)) . '/../../../logs';
        $tmpl_path = realpath(dirname(__FILE__)) . '/../../templates/error_images.html';
        $filename = "/failed_images_error_{$error_id}.html";


        if(! is_dir($log_path)){
            mkdir($log_path, 0775);
        }

        $report_path = $log_path . $filename;

        $template_start = file_get_contents($tmpl_path);
        $template_end = '</table></div></div></body></html>';

        $f = fopen($report_path, "w");
        fwrite($f, $template_start);
        fwrite($f, self::_renderTHead($data['fields']));
        fwrite($f, self::_renderTBody($data['data']));
        fwrite($f, $template_end);
        fclose($f);
        chmod($report_path, 0755);

        return plugins_url('/../../../logs' . $filename, __FILE__);
    }


    public static function generateFailedImagesCSVReport($data){
        return self::_array2csv($data);
    }


    protected static function _array2csv($data, $delimiter = ',', $enclosure = '"', $escape_char = "\\"){
        $f = fopen('php://memory', 'r+');
        foreach ($data as $item) {
            fputcsv($f, $item, $delimiter, $enclosure, $escape_char);
        }
        rewind($f);
        return stream_get_contents($f);
    }

    protected static function _renderTHead($fields){
        $tmp_str = '<thead><tr>'. PHP_EOL;
        $end_str = '</tr></head>'. PHP_EOL;

        foreach ($fields as $field) {
            $tmp_str .= "<th>{$field}</th>" . PHP_EOL;
        }

        return $tmp_str . $end_str;
    }

    protected static function _renderTBody($data){
        $tmp_str = '<tbody>' . PHP_EOL;
        $end_str = '</tbody>' . PHP_EOL;
        $count = 1;
        foreach ($data as $row) {
            $tmp_str .= '<tr>' . PHP_EOL;
            $tmp_str .= "<td>" . $count . "</td>" . PHP_EOL;
            foreach ($row as $field_value) {
                $tmp_str .= "<td>" . $field_value . "</td>" . PHP_EOL;
            }
            $count++;
        }
        return $tmp_str . $end_str;
    }
}
?>
