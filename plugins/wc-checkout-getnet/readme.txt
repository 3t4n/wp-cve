===  Plugin Oficial - Getnet para WooCommerce ===

Contributors: coffeecodetech, ritchellydomingos, alexandresa
Tags: checkout, woocommerce, cart, getnet, santander, gateway, payments, credit card, billet
Requires at least: 5.0
Requires PHP: 7.4
Tested up to: 6.4.2
Stable tag: 1.6.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Plugin oficial da Getnet para WooCommerce Construído com as melhores práticas de desenvolvimento. Proporcionando mais agilidade, segurança e conversão de vendas.

== Description ==

Plugin oficial da Getnet para WooCommerce Construído com as melhores práticas de desenvolvimento. Proporcionando mais agilidade, segurança e conversão de vendas.

Suporte [Coffee Code](https://coffee-code.tech/#contact).

[Documentação oficial](https://coffee-code.tech/getnet-para-woocommerce).

Ainda não é cliente Getnet? [CONTRATE DIRETAMENTE AQUI](https://site.getnet.com.br/link-de-pagamento/habilitar-getpay/?parceiro=ecommerce)

*Você será redirecionado para a página da Getnet, onde deverá informar seu CPF/CNPJ e seguir com a contratação do Pacote de e-commerce Getpay Avançado diretamente por lá.*

= Requisitos =

- PHP version 8.0 ou maior.
- WooCommerce version 7.0.x ou maior.
- Brazilian Market on WooCommerce.

= Métodos de pagamento =

- Cartão de crédito.
- Boleto.
- PIX.

== Instalação ==

1. Faça upload deste plugin em seu WordPress, e ative-o;
2. Entre no menu lateral "WooCommerce > Configurações > Getnet";
3. Adicione o Seller ID, Cliente ID e Client Secret ( entre em contato com a Getnet e solicite ).
4. Adicione Título, Descrição, Título do checkout, Status do pedido e habilite o metódo, depois salve alterações.

== Frequently Asked Questions ==

= Qual é a licença do plugin? =

Este plugin esta licenciado como GPL.

= Métodos de Pagamento Aceitos =

- Cartão de crédito.
- Boleto.
- PIX.

= O status dos pagamentos não estão sendo atualizados =

Este problema ocorre quando os callbacks não estão cadastrados, ou quando são cadastrados mas não estão liberados no firewall da Getnet.

Para resolver este problema siga o passo 10 da [Documentação oficial](https://coffee-code.tech/getnet-para-woocommerce).

= Não possui Plataforma Digital da Getnet, o que fazer? =

Caso não tenha cadastro na plataforma de e-commerce ou possua apenas a maquininha, solicite o cadastro [aqui.](https://site.getnet.com.br/link-de-pagamento/habilitar-getpay/?parceiro=ecommerce)

*Você será redirecionado para a página da Getnet, onde deverá informar seu CPF/CNPJ e seguir com a contratação do Pacote de e-commerce Getpay Avançado diretamente por lá.*

== Changelog ==

= 1.6.1 – 15/01/2023 =
- Compatibilidade com HPOS do Woocommerce.

= 1.6.0 – 14/12/2023 =
- Alteração de rotas da api V1 para v2.
- Removida a opção configuração de Sandbox, permanecendo apenas a de Homologação.
- Opção de Mostrar/Ocutar o Client Secret.
- Correção de Warnning referente ao desconto no PIX.

= 1.5.8.1 – 22/09/2023 =
- Rollback versão 1.5.7

= 1.5.8 - 14/09/2023 =
- Alteração de rotas da api V1 para v2

= 1.5.7 - 31/08/2023 =
- Correção de bug de de validação de javascript no método de pagamento de cartão de crédito.
- Alteração de rota de cancelamento/estorno para v2.
- Implementação de Cancelamento/Estorno para transações feitas a mais de um dia.

= 1.5.6 - 12/07/2023 =
- Corrigido bug no filtro plugin_action_links

= 1.5.5 - 11/07/2023 =
- Configuração de tempo de expiração do QRCode do PIX.
- Validação da versão do PHP.

= 1.5.4 - 01/07/2023 =
- Reestilização da página de configurações.
- Verificação se as dependencias estão instaladas.
- Link da documentação oficial dentro da página de configurações.

= 1.5.3 - 29/05/2023 =
- Melhoria na gestão de dependencias para evitar conflitos com outros plugins.
- Correção de load de javascript dos efeitos da imagem do cartão de crédito no checkout;

= 1.5.2 - 27/02/2023 =
- Correção de erros do JavaScript no checkout.

= 1.5.1 - 13/01/2023 =
- Correção de estilização do plugin no checkout.

= 1.5.0 - 23/11/2022 =
- Reesturação e reestilização das funcionalidades de Cartão de Crédito, PIX e Boleto;
- Desconto no PIX;
- Imagem do cartão de crédito no checkout;
- Habilitar e desabilitar imagem de cartão de crédito no checkout;

= 1.3.0 - 21/11/2022 =
- Compatibilidade com php 8.1

= 1.2.5 - 21/09/2022 =
- Flag de identificação do Woocommerce conforme novo requisito da Getnet

= 1.2.4 - 10/08/2022 =
- Tradução de texto das opções de parcelas
- Validação de IPs a serem enviados no Antifraude

= 1.2.3 - 25/07/2022 =
- Primeiras funcionalidades de Cancelamento/Estorno para transações feitas no mesmo dia (D0)
- Melhorias de tratativas de erros e alteração de timeout da transação

= 1.2.2 - 25/07/2022 =
- Correção de compatibilidade com php 7.1

= 1.2.1 - 08/07/2022 =
- Correção de responsividade na página de agradecimento
- Melhoria na documentação para a realização do primeiro cadastro junto a Getnet

= 1.2.0 - 27/05/2022 =
- Antifraude para pagamentos no cartão de crédito.

= 1.1.7 - 18/04/2022 =
- Registro das requests nos logs

= 1.1.6 - 07/04/2022 =
- Correção quando o soft_descriptor é vazio.
- Captura de logs do retorno do webhook.

= 1.1.5 - 25/03/2022 =
- Adicionado soft_descriptor quer permite editar o texto exibido na fatura do cartão do comprador.

= 1.1.4 - 19/02/2022 =
- Adicionado ambiente de homolog;
- Mascarado o campo "Client Secret", protegendo a privacidade das senhas dos ambientes.
- Alterado o nome da função get_fields para gn_get_fields, obrigado @wiliamjk e @hyanncoelho pela contribuição.

= 1.1.3 - 30/12/2021 =
- Validação se woocommerce está ativo.

= 1.1.2 - 02/12/2021 =
- Correção de funcionalidade copia/cola código PIX.
- Implementação das notificações 1.0 de callback.
- Implementação de log para o cartão de crédito.

= 1.1.1 - 19/11/2021 =
- Correção do tipo de pessoa física e/ou jurídica.
- Correção na quantidade de parcelas.

= 1.1.0 - 19/11/2021 =
- Implementação de pagamento via PIX.
- Implementação de parcelamento com ou sem juros na compra com cartão de crédito.
- Implementação de valor mínimo para parcelar uma compra com cartão de crédito.

= 1.0.2 - 18/10/2021 =
- Implementação de aviso caso não tenha os plugins Brazilian Market on WooCommerce e/ou WooCommerce.
- Adicionando desconto no boleto.

= 1.0.1 - 09/10/2021 =
- Implementação de aviso caso não tenha as credenciais configuradas no admin.
- Correção de bug relacionada ao número de parcelas no cartão de crédito.

= 1.0.0 - 05/10/2021 =
- Release inicial

== Screenshots ==
1. Configuração dos ambientes (Sandbox, Homologação e Produção)
2. Métodos de pagamentos disponíveis para habilitar
3. Configurações do Método de pagamento Cartão de crédito
4. Configurações do Método de pagamento Boleto
5. Configurações do Método de pagamento PIX
6. Checkout com opção de imagem de cartão de crédito habilitada
