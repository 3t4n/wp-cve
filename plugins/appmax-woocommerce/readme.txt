=== Appmax WooCommerce ===
Contributors: appmaxplataforma
Tags: woocommerce, appmax, payment
Requires at least: 4.0
Tested up to: 5.1
Stable tag: 2.0.46
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

= Compatibilidade =

Compatível desde a versão 2.2.x do WooCommerce.

= Instalação =

Confira o nosso guia de instalação e configuração do plugin na aba [Installation](http://wordpress.org/plugins/appmax-woocommerce/installation/).

= Dúvidas? =

Você pode esclarecer suas dúvidas usando:

* Criando um tópico no [fórum de ajuda do WordPress](http://wordpress.org/support/plugin/appmax-woocommerce).

== Installation ==

= Instalação do plugin: =

Antes de instalarmos o plugin, sua loja precisa estar com o [WooCommerce](http://wordpress.org/plugins/woocommerce/) instalado e habilitado.

Certifique-se também de que o PHP instalado em seu servidor tenha instalada e habilitada a extensão **php-calendar**.

Caso a sua loja WooCommerce possua campos personalizados no checkout, orientamos sempre seguir a convenção do nome no campo ser em inglês e separado pelo character "_". Veja a tabela a seguir.

| Campo | Nome Padrão |
| :---: | :---: |
| Bairro | _billing_neighborhood |
| CPF | _billing_cpf |
| Número | _billing_number |

Após verificar os pré-requisitos, vá para a aba "Plugins" > "Adicionar novo" e pesquise pelo nome **Appmax-Woocommerce**.

= Configuração do plugin: =

Uma vez instalado o plugin, vá para a aba "WooCommerce" > "Configurações". E em seguida, clique na tab "Pagamentos".

Na última linha vai aparecer os métodos de pagamento, são eles:

* **Appmax - Cartão de Crédito**
* **Appmax - Boleto Bancário**
* **Appmax - Pix**

Habilite ambos e em seguida vamos configurar cada método de pagamento.

= Configurando o Appmax - Cartão de Crédito: =

Após de clicar em "Gerenciar", deixe sempre checado a opção "Ativar Appmax - Cartão de Crédito".

Mantenha sempre o padrão de valores nos campos "Título" e "Descrição".

No campo **Appmax API Key**, cole o token gerado na plataforma da Appmax.

No campo **Número de parcelas**, selecione a quantidade de parcelas.

No campo **Exibir total na parcela**, selecione a opção "sim" caso queira que seja exibido o total da parcela ou selecione a opção "não" para não exibir o total na parcela.

No campo **Juros de cartão de crédito**, informe os juros de cartão de crédito. Exemplo: 1.5

No campo **Receber Pedidos de CallCenter** de **Cartão de Crédito**, selecione a opção "Quando estiver integrado" para receber os pedidos de CallCenter da plataforma Appmax quando os mesmos estiverem com status "Integrado" ou selecione a opção "Quando estiver pago" para receber os pedidos de CallCenter da paltaforma Appmax quando os mesmos estiverem com status "Aprovado".

No campo **Status dos pedidos em análise antifraude**, selecione a opção "Em processamento" para atualizar o status dos seus pedidos para "Em processamento" ou selecione "Aguardando" para atualizar o status dos seus pedidos para "Aguardando confirmação de pagamento". Isso uma vez que, o status do pedido na plataforma Appmax esteja em 'Análise Antifraude'.

No campo **Criar pedido na loja com status**, selecione a opção "Em processamento" para que seus pedidos sejam criados com o status 'Em processamento' ou selecione a opção "Pagamento pendente" para que seus pedidos seja criados com o status 'Pagamento pendente'.

Para uma vizualização melhor de layout, deixe checado a opção "Habilitar Checkout Appmax".

> **Atenção**: Deixe habilitado a opção "Habilitar log". Estando essa opção habilitado, podemos ver os logs de transações de Cartão de Crédito.

=  Configurando o Appmax - Boleto Bancário: =

Após de clicar em "Gerenciar", deixe sempre checado a opção "Ativar Appmax - Boleto Bancário".

Mantenha sempre o padrão de valores nos campos "Título" e "Descrição".

No campo **Appmax API Key**, cole o token gerado na plataforma da Appmax.

No campo **Dias de Vencimento**, informe o número de dias de vencimento dos boletos. Por padrão o número de dias é 3.

No campo **Receber Pedidos de CallCenter** de **Boleto**, selecione a opção "Quando estiver integrado" para receber os pedidos de CallCenter da plataforma Appmax quando os mesmos estiverem com status "Integrado" ou selecione a opção "Quando estiver pago" para receber os pedidos de CallCenter da paltaforma Appmax quando os mesmos estiverem com status "Aprovado".

> **Atenção**: Deixe habilitado a opção "Habilitar log". Estando essa opção habilitado, podemos ver os logs de transações de Boleto Bancário.

= Configurando o Appmax - Pix =

Após de clicar em "Gerenciar", deixe sempre checado a opção "Ativar Appmax - Pix".

Mantenha sempre o padrão de valores nos campos "Título" e "Descrição".

No campo **Appmax API Key**, cole o token gerado na plataforma da Appmax.

No campo **Receber Pedidos de CallCenter** de **Pix**, selecione a opção "Quando estiver integrado" para receber os pedidos de CallCenter da plataforma Appmax quando os mesmos estiverem com status "Integrado" ou selecione a opção "Quando estiver pago" para receber os pedidos de CallCenter da paltaforma Appmax quando os mesmos estiverem com status "Aprovado".

> **Atenção**: Deixe habilitado a opção "Habilitar log". Estando essa opção habilitado, podemos ver os logs de transações de Pix.

= Checkout por Cartão de Crédito: =

Quando o checkout for a opção Appmax - Cartão de Crédito, todos os campos são **obrigatórios** e devem ser preenchidos.

= Checkout por Boleto Bancário: =

Quando o checkout for a opção Appmax - Boleto Bancário, todos os campos são **obrigatórios** e devem ser preenchidos.

= Checkout por Pix =

Quando o checkout for a opção Appmax - Pix, todos os campos são **obrigatórios** e devem ser preenchidos.

= Logs: =

Para visualizar os logs das transações vá para a aba "WooCommerce" > "Status". E em seguida, clique na tab "Logs".

Descendo um pouco a página, você verá um seletor de logs.

> **Atenção**: Sempre verifique se no seletor está selecionado o arquivo de log do dia atual.

Uma vez feita essa verificação, clique no botão "Visualizar" ao lado do seletor.

Então você estará acompanhando os logs das transações.

Os arquivos de logs das transações são gerados automaticamente e nomeados conforme a escolha do checkout.

Logs de transações de Cartão de Crédito: **appmax-credit-card-{DATA-ATUAL}-{HASH}**

Logs de transações de Boleto: **appmax-billet-{DATA-ATUAL}-{HASH}**

Logs de transações de Pix: **appmax-pix-{DATA-ATUAL}-{HASH}**

== Changelog ==

= 2.0.46 =

* Alteração de mensagem exibida em caso de problemas com pagamento.

= 2.0.45 =

* Melhoria da exibição de logs.

= 2.0.44 =

* Adicionando configuração em Appmax - Cartão de Crédito para exibir o total por parcelas.

= 2.0.43 =

* Ajuste formulário de pagamento pix.

= 2.0.42 =

* Adicionado opção de pagamento via Pix com notificação em caso de pedido aprovado.

= 2.0.41 =

* Permitido envio de valores personalizados ao produto através da extensão do Woocommerce Extra Product Options Builder for WooCommerce.

= 2.0.40 =

* Validação que checa se o produto da loja tem sku antes de enviar o carrinho.

= 2.0.39 =

* Correção na validação do retorno de acesso ao servidor.

= 2.0.38 =

* Ajustando mensagens de retorno para o cliente final.

= 2.0.37 =

* Removendo parâmetro no header para conectar-se na API.

= 2.0.36 =

* Ajustando regra de troca de status automática dos pedidos pelo retorno dos webhooks.

= 2.0.35 =

* Trocado tipo do campo de CPF e número de cartão para impedir problemas de máscara em dispositivos android.

= 2.0.34 =

* Aplicando correção.

= 2.0.33 =

* Adicionado campo personalizado appmax_tracking_code.
* Códigos de rastreio adicionados no campo personalizado são enviados via API.
* Códigos de rastreio adicionados pelo plugin Claudio Sanches - Correios for WooCommerce são enviados via API.

= 2.0.32 =

* Ajustando a rotina que monta os itens do carrinho que são enviados via API.
* Tratando produtos do tipo Variável.

= 2.0.31 =

* Ajustando regra de atualização de status ao retornar webhook.

= 2.0.30 =

* Adicionado o campo de configuração no gateway de Cartão de Crédito que configura o status do pedido para ser atualizado uma vez que um pedido na plataforma Appmax esteja em Análise Antifraude.

= 2.0.29 =

* Enviando o IP do cliente na requisição para Api.

= 2.0.28 =

* Removendo a verificação do fluxo de dados enviado via API adicionada na versão 2.0.25.
* Removido um number_format do getPrice() do produto antes de enviar os produtos via API.

= 2.0.27 =

* Fazendo um ajuste na URL que faz as requisições para a API.

= 2.0.26 =

* Adicionando um botão para ver o boleto gerado na página de sucesso.

= 2.0.25 =

* Adicionando uma verificação no fluxo de dados enviados via API.

= 2.0.24 =

* Ajustando as rotinas utilizadas nos eventos de webhook.

= 2.0.23 =

* Adicionado o campo de configuração no gateway de Cartão de Crédito que configura o status inicial ao criar um novo pedido.

= 2.0.22 =

* Fazendo novos ajustes ao enviar os dados via API.

= 2.0.21 =

* Fazendo um ajuste nos dados enviados via API.

= 2.0.20 =

* Adicionando versionamento nas chamadas de arquivos JS e CSS.

= 2.0.19 =

* Ajustando a máscara de Telefone.

= 2.0.18 =

* Ajustando o envio do Desconto.

= 2.0.17 =

* Modificando uma validação.

= 2.0.16 =

* Fazendo um tratamento para pegar o campo bairro.

= 2.0.15 =

* Fazendo um tratamento para pegar o número de endereço pelo billing-number.
* Exibindo a mensagem de validação do endpoint /order quando ocorrer algum erro ao transacionar.

= 2.0.14 =

* Alterando o valor de algumas constantes.

= 2.0.13 =

* Adicionando novas validações no formulário de checkout.

= 2.0.12 =

* Ajustes na captura de logs.

= 2.0.11 =

* Ajustes na captura de logs.

= 2.0.10 =

* Ajustes CSS no formulário de checkout.

= 2.0.9 =

* Novas alterações no formulário de checkout.

= 2.0.8 =

* Alterações no formulário de checkout.

= 2.0.7 =

* Separando as regras de validação por formulário.

= 2.0.6 =

* Ajustando formulários de pagamento do plugin.

= 2.0.5 =

* Padronizando campos do checkout.

= 2.0.4 =

* Ajustado exibição das parcelas.

= 2.0.3 =

* Removida Classe Router não utilizada.

= 2.0.2 =

* Utilização da versão 3 da API da plataforma Appmax.

= 2.0.0 =

* Validação de data de vencimento dos boletos.
* Inserção de campos de configuração no gateway de Boleto Bancário.
* Inserção de campos de configuração no gateway de Cartão de Crédito.
* Entrada de informações via Webhook.

= 1.0.9 =

* Trocando o nome da chave de API

= 1.0.7 =

* Corrigindo bug do cálculo de parcelas.

= 1.0.6 =

* Corrigindo bug do cálculo de parcelas.

= 1.0.5 =

* Alterando endereço da API.

= 1.0.4 =

* Removido o README.md
* Atualizado o readme.txt
* Criado o license.txt

= 1.0.3 =

* Alterações no readme.txt

= 1.0.2 =

* Alterações no README.md

= 1.0.1 =

* Alterações no readme.txt e no README.md

= 1.0.0 =

* Versão inicial do plugin

