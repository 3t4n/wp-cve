<?php
class OfficeGuyAPI
{
    public static function GetURL($Path, $Environment)
    {
        if ($Environment == "dev")
            return 'http://' . $Environment . '.api.sumit.co.il' . $Path;
        else
            return 'https://api.sumit.co.il' . $Path;
    }

    public static function Post($Request, $Path, $Environment, $SendClientIP)
    {
        $Response = OfficeGuyAPI::PostRaw($Request, $Path, $Environment, $SendClientIP);

        // Quit if it didn't work
        if (is_wp_error($Response))
        {
            if (function_exists('wc_add_notice'))
            {
                wc_add_notice(__('Problem connecting to server at ', 'officeguy') . OfficeGuyAPI::GetURL($Path, $Environment) . ' (' . $Response->get_error_message() . ')', $notice_type = 'error');
            }
            return null;
        }

        $Body = wp_remote_retrieve_body($Response);
        $Body = json_decode($Body, true);
        return $Body;
    }

    public static function PostRaw($Request, $Path, $Environment, $SendClientIP)
    {
        if (empty($Environment))
            $Environment = 'www';
            
        $URL = OfficeGuyAPI::GetURL($Path, $Environment);

        $RequestLog = json_decode(json_encode($Request, JSON_PRETTY_PRINT));
        if (isset($RequestLog->PaymentMethod))
        {
            $RequestLog->PaymentMethod->CreditCard_Number = '';
            $RequestLog->PaymentMethod->CreditCard_CVV = '';
        }
        $RequestLog->CardNumber = '';
        $RequestLog->CVV = '';

        OfficeGuyAPI::WriteToLog('Request: ' . $URL . "\r\n" . json_encode($RequestLog, JSON_PRETTY_PRINT), 'debug');

        // Send request
        $Response = wp_remote_post($URL, array(
            'body' => json_encode($Request),
            'timeout' => 180,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(
                'Content-Type' => 'application/json',
                'Content-Language' => get_locale(),
                'X-OG-Client' => 'WooCommerce',
                'X-OG-ClientIP' => $SendClientIP ? $_SERVER['REMOTE_ADDR'] : null
            ),
            'cookies' => array(),
            'ssl_verify' => false
        ));

        OfficeGuyAPI::WriteToLog('Response: ' . $URL . "\r\n" . json_encode($Response), 'debug');
        return $Response;
    }

    public static function CheckCredentials($CompanyID, $APIKey)
    {
        $Gateway = GetOfficeGuyGateway();
        $Credentials = array();
        $Credentials['CompanyID'] = $CompanyID;
        $Credentials['APIKey'] = $APIKey;

        $Request = array();
        $Request['Credentials'] = $Credentials;
        $Response = OfficeGuyAPI::Post($Request, '/website/companies/getdetails/', $Gateway->settings['environment'], false);
        if ($Response == null)
            return 'No response';

        if ($Response['Status'] == 'Success')
            return null;
        else
            return $Response['UserErrorMessage'];
    }

    public static function CheckPublicCredentials($CompanyID, $APIPublicKey)
    {
        $Gateway = GetOfficeGuyGateway();

        $Credentials = array();   
        $Credentials['CompanyID'] = $CompanyID;
        $Credentials['APIPublicKey'] = $APIPublicKey;
        
        $Request = array();
        $Request['Credentials'] = $Credentials;
        $Request["CardNumber"] = '12345678';
        $Request["ExpirationMonth"] = '01';
        $Request["ExpirationYear"] = '2030';
        $Request["CVV"] = '123';
        $Request["CitizenID"] = '123456789';

        $Response = OfficeGuyAPI::Post($Request, '/creditguy/vault/tokenizesingleusejson/', $Gateway->settings['environment'], false);
        if ($Response == null)
            return 'No response';

        if ($Response['Status'] == 'Success')
            return null;
        else
            return $Response['UserErrorMessage'];
    }

    public static function WriteToLog($Text, $Type)
    {
        $Gateway = GetOfficeGuyGateway();
        if (!isset($Gateway) || $Gateway->settings['logging'] != 'yes')
            return;

        $Logger = wc_get_logger();
        if ($Logger == null)
            return;

        $Logger->add('SUMIT', $Type . ': ' . $Text . "\r\n");
    }
}
