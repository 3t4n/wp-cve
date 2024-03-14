<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'zendesk_chat_wordpress');

/** MySQL database username */
define('DB_USER', 'admin');

/** MySQL database password */
define('DB_PASSWORD', '123456');

/** MySQL hostname */
define('DB_HOST', '192.168.42.45');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '-`O_A[!v.awGv(9o QV=+wrf q&U|UM9^95[/x*_N-p;L10O@B ]L5cd6IE:-AuF');
define('SECURE_AUTH_KEY',  'pM`J(1,}kMsc1%t=_o,)e9gdU!Je=Yo*pE7`OK-*6B$XA*W1:?)Ue<Y7ZH_:zq[D');
define('LOGGED_IN_KEY',    'b;[r+g/o&hRM5Px~z;H+d-(Q/FKG:r6q~;$Kf-.XmKJhi*u:)?B<J!_+xQcPBYmH');
define('NONCE_KEY',        'T8<FlIxY8;x,RU@LWQE %;xZnA1Wb3Oj#2k9G7Q7LEuefJhXKuExQO^c=_0)pUit');
define('AUTH_SALT',        '.L+75<KY{`Gk7tw7g16k4:X$mBG0`[4SXbW#5PVA!]-IPw)JxZBExD9G&paV$_Ha');
define('SECURE_AUTH_SALT', '8xJJ3{9v<ocq*M2^F*aA#j@kf@,5z6V<c|Cq{uQNc2m^Ua.GG4-$w+M$K{ZFO>dJ');
define('LOGGED_IN_SALT',   '_B`,j5VPpzIFll<jC!#F9DFf1);[@4]q5gP&sx`/B?/&RQ:;m65<;>JN1cXi{#_.');
define('NONCE_SALT',       'rcg2@S%WIk:|U@/,?2<k32cG7<On;<ARs<[.Q<7#,^`.UjU^n*Gl@vR,1T!YkC<Z');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
