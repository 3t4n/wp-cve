# Pix Automático com Pagarme para WooCommerce

** AGORA TAMBÉM DISPONÍVEL PARA V5 e V4 DA PAGARME **

Para incentivar o projeto, de uma ⭐ no repositório.

O Plugin gera um QR code para pagamento PIX na finalização da compra. O cliente só precisa clicar no botão "Copiar Código" e então ir no aplicativo do banco, entrar na opção "PIX Copia e Cola" e fazer o pagamento.

Quando ele terminar de pagar e voltar para o site, o plugin recebe a confirmação de pagamento e automaticamente uma animação é mostrada com a mensagem de "Pagamento Confirmado".

O pedido é alterado para processando automaticamente. (Você pode alterar para que o pedido mude para outro status de sua preferência)

Não é preciso pedir comprovantes para o cliente pois o pagamento é confirmado direto pela API da Pagarme com o Plugin.

Você também pode:

- Escolher o tempo de expiração do qr code (Em dias ou horas)
- Escolher cancelar automaticamente o pedido quando o qr code expirar
- Customizar as mensagens de email e da tela de pagamento
- Escolher a cor do icone do pix nas opções de pagamento
- Entre outras coisas
- Se quiser sugerir uma alteração, acesse a guia suporte na pagina do plugin
- Sinta-se à vontade de fazer uma doação via PIX pela chave aleatória: 58a2463e-0e6b-4b00-aa7d-c62c6c4b712a

Esse plugin é integrado com a plataforma de pagamento **Pagar.me**, portanto você precisa criar uma conta caso não tenha.

Você precisa ter as duas chaves, o API KEY e Encryption Key da **Pagar.me** para que o Plugin funcione.

Para gerar a API Key e Encryption Key é fácil, só entrar na dashboard da Pagar.me. Caso você já tenha a Pagarme como método de pagamento de cartão de crédito e boleto no seu site, você não precisa gerar novas Chaves, use as mesmas adicionando nas configurações do plugin.

Para adicionar essas duas chaves nas configurações do nosso plugin é só ir no menu WooCommerce -> Configurações -> Na aba "Pagamentos", clicar em configuração do "Pix Instantâneo" e preencher os campos com as respectivas chaves.

Muitas vezes a opção de receber PIX pela Pagar.me vem desativada por padrão, mas é bem simples de ativá-la, você pode entrar em contato com a Pagar.me pelo chat deles que eles liberam na mesma hora.

Compativel com a última versão do Woocommerce.

## 2.1.2

- WooCommerce HPOS compatibilidade
- Wordpress Block Support

## 2.1.0

- Validação de CPF

## 2.0.9

- Adicionado informações de pagamento dentro do detalhe do pedido (caso o cliente saia da tela do pagamento)
- Corrigido Pedidos Vazios gerados quando é usado o plugin em conjunto com o Oficial da pagar.me

## 2.0.8

- Correção do Bug de número quebrado que estava dando erro ao finalizar compra

## 2.0.5

- Add Debug Logs

## 2.0.4

- Correção do campo celular para pagarme V5
- Correções de Bugs

## 2.0.3

- Agora é possível alterar o tamanho do Icone
- Correções

## 2.0.0

- Adicionada a compatibilidade com versão 5 da pagarme API

## 1.5.0

- Agora é possível criar desconto quando o método de pagamento for pix
- Escolha entre desconto fixo ou Porcentagem
- Uma aba de "Doação" para quem quiser ajudar com qualquer valor

## 1.4.1

- Correções e Bugs

## 1.4.0

- Opção de cancelamento automático do pedido quando expirado o qr code
- Opção de expiração em horas do qr code
- Mudar intervalo de verificação de pegamento concluído na página do qr code
- Correção de bugs

## 1.3.4

- Agora você pode escolher qual status o pedido ficará após o pagamento confirmado

## 1.3.2

- Correção de Bugs

## 1.3.1

- Correção de Bugs

## 1.3.0

- Correção de Bugs

## 1.3.0

- Agora é possível alterar o titulo do pix "Pix Instantêneo" para o de sua preferência
- No template do email é possível colocar o link para a página de pagamento que contém o botão "Copiar QR code"
- Inclusão do Icone de Pix
- Escolha as cores do icone pix
- Mude a data de expiração do QR Code
- Novo shorcode para inserir a data que expira para o seu cliente

## 1.2.0

- QR Code de pagamento PIX na tela de pedidos
- Instruções de pagamento PIX no E-mail
- Agora é possivel definir a localização da imagem do QR Code e do botão no texto
- Texto no finalizar compra pode ser feito em HTML
- Correções de bugs para temas que não tem bootstrap ( Centralização de textos padrões )

## 1.1.0

- Customização de textos nas mensagens na tela de pagamento
- Otimização do código

## 1.0.1

- Correções de bugs

## 1.0.0

- Versão inicial do plugin.
