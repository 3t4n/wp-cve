=== Better File Editor ===
Contributors: bpetty
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=bryan%40ibaku%2enet&item_name=WordPress%20Better%20File%20Editor%20Plugin
Tags: plugin, theme, editor, code, syntax, highlighter
Version: 2.3.1
Requires at least: 3.9
Tested up to: 4.3
Stable tag: 2.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds line numbers, syntax highlighting, code folding, and lots more to the
theme and plugin editors in the admin panel.

== Description ==

Adds line numbers, syntax highlighting, code folding, and lots more to the
theme and plugin editors in the admin panel.

This plugin replaces the boring, clunky, and difficult to use theme and plugin
editor with a full-featured code editor using the Ajax.org Cloud9 Editor.

Requirements:

* Javascript Enabled (will use default editor if disabled)
* Browsers: Internet Explorer 9+, Chrome 21+, Firefox 10+, Safari 6.0+

Included Themes:

* Ambiance
* Chaos
* Chrome
* Clouds
* Clouds Midnight
* Cobalt
* Crimson Editor
* Dawn
* Dreamweaver
* Eclipse
* GitHub
* idleFingers
* IPlastic
* KatzenMilch
* krTheme
* Kuroir
* Merbivore
* Merbivore Soft
* Mono Industrial
* Monokai
* Pastel on Dark
* Solarized Dark
* Solarized Light
* SQL Server
* Terminal
* TextMate
* Tomorrow
* Tomorrow Night
* Tomorrow Night Blue
* Tomorrow Night Bright
* Tomorrow Night 80s
* Twilight
* Vibrant Ink
* Xcode

Supported Languages:

* ABAP
* ABC Notation
* ADA
* ActionScript
* AppleScript
* AsciiDoc
* Assembly x86
* AutoHotKey
* BatchFile
* C9Search
* C/C++
* Clojure
* Cobol
* CoffeeScript
* ColdFusion
* C#
* CSS
* Curly
* D
* Dart
* Diff
* Dot
* Elixir
* Elm
* Erlang
* EJS
* Forth
* FreeMarker
* Gherkin
* gitignore
* Glsl
* Go
* Groovy
* HAML
* Handlebars
* Haskell
* haXe
* HTML
* HTML (Ruby)
* Ini
* Jade
* Java
* JavaScript
* JSON
* JSONiq
* JSP
* JSX
* Julia
* LaTeX
* Lean
* LESS
* Liquid
* Lisp
* LiveScript
* LogiQL
* LSL
* Lua
* LuaPage
* Lucene
* Makefile
* MATLAB
* Markdown
* MaskJS
* Maze
* MySQL
* MUSHCode
* Nix
* Objective-C
* OCaml
* Pascal
* Perl
* pgSQL
* PHP
* Powershell
* Prolog
* Properties
* Protobuf
* Python
* R
* RDoc
* RHTML
* Ruby
* Rust
* SASS
* SCAD
* Scala
* Scheme
* SCSS
* SH
* Smarty
* snippets
* Soy
* SQL
* SQLServer
* Stylus
* SVG
* Tcl
* Tex
* Text
* Textile
* Toml
* Twig
* Typescript
* Vala
* VBScript
* Velocity
* XML
* XQuery
* YAML

== Installation ==

1. Upload the entire `better-file-editor` folder to the `/wp-content/plugins/`
   directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. Theme Editor

== Changelog ==

= 2.3.1 (2015-10-18) =

* Use no-conflict builds of ACE editor.
* Remove extraneous RequireJS dependency.
* Add extra namespacing to registered scripts to avoid conflicts.

= 2.3.0 (2015-10-08) =

* Updated ACE editor to 1.2.0 (2015-07-11).
* New plugin structure with asset build system.
* Added support for translations (none provided yet).
* Improved autoindent for HTML and PHP.
* New Themes: IPlastic, SQL Server
* New Modes: Elixir, Elm, LiveScript, SQLServer

= 2.2.0 (2014-10-01) =

* Updated ACE editor to 1.1.7 (2014-09-20).
* New Themes: KatzenMilch, Kuroir
* New Notable Modes: Handlebars, Smarty
* New HTML syntax checker, and improved PHP syntax checker.

= 2.1.2 (2013-12-01) =

* Editor enabled based on support for HTML5 Canvas API, which removed
  dependency on jQuery migrate layer for browser sniffing.

= 2.1 (2013-06-08) =

* Updated ACE editor with new themes and syntax modes.
* New Themes: Ambiance, Chaos, Terminal, Xcode
* New Notable Modes: INI, Dart, ActionScript, MySQL, SASS, and HAML.
* Saves cursor position in file.

= 2.0 (2012-07-22) =

* Complete rewrite built on Ajax.org Cloud9 Editor, and replacing official
  plugin and theme editors rather than adding extra stand-alone editor.

= 1.0.1 (2009-12-19) =

* Include local copy of Bespin editor JS rather than hotlinking from Mozilla.

= 1.0 (2009-06-13) =

* Initial release based on Mozilla's Bespin editor.
