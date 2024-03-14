<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Logger;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject;
/**
 * Class ImportLoggerService.
 * @package WPDesk\Library\DropshippingXmlCore\Service\Logger
 */
class ImportLoggerService
{
    /**
     * @var array
     */
    private $messages = array();
    public function error($message)
    {
        $this->add_message('error', $message);
    }
    public function notice($message)
    {
        $this->add_message('notice', $message);
    }
    public function debug($message)
    {
        $this->add_message('debug', $message);
    }
    public function flush_to_file(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\File\FileObject $file)
    {
        $fp = \fopen($file->getRealPath(), 'a');
        \fwrite($fp, $this->get_formated_messages());
        \fclose($fp);
    }
    public function get_formated_messages() : string
    {
        $str = '';
        foreach ($this->messages as $message) {
            $str .= $this->format_data($message['type'], $message['message'], $message['time']) . "\n";
        }
        return $str;
    }
    private function format_data(string $type, string $message, int $time) : string
    {
        return '[' . \date('d-m-Y H:i:s', $time) . '] ' . \strtoupper($type) . ': ' . $message;
    }
    private function add_message(string $type, string $message)
    {
        $this->messages[] = ['type' => $type, 'message' => $message, 'time' => \time()];
    }
}
