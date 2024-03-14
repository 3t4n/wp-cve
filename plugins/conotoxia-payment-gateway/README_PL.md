# Conotoxia Payment Gateway

Wtyczka do WooCommerce, która dostarcza nową metodę płatności Cinkciarz Pay.

## Dziennik zmian

- 1.31.19 Zaktualizowano plugin w celu zapewnienia kompatybilności z Wordpress 6.4.
- 1.31.13 Zaktualizowano plugin w celu zapewnienia kompatybilności z WooCommerce w wersji 8.2. 
- 1.31.5 Zaktualizowano plugin w celu zapewnienia kompatybilności z WooCommerce w wersji 8.1.
- 1.30.9 Zaktualizowano plugin w celu zapewnienia kompatybilności z Wordpress 6.3.0 i WooCommerce w wersji 7.9.0.
- 1.29.6 Zaktualizowano plugin w celu zapewnienia kompatybilności z Wordpress 6.2.2 i WooCommerce w wersji 7.8.2.
- 1.27.0 Dodano możliwość realizacji płatności BLIK bez opuszczania Sklepu (BLIK Level 0).
- 1.26.25 Zaktualizowano plugin w celu zapewnienia kompatybilności z Wordpress 6.2 i WooCommerce w wersji 7.5.1.
- 1.26.5 Zaktualizowano plugin w celu zapewnienia kompatybilności WooCommerce z nowszymi wersjami (WooCommerce 7.2.3).
- 1.26.0 Zaktualizowano plugin w celu zapewnienia kompatybilności z WordPress 6.1.
- 1.25.16 Zakończono wsparcie dla PHP w wersji mniejszej niż 7.2.
- 1.25.0 Zaktualizowano plugin w celu zapewnienia kompatybilności WooCommerce z nowszymi wersjami (WooCommerce 7.0.0).
- 1.21.0 Zaktualizowano plugin w celu zapewnienia kompatybilności z WordPress 6.0 i PHP 8.1.
- 1.20.1 Zaktualizowano plugin w celu zapewnienia kompatybilności WooCommerce z nowszymi wersjami (WooCommerce 5.9.1).
- 1.20.0 Dodano ikonę Vipps.
- 1.19.0 Dodano ikonę PrzelewOnline.
- 1.18.0 Dodano opcję ustawienia Cinkciarz Pay jako domyślną wybraną metodę płatności.
- 1.17.0 Dodano opcję wyboru widocznych ikon metod płatności na ekranie wyboru metody płatności.
- 1.15.0 Zaktualizowano plugin w celu zapewnienia kompatybilności WooCommerce z nowszymi wersjami (WooCommerce 5.8.0).
- 1.14.0 Dodano informację o potrzebie aktywacji klucza publicznego.
- 1.13.0 Dodano wsparcie dla statusu CANCELLED dla zwrotów.
- 1.11.0 Zaktualizowano plugin w celu zapewnienia kompatybilności WooCommerce z nowszymi wersjami (WooCommerce 5.4.2).
- 1.10.1 Usuwanie niedozwolonych znaków i protokołu komunikacji z opisu zamówienia i powodu zwrotu.
- 1.8.0 Zmiany pozwalające na wsparcie niestandardowych identyfikatorów zamówienia.
- 1.6.0 Zaktualizowano plugin w celu zapewnienia kompatybilności WooCommerce z nowszymi wersjami (WooCommerce 5.1.0).
- 1.4.0 Zaktualizowano WordPress i WooCommerce (do wersji 4.9.2).
- 1.1.0 Dodano możliwość ukrycia ikony na liście wyboru płatności.
- 0.9.0 Dodano możliwość generowania kluczy publicznych i prywatnych podczas konfiguracji.
- 0.8.0 Dodano tryb sandbox umożliwiający wykonanie płatności na środowisku testowym.
- 0.4.5 Dodano identyfikator płatności na stronie podsumowania płatności.

## Spis treści

* [Wymagania](#wymagania)
* [Instalacja i aktywacja wtyczki](#instalacja-i-aktywacja-wtyczki)
    * [Instalacja za pomocą panelu administracyjnego WordPress](#instalacja-za-pomocą-panelu-administracyjnego-wordpress)
    * [Instalacja manualna](#instalacja-manualna)
* [Konfiguracja](#konfiguracja)
    * [Konfiguracja punktu płatności w Panelu sprzedawcy](#konfiguracja-punktu-płatności-w-panelu-sprzedawcy)
    * [Aktywacja klucza publicznego w Panelu sprzedawcy](#aktywacja-klucza-publicznego-w-panelu-sprzedawcy)
* [Zwrot w Panelu Merchanta](#zwroty)

## Wymagania

* WordPress 5.4 - 6.4
* WooCommerce 4.2 - 8.2
* PHP 7.2 - 8.1
* Rozszerzenia PHP:
    * curl
    * json
    * openssl
    * readline

## Instalacja i aktywacja wtyczki

Wtyczka jest dostępna do pobrania z witryny [Wordpress.org](https://pl.wordpress.org/plugins/conotoxia-payment-gateway).  
Wtyczkę można zainstalować na dwa sposoby — za pomocą panelu administratora lub manualnie.  
Jeżeli nie jesteś zaawansowanym użytkownikiem WordPress'a, zalecamy instalację poprzez panel administratora.

#### Instalacja za pomocą panelu administracyjnego WordPress

1. W panelu administratora wybierz `Wtyczki → Dodaj nową`, następnie wybierz przycisk `Wyślij wtyczkę na serwer` i w
   oknie dialogowym wybierz plik zip.
2. Następnie wybierz `Włącz` w `Wtyczki → Zainstalowane wtyczki`.

#### Instalacja manualna

1. Pobrany plik zip należy rozpakować w katalogu `wp-content/plugins` na serwerze.
2. W `Wtyczki → Zainstalowane wtyczki` należy włączyć wtyczkę.

## Konfiguracja

1. Należy przejść do ustawień wtyczki WooCommerce `WooCommerce → Ustawienia`.
2. Następnie wybrać sekcje `Płatności`.
3. Należy wybrać `Zarzadzaj` dla metody płatności `Conotoxia Pay`.
4. Należy wypełnić wszystkie pola na stronie konfiguracji wtyczki:
    - `Identyfikator klienta API` i `Hasło klienta API` - dane dostępowe można wygenerować w
      [Panelu sprzedawcy](https://fx.cinkciarz.pl/merchant/configuration) w sekcji `Dane dostępowe`.
    - `Identyfikator punktu płatności*`. - identyfikator utworzonego punktu płatności.
    - `Klucz prywatny` - możliwe jest wygenerowanie klucza prywatnego na stronie konfiguracji wtyczki. Z wprowadzonego
      na stronie konfiguracji wtyczki klucza prywatnego generowany jest klucz publiczny. Klucz ten przy generacji jest
      automatycznie przesyłany do Cinkciarz Pay. Nie jest wtedy konieczne wprowadzenie klucza
      w [Panelu sprzedawcy](https://fx.cinkciarz.pl/merchant/public-keys/add?context=configuration). Dodatkowa
      instrukcja generowania kluczy znajduje się
      w [dokumentacji](https://docs.cinkciarz.pl/platnosci/sklepy-online#generowanie-klucza-publicznego).
    - `Tryb testowy` - możliwe jest przetestowanie modułu w środowisku testowym. W celu pozyskania dostępu do środowiska
      testowego należy udać się na stronę [cinkciarz.pl](https://cinkciarz.pl/kontakt/biznes).
    - `Ikona płatności Cinkciarz Pay` - możliwe jest dodanie ikony Cinkciarz Pay na liście wyboru płatności.
    - `Ikony metod płatności` - możliwe jest wybranie widocznych ikon metod płatności na ekranie wyboru metody
      płatności.
5. Po zakończeniu konfiguracji należy włączyć metodę płatności.

`*` Dane można pozyskać generując sklep oraz punkt płatności w kreatorze dostępnym
w [Panelu sprzedawcy](https://fx.cinkciarz.pl/merchant).

### Konfiguracja punktu płatności w [Panelu sprzedawcy](https://fx.cinkciarz.pl/merchant)

Punkt płatności powinien być skonfigurowany zgodnie z danymi widocznymi poniżej:

- `Adres powiadomienia o utworzeniu płatności` - https://sklep.pl/?wc-api=WC_Gateway_Conotoxia_Pay
- `Adres powiadomienia o utworzeniu zwrotu` - https://sklep.pl/?wc-api=WC_Gateway_Conotoxia_Pay
- `Adres strony dla płatności udanej` - https://sklep.pl
- `Adres strony dla płatności nieudanej` - https://sklep.pl
- `Lista dozwolonych adresów` - https://sklep.pl

gdzie `sklep.pl` powinien być zastąpiony rzeczywistym adresem sklepu.

### Aktywacja klucza publicznego w [Panelu sprzedawcy](https://fx.cinkciarz.pl/merchant)
Klucz publiczny utworzony i przesłany za pomocą wtyczki musi zostać aktywowany.  
W celu aktywacji klucza publicznego przejdź do [Panelu sprzedawcy](https://fx.cinkciarz.pl/merchant/configuration).

# Zwroty
Zwroty można zlecić z poziomu wtyczki oraz z [Panelu Sprzedawcy](https://fx.cinkciarz.pl/merchant).
W przypadku zlecenia z Panelu Sprzedawcy należy uzupełnić `Zewnętrzny numer zwrotu płatności` zgodnie z numerem zamówienia z sklepu.
Jeżeli pole zostanie pominięte to nie zostaną dostarczone powiadominia do sklepu.
