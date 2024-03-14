=== Autopay ===
Contributors: inspirelabs
Tags: woocommerce, bluemedia, autopay
Requires at least: 6.0
Tested up to: 6.4.3
Stable tag: 4.2.8
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Autopay to moduł płatności umożliwiający realizację transakcji bezgotówkowych w sklepie opartym na platformie WordPress (WooCommerce).

== Opis ==

Autopay to moduł płatności umożliwiający realizację transakcji bezgotówkowych w sklepie opartym na platformie WordPress (WooCommerce).

Do najważniejszych funkcji modułu zalicza się:
- realizację płatności online poprzez odpowiednie zbudowanie startu transakcji
- obsługę powiadomień o statusie transakcji (notyfikacje XML)
- obsługę zakupów bez rejestracji w serwisie
- obsługę dwóch trybów działania – testowego i produkcyjnego (dla każdego z nich wymagane są osobne dane kont, po które zwróć się do nas)
- obsługę popularnych metod płatności, które pozwalają Twoim klientom płacić za zakupy w wygodny sposób
- możliwość korzystania z BLIKA.
- wybór banku po stronie sklepu i bezpośrednie przekierowanie do płatności w wybranym banku

Wymagania

- WordPress – przetestowane na wersjach od 6.0 do 6.3.2
- Wtyczka WooCommerce – przetestowano na wersjach od 7.0 do 8.2.1
- +

== Installation	 ==

Zainstaluj wtyczkę w panelu administracyjnym Wordpress:

1. Pobierz wtyczkę
2. Przejdź do zakładki Wtyczki > Dodaj nową a następnie wskaż pobrany plik instalacyjny.
3. Po zainstalowaniu wtyczki włącz moduł.
1. Przejdź do zakładki WooCommerce ➝ Ustawienia ➝ Płatności.
2. Wybierz Autopay, żeby przejść do konfiguracji.

Konfiguracja podstawowych pól wtyczki:

1.	Przy nazwie Autopay ustaw Włącz, dzięki temu Twoi klienci będą mogli już korzystać z płatności internetowych.
2.	Zaznacz pole: “Pokazuj metody płatności w sklepie”
3.	W polu Nazwa modułu płatności w sklepie wpisz nazwę płatności, czyli np.: Płatności Autopay.

4.	W polu Opis modułu płatności w sklepie dodaj opis używanej bramki płatności, czyli Autopay – Twoi klienci będą widzieć tę nazwę składając zamówienie i wybierając metodę płatności.
      W polu “Identyfikator serwisu” wpisz identyfikator serwisu.
5.	W polu “Klucz współdzielony” wpisz klucz współdzielony.

Powyższe pola uzupełnisz danymi, które otrzymasz od Autopay S.A. Jeśli jeszcze ich nie masz - skontaktuj się z nami.
W momencie, gdy skończysz już sprawdzać, czy wszystko działa prawidłowo – wyłącz tryb testowy, wówczas płatności na Twojej stronie będą w 100% aktywne.
Po uzupełnieniu wszystkich pól – wybierz: Zapisz zmiany i gotowe.


== Screenshots ==

1. Widok pól do uzupełnienia
2. Dostępne metody płatności

== Changelog ==

## [4.0.3] - 2022-07-15
### Fixed
- Show API errors only if BLUE_MEDIA_DEBUG constant is defined

## [4.0.4] - 2022-07-20
### Fixed
- Plugin boilerplate refactor

## [4.0.5] - 2022-07-27
### Fixed
- CSS fix

## [4.0.6] - 2022-07-27
### Fixed
- README.md revert

## [4.0.7] - 2022-08-19
### Fixed
- Minor fixes

## [4.0.8] - 2022-08-19
### Fixed
- Docs

## [4.0.9] - 2022-08-19
### Fixed
- Docs

## [4.0.10] - 2022-08-19
### Fixed
- Docs

## [4.1.2] - 2022-10-06
### Fixed
- Minor fixes
### Added
- GA4 support
- Settings page updates

## [4.1.3] - 2022-10-11
### Fixed
- Fatal error on change order status
- Minor fixes
### Added
- Tutorial banner on bm setting page

## [4.1.4] - 2022-10-17
### Fixed
- GA4

## [4.1.5] - 2022-10-20
### Fixed
- Fatal error in Item_In_Cart_DTO.php

## [4.1.6] - 2022-10-21
### Updated
- Documentation

## [4.1.7] - 2022-10-26
### Fixed
- Fatal error in Item_In_Cart_DTO.php

## [4.1.8] - 2022-11-02
### Added
- First release on wp.org

## [4.1.9] - 2022-11-02
### Updated
- Documentation

## [4.1.10] - 2022-11-10
### Updated
- Documentation

## [4.1.11] - 2023-01-12
### Updated
- Ga4 integration
- Docs
### Fixed
- Unpaid order cancel feature
- Minor fixes
- Admin panel fixes
### Added
- Payment channels grouping refactor

## [4.1.12] - 2023-01-23
### Fixed
- Minor fixes
- Fix Google Analitics module: submit proper value of pseudo_user_id field

## [4.1.13] - 2023-02-17
### Fixed
- Update plugin core
- Update translations
- Update docs

## [4.1.14] - 2023-02-23
### Updated
- Update frontend scripts
- Update translations
- Update docs
- PayPO whitelabel descriptions

## [4.1.15] - 2023-02-23
### Updated
- Docs

## [4.1.16] - 2023-03-08
### Added
- Protection against accidentally installing the development version instead of the production package
### Fixed
- Missing call of payment_complete() method on Order object

## [4.1.17] - 2023-03-21
### Fixed
- Styles

## [4.1.18] - 2023-03-27
### Added
- Astra theme support

## [4.1.19] - 2023-05-02
### Fixed
- Redirect to payment issues for some scenarios
- WP 6.2 compatibility

## [4.1.20] - 2023-05-05
### Fixed
- Fatal error on activate / deactivate hook for some scenarios

## [4.1.21] - 2023-05-08
### Fixed
- Show log only on demand

## [4.1.23] - 2023-06-22
### Added
- Improved checkout UI
### Fixed
- Styles
- Minor fixes

## [4.1.24] - 2023-07-28
### Added
- New bank list styles
- New module: Preview payment methods in Admin Panel

## [4.1.25] - 2023-08-03
### Fixed
- Blik redirect fix

## [4.1.26] - 2023-08-24
### Added
- Blik-0 support
### Fixed
- Minor fixes
- Styles

## [4.2.0] - 2023-08-31
### Added
- Rebranding

## [4.2.1] - 2023-09-29
### Fixed
- Translations
- Blik: problem with code starting with "0"
- Improved payment method selection UI
- Fatal error during a page update in a specific scenario

## [4.2.2] - 2023-10-02
### Fixed
- Translations

## [4.2.3] - 2023-10-19
### Added
- Ability to assign a separate status for virtual products
- Debug and testing new features

### Fixed
- Settings texts updates
- An order cannot be paid if there is only one payment method available to the partner
- Styles

## [4.2.4] - 2023-11-05
### Added
- Debug mode improved

### Fixed
- Minor CSS fixes
- Redirect to payment loop issue for some scenarios


## [4.2.5] - 2023-11-28
### Added
- Show countdown screen before redirection to increase compatibility
- Minor changes in Admin Panel

### Fixed
- CSS compatibility issues

## [4.2.6] - 2023-12-11
### Added
- Option: Compatibility mode with third-party plugins that reload checkout fragments

### Fixed
- CSS minor fixes

## [4.2.7] - 2024-01-18
### Fixed
- CSS fixes
### Added
- Block Editor support (express payment)

## [4.2.8] - 2024-02-13
### Updated
- Payment methods integration
### Fixed
- Blik-0 issues for some scenarios
- Apple Pay method visibility problem
- Styles
- Payment process on "My account" page
- Email payment link support
### Added
- Ability to migrate settings from 2.x and 3.x plugins