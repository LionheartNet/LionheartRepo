<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'fishpooDB9dpdp');

/** MySQL database username */
define('DB_USER', 'fishpooDB9dpdp');

/** MySQL database password */
define('DB_PASSWORD', 'S8bjh5Ppmv');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         ']aO;]+eO51xtdO9]wtZW^$jfQI{yubXEA<.qmTP62*$ifLI;{yubXEA<.qmTP6VC');
define('SECURE_AUTH_KEY',  'xSD9#_plSO52~+heKH9#_plSD51~-hdKG:[wsZ3^$jfMI{yubXEA<.qmTP6;*+ie');
define('LOGGED_IN_KEY',    '7@nYFB3,rnUQ73^$jfMFB>,vbYEB<,rnUQ73{-hdKG8|!okRN40@zgcJG}[vsZVC8');
define('NONCE_KEY',        '7vfcUB7_~wdZGC5_~ldZGC5!~khZGC[|@khNGC[|skgNJC[|skgNJCmiaHD6.*mea');
define('AUTH_SALT',        'Vd[u$yfbIA6.*yebHA6.+xeaHA6.*xeaH96.*xeaH@zgcUB70$zrYUM3>vrYQM3');
define('SECURE_AUTH_SALT', 'B@nUQ73^$jfMI{yvbYEB>,rnUQ73^$jf[|soVR84!@khNK0:zwoVR84!@kgNJ0}z');
define('LOGGED_IN_SALT',   'B<yjfXE{yubXEA<.qmTPI;{+ieLH;]xscVC8|@zgcJB8,!zgcJB7,@zgcJB7,^zf');
define('NONCE_SALT',       '!4g@nUQ73eaHD]#tpWS95_~lhOK1:-wdZGC[|soZGC[|soVR84!O51~plSO51~-hd');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'j11x3v7_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define('FS_METHOD', 'direct');


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
