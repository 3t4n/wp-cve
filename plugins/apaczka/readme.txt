=== Apaczka.pl WooCommerce ===
Contributors: inspirelabs
Donate link: https://www. inspirelabs.pl/
Tags: apaczka, woocommerce
Requires at least: 4.0
Tested up to: 6.1.1
Stable tag: 1.4.8
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Zintegruj WooCommerce z Apaczka.pl. Dzięki integracji, możesz skorzystać z promocyjnej oferty na usługi UPS, DHL, K-EX, DPD, TNT, FedEx, InPost i Pocztex 24.

== Description ==

> Zainstaluj najnowszą wersję wtyczki, dostępną poniżej:
> [https://wordpress.org/plugins/apaczka-pl/](https://wordpress.org/plugins/apaczka-pl/)
> Zyskaj dostęp do najnowszych aktualizacji oraz wsparcia

Apaczka.pl WooCommerce

Dzięki tej wtyczce zintegrujesz swój sklep internetowy z operatorem logistycznym Apaczka.pl. Oszczędzisz czas potrzebny na wygenerowanie listu przewozowego oraz ułatwisz sobie złożenie zlecenia. Zyskasz również promocyjne stawki na przewozy kurierskie u zintegrowanych przewoźników.

Funkcjonalność wtyczki:

* wybór kuriera UPS, DHL, K-EX, DPD, TNT, FedEx, InPost lub Pocztex 24
* wybór wagi oraz rozmiarów przesyłki
* zadeklarowanie zawartości przesyłki
* wybranie pobrania lub ubezpieczenia
* wybór daty oraz godzin w których ma pojawić się kurier
* wygenerowania zlecenia
* wygenerowanie listu przewozowego oraz jego zapis na dysku
* ustawienia wartości domyślnych

Aby korzystać z tej wtyczki, należy mieć utworzone konto na [Apaczka.pl](https://www.apaczka.pl/?register=1&register_promo_code=WooCommerce).
Wymagana wersja PHP: 5.6 lub wyższa.
Jeśli masz pytania dotyczące wtyczki lub założenia konta na Apaczka.pl, możesz napisać na sprzedaz@apaczka.pl.

== Installation	 ==

Możesz zainstalować tę wtyczkę tak jak każdy plugin do WordPressa.

1. Ściągnij i rozpakuj plik z wtyczką.
2. Wgraj cały katalog wtyczki do katalogu /wp-content/plugins/ na serwerze.
3. Aktywuj wtyczkę w menu Wtyczki w panelu administracyjnym WordPressa.
4. Konieczne będzie również zainstalowanie wymaganej wtyczki [Flexible Shiping](href="https://wordpress.org/plugins/flexible-shipping)

Możesz również użyć wysyłania wtyczki w pliku zip w panelu administracyjnym WordPressa w menu Wtyczki -> Dodaj nową -> Wyślij wtyczkę na serwer. W takim przypadku przejdź bezpośrednio do punktu 3.

== Frequently Asked Questions ==

= Czy potrzebuję konta w Apaczka.pl, żeby korzystać z wtyczki? =

Tak. Zarejestruj się [tutaj](https://www.apaczka.pl/?register=1&register_promo_code=WooCommerce).

== Screenshots ==

1. Ustawienia główne.

== Changelog ==

= 1.4.8 - 2023.06.02 =
* Link do zaktualizowanej wersji wtyczki

= 1.4.7 - 2023.03.07 =
* Zmiana adresu email

= 1.4.6 - 2023.02.14 =
* Przetestowanie zgodności z WordPress 6.1.1 oraz WooCommerce 7.3.0.
  Poprawka błędu biblioteki sdk-for-javascript.js

= 1.4.5 - 2022.01.31 =
* Przetestowanie zgodności z WordPress 5.9.0 oraz WooCommerce 6.1.1.

= 1.4.4 - 2021.12.31 =
* Przetestowanie zgodności z WooCommerce 6.0.0.

= 1.4.3 - 2021.11.30 =
* Przetestowanie zgodności z WordPress 5.8.2 oraz WooCommerce 5.9.0.

= 1.4.2 - 2021.10.29 =
* Przetestowanie zgodności z WooCommerce 5.8.0.

= 1.4.1 - 2021.09.28 =
* Przetestowanie zgodności z WordPress 5.8.1 oraz WooCommerce 5.7.1.

= 1.4.0 - 2021.07.12 =
* Obsługa punktów wybranych na mapie z wtyczki Apaczka.pl Mapa punktów. Przekazanie wybranego paczkomatu do pola "Paczkomat docelowy".
* Poprawka - podstawienie startowych rozmiarów paczki przy domyślnie wybranej usłudze InPost Paczkomaty.

= 1.3.14 - 2021.05.14 =
* Poprawka - odblokowanie edycji wymiarów paczki po zmianie usługi przy domyślnym ustawieniu InPost Paczkomaty.

= 1.3.13 - 2021.04.29 =
* Poprawka - usunięcie domyślnie zdefiniowanej strefy czasowej "Europe/Warsaw".

= 1.3.12 - 2021.04.16 =
* Poprawka - InPost Paczkomaty - wybór wymiaru na podstawie szablonu.
* Przetestowanie zgodności z WordPress 5.7.1 oraz WooCommerce 5.2.2.

= 1.3.11 - 2021.02.09 =
* Poprawka - usunięcie ostrzeżenia podczas składania zamówienia.
* Poprawka - usunięcie ostrzeżenia podczas nadawania przesyłki.
* Przetestowanie zgodności z WordPress 5.6.1 oraz WooCommerce 4.9.2.

= 1.3.10 - 2019.11.18 =
* Poprawka - pokazanie adresu paczkomatu jako adresu dostawy jeśli wybierzemy tą formę wysyłki
* Poprawka Geowidgetu - automatyczne zamykanie po wybraniu paczkomatu
* Poprawka Geowidgetu - skalowanie szerokości w widoku mobile

= 1.3.9 - 2018.11.19 =
* Poprawka wydajności: Ładowanie skryptów Geowidgetu tylko tam gdzie są wykorzystywane.

= 1.3.8 - 2018.10.29 =
* Poprawka walidacji formularza wyboru paczkomatu.

= 1.3.7 - 2018.10.23 =
* Usunięcie ustawień obsługiwanych w ramach integracji z Flexible Shipping

= 1.3.6 - 2018.10.04 =
* Poprawka błędu związanego z określeniem paczkomatu nadawczego

= 1.3.5 - 2018.08.17 =
* Aktualizacja dokumentacji
* Poprawki drobniejszych błędów

= 1.3.4 - 2018.08.10 =
* Obsługa Paczkomatów InPost
* Dodanie przewoźnika Apaczka Niemcy
* Ikonki akcji w tabeli zamówień
* Poprawki drobniejszych błędów

= 1.3.3 - 2018.01.22 =
* Aktualizacja dokumentacji

= 1.3.2 - 2018.01.17 =
* Dodanie obsługi kuriera Inpost

= 1.3.1 - 2017.12.14 =
* Poprawki wykrytych błędów

= 1.3.0 - 2017.11.20 =
* Integracja z wtyczką Flexible Shiping
* Opcje dodatkowe do listu przewozowego: przesyłka niestandardowa, dostawa w sobotę, dokumenty zwrotne
* Możliwość wyboru pomiędzy zamówieniem kuriera a wygenerowaniem samego listu przewozowego
* Obsługa kurierów zagranicznych
* Poprawiony link do rejestracji
* Poprawki wykrytych błędów
* Zgodność z Wordpress 4.9 oraz Woocommerce 3.2.4

= 1.2.1 - 2016.12.23 =
* Poprawienie wpisywania cen z wartościami dziesiętnymi

= 1.2 - 2016.09.06 =
* Zmiana pobierania danych do wysyłki - jeśli zostanie usunięty adres do wysyłki to dane zostaną pobrane z danych płatności
* Metabox Apaczka w edycji zamówienia widoczny jest dla każdego zamówienia
* Poprawienie działania opcji włącz/wyłącz

= 1.1.1 - 2016.07.11 =
* Poprawienie błędu z logowaniem do panelu admina

= 1.1 - 2016.07.04 =
* Dodanie nowych kurierów: DPD, TNT, FedEx
* Poprawienie błędu ze zmianą metody wysyłki

= 1.0.1 - 2016.06.21 =
* Wyłączenie wysyłki poza Polskę (wtyczka obsługuje wysyłkę tylko w Polsce)

= 1.0 - 2016.05.23 =
* Pierwsze wydanie!
