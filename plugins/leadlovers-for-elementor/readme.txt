=== leadlovers for Elementor ===
Contributors: leadlovers
Tags: page-builder, elementor, leadlovers, leadlovers integration, leadlovers widget, widget, elementor add on, email, form, lead capture, integração leadlovers
Requires at least: 4.7
Tested up to: 6.2
Requires PHP: 5.4
License: GPLv2 or later
Stable tag: 1.10.1
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Este plugin WordPress insere uma nova opção de integração para os formulários criados com o Elementor Page Builder. Com ele é possível adicionar uma ação após a submissão dos formulários que envia os dados do lead capturado para a plataforma leadlovers.

Você precisa ter o [Elementor Page Builder](https://wordpress.org/plugins/elementor/) instalado


== Descrição ==

Este plugin adiciona uma nova integração para os formulários construídos no Elementor Page Builder. Com ele é possível adicionar uma ação após a submissão do formulário para enviar os dados do lead para dentro de uma máquina na leadlovers. 

Com ele você poderá:
* Selecionar uma máquina, funil e sequencia para enviar os novos leads conquistados pelos seu formulário no Elementor;
* Salvar dados dos lead como nome, email, telefone, data de nascimento, cidade, estado, gênero e empresa na leadlovers;
* Atribuir tags aos leads capturados;
* E continuar utilizando todas as outras funcionalidades já disponíveis nos formulários do Elementor!

Nota: Este plugin adicional ao Elementor Page Builder (https://wordpress.org/plugins/elementor/) e irá funcionar apenas com o Elementor Page Builder Pro instalado. 


== Instalação ==

1. Após instalar o plugin, navegue até Plugins -> Plugins instalados e ative o plugin "Leadlovers Elementor Integration".
2. Após ativar, navegue até Elementor -> Configurações -> Integrações. Encontre a seção Leadlovers e preencha o campo API Key com o seu token pessoal disponível nas configurações da sua conta na plataforma leadlovers.
3. Após salvar seu token pessoal, abra o seu formulário de captura com o painel de edição do Elementor e clique na seção "Actions After Submit" e adicione a opção "Leadlovers".
4. Após adicionar a ação da Leadlovers, uma nova seção chamada "Leadlovers" ficará disponível no painel de edição do Elementor. Realize as configurações da integração do formulário com a Leadlovers através desta seção e pronto!


== Screenshots ==

1. /assets/screenshot-1.jpg


== Changelog ==
= 1.0 =
* Versão inicial.
= 1.1 - 2019-07-08 =
* Fix: Ajuste para problema de compatibilidade em ambientes com php 7.3.
= 1.2 - 2019-07-09 =
* Feature: Possibilidade de configurar tags para atribuir aos lead capturados pelo formulário.
= 1.3 - 2019-07-16 =
* Fix: Ajuste para problema de carregamento do campo de tags para contas que não possuiam tags cadastradas.
= 1.4 - 2019-07-16 =
* Fix: Ajuste para problema de capturar os leads sem vincular tags (Erro 400).
= 1.5 - 2019-07-17 =
* Fix: Ajuste para problema de capturar email e telefone juntos.
= 1.6 - 2019-07-24 =
* Fix: Ajuste para incompatibilidade com elementor v2.6.*
* Fix: Ajuste para incompatibilidade com popup do elementor.
= 1.7 - 2019-08-08 =
* Feature: Possibilidade de configurar a mensagem de cadastro para leads já existentes na máquina.
= 1.7 - 2019-08-08 =
* Feature: Possibilidade de adicionar campos de captura do tipo de texto.
= 1.8 - 2021-01-13 =
* Feature: Possibilidade de capturar campos dinâmicos da leadlovers pelos formulários do elementor.
= 1.9 - 2021-01-26 =
* Fix: Ajuste para problemas na captura de campos
= 1.9.3 - 2023-02-09 =
* Fix: Ajuste no tratamento do retorno da captura do lead para previnir a mensagem "parseerror" após preencher o form.
= 1.9.4 - 2023-05-12 =
* Fix: Ajuste na sintax do código para previnir a mensagem "parseerror" após preencher o form em casos isolados.
= 1.10.0 - 2024-01-22 =
* Feature: Possibilidade de capturar parâmetros de UTM pelos formulários do elementor.
= 1.10.1 - 2024-01-23 =
* Fix: Ajuste no envio de parâmetros de UTM para a API.