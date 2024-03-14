<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Errors_Api
{
    const AWC_MESSAGE_001 = "Solicite a liberação de  IP.";
    const AWC_MESSAGE_002 = "Erro interno. Para mais informações entre em contato.";
    const AWC_MESSAGE_003 = "Há um problema de conexão com o gateway de pagamento. Desculpe pela inconveniência.";
    const AWC_MESSAGE_004 = "Chave de API inválida. Contate o gateway de pagamento.";
    const AWC_MESSAGE_005 = "Houve um erro ao processar seu pagamento. Verifique mais detalhes nas informações do seu pedido.";

    const AWC_MESSAGE_ERROR_CUSTOMER = "Erro no processamento de informações do cliente.";
    const AWC_MESSAGE_ERROR_ORDER = "Erro no processamento de informações do pedido.";
    const AWC_MESSAGE_ERROR_PAYMENT = "Erro no processamento de informações do pagamento.";
    const AWC_MESSAGE_ERROR_TRACKING = "Erro no processamento de informações do tracking.";

    const AWC_INVALID_ACCESS_TOKEN = "Invalid Access Token";
    const AWC_VALIDATE_REQUEST = "The given data failed to pass validation.";
}