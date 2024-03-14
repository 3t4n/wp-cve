<?php
/**
 * JqJsonResponse
 *
 * Simple resp wrapper for some jquery stuff.
 *
 * Copyright(c) Schuyler W Langdon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class JqJsonResponse
{
    protected $params = array();

    public function send($success = false)
    {
        $this->params['success'] = $success  ? '1' : '0';
        $response = $this->buildResponse();
        $this->killBuffers();
        ob_start();
        header('Content-Type: application/json');
        die($response);
    }

    public function setParam($key, $payload)
    {
        $this->params[$key] = $payload;
        return $this;
    }

    protected function buildResponse()
    {
        $payload = array();
        foreach ($this->params as $key => $val) {
            $payload[] = '"' . $key . '":' . json_encode($val);
        }
        return '{' . implode(',', $payload) . '}';
    }

    protected function killBuffers()
    {
        for ($ct = count(ob_list_handlers()), $xx=0;$xx<$ct;$xx++) {
            ob_end_clean();
        }
    }
}
