<?php

namespace WpifyWooDeps\Heureka\ShopCertification;

/**
 * @author Vladimír Kašpar <vladimir.kaspar@heureka.cz>
 * @author Jakub Chábek <jakub.chabek@heureka.cz>
 */
class CurlRequester implements IRequester
{
    /**
     * @var ApiEndpoint
     */
    private $endpoint;
    /**
     * @param ApiEndpoint $endpoint
     */
    public function setApiEndpoint(ApiEndpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }
    /**
     * @inheritdoc
     */
    public function request($action, array $getData = [], array $postData = [])
    {
        try {
            $curlOptions = [\CURLOPT_RETURNTRANSFER => \true];
            if ($postData) {
                $json = \json_encode($postData, \JSON_PRETTY_PRINT);
                if ($json === \false) {
                    throw new RequesterException('Failed to serialize data into JSON. Data: ' . \var_export($postData, \true));
                }
                $curlOptions = $curlOptions + [\CURLOPT_POSTFIELDS => $json, \CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Content-Length: ' . \strlen($json)]];
            }
            $getParams = $getData ? '?' . \http_build_query($getData) : '';
            $curl = \curl_init($this->endpoint->getUrl() . $action . $getParams);
            \curl_setopt_array($curl, $curlOptions);
            $result = \curl_exec($curl);
            if ($result === \false) {
                throw new RequesterException(\sprintf('cURL error: [%d] %s', \curl_errno($curl), \curl_error($curl)));
            }
            $httpCode = \curl_getinfo($curl, \CURLINFO_HTTP_CODE);
            \curl_close($curl);
        } catch (RequesterException $e) {
            throw $e;
        } catch (\Exception $e) {
            $result = empty($result) ? '' : ', result: ' . $result;
            throw new RequesterException('An error occurred during the transfer' . $result, null, $e);
        }
        if ($httpCode !== 200) {
            throw new RequesterException(\sprintf("Request resulted in HTTP code '%d'. Response result:\n%s", $httpCode, $result));
        }
        return new Response($result);
    }
}
