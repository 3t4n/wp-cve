/**
 * Change api password and secret word attribute value to stars (*********)
 * so user can't see the value if using inspection element
 *
 * @package Skrill setting/JS
 */

var skrill_api_passwd  = document.getElementById( 'skrill_api_passwd' ).value;
var skrill_secret_word = document.getElementById( 'skrill_secret_word' ).value;

document.getElementById( 'skrill_api_passwd' ).setAttribute( 'value', '**********' );
document.getElementById( 'skrill_secret_word' ).setAttribute( 'value', '**********' );

document.getElementById( 'skrill_api_passwd' ).value  = skrill_api_passwd;
document.getElementById( 'skrill_secret_word' ).value = skrill_secret_word;
