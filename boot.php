<?php

/* @var $this rex_addon*/

$dbconfig = rex::getProperty('db');
$DBID = 1;

/**
 * SIMPLE_STATS_DB_SERVER. In most cases, this will be 'localhost'
 */
define( 'SIMPLE_STATS_DB_SERVER', $dbconfig[$DBID]['host'] );

/**
 * SIMPLE_STATS_DB. The name of the database you wish to use with Simple Stats.
 * You can use the same dataase for multiple installations of Simple Stats,
 * provided you use a different table prefix below.
 */ 
define( 'SIMPLE_STATS_DB', $dbconfig[$DBID]['name'] );

/**
 * SIMPLE_STATS_DB_USER: Your database username
 */
define( 'SIMPLE_STATS_DB_USER', $dbconfig[$DBID]['login'] );

/**
 * SIMPLE_STATS_DB_PASS: Your database password
 */
define( 'SIMPLE_STATS_DB_PASS', $dbconfig[$DBID]['password'] );

/**
 * SIMPLE_STATS_DB_PREFIX: The prefix to use for tables in the database.
 */
define( 'SIMPLE_STATS_DB_PREFIX', rex::getTablePrefix() .'simple_stats' );

if (!rex::isBackend()) {
    // prevent "Argument $new is no longer supported in PHP > 7"
    $oldReporting = error_reporting(error_reporting() &~(E_DEPRECATED|E_WARNING|E_USER_WARNING|E_NOTICE));

    try {
        require_once ($this->getPath("vendor/autoload.php"));
        require_once( $this->getPath('vendor/Simple-Stats/stats-include.php') );
    } finally {
        error_reporting($oldReporting);
    }
}