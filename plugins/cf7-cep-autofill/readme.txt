=== Autopreenchimento de endereço em formulários ===
Contributors: fabbricaweb
Donate link: https://www.fabbricaweb.com.br
Tags: Contact Form 7, CF7, Gravity Forms, Ninja Forms, WPForms, Elementor, Formidable Forms, cep, endereco, autopreenchimento, autopreencher, rua, avenida, bairro, cidade, estado
Requires at least: 4.0
Tested up to: 6.1.1
Requires PHP: 5.5
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Preenchimento automático de campos de endereço baseado no CEP informado.

== Description ==

**Autopreenchimento de endereço em formulários** permite que os campos de logradouro (rua, avenida e etc), bairro, cidade e estado (UF) sejam prenchidos automaticamente baseado no CEP informado. Ele 

Plugins testados até o momento:

* <a href="https://br.wordpress.org/plugins/contact-form-7/">Contact Form 7</a>
* <a href="https://br.wordpress.org/plugins/wpforms-lite/">WPForms</a>
* <a href="https://br.wordpress.org/plugins/elementor/">Elementor</a>
* <a href="https://br.wordpress.org/plugins/formidable/">Formidable Forms</a>
* <a href="https://br.wordpress.org/plugins/ninja-forms/">Ninja Forms</a>
* <a href="https://www.gravityforms.com/">Gravity Forms</a>


**Autopreenchimento de endereço em formulários** identifica o campo que irá receber o CEP e os campos a serem preenchidos automaticamente através de **classes CSS específicas** que informam ao plugin o valor que o campo deve receber.

São elas:

* **cf7-cep-autofill** no campo em que o usuário deve preencher o **CEP**
* **cf7-cep-autofill__rua** para receber o valor do **logradouro**
* **cf7-cep-autofill__bairro** para receber o valor do **bairro**
* **cf7-cep-autofill__cidade** para receber o valor da **cidade**
* **cf7-cep-autofill__uf** para receber o valor do **estado**

Exemplo de uso com o plugin <a href="https://br.wordpress.org/plugins/contact-form-7/">Contact Form 7</a>:

`[text* cep class:cf7-cep-autofill]`
`[text* logradouro class:cf7-cep-autofill__rua]`
`[text* bairro class:cf7-cep-autofill__bairro]`
`[text* cidade class:cf7-cep-autofill__cidade]`
`[text* estado class:cf7-cep-autofill__uf]`
`[select* estado class:cf7-cep-autofill__uf include_blank "AC" "AL" "AP" "AM" "BA" "CE" "DF" "ES" "GO" "MA" "MT" "MS" "MG" "PA" "PB" "PR" "PE" "PI" "RJ" "RN" "RS" "RO" "RR" "SC" "SP" "SE " "TO"]`

Caso o campo da UF seja do tipo select (menu suspenso):

`[select* estado class:cf7-cep-autofill__uf include_blank "AC" "AL" "AP" "AM" "BA" "CE" "DF" "ES" "GO" "MA" "MT" "MS" "MG" "PA" "PB" "PR" "PE" "PI" "RJ" "RN" "RS" "RO" "RR" "SC" "SP" "SE " "TO"]`

Nos outros plugins basta informar as classes do plugin nos respectivos campos que os editores dos formulários oferecem.

= Dúvidas? =

Você pode esclarecer suas dúvidas criando um tópico no [fórum de ajuda do WordPress](https://wordpress.org/support/plugin/cf7-cep-autofill).

= Créditos =

Foram utilizados os seguintes scripts/serviços de terceiros:

* [ViaCEP](https://viacep.com.br/).

== Installation ==

1. Faça o upload dos arquivos do plugin no diretório `/wp-content/plugins/cf7-cep-autofill`, ou instale diretamente pela área de plugins do WordPress.
2. Ative o plugin.

== Frequently Asked Questions ==

= Quais formatos de CEP o plugin aceita? =

O plugin aceita o CEP nos seguintes formatos: 00000-000, 00.000-00 e 00000000.

= O campo de estado (UF) do tipo select (drop down) não funciona =

Assegure-se de ter cadastrado as opções do campo com a sigla do estados ao invéa do nome.

== Changelog ==

= 1.2 (2023-01-20) =
- Correção do arquivo readme.txt.

= 1.1 (2023-01-20) =
- Adicionada a compatibilidade com outros plugins de formulários.

= 1.0.8 (2022-03-25) =
- Adicionada a compatibilidade para a versão 5.9.2.

= 1.0.7 (2020-09-12) =
- Adicionada a compatibilidade para a versão 5.5.1.

= 1.0.6 (2019-04-06) =
- Adicionada a compatibilidade para múltiplos formulários na mesma página.

= 1.0.5 (2018-06-04) =
- Correção do arquivo readme.txt.

= 1.0.4 (2018-06-04) =
- Correção do Plugin URI.

= 1.0.3 (2018-06-04) =
- Correção do arquivo readme.txt.

= 1.0.2 (2018-06-03) =
- Correção do arquivo readme.txt.

= 1.0.1 (2018-06-03) =
- Correção do arquivo readme.txt.

= 1.0 (2018-06-03) =
- Lançamento.