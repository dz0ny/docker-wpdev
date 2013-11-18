<?php

// ===================================================
// Load database info and local development parameters
// ===================================================


define( 'DB_NAME', 'test' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'docker' );
define( 'DB_HOST', 'localhost' ); // Probably 'localhost'

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', '/srv/wordpress/content' );
define( 'WP_CONTENT_URL', 'http://localhost/content' );
define( 'UPLOADS',        'http://localhost/uploads' );
define( 'WP_PLUGIN_URL',  'http://localhost/content/plugins' );

define( 'COMPRESS_CSS',        false );
define( 'COMPRESS_SCRIPTS',    false );
define( 'CONCATENATE_SCRIPTS', false );
define( 'ENFORCE_GZIP',        false );

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define('AUTH_KEY',         'rs.xsa&N 3.|2gK{mFq]Ib=|9=l+2b{ YHtzpv:f~FwQIztb&n5Jk^(f.NyFn{#q');
define('SECURE_AUTH_KEY',  'mhQ*`5&3|r|PrTpHHQ&q%}{Egl[`;enp^&i%evD^Y-=K;W&*axCXkIqO<dhpY*D9');
define('LOGGED_IN_KEY',    'DQhGF;P4A2Kks! +Ck{w_@qQ*i]^a:P#^IWre,k*-UuR<K9JkE?btmj8`n?0K(%Y');
define('NONCE_KEY',        'Ui~ip]B)} TEpA6yE_ 8-Q+OSQr6)|6TD!rm4~n-q0jDGx03+AZqs^FgzQ{6Hcc9');
define('AUTH_SALT',        'o{8>4mP5_o[(V04?rJt]+OVKNjGdz,=!OvhkslI>VW7+r-+P S3TkeC*+9DeB=,3');
define('SECURE_AUTH_SALT', '_?Um%k8c>awaJqHHZas+`zddWrI<9(q<lvbjNKgD]&VnJ,}7RUq7qKYD>jMl77=,');
define('LOGGED_IN_SALT',   'o*^RF9CcY_EoB-{f1rC;~7}cSwYs4)y#3W6Q,>+FVkm<O>f+F>GJ]p}pCoL:RQ1~');
define('NONCE_SALT',       '%--;9{~Ic,+Ppkz$h~q}p?UA ZnD&u8|k%< voWI)eO6Rx3o_=wySh:=.DVo0IJ4');

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================


define( 'WP_DEBUG',         true );
define( 'WP_DEBUG_LOG',     true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG',     false );
define( 'SAVEQUERIES',      false );

// ===================
// Bootstrap WordPress
// ===================
define( 'ABSPATH', '/srv/wordpress/' );
require_once( ABSPATH . 'wp-settings.php' );
