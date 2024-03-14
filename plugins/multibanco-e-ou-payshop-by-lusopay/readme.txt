=== Multibanco / MB Way / Transfer&ecirc;ncia Simplificada / Payshop / Cofidis Pay (by LUSOPAY) for WooCommerce ===
Contributors: lusopay
Tags: lusopay, multibanco, mb, mbway, e-commerce, ecommerce, woocommerce, payment, mb way, payshop, gateway, referencias, luso, pay, cofidis, cofidispay
Requires at least: 4.4
Tested up to: 6.4.2
Stable tag: 4.0.5
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce plugin for MULTIBANCO, PAYSHOP, MB Way and Simplified Transfer payments. It allows to send SMS and e-mail to the customer with payment details. 

== Description ==

English below

(PORTUGU&Ecirc;S)
Plugin que permite receber pagamentos por refer&ecirc;ncias Multibanco, Payshop, MB Way e Transfer&ecirc;ncia Simplificada. Permite tamb&eacute;m enviar por SMS as refer&ecirc;ncias multibanco e payshop (seja usando o plugin WooCommerce - APG SMS Notifications ou o SMS Orders Alert/Notifications for WooCommerce). As refer&ecirc;ncias multibanco podem ser pagas num ATM ou via homebanking. No caso das refer&ecirc;ncias Payshop, nos repectivos agentes. O MB Way &eacute; pago por smartphone, usando a aplica&ccedil;&atilde;o MB Way ou a aplica&ccedil;&atilde;o do seu Banco. As Transfer&ecirc;ncias Simplificadas s&atilde;o transfer&ecirc;ncias banc&aacute;rias sem necessidade de inserir nome do destinat&aacute;rio, valor, descritivo ou IBAN do destinat&aacute;rio do pagamento. Acresce que estas transfer&ecirc;ncias banc&aacute;rias t&ecirc;m um identificador &uacute;nico. As Transfer&ecirc;ncias Simplificadas permitem receber pagamentos de clientes de toda a Uni&atilde;o Europeia. Este plugin permite, de forma autom&aacute;tica e em tempo real, alterar os estados das encomendas para pagas no preciso momento em que o cliente paga a refer&ecirc;ncia multibanco ou payshop ou MB Way, bem como permite actualizar automaticamente o stock dos produtos. Desta forma, com este plugin, para al&eacute;m de permitir enviar os produtos de forma mais c&eacute;lere, reduz o trabalho administrativo que tem para gerir o seu neg&oacute;cio, ao mesmo tempo que permite aos seus clientes pagar de uma forma segura, confort&aacute;vel e com a qual est&atilde;o familiarizados. Mais acrescentamos o nosso plugin usa a funcionalidade HPOS(High Performance Order Storage).  

(ENGLISH)
Plugin that allows you to receive payments by Multibanco (ATM) references, Payshop, MB Way and Simplified Transfers. It also allows to send SMS messages with multibanco and payshop references (using WooCommerce &#45; APG SMS Notifications plugin or SMS Orders Alert/Notifications for WooCommerce).
Multibanco references can be paid at ATM network or at customer&#39;s homebanking.
In the case of Payshop references, they can be paid at Payshop&#39;s agents.
MB Way is paid using MB Way smartphone app or the customer&#39;s bank app.
Simplified Transfers are bank transfers without the need to enter the recipient's name, amount, description or IBAN of the payment recipient. Furthermore, these bank transfers have an unique identifier. Simplified Transfers allows you to receive payments from customers across the European Union.
This plugin allows, automatically and in real time, to change the status of orders paid at the right moment of the payment made by the customer. At the same time it changes the stock of products. This way, with this plugin, you can send your orders faster than usual, with less administrative work to manage your business and, at the same time, allow your customers to pay in a safe and comfortably way. We added that our plugin used the feature HPOS(High Performance Order Storage).

== Installation ==

(PORTUGU&Ecirc;S)
1. Vai a "Plugins" - > "Adicionar Novo" e procura por Lusopay.
2. Instale e ative o plugin.
3. Vai a "Woocommerce"->"Configura&ccedil;&otilde;es" escolhe a aba "Integra&ccedil;&atilde;o" clica na hiperliga&ccedil;&atilde;o que diz "LUSOPAY" para colocar a clientGuid e o nif fornecidos pela LUSOPAY.
4. Vai a "Woocommerce"->"Configura&ccedil;&otilde;es" escolhe a aba "Pagamentos" clica na hiperliga&ccedil;&atilde;o "Multibanco (By LUSOPAY)" ou / e "Payshop (by LUSOPAY)" ou / e "MB Way (By LUSOPAY)" ou / e "Transfer&ecirc;ncia Simplificada".
5. Tenha a certeza que envia o email a pedir a activa&ccedil;&atilde;o do sistema callback para geral@lusopay.com (Instru&ccedil;&otilde;es na p&aacute;gina de configura&ccedil;&otilde;es do plugin) para que possamos ativar o mesmo e para o servi&ccedil;o MB Way o IP da sua loja.


(ENGLISH)
1. Go to "Plugins" - > "Add New" and search by Lusopay.
2. Install and activate the plugin through the 'Plugins' menu in WordPress.
3. Go to "Woocommerce" -> "Settings" tab and choose the "Integration" and enter the clientGuid and the vatnumber provided by LUSOPAY.
4. Go to "Woocommerce" -> "Settings" tab and choose the "Payments". Click the link "Multibanco (By LUSOPAY)" e / ou "Payshop (By LUSOPAY)" e / ou "MB Way (By LUSOPAY)" ou / e "Transfer&ecirc;ncia Simplificada".
5. Make sure that you send an email with url callback to geral@lusopay.com to allow us to activate callback system. And the IP address of your store to allow MB Way service to work.


== Frequently Asked Questions ==

(PORTUGU&Ecirc;S)

= Como &eacute; que obtenho a chave de activa&ccedil;&atilde;o? =

Tem que ir a https://www.lusopay.com e registar-se como cliente (clicando o bot&atilde;o ADERIR) e enviar um email a pedir o servi&ccedil;o que quer para depois enviarmos a chave.

= O que &eacute; o sistema callback? =

O sistema callback &eacute; um tipo de notifica&ccedil;&atilde;o dos pagamentos atrav&eacute;s de um POST. Quando o cliente pagar uma encomenda atrav&eacute;s das refer&ecirc;ncias Multibanco ou Payshop ou atrav&eacute;s da LUSOPAY Wallet, a loja vai automaticamente mudar o estado da encomenda para 

"Confirmado pagamento" e enviar um email dessa confirma&ccedil;&atilde;o. Com isso o dono da loja n&atilde;o vai ter que estar sempre a verificar a caixa de emails. Ainda especifica o m&eacute;todo de pagamento usado.

= Porque &eacute; que o callback n&atilde;o funciona? =

Se j&aacute; comunicou para n&oacute;s a informar para activar o callback, talvez tenha que ir a "Op&ccedil;&otilde;es" escolher "Liga&ccedil;&otilde;es permanentes" e mudar a op&ccedil;&otilde;es para predifini&ccedil;&atilde;o.
 
= Como incluir as instru&ccedil;&otilde;es de pagamento no envio de SMS usando o plugin &ldquo;WooCommerce - APG SMS Notifications&rdquo;? =

Aceda a WooCommerce &gt; SMS Notifications e adicione o texto `%lusopay_gateway_sms%` ao campo &ldquo;Order received custom message&rdquo;.

(ENGLISH)

= How do I get the key? =

You must go to https://www.lusopay.com, register and send an email to geral@lusopay.com in order to obtain the activation key.

= What is callback? =

Callback is a payment notification type through a simple POST, when a client pays an order by Multibanco (ATM) or Payshop references or by LUSOPAY Wallet the online store updates automatically the order state to "Confirmado pagamento" (that means Payment Confirmed) and sends an email informing this status change. Also the store owner doesn't need to check his email boxes to see if the client paid. It also specifies the used payment method.

= Why callback doesn't work? =

If you already sent the email to tell us to activate callback system, probably you will need to go to menu "Settings" and choose "Permalink" to change it to "default" option and save.

= How to include the Multibanco payment instructions on the SMS sent by &ldquo;WooCommerce - APG SMS Notifications&rdquo;? =

Go to WooCommerce &gt; SMS Notifications and add the `%lusopay_gateway_sms%` variable to &ldquo;Order received custom message&rdquo;.



== Changelog ==

(PORTUGU&Ecirc;S)

= 4.0.5 =

- Corrigimos um erro no m&eacute;todo de pagamento MB Way(tratamento da resposta).
- Corrigimos um erro na informa&ccedil;&atilde;o do IP mostrado nas instru&ccedil;&otilde;es do m&eacute;todo MB Way.

= 4.0.4 =

- Alteramos a fun&ccedil;&atilde;o lusopaygateway_lang_fix_wpml_ajax referente ao WPML para ser compat&iacute;vel com PHP 8.2. 
- Alteramos a forma como referenciamos os filtros.

= 4.0.3 =

- Remo&ccedil;&atilde;o de um echo (imprime o valor para a tela).

= 4.0.2 =

- Compatibilidade com a vers;&atilde;o 7 ou menor do woocommerce.

= 4.0.1 =

- Corre&ccedil;&atilde;o de um erro.

= 4.0.0 =

- Inser&ccedil;&atilde;o de um novo m&eacute;todo de pagamento "CofidisPay".
- Compatibilidade com a funcionalidade HPOS(High Performance Order Storage).

= 3.0.0 =

- Inser&ccedil;&atilde;o de um novo m&eacute;todo de pagamento "Transfer&ecirc;ncia Simplificada".
- A possibilidade de enviar um email com os urls de callback automaticamente.
- A possibilidade de usar uma nova entidade de refer&ecirc;ncias Multibanco.

= 2.0.7 =

- Corre&ccedil;&atilde;o de um problema na op&ccedil;&atilde;o "Apenas para encomendas abaixo de" que n&atilde;o funcionava.

= 2.0.6 =

- Inser&ccedil;&atilde;o de uma nota publicit&aacute;ria.

= 2.0.5 =

- Correc&ccedil;&atilde;o de bugs no m&eacute;todo MB Way.
- Correc&ccedil;&atilde;o de bugs na apresenta&ccedil;&atilde;o das tabelas Multibanco, Payshop e MB Way.
- Prevenimos erros que podem acontecer na inser&ccedil;&atilde;o do numero de telem&oacute;vel.

= 2.0.4 =

- Correc&ccedil;&atilde;o de um bug no servi&ccedil;o MB Way.
- Editamos das tabelas e tamanho das imagens.

= 2.0.3 =

- Correc&ccedil;&atilde;o de um bug.

= 2.0.2 =

- Correc&ccedil;&atilde;o de um bug.

= 2.0.1 =

- Correc&ccedil;&atilde;o de um problema.

= 2.0.0 =

- Se tiver o servi&ccedil;o Payshop, depois de fazer a actuliza&ccedil;&atilde;o ter&aacute; de re-activar e seguir as instru&ccedil;&otilde;es que est&atilde;o na p&aacute;gina de configura&ccedil;&otilde;es de cada m&eacute;todo 
- Correc&ccedil;&atilde;o do problema quando se clicava no bot&atilde;o "Activar" quando o plugin era instalado.
- Reestrutura&ccedil;&atilde;o de todo o plugin para permitir os tr&ecirc;s servi&ccedil;os MB, Payshop e MB Way em separado.
- Implementa&ccedil;&atilde;o do servi&ccedil;os MB Way.

= 1.3.7 =

- Correc&ccedil;&atilde;o no envio do email quando muda para o estado em processamento nos artigos virtuais.

= 1.3.6 =

- Correc&ccedil;&atilde;o de um bug.

= 1.3.5 =

- Correc&ccedil;&atilde;o de um bug.

= 1.3.4 =

- Adicionado as tradu&ccedil;&otilde;es para Portugu&ecirc;s e Ingl&ecirc;s.
- Adicionado a compatibilidade com o plugin &quot;APG SMS Notifications&quot;.
- Reformula&ccedil;&atilde;o geral do plugin

= 1.3.3 =

- Retifica&ccedil;&atilde;o de uma frase nas configura&ccedil;&otilde;es.

= 1.3.2 =

- Coloca&ccedil;&atilde;o de novos logos MB e Payshop.

= 1.3.1 =

- Correc&ccedil;&atilde;o de um erro no sistema callback.

= 1.3.0 =

- Adicionado o novo m&eacute;todo de pagamento LUSOPAY Wallet.

= 1.2.3 =

- Correc&ccedil;&atilde;o de um erro do formato do valor na fun&ccedil;&atilde;o callback.

= 1.2.2 =

- Correc&ccedil;&atilde;o de um erro na fun&ccedil;&atilde;o callback.

= 1.2.1 =

- A imagem no checkout n&atilde;o aparecia.

= 1.2 =

- Implementa&ccedil;&atilde;o do sistema callback (tipo notifica&ccedil;&atilde;o de pagamento).
- O estado muda autom&aacute;ticamente ap&oacute;s um pagamento.
- Reduz o stock automaticamente quando recebe um pagamento. (&Eacute; necess&aacute;rio ter o callback activo)
- &Eacute; poss&iacute;vel definir um valor m&iacute;nimo para que apare&ccedil;a o m&eacute;todo de pagamento. (opcional)
- E definir um limite para o qual o m&eacute;todo de pagamento apare&ccedil;a.

= 1.1.0 =

- Publica&ccedil;&atilde;o do plugin

(ENGLISH)

= 4.0.5 =

- Fixed the response of the MB Way payment gateway.
- Fixed the IP information on MB Way payment gateway instrutions.

= 4.0.4 =

- Edited the lusopaygateway_lang_fix_wpml_ajax function about WPML to be compatible with PHP 8.2 and changed the way how to references the filters. 

= 4.0.3 =

- Removed commmand echo (show the value on layout).

= 4.0.2 =

- Compatibility with woocommerce version 7 or under.

= 4.0.1 =

- Fix a bug.

= 4.0.0 =

- Added an another payment method "CofidisPay".
- Compatibility with feature HPOS(High Performance Order Storage).

= 3.0.0 =

- Added an another payment method "Simplified Transfer".
- Possibility to send automatically the callback urls.
- Added a possibility to use another entity.

= 2.0.7 =

- Fix a bug in "Only for orders below" option.

= 2.0.6 =

- Display a marketing note.

= 2.0.5 =

- Fix some bugs in MB Way.
- Fix somes bug in layout.
- We prevent errors that can happen when entering the mobile phone number in MB Way.

= 2.0.4 =

- Fix service MB Way bug.
- Edit tables layouts and images size.

= 2.0.3 =

- Fix some bug.

= 2.0.2 =

- Fix some bug.

= 2.0.1 =

 - Fix some important bug.

= 2.0.0 =

- If you have Payshop service, we ask you to activate it again and send us the new callback url.
- Fix invalid plugin header.
- Restructured of all plugin to allow use separate services like Multibanco and / or Payshop and / or MB Way.
- Implemention of the MB Way service.

= 1.3.7 =

- Correction in sending the email when it changes to the state in processing in the virtual products.

= 1.3.6 =

- Fix a bug.

= 1.3.5 =

- Fix a SMS bug.

= 1.3.4 =

- Added translations for Portuguese and English.
- Added compatibility with "APG SMS Notifications" Plugin.
- General redesign of the plugin

= 1.3.3 =

- Fix description in configuration menu.

= 1.3.2 =

- Change MB and Payshop icons.

= 1.3.1 =

- Fix some error on callback system.

= 1.3.0 =

- Added the new payment method LUSOPAY Wallet.

= 1.2.3 =

- Fix value format on callback function. 

= 1.2.2 =

- Fix callback function.

= 1.2.1 =

- Fix image in Checkout.

= 1.2 =

- Implementation of the callback system.
- Status of order change automatically when the store receives the payment.
- Reduce stock automatically when receives the payment. (Must have that callback system activated)
- It's possible to specify the minimum amount of the order to show the payment method. (optional)
- And limit the maximum amount of the order to show the payment method. (optional)

= 1.1.0 =

- Plugin released

== Upgrade Notice ==

(PORTUGU&Ecirc;S)

= 4.0.5 =

- Fixed the response of the MB Way payment gateway.
- Fixed the IP information on MB Way payment gateway instrutions.

(ENGLISH)

- Fixed the response of the MB Way payment gateway.
- Fixed the IP information on MB Way payment gateway instrutions.