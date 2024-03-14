<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Form_Payment
{
    public static function awc_display_options_card_expiration_month()
    {
        $months = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
        ];

        $select_months = "<option value='' selected>Mês</option>";

        foreach ( $months as $key => $month ) {
            $select_months .= sprintf( "<option value='%s'>%s | %s</option>", $key, $key, $month );
        }

        return $select_months;
    }

    public static function awc_display_options_card_expiration_year()
    {
        $select_years = "<option value='' selected>Ano</option>";

        for ( $index = 0; $index <= 10; $index++ ) {
            $year_index = date( 'y', strtotime( date(  'Y-m-d' ) . sprintf( '+ %s year', $index ) ) );
            $year = date( 'Y', strtotime( date(  'Y-m-d' ) . sprintf( '+ %s year', $index ) ) );
            $select_years .= sprintf( "<option value='%s'>%s</option>", $year_index, $year );
        }

        return $select_years;
    }

    public static function awc_display_installments( $settings )
    {
        $calculateInstallments = AWC_Calculate::awc_calculate_installments(
            AWC_Helper::awc_get_total_cart(),
            $settings['installments'],
            $settings['interest']
        );

        $installments = "";

        foreach ($calculateInstallments as $key => $installment) {
            $installments .= self::awc_make_installments($key, $installment, $settings['show_total_installments']);
        }

        return $installments;
    }

    public static function awc_make_installments($key, $installment, $showTotalInstallments)
    {
        $installmentAmount = $installment / $key;
        $installmentAmountFormatted = AWC_Helper::awc_monetary_format( $installmentAmount );

        $totalAmountInstallment = $installmentAmount * $key;
        $totalAmountInstallmentFormatted = AWC_Helper::awc_monetary_format( $totalAmountInstallment );

        if (true == $showTotalInstallments && $key != 1) {
            return sprintf( "<option value='%s'> %s x %s (%s com juros) </option>",
                $key, $key, $installmentAmountFormatted, $totalAmountInstallmentFormatted
            );
        }

        return sprintf( "<option value='%s'> %s x %s </option>", $key, $key, $installmentAmountFormatted );
    }

    public static function awc_display_script_payment_credit_card()
    {
        return "<script src=" . plugins_url( AWC_PLUGIN_ROOT_PATH . "/assets/js/my-scripts/awc_credit_card.min.js" ) . "></script>";
    }

    public static function awc_display_script_payment_billet()
    {
        return "<script src=" . plugins_url( AWC_PLUGIN_ROOT_PATH . "/assets/js/my-scripts/awc_billet.min.js" ) . "></script>";
    }

    public static function awc_display_script_payment_pix()
    {
        return "<script src=" . plugins_url( AWC_PLUGIN_ROOT_PATH . "/assets/js/my-scripts/awc_pix.min.js" ) . "></script>";
    }
}
