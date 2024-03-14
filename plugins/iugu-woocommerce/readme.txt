=== WooCommerce iugu ===
Contributors: iugu, claudiosanches, braising, andsnleo, eduardoiugu
Tags: woocommerce, iugu, payment
Requires at least: 3.9
Tested up to: 4.9
Stable tag: 3.1.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Receba pagamentos por cartão de crédito, boleto bancário e pix na sua loja WooCommerce com a iugu.

== Description ==

A iugu disponibiliza toda a infraestrutura necessária para que você possa transacionar pagamentos online com menos burocracia e mais vantagens. Com a nossa plataforma, você pode oferecer pagamentos com checkout transparente com cartão de crédito, pix e boleto bancário. Para mais informações sobre o funcionamento da iugu, [leia a documentação](https://docs.iugu.com).

= Compatibilidade =

O **WooCommerce iugu** é compatível com:

* [WooCommerce 5.1+](https://wordpress.org/plugins/woocommerce/)
* [WooCommerce Subscriptions 3.1.4+](http://www.woothemes.com/products/woocommerce-subscriptions/): para pagamentos recorrentes/assinaturas.
* [Brazilian Market on WooCommerce](https://br.wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/): permite enviar dados do cliente como **CPF** ou **CNPJ**, além dos campos **número** e **bairro** do endereço.

= Requerimentos =

* [Wordpress v5.6 ou superior](https://wordpress.org).
* [WooCommerce v5.1 ou superior](https://br.wordpress.org/plugins/woocommerce/).
* [Brazilian Market on WooCommerce](https://br.wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/).
* Conta ativa na [iugu](https://iugu.com/) com boleto bancário e/ou cartão de crédito habilitados como métodos de pagamento. Entenda [*O que é necessário para começar a usar iugu?*](https://support.iugu.com/hc/pt-br/articles/201531709).


== Installation ==

= 1. Instale o plugin =
Envie os arquivos do plugin para a pasta `wp-content/plugins` ou instale-o usando o instalador de plugins do WordPress. Em seguida, ative o **WooCommerce iugu**.

= 2. Obtenha o ID da sua conta iugu e um API token =
No painel da iugu, acesse o menu [*Configurações > Informações da conta*](https://alia.iugu.com/) e crie um *API token* do tipo LIVE [*Configurações > Integração via API*]. Ele será usado, junto com o ID da sua conta iugu, para configurar o plugin.

Você também pode criar um API token do tipo TEST para realizar testes com o plugin.

= 3. Configure o WooCommerce =
No WordPress, acesse o menu *WooCommerce > Configurações > Produtos > Inventário* e deixe em branco a opção **Manter estoque (minutos)**.

Essa funcionalidade, introduzida na versão 2.0 do Woocommerce, permite cancelar a compra e liberar o estoque depois de alguns minutos, mas não funciona muito bem com pagamentos por boleto bancário, pois estes podem levar até 48 horas para serem validados.

[Configurações do Plugin WooCommerce iugu 3.1+] (https://dev.iugu.com/docs/configura%C3%A7%C3%A3o-plugin-woocommerce-iugu-30)

[Funcionalidades do plugin WooCommerce iugu 3.1+] (https://dev.iugu.com/docs/funcionalidades-plugin-woocommerce-iugu-30)


= 4. Ative os pagamentos pela iugu =

Ainda no WordPress, acesse o menu *WooCommerce > Configurações > Finalizar compra* e selecione **iugu - Cartão de crédito** ou **iugu - Boleto bancário**. Marque a caixa de seleção para ativar o(s) método(s) de pagamento que lhe interessa(m) e preencha as opções de **ID da conta** e **API Token** para cada um deles.


= Modo de testes =

É possível trabalhar com o plugin **WooCommerce iugu** em Sandbox (modo de testes). Dessa forma, você pode testar os processos de pagamentos por boleto e cartão antes de realizá-los em produção.

1. Acesse o painel da iugu e, no menu [*Configurações> Informações da conta*](https://alia.iugu.com/), crie um API token do tipo TEST.
2. Agora no WordPress, acesse as configurações de cartão de crédito ou boleto bancário do WooCommerce iugu (*WooCommerce > Configurações > Finalizar compra*) e adicione o seu API token (TEST).
3. Ao fim da página, marque a caixa de seleção *Ativar o sandbox da iugu*.

Opcional: Marque também a opção *Habilitar log* para ver o registro dos seus pagamentos no menu *WooCommerce > Status > Logs*.


== Frequently Asked Questions ==

= Qual é a licença do plugin? =

[GNU GPL (General Public Licence) v2](http://www.gnu.org/licenses/gpl-2.0.html).

= Do que preciso para utilizar o plugin? =

Ver [Requerimentos](https://github.com/iugu/iugu-woocommerce/wiki/Requerimentos).

= Quais são as tarifas da iugu? =

Conheça todas as tarifas da iugu em [iugu.com/precos](https://iugu.com/precos/).

= É possível utilizar a opção de pagamento recorrente/assinaturas? =

Sim, é possível utilizar este plugin para fazer pagamentos recorrentes com o [WooCommerce Subscriptions](https://www.woothemes.com/products/woocommerce-subscriptions/), que permite um maior controle sobre assinaturas dentro da sua loja WooCommerce.

= O pedido foi pago e ficou com o status *processando*, e não *concluído*. Isso está certo? =

Sim. Todo gateway de pagamento no WooCommerce deve mudar o status do pedido para *processando* no momento em que o pagamento é confirmado. O status só deve ser alterado para *concluído* após o pedido ter sido entregue.

Para produtos digitais, por padrão, o WooCommerce só permite o acesso do comprador quando o pedido tem o status *concluído*. No entanto, nas configurações do WooCommerce, na aba *Produtos*, é possível ativar a opção **Conceder acesso para download do produto após o pagamento**, liberando o download no status *processando*.


== Changelog ==

= 3.1.10 = 
* Melhoria: Adicionado forma de pagamento carnê

= 3.1.9 = 
* Melhoria: Adicionado integração com o plugin "WooCommerce Gift Cards", para envio dos cupons feitos por ele na fatura da IUGU

= 3.1.8 = 
* Correção: Removido alteração de status do pedido no invoice.released

= 3.1.7 = 
* Correção: Marcava erroneamente a fatura como paga, quando a mesma era cancelada ou expirada

= 3.1.6 = 
* Melhoria: Adicionado recepção de gatilho para Considerar Paga Externamente
* Correção: Split de pagamento não funciona com produto ou assinatura variavel
* Correção: Não demonstra cartões salvos de outros meios de pagamento em minha conta

= 3.1.5 = 
* Correção: Ao salvar o cartão em minha conta, verificar se o plugin que está selecionado é o IUGU.

= 3.1.4 = 
* Correção: Forçar salvar o cartão no carrinho, em uma renovação de assinatura.

= 3.1.3 = 
* Correção: Não enviar s/n caso não tenha endereço informado

= 3.1.2 = 
* Melhoria: Adicionado configuração no cartão de crédito para valores padrão para salvar cartão e usar cartão como padrão
* Melhoria: Adicionado ao cancelar pedido pelo admin, cancelar a fatura na IUGU se a mesma ainda não paga

= 3.1.1 = 
* Melhoria: Adicionado configuração para parcelamento de cartão Geral ou por Produto
* Melhoria: Adicionado anotação no produto com o LR de resposta do cartão de crédito
* Melhoria: Adicionado mensagem explicativa "Split por parcela apenas permitido para o método cartão de crédito", no Split do produto
* Correção: Ao cadastrar um produto, não mostrava a quantidade de parcelas sem alterar os pagamentos disponíveis.

= 3.0.0.13 = 
* Correção: Gerava um erro de session quando o usuário era excluido pelo admin

= 3.0.0.12 = 
* Correção: Quando PIX ou Boleto sáo pagos, o plugin recebe duas vezes a informação de pagamento. Alterado para ignorar a segunda recepção

= 3.0.0.11 = 
* Correção: Quando a transação do cartão for negada, alterar o status para malsucedido direto.

= 3.0.0.10 = 
* Correção: Envio de Bairro na criação do Customer

= 3.0.0.9 = 
* Melhoria: Adicionado parâmetro para configurar a partir de qual data deve ser verificado se os Customers IDs são validos. Usado quando o "ID da conta na IUGU" precisa ser alterado.

= 3.0.0.8 = 
* Melhoria: Alterado para quando tem apenas uma parcela no cartão não demostrar o combo com a seleção de parcela

= 3.0.0.7 = 
* Melhoria: Incluído a possibilidade de na assinatura alterar a forma de pagamento para a proxima recorrencia

= 3.0.0.6 = 
* Melhoria: Unificado as configurações comuns da API, na aba IUGU das configurações
* Melhoria: Ao salvar a aba IUGU nas configurações, agora é verificado se é preciso recriar o webhook
* Melhoria: Incluído configuração para qual status deve ser modificado o pedido para pagamento é pendente
* Melhoria: Incluído configuração para qual status deve ser modificado o pedido para processamento
* Melhoria: Incluído para utlizar o celular no cadastro do cliente caso o telefone não esteja informado

= 3.0.0.5 = 
* Melhoria: Alterado para permitir postegar o ciclo de assinatura no woocommerce subscriptions
* Melhoria: Alterado para enviar s/n caso o número do endereço no cadastro do cliente não esteja preenchido

= 3.0.0.4 = 
* Melhoria: Adicionado compatibilidade com o plugin "HandMade WooCommerce Order Status Control"

= 3.0.0.3 = 
* Melhoria: Adicionado mensagem ao clicar no botão de cópia do PIX

= 3.0.0.2 = 
* Melhoria: Criação de configuração para envio de split na fatura

= 3.0.0.1 = 
* Correção: Não obrigar salvar o cartão se não tiver assinatura no carrinho
* Correção: Correção para gerar o boleto com vencimento futuro

= 3.0.0.0 = 
* Correção: Correções de bugs

= 2.2.1 = 
* Correção: Correção na demostração de formas de pagamento do Pluggin da IUGU, para produtos já cadastrados no Woocommerce antes da ativação do Plugin da IUGU

= 2.1.5 =
* Correção: Correção no parcelamento, que estava sendo gravado errado em certos cenários

= 2.1.4 =
* Correção: Correções na integração com WooCommerce Subscriptions com cartão de crédito

= 2.1.3 =
* Correção: Correções na integração com WooCommerce Subscriptions
* Melhoria: Suporte a salvamento e reutilização de cartões de crédito
* Melhoria: Suporte a reembolso de pagamentos feitos com cartão de crédito
* Melhoria: Campo de parcelamento agora precisa ser selecionado a cada recarga de página
* Melhoria: Criação de parâmetro para ligar/desligar envio de emails de cobrança pela Iugu
* Correção: Valor de menor parcela agora pode ser maior do que o do pedido
* Correção: Correção no tratamento de erros no acesso à API Iugu

= 2.0.2 =
* Correção: Algumas traduções para o português não funcionavam.

= 2.0.1 =
* Adição: Tradução do plugin para o português.

= 2.0.0 =
* Melhoria: Removidas as funções deprecadas do WooCommerce.
* Correção: Função responsável por identificar se o cliente é uma empresa não funcionava apropriadamente.

Veja o [changelog completo no Github](https://github.com/iugu/iugu_payment-gateway-woocommerce/wiki).


== Upgrade Notice ==

= 2.0.2 =
Alguns usuários reportaram que tiveram problemas com algumas das traduções para o português liberadas na v2.0.1. Tentamos dar um jeito nisso nesta versão. Se, ainda assim, você não ver as   traduções direitinho, é só mandar abrir um chamado no link https://support.iugu.com/hc/pt-br/requests/new .


== Suporte ==

= Canais =
* [Issues no Github](https://github.com/iugu/iugu_payment-gateway-woocommerce/issues)
* Atendimento da iugu:
* Chamados: Necessário enviar solicitação no link https://support.iugu.com/hc/pt-br/requests/new

= Compartilhando os logs =
1. Na administração do plugin do WooCommerce, acesse as configurações da iugu, ative o **Log de depuração** e tente realizar o pagamento novamente. Caso o log já esteja ativado, procure o número do pedido feito pelo comprador.
2. Copie o log referente ao número do pedido no menu *WooCommerce > Status > Logs*.
3. Crie um [pastebin](http://pastebin.com) ou um [gist](http://gist.github.com) e salve o log para gerar um link público de compartilhamento.

= Outras dúvidas =
Para dúvidas específicas sobre a iugu, acesse nossa [base de conhecimento](https://support.iugu.com).

== Colabore ==

Você pode contribuir para o desenvolvimento do plug-in fazendo o fork do repositório no [GitHub](https://github.com/iugu/iugu_payment-gateway-woocommerce).