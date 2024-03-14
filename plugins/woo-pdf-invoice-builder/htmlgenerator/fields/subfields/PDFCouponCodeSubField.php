<?php
namespace rnwcinv\htmlgenerator\fields\subfields;
class PDFCouponCodeSubField extends PDFSubFieldBase {



    public function FormatValue($value,$format='')
    {
        $coupons=$value;
        $couponText='';
        if(is_array($coupons))
        {
            foreach ($coupons as $coupon)
            {
                if(strlen($couponText)>0){
                    $couponText=$couponText.',';
                }

                $couponText.=htmlspecialchars($coupon);
            }
        }else{
            if($coupons!=null)
            {
                $couponText = $coupons;
            }
        }
        return $couponText;
    }

    public function GetTestFieldValue()
    {
        return "COUPON_AX1";
    }


    public function GetWCFieldName()
    {
        return 'used_coupons';
    }
}