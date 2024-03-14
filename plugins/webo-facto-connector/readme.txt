=== Webo-facto ===
Contributors: jeremieglotin
Tags: Medialibs, Webo-facto, SSO
Requires at least: 4.6
Tested up to: 6.4.2
Requires PHP: 5.6.0
Stable tag: 1.37
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Utiliser l'extension "Webo-facto" pour lier votre site WordPress à votre espace de travail dans le webo-facto.

== Description ==
Cette extension permet de lier votre site WordPress avec votre espace de travail dans le webo-facto. Le webo-facto est un gestionnaire d'activité digitale rassemblant les outils nécessaires à la création, l'hébergement et la maintenance de tous vos projets web en une seule interface (<https://www.webo-facto.com>).

L'extension “webo-facto” vous permet:
- Une connexion centralisée : Authentifiez-vous automatiquement à l'interface de votre site WordPress à l'aide de votre compte webo-facto, un compte administrateur WordPress est automatiquement créé
- La récupération de la version de WordPress : Récupérez automatiquement, dans le webo-facto, la version WordPress de votre site
- La récupération de l'URL d'authentification : Bénéficiez, dans le webo-facto, d'un lien pointant directement sur l'interface d'administration de votre site WordPress
- Une collaboration sécurisée sur votre projet : Gérez les accès à votre projet depuis le webo-facto

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/webo-facto-connector` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

== Screenshots ==
1. Suite à l'installation de l'extension, une action "Accéder à WordPress" est disponible pour votre fiche projet du webo-facto.
2. Les accès à votre projet WordPress sont définis depuis le webo-facto

== Changelog ==

= 1.37 =
Permettre la reconnexion si admin est déjà connecté

= 1.36 =
Correction url si utilisateur déjà connecté

= 1.35 =
Renforcement complémentaire pour sécurisation du plugin

= 1.34 =
Renforcement sécurisation du plugin

= 1.33 =
Prise en compte url de connexion du genre domain/xxxx/wp-admin
Si authentification page protégé par mot de passe on ne fait pas d'authentification SSO
Si utilisateur déjà connecté on ne fait pas d'authentification SSO

= 1.32 =
Compatibilité avec WordPress 6.1.1
Correction bug d'authentification depuis d'autres espaces de travail

= 1.31 =
Compatibilité avec WordPress 6.1

= 1.30 =
Validation de l'extension pour WordPress 6.1

= 1.29 =
Correction message d'erreur str_pos lors de la deconnexion woocommerce
Modification condition d'authentification pour la ligne 124 L'authentification webo-facto est en cours


= 1.28 =
Correction bug pour la compatibilité multisite


= 1.27 =
Validation de l'extension pour WordPress 6.0.1
Correction de bug sur la déconnexion d'un compte woocommerce
Correctif pour compatibilité multisite

= 1.26 =
Optimisation de la connexion SOAP avec le webo-facto
Ajout d'une URL permettant la mise à jour du numéro de version et de l'URL d'authentification dans le webo-facto
Validation de l'extension pour WordPress 6.0.0


= 1.25 =
Correction des notices qui créent des bugs sur plusieurs sites (ajout condition)

= 1.24 =
Correction des notices qui créent des bugs sur plusieurs sites

= 1.23 =
Correction permettant l'authentification SSO pour les URL de connexion personnalisés
Correction pour ne pas prendre en compte la traduction du genre ?lang=en lors de l'authentification SSO
Validation de l'extension pour WordPress 5.9.2

= 1.22 =
Correction des warnings qui empêchent la redirection vers la bonne page après déconnexion

= 1.21 =
Correction du bug lors de la réinitialisation du mot de passe.

= 1.20 =
Validation de l'extension pour WordPress 5.8.

= 1.19 =
Optimisation du code PHP (suppression d'erreurs de type Notice).

= 1.18 =
Validation de l'extension pour WordPress 5.7.

= 1.17 =
Correction du comportement de l'extension par rapport à l'action de rappel du mot de passe.

= 1.16 =
Validation de l'extension pour WordPress 5.6.

= 1.15 =
Correction de la vérification des droits avant tentative d'authentification SSO (droits spécifiques)

= 1.14 =
Validation de l'extension pour WordPress 5.5.

= 1.13 =
Optimisation du code PHP (suppression d'erreurs de type Notice et Deprecated).
Optimisation de l'affichage des erreurs sur l'appel SOAP.

= 1.12 =
Validation de l'extension pour WordPress 5.4.

= 1.11 =
Correction d'un Warning PHP.

= 1.10 =
Certaines URLs utilisées pour se logguer à WordPress n'étaient pas correctement prises en compte (plugin de sécurité).

= 1.9 =
Modification du système de cryptage pour le support des versions PHP 7.1 et supérieures.

= 1.8 =
Certaines actions réalisées par un contributeur pouvaient ne pas fonctionner correctement.

= 1.7 =
Depuis le site public, les appels à l'action "admin-ajax.php" pouvaient ne pas fonctionner.

= 1.6 =
Lors d'une déconnexion d'un administrateur créé depuis WordPress, la déconnexion du webo-facto était invoquée.

= 1.5 =
Depuis le site public, les appels à l'action "admin-ajax.php" pouvaient ne pas fonctionner.

= 1.4 =
Certains plugins dédié à la sécurisation de WordPress pouvaient bloquer l'authentification SSO.

= 1.3 =
La connexion SSO pouvait empêcher l'authentification native de WordPress de fonctionner

= 1.2 =
Ajout d'un autoloader et encapsulation des appels au framework de WordPress

= 1.1 =
Gestion de l'activation et de la désactivation du plugin.

= 1.0 =
Authentification SSO avec le webo-facto
