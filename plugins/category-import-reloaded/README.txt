=== Category Import Reloaded ===
Contributors: nir0ma, aurovrata
Tags: category, taxonomy, import, create, bulk
Donate link: https://www.niroma.net
Requires at least: 3.0.1
Tested up to: 5.6.1
Requires PHP: 5.6.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Category Import Reloaded is a wordpress plug-in which allows user to bulk create categories and taxonomies with a custom input format. This plugin is a quick update of the no more maintained "Category Import" plugin originally developped by Jiayu (James) Ji.

== Description ==

Category Import Reloaded is a wordpress plug-in which allows user to bulk create categories and taxonomies with a custom input format.

Plugins allows to :

*  Bulk create categories or taxonomies
*  Specify custom slug delimiter
*  Possibility to specify a custom slug for each categories

Default format for importing is the following :

* Without Slug :

`
Categorie1/subactegorie1

Categorie1/subactegorie2

Categorie1/subactegorie3

Categorie2/subactegorie1

Categorie2/subactegorie2

Categorie2/subactegorie3
`

* Specifying slug :

`
Categorie1$categorie1-slug/subactegorie1$subcategorie1-slug

Categorie1$categorie1-slug/subactegorie2$subcategorie2-slug

Categorie1$categorie1-slug/subactegorie3$subcategorie3-slug

Categorie2$categorie2-slug/subactegorie1$subcategorie1-slug

Categorie2$categorie2-slug/subactegorie2$subcategorie2-slug

Categorie2$categorie2-slug/subactegorie3$subcategorie3-slug
`

== Installation ==

1. Upload `category-import-reloaded.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create categories or taxonomies in bulk using through the `Category Import Reloaded` menu

== Changelog ==

= 1.0.0 =
* Total rewrite of "Category Import" plugin by Jiayu (James) Ji.
* Now support multiple sub categories with the same name (example : Level0/Level1, Level1/Level1).
* Now support taxonomies.

= 1.1.0 =
* Taxonomies support fixed (Was not working in v1.0.0)

= 1.1.1 =
* Mutliples fixes thanks to aurovrata
