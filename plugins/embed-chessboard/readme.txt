=== Embed Chessboard ===
Contributors: pgn4web
Donate link: http://pgn4web.casaschi.net
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 2.9
Stable tag: 3.06.00
Tags: chess, chessboard, pgn, pgn4web
Tested up to: 6.5

Allows for the insertion of a chessboard displaying chess games within wordpress articles.

== Description ==

Embed Chessboard is a plugin that allows for the insertion of a chessboard displaying chess games within worpress articles.

Use following shortcode tag to insert a chessboard:

    [pgn parameter=value ...]
    ... chess games in PGN format ...
    [/pgn]

Shortcode tag parameters:

 * layout=horizontal|vertical
 * height=auto|*number*
 * showMoves=figurine|text|puzzle|hidden
 * initialGame=first|last|random|*number*
 * initialVariation=*number*
 * initialHalfmove=start|end|random|comment|*number*
 * autoplayMode=game|loop|none

Example:

    [pgn height=500 initialHalfmove=16 autoplayMode=none]
    
    [Event "World championship"]
    [Site "Moscow URS"]
    [Date "1985.10.15"]
    [Round "16"]
    [White "Karpov"]
    [Black "Kasparov"]
    [Result "0-1"]
    
    1. e4 c5 2. Nf3 e6 3. d4 cxd4 4. Nxd4 Nc6 5. Nb5 d6 6.
    c4 Nf6 7. N1c3 a6 8. Na3 d5 9. cxd5 exd5 10. exd5 Nb4
    11. Be2 Bc5 12. O-O O-O 13. Bf3 Bf5 14. Bg5 Re8 15.
    Qd2 b5 16. Rad1 Nd3 17. Nab1 h6 18. Bh4 b4 19. Na4 Bd6
    20. Bg3 Rc8 21. b3 g5 22. Bxd6 Qxd6 23. g3 Nd7 24. Bg2
    Qf6 25. a3 a5 26. axb4 axb4 27. Qa2 Bg6 28. d6 g4 29.
    Qd2 Kg7 30. f3 Qxd6 31. fxg4 Qd4+ 32. Kh1 Nf6 33. Rf4
    Ne4 34. Qxd3 Nf2+ 35. Rxf2 Bxd3 36. Rfd2 Qe3 37. Rxd3
    Rc1 38. Nb2 Qf2 39. Nd2 Rxd1+ 40. Nxd1 Re1+ 0-1
    
    [/pgn]

When using the gutenberg editor, please add shortcodes as shortcodes blocks in guntenberg.

Any PGN header tag missing will not be displayed.

Note: HTML tags are stripped from the PGN data, removing all text between "<" and ">" characters; please make sure your PGN data does not contain "<" and ">" characters.

== Installation ==

Reccomended installation method is from the plugins section of the administration pages of your site, serching for the "Embed Chessboard" plugin.

Alternative manual install option:

1. Download the Embed Chessboard plugin package [from the Wordpress plugin directory](https://wordpress.org/plugins/embed-chessboard/) or [from the pgn4web project site](http://pgn4web-downloads.casaschi.net)
1. Unzip the plugin package to the '/wp-content/plugins' directory of your site
1. Activate the plugin from the wordpress.org administration pages of your site

You can find full details of installing a plugin on the [plugin installation page](https://wordpress.org/support/article/managing-plugins/).

The chessboard plugin can be configured by the site administrator from the "Embed Chessboard" submenu in the administrator "Settings" menu; here the horizontal or vertical position of the header/moves text respect to the chessboard can be configured, the colors of the chessboard can be changed to match the site template and the autoplay mode can be set (whether the chess games should be autoplayed on page load).

The plugin is based on the [pgn4web](http://pgn4web.casaschi.net) tool; for more information, including updated troubleshooting notes, please refer to [the plugin tutorial at the pgn4web wiki](http://pgn4web-project.casaschi.net/wiki/User_Notes_wordpress/).

== Screenshots ==

1. chessboard with basic chess notation
2. chessboard with chess informant notation
3. chessboard with chess puzzle

