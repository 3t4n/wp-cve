<?php
/**
 *
 * CashBill Payment PHP SDK
 *
 * @author Lukasz Firek <lukasz.firek@cashbill.pl>
 * @version 1.0.0
 * @license MIT
 * @copyright CashBill S.A. 2015
 *
 * http://cashbill.pl
 *
 */
namespace CashBill\Payments;

require_once dirname(__FILE__) . '/Interface/Data.php';
require_once dirname(__FILE__) . '/Model/PaymentChannels.php';
require_once dirname(__FILE__) . '/Model/PersonalData.php';
require_once dirname(__FILE__) . '/Model/Transaction.php';
require_once dirname(__FILE__) . '/Model/Options.php';
require_once dirname(__FILE__) . '/Model/Amount.php';
require_once dirname(__FILE__) . '/Services/CurlConnection.php';
require_once dirname(__FILE__) . '/Services/Exception.php';
require_once dirname(__FILE__) . '/Helpers/SignatureGenerator.php';

use CashBill\Payments\Services\CurlConnection;
use CashBill\Payments\Services\CashBillException;
use CashBill\Payments\Services\CashBillConfigurationException;
use CashBill\Payments\Services\CashBillCurlException;
use CashBill\Payments\Model\PaymentChannels;
use CashBill\Payments\Model\Transaction;
use CashBill\Payments\Model\Amount;
use CashBill\Payments\Model\Options;
use CashBill\Payments\Model\PersonalData;
use CashBill\Payments\Helpers\SignatureGenerator;
use Exception;

class Shop
{
    const productionEndpoint = 'https://pay.cashbill.pl/ws/rest/';
    const testEndpoint = 'https://pay.cashbill.pl/testws/rest/';
    /**
     *
     * @var string $pointId
     * @var string $pointKey
     * @var CurlConnection $curlConnection
     * @var Transaction $transaction
     * @var PersonalData $personalData
     */
    private $pointId;
    private $pointKey;
    private $curlConnection;
    
    /**
     *
     * @param string $pointId
     * @param string $pointKey
     * @param boolean $production
     * @throws CashBillConfigurationException
     */
    public function __construct($pointId, $pointKey, $production = false)
    {
        if (empty($pointId) || empty($pointKey)) {
            throw new CashBillConfigurationException("Configuration required params not set");
        }
        
        if (strlen($pointKey) !==32) {
            throw new CashBillConfigurationException("Payment Point Key invalid value");
        }
        
        $this->pointId = $pointId;
        $this->pointKey = $pointKey;
        $this->curlConnection = $production===true ? new CurlConnection(self::productionEndpoint) : new CurlConnection(self::testEndpoint);
    }
    
    /**
     *
     * @param string $lang
     * @throws CashBillException
     * @return array
     */
    public function getPaymentChannels($lang = 'pl')
    {
        try {
            $paymentChannels = $this->curlConnection->get("paymentchannels/{$this->pointId}/{$lang}");
        } catch (CashBillCurlException $exception) {
            throw new CashBillException("Payment Channels Get Exception " . $exception->getMessage());
        }
        
        $paymentChannels = new PaymentChannels($paymentChannels, $lang);
        return $paymentChannels->getAllPaymentChannels();
    }
    /**
     *
     * @param string $title
     * @param CashBillAmount | array $amount
     * @param string $description
     * @param string $additionalData
     * @param string $personalData
     * @param string $returnUrl
     * @param string $negativeReturnUrl
     * @param string $paymentChannel
     * @param string $languageCode
     * @param string $referer
     * @throws CashBillException
     * @return \CashBill\Payments\Services\mixed
     */
    public function createPayment($title, $amount, $description, $additionalData, $personalData = null, $returnUrl = null, $negativeReturnUrl = null, $paymentChannel = null, $languageCode = null, $referer = null, $options = null)
    {
        try {
            if (! ($amount instanceof Amount)) {
                $amount = Amount::fromArray($amount);
            }
        
            $transactionData = new Transaction($title, $amount, $description, $additionalData);
            if ($paymentChannel !==null) {
                $transactionData->setPaymentChannel($paymentChannel);
            }
        
            if ($referer !== null) {
                $transactionData->setReferer($referer);
            }
        
            if ($returnUrl !== null) {
                if ($negativeReturnUrl !==null) {
                    $transactionData->setReturnUrls($returnUrl, $negativeReturnUrl);
                } else {
                    $transactionData->setReturnUrls($returnUrl);
                }
            }
        
            if ($languageCode !== null) {
                $transactionData->setLanguage($languageCode);
            }
        
            if ($personalData === null) {
                $transactionData = $transactionData->toArray();
            } else {
                if (! ($personalData instanceof PersonalData)) {
                    $personalData = PersonalData::fromArray($personalData);
                }
            
                $transactionData = array_merge($transactionData->toArray(), $personalData->toArray());
            }

            if ($options !== null && $options instanceof Options) {
                $transactionData = array_merge($transactionData, array("options" => $options->toArray()));
            }

            $transactionData ['sign'] = SignatureGenerator::generateSHA1($transactionData, $this->pointKey);

            if (isset($transactionData['options']) && $transactionData['options'] !== null) {
                $transactionData['options'] = json_encode($transactionData['options']);
            }

            return $this->curlConnection->post("payment/{$this->pointId}", $transactionData);
        } catch (CashBillException $exception) {
            throw new CashBillException("Create Payment Get Exception " . $exception->getMessage());
        }
    }
    
    /**
     *
     ** @param string $orderId
     * @param string $returnUrl
     * @param string $negativeReturnUrl
     * @throws CashBillException
     * @return boolean
     */
    public function changeRedirectUrl($orderId, $returnUrl, $negativeReturnUrl = '')
    {
        if (empty($orderId)) {
            throw new CashBillException("orderId is empty");
        }
        
        if (empty($returnUrl)) {
            throw new CashBillException("returnUrl is empty");
        }
        
        if (! filter_var($returnUrl, FILTER_VALIDATE_URL)) {
            throw new CashBillException("returnUrl not valid");
        }
        
        if (! empty($negativeReturnUrl) && ! filter_var($returnUrl, FILTER_VALIDATE_URL)) {
            throw new CashBillException("negativeReturnUrl not valid");
        }
        
        $changeData = array(
                'id' => $orderId,
                'returnUrl' => $returnUrl,
                'negativeReturnUrl' => $negativeReturnUrl
        );
        
        $changeData ['sign'] = SignatureGenerator::generateSHA1($changeData, $this->pointKey);
        
        try {
            $this->curlConnection->put("payment/{$this->pointId}/{$changeData['id']}", $changeData);
            return true;
        } catch (CashBillCurlException $exception) {
            throw new CashBillException("Url Change Exception " . $exception->getMessage());
        }
    }
    
    /**
     *
     * @param string $orderId
     * @throws CashBillException
     * @return stdClass
     */
    public function getFullPaymentInfo($orderId)
    {
        $paymentData = array(
                'sign' => SignatureGenerator::generateSHA1($orderId, $this->pointKey)
        );
        
        try {
            return $this->curlConnection->get("payment/{$this->pointId}/{$orderId}", $paymentData);
        } catch (CashBillCurlException $exception) {
            throw new CashBillException("Payment Info Exception " . $exception->getMessage());
        }
    }
    
    /**
     *
     * @param string $orderId
     * @throws CashBillException
     * @return boolean
     */
    public function isPaid($orderId)
    {
        $paymentData = array(
                'sign' => SignatureGenerator::generateSHA1($orderId, $this->pointKey)
        );
        
        try {
            $transaction = $this->curlConnection->get("payment/{$this->pointId}/{$orderId}", $paymentData);
            
            if ($transaction->status==="PositiveFinish") {
                return true;
            } else {
                return false;
            }
        } catch (CashBillCurlException $exception) {
            throw new CashBillException("Payment Info Exception : " . $exception->getMessage());
        }
    }
    
    /**
     *
     * @param string $finishStatusNotify
     * @param string $anotherStatusNotify
     * @param string $class
     */
    public function notificationHandler($finishStatusNotify, $anotherStatusNotify = null, $class = null)
    {
        if (isset($_GET ['cmd']) && isset($_GET ['args']) && isset($_GET ['sign'])) {
            if (SignatureGenerator::generateMD5($_GET ['cmd'] . $_GET ['args'], $this->pointKey)===$_GET ['sign']) {
                if ($this->isPaid($_GET ['args'])) {
                    if ($class === null) {
                        call_user_func($finishStatusNotify, $this->getFullPaymentInfo($_GET ['args']));
                    } else {
                        call_user_func(array(
                                $class,
                                $finishStatusNotify
                        ), $this->getFullPaymentInfo($_GET ['args']));
                    }
                } else {
                    if ($anotherStatusNotify !== null) {
                        if ($class === null) {
                            call_user_func($anotherStatusNotify, $this->getFullPaymentInfo($_GET ['args']));
                        } else {
                            call_user_func(array(
                                    $class,
                                    $anotherStatusNotify
                            ), $this->getFullPaymentInfo($_GET ['args']));
                        }
                    }
                }
                echo "OK";
                exit();
            } else {
                echo "SIGNATURE ERROR";
                exit();
            }
        }
    }
}
