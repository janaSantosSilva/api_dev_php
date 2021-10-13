<?php

require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/config/environment/environment.php');
    
if( PRODUCTION_ENVIRONMENT === 'true' ) {

    define( "DATABASE_HOST", "" );
    define( "DATABASE_PORT", "" );
    define( "DATABASE_USER", "" );
    define( "DATABASE_PASSWORD", "" );
    define( "DATABASE_NAME", "" );
    define( "DATABASE_TYPE", "" );

    error_reporting(0);
    ini_set('display_errors', false);

} else {

    define( "DATABASE_HOST", "localhost" );
    define( "DATABASE_PORT", "3306" );
    define( "DATABASE_USER", "root" );
    define( "DATABASE_PASSWORD", ".panda123" );
    define( "DATABASE_NAME", "api_dev" );
    define( "DATABASE_TYPE", "mysql" );

    error_reporting(E_ALL);
    ini_set('display_errors', true);

}