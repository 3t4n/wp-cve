<?php

namespace ecpay_shipping;

require_once(ECPAY_PLUGIN_PATH . 'ECPayLogisticsModuleHelper.php');

/**
 *  物流類型
 */
if (!class_exists('EcpayLogisticsType', false)) {
    abstract class EcpayLogisticsType {
        const CVS = 'CVS';// 超商取貨
        const HOME = 'Home';// 宅配
    }
}

/**
 *  物流子類型
 */
if (!class_exists('EcpayLogisticsSubType', false)) {
    abstract class EcpayLogisticsSubType {
        const TCAT = 'TCAT';// 黑貓(宅配)
        const ECAN = 'ECAN';// 宅配通
        const FAMILY = 'FAMI';// 全家
        const UNIMART = 'UNIMART';// 統一超商
        const HILIFE = 'HILIFE';// 萊爾富
        const FAMILY_C2C = 'FAMIC2C';// 全家店到店
        const UNIMART_C2C = 'UNIMARTC2C';// 統一超商寄貨便
        const HILIFE_C2C = 'HILIFEC2C';// 萊爾富富店到店
    }
}
/**
 *  是否代收貨款
 *
 */
if (!class_exists('EcpayIsCollection', false)) {
    abstract class EcpayIsCollection {
        const YES = 'Y';// 貨到付款
        const NO = 'N';// 僅配送
    }
}

/**
 *  使用設備
 */
if (!class_exists('EcpayDevice', false)) {
    abstract class EcpayDevice {
        const PC = 0;// PC
        const MOBILE = 1;// 行動裝置
    }
}

/**
 *  測試廠商編號
 */
if (!class_exists('EcpayTestMerchantId', false)) {
    abstract class EcpayTestMerchantId {
        const B2C = '2000132';// B2C
        const C2C = '2000933';// C2C
    }
}

/**
 *  正式環境網址
 */
if (!class_exists('EcpayUrl', false)) {
    abstract class EcpayUrl {
        const CVS_MAP = 'https://logistics.ecpay.com.tw/Express/map';// 電子地圖
        const SHIPPING_ORDER = 'https://logistics.ecpay.com.tw/Express/Create';// 物流訂單建立
        const HOME_RETURN_ORDER = 'https://logistics.ecpay.com.tw/Express/ReturnHome';// 宅配逆物流訂單
        const UNIMART_RETURN_ORDER = 'https://logistics.ecpay.com.tw/express/ReturnUniMartCVS';// 超商取貨逆物流訂單(統一超商B2C)
        const HILIFE_RETURN_ORDER = 'https://logistics.ecpay.com.tw/express/ReturnHiLifeCVS';// 超商取貨逆物流訂單(萊爾富超商B2C)
        const FAMILY_RETURN_ORDER = 'https://logistics.ecpay.com.tw/express/ReturnCVS';// 超商取貨逆物流訂單(全家超商B2C)
        const FAMILY_RETURN_CHECK = 'https://logistics.ecpay.com.tw/Helper/LogisticsCheckAccoounts';// 全家逆物流核帳(全家超商B2C)
        const UNIMART_UPDATE_LOGISTICS_INFO = 'https://logistics.ecpay.com.tw/Helper/UpdateShipmentInfo';// 統一修改物流資訊(全家超商B2C)
        const UNIMART_UPDATE_STORE_INFO = 'https://logistics.ecpay.com.tw/Express/UpdateStoreInfo';// 更新門市(統一超商C2C)
        const UNIMART_CANCEL_LOGISTICS_ORDER = 'https://logistics.ecpay.com.tw/Express/CancelC2COrder';// 取消訂單(統一超商C2C)
        const QUERY_LOGISTICS_INFO = 'https://logistics.ecpay.com.tw/Helper/QueryLogisticsTradeInfo/V2';// 物流訂單查詢
        const PRINT_TRADE_DOC = 'https://logistics.ecpay.com.tw/helper/printTradeDocument';// 產生托運單(宅配)/一段標(超商取貨)
        const PRINT_UNIMART_C2C_BILL = 'https://logistics.ecpay.com.tw/Express/PrintUniMartC2COrderInfo';// 列印繳款單(統一超商C2C)
        const PRINT_FAMILY_C2C_BILL = 'https://logistics.ecpay.com.tw/Express/PrintFAMIC2COrderInfo';// 全家列印小白單(全家超商C2C)
        const Print_HILIFE_C2C_BILL = 'https://logistics.ecpay.com.tw/Express/PrintHILIFEC2COrderInfo';// 萊爾富列印小白單(萊爾富超商C2C)
        const CREATE_TEST_DATA = 'https://logistics.ecpay.com.tw/Express/CreateTestData';// 產生 B2C 測標資料
    }
}

/**
 *  測試環境網址
 */
if (!class_exists('EcpayTestUrl', false)) {
    abstract class EcpayTestUrl {
        const CVS_MAP = 'https://logistics-stage.ecpay.com.tw/Express/map';// 電子地圖
        const SHIPPING_ORDER = 'https://logistics-stage.ecpay.com.tw/Express/Create';// 物流訂單建立
        const HOME_RETURN_ORDER = 'https://logistics-stage.ecpay.com.tw/Express/ReturnHome';// 宅配逆物流訂單
        const UNIMART_RETURN_ORDER = 'https://logistics-stage.ecpay.com.tw/express/ReturnUniMartCVS';// 超商取貨逆物流訂單(統一超商B2C)
        const HILIFE_RETURN_ORDER = 'https://logistics-stage.ecpay.com.tw/express/ReturnHiLifeCVS';// 超商取貨逆物流訂單(萊爾富超商B2C)
        const FAMILY_RETURN_ORDER = 'https://logistics-stage.ecpay.com.tw/express/ReturnCVS';// 超商取貨逆物流訂單(全家超商B2C)
        const FAMILY_RETURN_CHECK = 'https://logistics-stage.ecpay.com.tw/Helper/LogisticsCheckAccoounts';// 全家逆物流核帳(全家超商B2C)
        const UNIMART_UPDATE_LOGISTICS_INFO = 'https://logistics-stage.ecpay.com.tw/Helper/UpdateShipmentInfo';// 統一修改物流資訊(全家超商B2C)
        const UNIMART_UPDATE_STORE_INFO = 'https://logistics-stage.ecpay.com.tw/Express/UpdateStoreInfo';// 更新門市(統一超商C2C)
        const UNIMART_CANCEL_LOGISTICS_ORDER = 'https://logistics-stage.ecpay.com.tw/Express/CancelC2COrder';// 取消訂單(統一超商C2C)
        const QUERY_LOGISTICS_INFO = 'https://logistics-stage.ecpay.com.tw/Helper/QueryLogisticsTradeInfo/V2';// 物流訂單查詢
        const PRINT_TRADE_DOC = 'https://logistics-stage.ecpay.com.tw/helper/printTradeDocument';// 產生托運單(宅配)/一段標(超商取貨)
        const PRINT_UNIMART_C2C_BILL = 'https://logistics-stage.ecpay.com.tw/Express/PrintUniMartc2cOrderInfo';// 列印繳款單(統一超商C2C)
        const PRINT_FAMILY_C2C_BILL = 'https://logistics-stage.ecpay.com.tw/Express/PrintFamic2cOrderInfo';// 全家列印小白單(全家超商C2C)
        const Print_HILIFE_C2C_BILL = 'https://logistics-stage.ecpay.com.tw/Express/PrintHilifec2cOrderInfo';// 萊爾富列印小白單(萊爾富超商C2C)
        const CREATE_TEST_DATA = 'https://logistics-stage.ecpay.com.tw/Express/CreateTestData';// 產生 B2C 測標資料
    }
}

final class ECPayLogisticsHelper extends ECPayLogisticsModuleHelper
{
    /**
     * @var string SDK class name(required)
     */
    protected $sdkClassName = 'Ecpay\Sdk\Factories\Factory';

    /**
     * @var string SDK file path(required)
     */
    protected $sdkFilePath = '/vendor/autoload.php';

    /**
     * @var string 目錄路徑
     */
    public $dirPath = '';

    /**
     * @var array 綠界物流項目
     */
    public $ecpayLogistics = array(
        'B2C' => array(
            'HILIFE'            => '萊爾富',
            'HILIFE_Collection' => '萊爾富取貨付款',
            'FAMI'              => '全家',
            'FAMI_Collection'   => '全家取貨付款',
            'UNIMART'           => '統一超商',
            'UNIMART_Collection'=> '統一超商寄貨便取貨付款'
        ),
        'C2C' => array(
            'HILIFE'            => '萊爾富',
            'HILIFE_Collection' => '萊爾富取貨付款',
            'FAMI'              => '全家',
            'FAMI_Collection'   => '全家取貨付款',
            'UNIMART'           => '統一超商',
            'UNIMART_Collection'=> '統一超商寄貨便取貨付款'
        )
    );

    /**
     * @var array 綠界取貨付款列表
     */
    public $shippingPayList = array(
        'HILIFE_Collection',
        'FAMI_Collection',
        'UNIMART_Collection'
    );

    /**
     * @var array 綠界C2C列印繳費單功能列表
     */
    public $paymentFormMethods = array(
        'FAMIC2C'    => 'PRINT_FAMILY_C2C_BILL',
        'UNIMARTC2C' => 'PRINT_UNIMART_C2C_BILL',
        'HILIFEC2C'  => 'Print_HILIFE_C2C_BILL',
    );

    /**
     * @var array 訂單狀態
     */
    public $orderStatus = array(
        'pending'    => '', // 等待付款
        'processing' => '', // 處理中(已付款)
        'onHold'     => '', // 保留
        'ecpay'      => '', // ECPay Shipping
    );

    /**
     * ECPayLogisticHelper constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * changeStore function
     * 變更門市
     *
     * @param  array  $data
     * @return string $postHTML
     */
    public function changeStore($data)
    {
        $postHTML  = $this->genPostHTML($data, 'ecpay');
        $postHTML .= "<input class='button' type='button' onclick='ecpayChangeStore();' value='變更門市' /><br />";

        return $postHTML;
    }

    /**
     * createShippingOrder function
     * 建立物流訂單
     *
     * @param  array  $data
     * @return string $html
     */
    public function createShippingOrder($data)
    {
        $this->hashKey    = $data['HashKey'];
        $this->hashIv     = $data['HashIV'];
        $this->hashMethod = 'md5';
        $this->setFactory();

        $autoSubmitFormService = $this->sdk->create('AutoSubmitFormWithCmvService');

        $input = [
            'MerchantID'           => $this->getMerchantId(),
            'MerchantTradeNo'      => $this->setMerchantTradeNo($data['MerchantTradeNo']),
            'MerchantTradeDate'    => $this->getDateTime('Y/m/d H:i:s', ''),
            'LogisticsType'        => EcpayLogisticsType::CVS,
            'LogisticsSubType'     => $data['LogisticsSubType'],
            'GoodsAmount'          => $data['GoodsAmount'],
            'CollectionAmount'     => $data['CollectionAmount'],
            'IsCollection'         => $this->isCollection($data['IsCollection']),
            'GoodsName'            => '網路商品一批',
            'SenderName'           => $data['SenderName'],
            'SenderPhone'          => $data['SenderPhone'],
            'SenderCellPhone'      => $data['SenderCellPhone'],
            'ReceiverName'         => $data['ReceiverName'],
            'ReceiverPhone'        => $data['ReceiverPhone'],
            'ReceiverCellPhone'    => $data['ReceiverCellPhone'],
            'ReceiverEmail'        => $data['ReceiverEmail'],
            'TradeDesc'            => '',
            'ServerReplyURL'       => $data['ServerReplyURL'],
            'LogisticsC2CReplyURL' => $data['LogisticsC2CReplyURL'],
            'Remark'               => $this->replaceStrNewLine($data['Remark']),
            'PlatformID'           => '',
            'ReceiverStoreID'      => $data['ReceiverStoreID'],
            'ReturnStoreID'        => $data['ReturnStoreID']
        ];
        $action = $this->GetURL('SHIPPING_ORDER');

        // SDK 無法控制是否自動送出，因此由此處理移除
        $target = 'Map';
        $formId = 'ECPayForm';
        $html = $autoSubmitFormService->generate($input, $action, $target, $formId);
        $html = $this->replaceAutoSubmit($formId, $html);

        return $html;
    }

    /**
     * genPostHTML function
     * 產生 POST 提交表單
     *
     * @param  string $target 表單 action 目標
     * @return string $postHTML
     */
    public function genPostHTML($params, $target = '_self')
    {
        $whiteList = array(
            'formId'    ,
            'serviceURL',
            'postParams',
        );
        $inputs = $this->only($params, $whiteList);

        $postParams = array(
            'MerchantID'       ,
            'MerchantTradeNo'  ,
            'LogisticsSubType' ,
            'IsCollection'     ,
            'ServerReplyURL'   ,
            'ExtraData'        ,
            'Device'           ,
            'LogisticsType'    ,
        );
        $inputs['postParams'] = $this->only($inputs['postParams'], $postParams);

        $postHTML = $this->addNextLine('  <form id="'. $inputs['formId'] .'" method="POST" action="' . $inputs['serviceURL'] . '" target="' . $target . '"  style="display:none">');
        foreach ($inputs['postParams'] as $name => $value) {
            if ($name == 'Device') {
                $postHTML .= $this->addNextLine('    <input type="hidden" name="' . $name . '" value="' . $this->getDevice($value) . '" />');
            } else {
                $postHTML .= $this->addNextLine('    <input type="hidden" name="' . $name . '" value="' . $value . '" />');
            }
        }
        $postHTML .= $this->addNextLine('  </form>');

        return $postHTML;
    }

    /**
     * getCvsMap function
     * 電子地圖
     *
     * @param  array  $data
     * @return string
     */
    public function getCvsMap($data, $buttonText = '電子地圖')
    {
        $this->setFactory();

        // Filter inputs
        $whiteList = array(
            'MerchantID',
            'MerchantTradeNo' ,
            'LogisticsSubType',
            'IsCollection' ,
            'ServerReplyURL' ,
            'ExtraData',
            'Device',
        );
        $inputs = $this->only($data, $whiteList);

        $autoSubmitFormService = $this->sdk->create('AutoSubmitFormWithCmvService');

        $input = [
            'MerchantID'       => $this->getMerchantId(),
            'MerchantTradeNo'  => $inputs['MerchantTradeNo'],
            'LogisticsType'    => EcpayLogisticsType::CVS,
            'LogisticsSubType' => $inputs['LogisticsSubType'],
            'IsCollection'     => EcpayIsCollection::NO,
            'ServerReplyURL'   => $inputs['ServerReplyURL'],
        ];
        $action = $this->GetURL('CVS_MAP');

        return $autoSubmitFormService->generate($input, $action);
    }

    /**
     * getDevice function.
     * 取得裝置類別 : PC or MOBILE
     *
     * @param  bool    是否為mobile裝置
     * @return integer PC = 0 ; MOBILE = 1
     */
    public function getDevice($isMobile)
    {
        // 預設裝置為PC
        $device = EcpayDevice::PC;

        if($isMobile){
            $device = EcpayDevice::MOBILE;
        }
        return $device;
    }

    /**
     * getOrderStatusPending function
     * 取得購物車訂單狀態 - 等待付款
     *
     * @return string 等待付款
     */
    public function getOrderStatusPending()
    {
        return $this->orderStatus['pending'];
    }

    /**
     * getOrderStatusProcessing function
     * 取得購物車訂單狀態 - 處理中(已付款)
     *
     * @return string 處理中(已付款)
     */
    public function getOrderStatusProcessing()
    {
        return $this->orderStatus['processing'];
    }

    /**
     * getOrderStatusOnHold function
     * 取得購物車訂單狀態 - 保留
     *
     * @return string 保留
     */
    public function getOrderStatusOnHold()
    {
        return $this->orderStatus['onHold'];
    }

    /**
     * getOrderStatusEcpay function
     * 取得購物車訂單狀態 - ECPay Shipping
     *
     * @return string ECPay Shipping
     */
    public function getOrderStatusEcpay()
    {
        return $this->orderStatus['ecpay'];
    }

    /**
     * getPaymentCategory function
     * 取得物流子類別清單
     *
     * @param  string $category 物流類型 "B2C" or "C2C"
     * @return array            物流子類別清單
     */
    public function getPaymentCategory($category)
    {
        if ($category == "B2C") {
            return array('FAMI' => EcpayLogisticsSubType::FAMILY,
                'FAMI_Collection' => EcpayLogisticsSubType::FAMILY,
                'UNIMART' => EcpayLogisticsSubType::UNIMART,
                'UNIMART_Collection' => EcpayLogisticsSubType::UNIMART,
                'HILIFE' => EcpayLogisticsSubType::HILIFE,
                'HILIFE_Collection' => EcpayLogisticsSubType::HILIFE
            );
        } else {
            return array(
                'FAMI' => EcpayLogisticsSubType::FAMILY_C2C,
                'FAMI_Collection' => EcpayLogisticsSubType::FAMILY_C2C,
                'UNIMART' => EcpayLogisticsSubType::UNIMART_C2C,
                'UNIMART_Collection' => EcpayLogisticsSubType::UNIMART_C2C,
                'HILIFE' => EcpayLogisticsSubType::HILIFE_C2C,
                'HILIFE_Collection' => EcpayLogisticsSubType::HILIFE_C2C
            );
        }
    }

    /**
     * getReceiverName function
     * 取得收件者姓名
     *
     * @param  array    $orderInfo    訂單資訊
     * @return string                 收件者姓名
     */
    public function getReceiverName($orderInfo)
    {
        $receiverName = '';
        if (array_key_exists('shippingFirstName', $orderInfo) && array_key_exists('shippingLastName', $orderInfo)) {
            $receiverName = $orderInfo['shippingLastName'] . $orderInfo['shippingFirstName'];
        } else {
            $receiverName = $orderInfo['billingLastName'] . $orderInfo['billingFirstName'];
        }
        return $receiverName;
    }

    /**
     * getStatusTable function.
     * 狀態對照表
     *
     * @param  array   $data
     * @var $data['category']     string, required, "B2C" or "C2C"
     * @var $data['orderStatus']  string, required, 訂單狀態
     * @var $data['isCollection'] string, required, 是否為取貨付款 "Y" or "N"
     *
     * @return integer $status       比對狀態
     */
    public function getStatusTable($data)
    {
        // 回傳狀態
        $status = '99';

        // Filter inputs
        $whiteList = array(
            'category'     ,
            'orderStatus'  ,
            'isCollection' ,
        );
        $inputs = $this->only($data, $whiteList);

        // 接收參數
        $category = $inputs['category'];
        $orderStatus = $inputs['orderStatus'];
        $isCollection = $this->isCollection($inputs['isCollection']);

        // 對照狀態
        if (($isCollection == EcpayIsCollection::YES && $orderStatus == $this->getOrderStatusOnHold()) || ($isCollection == EcpayIsCollection::NO && $orderStatus == $this->getOrderStatusProcessing())) {
            // 可建立物流訂單的狀態:
            // 貨到付款並且訂單狀態為保留(訂單成立) 或 除了貨到付款以外的付款方式並且訂單狀態為處理中(已付款)
            $status = 0;
        } elseif ($orderStatus == $this->getOrderStatusEcpay() && $category == 'C2C') {
            // 訂單狀態為ECPay Shipping並且物流類型為C2C
            $status = 1;
        }

        return $status;
    }

    /**
     * isCollection function
     * 是否代收貨款(取貨付款)
     *
     * @param  string  $shippingType
     * @return string  'Y' or 'N'
     */
    public function isCollection($shippingType)
    {
        return (in_array($shippingType, $this->shippingPayList)) ? EcpayIsCollection::YES : EcpayIsCollection::NO;
    }

    /**
     * paymentForm function
     * 產生列印繳款單
     *
     * @param  array  $data
     * @param  string $paymentFormMethod
     * @param  array  $paymentFormFileds
     * @return string
     */
    public function paymentForm($data, $paymentFormMethod, $paymentFormFileds)
    {
        $postHTML = '';

        $this->hashKey    = $data['HashKey'];
        $this->hashIv     = $data['HashIV'];
        $this->hashMethod = 'md5';
        $this->setFactory();

        $autoSubmitFormService = $this->sdk->create('AutoSubmitFormWithCmvService');

        $input = [
            'MerchantID'        => $this->getMerchantId(),
            'AllPayLogisticsID' => $paymentFormFileds['AllPayLogisticsID'],
            'CVSPaymentNo'      => $paymentFormFileds['CVSPaymentNo'],
            'CVSValidationNo'   => $paymentFormFileds['CVSValidationNo']
        ];
        $action = $this->GetURL($paymentFormMethod);
        $formId = 'ECPayForm';

        if ( isset($paymentFormFileds['AllPayLogisticsID'], $paymentFormMethod) && ! is_null($this->sdk) && ! is_string($this->sdk) && ! is_numeric($this->sdk) ) {
            if (in_array($paymentFormMethod, $this->paymentFormMethods)) {
                $postHTML = $autoSubmitFormService->generate($input, $action, '_blank', $formId);
                $postHTML .= "<input class='button' type='button' onclick='ecpayPaymentForm();' value='列印繳款單' /><br />";
                $postHTML = $this->replaceAutoSubmit($formId, $postHTML);
            }
        }

        return $postHTML;
    }

    /**
     * receiveResponse function
     * 物流貨態回傳值
     *
     * @param  string  $rtnCode 回傳的狀態碼
     * @return integer $status  對應狀態
     */
    public function receiveResponse($rtnCode)
    {
        $status = 99 ;
        $aSuccessCodes = ['300', '2001', '2067', '3022'];

        // 判斷是否回傳成功狀態
        if (in_array($rtnCode, $aSuccessCodes)) {

            // 300  : 訂單處理中(已收到訂單資料)
            // 2001 : 檔案傳送成功
            if ($rtnCode == '300' || $rtnCode == '2001') {
                $status = 0 ;
            }

            // 2067 : 消費者成功取件
            // 3022 : 買家已到店取貨
            if ($rtnCode == '2067' || $rtnCode == '3022') {
                $status = 1 ;
            }
        }

        return $status;
    }

    /**
     * setOrderStatus function
     * 設定購物車訂單狀態 - 全部
     *
     * @param  array $data
     * @return void
     */
    public function setOrderStatus($data)
    {
        $status = array('Pending', 'Processing', 'OnHold', 'Ecpay');

        foreach($status as $value) {
            $funName = 'setOrderStatus' . $value; // 組合 function name
            $this->$funName($data[$value]);
        }
    }

    /**
     * setOrderStatusPending function
     * 設定購物車訂單狀態 - 等待付款
     *
     * @param  string $value 要儲存的值
     * @return void
     */
    public function setOrderStatusPending($value)
    {
        $this->orderStatus['pending'] = $value;
    }

    /**
     * setOrderStatusProcessing function
     * 設定購物車訂單狀態 - 處理中(已付款)
     *
     * @param  string $value 要儲存的值
     * @return void
     */
    public function setOrderStatusProcessing($value)
    {
        $this->orderStatus['processing'] = $value;
    }

    /**
     * setOrderStatusOnHold function
     * 設定購物車訂單狀態 - 保留
     *
     * @param  string $value 要儲存的值
     * @return void
     */
    public function setOrderStatusOnHold($value)
    {
        $this->orderStatus['onHold'] = $value;
    }

    /**
     * setOrderStatusEcpay function
     * 設定購物車訂單狀態 - ECPay Shipping
     *
     * @param  string $value 要儲存的值
     * @return void
     */
    public function setOrderStatusEcpay($value)
    {
        $this->orderStatus['ecpay'] = $value;
    }

    /**
     * 替換字串換行符號
     *
     * @param  string $subject 被執行動作字串
     * @param  string $replace 替換換行符號的值，預設為空格
     * @return string
     */
    public function replaceStrNewLine($subject, $replace = ' ')
    {
        return str_replace(array("\r", "\n", "\r\n", "\n\r"), $replace, $subject);
    }

    /**
     * 替換自動送出表單 script
     *
     * @param string $formId  表單ID
     * @param string $subject 被執行動作字串
     */
    public function replaceAutoSubmit($formId, $subject)
    {
        return str_replace('document.getElementById("' . $formId . '").submit();', '', $subject);
    }

    /**
     *  取得 ECPay URL
     *
     * @param  string $FunctionType 功能名稱
     * @return string
     */
    public function GetURL($FunctionType)
    {
        $MerchantID = $this->getMerchantId();
        $UrlList = array();
        if ($MerchantID == EcpayTestMerchantID::B2C or $MerchantID == EcpayTestMerchantID::C2C) {
            // 測試環境
            $UrlList = array(
                'CVS_MAP' => EcpayTestURL::CVS_MAP,
                'SHIPPING_ORDER' => EcpayTestURL::SHIPPING_ORDER,
                'HOME_RETURN_ORDER' => EcpayTestURL::HOME_RETURN_ORDER,
                'UNIMART_RETURN_ORDER' => EcpayTestURL::UNIMART_RETURN_ORDER,
                'HILIFE_RETURN_ORDER' => EcpayTestURL::HILIFE_RETURN_ORDER,
                'FAMILY_RETURN_ORDER' => EcpayTestURL::FAMILY_RETURN_ORDER,
                'FAMILY_RETURN_CHECK' => EcpayTestURL::FAMILY_RETURN_CHECK,
                'UNIMART_UPDATE_LOGISTICS_INFO' => EcpayTestURL::UNIMART_UPDATE_LOGISTICS_INFO,
                'UNIMART_UPDATE_STORE_INFO' => EcpayTestURL::UNIMART_UPDATE_STORE_INFO,
                'UNIMART_CANCEL_LOGISTICS_ORDER' => EcpayTestURL::UNIMART_CANCEL_LOGISTICS_ORDER,
                'QUERY_LOGISTICS_INFO' => EcpayTestURL::QUERY_LOGISTICS_INFO,
                'PRINT_TRADE_DOC' => EcpayTestURL::PRINT_TRADE_DOC,
                'PRINT_UNIMART_C2C_BILL' => EcpayTestURL::PRINT_UNIMART_C2C_BILL,
                'PRINT_FAMILY_C2C_BILL' => EcpayTestURL::PRINT_FAMILY_C2C_BILL,
                'Print_HILIFE_C2C_BILL' => EcpayTestURL::Print_HILIFE_C2C_BILL,
                'CREATE_TEST_DATA' => EcpayTestURL::CREATE_TEST_DATA,
            );
        } else {
            // 正式環境
            $UrlList = array(
                'CVS_MAP' => EcpayURL::CVS_MAP,
                'SHIPPING_ORDER' => EcpayURL::SHIPPING_ORDER,
                'HOME_RETURN_ORDER' => EcpayURL::HOME_RETURN_ORDER,
                'UNIMART_RETURN_ORDER' => EcpayURL::UNIMART_RETURN_ORDER,
                'HILIFE_RETURN_ORDER' => EcpayURL::HILIFE_RETURN_ORDER,
                'FAMILY_RETURN_ORDER' => EcpayURL::FAMILY_RETURN_ORDER,
                'FAMILY_RETURN_CHECK' => EcpayURL::FAMILY_RETURN_CHECK,
                'UNIMART_UPDATE_LOGISTICS_INFO' => EcpayURL::UNIMART_UPDATE_LOGISTICS_INFO,
                'UNIMART_UPDATE_STORE_INFO' => EcpayURL::UNIMART_UPDATE_STORE_INFO,
                'UNIMART_CANCEL_LOGISTICS_ORDER' => EcpayURL::UNIMART_CANCEL_LOGISTICS_ORDER,
                'QUERY_LOGISTICS_INFO' => EcpayURL::QUERY_LOGISTICS_INFO,
                'PRINT_TRADE_DOC' => EcpayURL::PRINT_TRADE_DOC,
                'PRINT_UNIMART_C2C_BILL' => EcpayURL::PRINT_UNIMART_C2C_BILL,
                'PRINT_FAMILY_C2C_BILL' => EcpayURL::PRINT_FAMILY_C2C_BILL,
                'Print_HILIFE_C2C_BILL' => EcpayURL::Print_HILIFE_C2C_BILL,
                'CREATE_TEST_DATA' => EcpayURL::CREATE_TEST_DATA,
            );
        }

        return $UrlList[$FunctionType];
    }
}