<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Post_Payment
{
    public static function awc_get_structure_post_billet()
    {
        return array(
            'cpf_billet' => array(
                'validation' => 'empty|cpf',
                'messages' => array(
                    AWC_Validation_Type::AWC_EMPTY => 'Necessário preencher o CPF para emissão da nota fiscal!',
                    AWC_Validation_Type::AWC_CPF   => 'CPF inválido!',
                ),
            ),
        );
    }

    public static function awc_get_structure_post_pix()
    {
        return array(
            'cpf_pix' => array(
                'validation' => 'empty|cpf',
                'messages' => array(
                    AWC_Validation_Type::AWC_EMPTY => 'Necessário preencher o CPF!',
                    AWC_Validation_Type::AWC_CPF   => 'CPF inválido!',
                ),
            ),
        );
    }

    public static function awc_get_structure_post_credit_card()
    {
        return array(
            'card_number' => array(
                'validation' => 'empty|credit-card',
                'messages' => array(
                    AWC_Validation_Type::AWC_EMPTY       => 'Necessário preencher o número do cartão!',
                    AWC_Validation_Type::AWC_CREDIT_CARD => 'Número do Cartão inválido!',
                ),
            ),
            'card_name' => array(
                'validation' => 'empty',
                'messages' => array(
                    AWC_Validation_Type::AWC_EMPTY => 'Necessário preencher o nome do titular do cartão!',
                ),
            ),
            'card_cpf' => array(
                'validation' => 'empty|cpf',
                'messages' => array(
                    AWC_Validation_Type::AWC_EMPTY => 'Necessário preencher o CPF do titular do cartão!',
                    AWC_Validation_Type::AWC_CPF   => 'CPF do titular do cartão é inválido!',
                ),
            ),
            'card_month' => array(
                'validation' => 'empty|number',
                'messages' => array(
                    AWC_Validation_Type::AWC_EMPTY  => 'Necessário preencher o mês de expiração do cartão!',
                    AWC_Validation_Type::AWC_NUMBER => 'O mês de expiração do cartão deve ser informado somente números!',
                ),
            ),
            'card_year' => array(
                'validation' => 'empty|number',
                'messages' => array(
                    AWC_Validation_Type::AWC_EMPTY  => 'Necessário preencher o ano de expiração do cartão!',
                    AWC_Validation_Type::AWC_NUMBER => 'O ano de expiração do cartão deve ser informado somente números!',
                ),
            ),
            'card_security_code' => array(
                'validation' => 'empty|number|ccv',
                'messages' => array(
                    AWC_Validation_Type::AWC_EMPTY  => 'Necessário preencher o código de segurança do cartão!',
                    AWC_Validation_Type::AWC_NUMBER => 'Deve ser informado somente números!',
                    AWC_Validation_Type::AWC_CCV    => 'O código de segurança deve ser informado com no mínimo 3 dígitos e no máximo 4 dígitos!'
                ),
            ),
        );
    }
}