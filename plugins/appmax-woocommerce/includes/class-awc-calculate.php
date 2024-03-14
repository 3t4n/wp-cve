<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Calculate
{
    public static function awc_calculate_installments( $totalValue, $maxInstallments = 12, $interest = 0 )
    {
        if ( $maxInstallments < 1 OR $maxInstallments > 12 ) {
            throw new \Exception('Invalid installments, min 1, max 12');
        }

        $installments = [];

        $interest = $interest > 0 ? $interest / 100 : 0;

        if ( $interest > 0 ) {
            foreach ( range( 1, $maxInstallments ) as $installment ) {
                if ( $installment == 1 ) {
                    $installmentValue = $totalValue;
                } else {
                    $installmentValue = ( $totalValue * $interest / ( 1 - pow( 1 + $interest, -$installment ) ) * $installment );
                }

                $installments[$installment] = (float) number_format( $installmentValue, 2, ".", "" );
            }

            return $installments;
        }
    }

    public static function awc_calculate_total_interest( $total, $installments = 12, $interest = 0)
    {
        $totalInterest = AWC_Calculate::awc_calculate_installments($total, $installments, $interest);
        return $totalInterest[$installments] - $total;
    }


}
