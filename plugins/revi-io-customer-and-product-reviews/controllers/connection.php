<?php
class revi_connection
{
    public function __construct()
    {
        $to = "google";
        if (isset($_REQUEST['to'])) {
            $to = $_REQUEST['to'];
        }

        $connection_result = $this->getConnection($to);

        echo $connection_result;
    }

    private function getConnection($to)
    {
        $url = '';
        switch ($to) {
            case 'google':
                $url = 'https://google.es';
                break;
            case 'dinahosting':
                $url = 'https://dinahosting.com';
                break;
            case 'revi':
                $url = 'https://revi.io';
                break;
            case 'whatsmyip':
                $url = 'https://www.whatsmyip.org/';
                break;
            default:
                die('Tipo no válido');
        }
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $ip_server = getHostByName(getHostName());

        echo "RESULTADO CONEXIÓN:" . "</br>";
        echo "IP PÚBLICA: {$ip_address}" . "</br>";
        echo "IP PRIVADA: {$ip_server}" . "</br>";
        $this->ping($url);
        $this->get_web_page($url);

        $response = file_get_contents($url);

        return $response;
    }

    private function ping($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        // Descomenta la siguiente línea si necesitas evitar la verificación de SSL
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if ($response === false) {
            // Imprime el error si la solicitud cURL falla
            echo "cURL Error: " . curl_error($ch);
            echo "\nError Number: " . curl_errno($ch);
        } else {
            // Verifica el código de estado HTTP
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpcode >= 200 && $httpcode < 300) {
                echo "</br>" . "La página responde correctamente." . "</br>";
            } else {
                echo "</br>" . "La página no está accesible. Código de estado: " . $httpcode . "</br>";
            }
        }

        curl_close($ch);
    }

    function get_web_page($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 30,      // timeout on connect
            CURLOPT_TIMEOUT        => 30,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
        );

        $ch      = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        echo '<pre>';
        print_r($header);
        echo '</pre>';
    }
}
