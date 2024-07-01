<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pallmall_dtb' );

/** Database username */
define( 'DB_USER', 'pallmall_dtb' );

/** Database password */
define( 'DB_PASSWORD', 'pallmall_dtb' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ED#U&a?x_&eO/`Rw%.kE<Smg;e zQjGDs%Jtt.,l-rdsLeLD&bQjUKaEt~L6*LnJ' );
define( 'SECURE_AUTH_KEY',  'F[n#jS/$57Y=xqWoO5Ry1#tlI?}t+g4}w]#`c4=j^3+Rq^d();;C3}OC[ttlKP4#' );
define( 'LOGGED_IN_KEY',    'fF=7#8S!]h4.)bETB^7R3mMTd;+.uHs RzvR$YX3ot`p&*J>lM^VA@B0 QF?y:Hv' );
define( 'NONCE_KEY',        '<,it|=gxcYDlSgQisUmCKpKO{nKaxNXLi/)=sDnB/WMEL=r)Ke4ha]}s_/56dg%%' );
define( 'AUTH_SALT',        'Vv`KTN;TnSVl0 W$J:OC]KDosVYCk%R?8w]ckrik{$c9Mz&a9efJtN!+(TCmfk2&' );
define( 'SECURE_AUTH_SALT', '!5tx~k#=c/aUG]b$e[s%r_^T~#;?A}:ypWfS_nz43qw]LLEg2`ybV,|R$g|)ZyU|' );
define( 'LOGGED_IN_SALT',   't@O]6i]$q|_y3XM(,3S68(@}$b$M7~g? RN{3x7mu;W!=&=..,?Xv)y!|E=W;NiZ' );
define( 'NONCE_SALT',       'uU)_=93mQdl%AZ~~KHy4r%!ZylT.JeIK&E`R0=I;M4y4MgkWbME6`s(s%}[ :vf+' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
