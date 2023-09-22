<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
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
define( 'DB_NAME', 'voltgit' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'sh$j?Hmg Uuus(_v@lylMR%~s%Nz6LTY>v#s=<FSs`KB=Y17m7V^i&a]^sLP=#S[' );
define( 'SECURE_AUTH_KEY',  'g&oXLdD_ a0V$hZA.#[RkLQ=qL2,3KWIw{?T#AL4;[^ XICR!_tMc U @`L[RF^R' );
define( 'LOGGED_IN_KEY',    'HI 30IxS}i#.4OEuviH@J;U P7}rO)XL]Fqi=7@wj4IfTDR>c#j?/x*sY-5!1Jon' );
define( 'NONCE_KEY',        '.)XXt:lKO,4d:>W$/I{4>;7b#-lGP-8Z;f:o0fZb^8cm(dIWB*pCy2*RpRf`x=B%' );
define( 'AUTH_SALT',        'e^]t?e`jy%%3k!BdSM~wGF9{@4;_n:#IE6:YQY]N!u2t6yG)*W($`Vv)0EJ?u]bz' );
define( 'SECURE_AUTH_SALT', 'WvqC(,L0Z^1>k[< +(}.o:KU]z]~!?Bz/zmUAzEk/Bm2%=2_k,=DmA[v_RjXG^05' );
define( 'LOGGED_IN_SALT',   ' <i4H[|n}<,({GPc}= kW}e!zkJ/y#YI0juyjDr0tZ{E#9uOC-g5&Oe:2 s,L@4P' );
define( 'NONCE_SALT',       '!2?#+P0v/^b9D`Dt8]|o(2N:nMn/gL2J=@28A+$z3tXaxDnOj()_1R8`C{Lw^:|>' );

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
