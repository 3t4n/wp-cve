=== Wtyczka SeoPilot dla WP ===
Contributors: SeoPilot
Tags: moneymaker, widget, shortcode, advertising
Requires at least: 3.0.1
Tested up to: 6.0.2
Stable tag: 6.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Wtyczka umożliwia wyświetlanie reklam systemu seopilot.pl przy użyciu widgetów lub short code'u. 

== Description ==

Wtyczka umożliwia wyświetlanie reklam systemu seopilot.pl przy użyciu widgetów lub short code'u.
Jeśli Twój serwis został stworzony w oparciu o CMS WordPress, możesz zainstalować nasz kod boksu reklamowego bezpośrednio w panelu administratora, po prostu instalując poniższą wtyczkę.

Dostępne funkcje wtyczki:

* Możliwość zamieszczania kodu reklamowego w widgetach
* Możliwość wstawiania kodu przy użyciu shortcode
* Możliwość zmiany kodowania reklam
* Możliwość włączania i wyłączania trybu testowego

== Installation ==

1. Pobierz wtyczkę dla WordPress z panelu webmastera w SeoPilot lub ze strony WordPress;
1. Zaloguj się do swojego panelu administratora w WP i w dziale Wtyczki kliknij Dodaj;
1. Kliknij link Wyślij na serwer, a następnie załaduj pobraną z naszej strony wtyczkę i kliknij Zainstaluj. Wtyczka zostanie skopiowana na serwer, rozpakowana i zainstalowana;
1. Na liście zainstalowanych wtyczek znajdź wtyczkę Seopilot i włącz ją. Wtyczka pojawi się w prawym menu;
1. Otwórz ustawienia wtyczki i wprowadź swój hashcode (znajdziesz go w swoim panelu webmastera);
1. W ustawieniach wtyczki możesz także: zmienić kodowanie znaków, jeśli jest inne niż UTF-8, włączyć tryb testowy, aby zobaczyć podgląd boksu reklamowego w miejscu, gdzie został on umieszczony, wyczyścić cache, jeśli dokonałeś zmian w szablonie boksu reklamowego;
1. Przed wysłaniem serwisu na indeksację należy wyłączyć tryb testowy;
1. W menu Personalizacja (/wp-admin/customize.php) znajdź widget Seopilot i dodaj go w miejscu, gdzie będą wyświetlane reklamy, np. w sidebarze.;
1. Wywołanie skryptu możliwe jest także za pomocą shortcode-ów:
 - `<?php echo do_shortcode('[seopilot_build_links count=false orientation="v"]'); ?>` - domyślna konfiguracja,
 - `<?php echo do_shortcode('[seopilot_build_links_is count=false orientation="v"] <hr>%links%[/seopilot_build_links_is]'); ?>` - umożliwia indywidualną konfigurację
<br>Więcej informacji na temat dodatkowych możliwości konfiguracji boksu i reklam znajdziesz w Opcjach dla zaawansowanych;
1. Wróć do swojego panelu Wydawcy w SeoPilot.pl i kliknij ikonkę Dalej, aby wysłać serwis na indeksację;

= Uwaga! =

* Jeśli po poprzedniej instalacji kodu reklamowego (standardowej), pozostały na serwerze pliki, wywołania lub inne modyfikacje kodu, należy je koniecznie usunąć przed zainstalowaniem kodu za pomocą wtyczki, gdyż może to wywołać konflikt i być przyczyną błędów na stronie.
* Kod boksu reklamowego nie może być zainstalowany w stopce strony. Serwisy z kodem w stopce nie będą przyjmowane do Systemu.
* Jeśli Twój serwis korzysta z funkcji cache’owania, zalecamy na czas indeksacji tę funkcję wyłączyć, natomiast po pomyślnej weryfikacji ustawić czas ważności cache w taki sposób, aby odnawianie cache’u dokonywało się przynajmniej raz na 3 godziny.
* Jeśli CMS Twojego serwisu powoduje wycinanie komentarzy html, dla poprawnego działania kodu należy tę funkcję wyłączyć.