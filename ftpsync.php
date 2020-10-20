<?php

// Подключение библиотек
require_once( 'lib/log.php' );
require_once( 'lib/ftp.php' );
require_once( 'lib/sync.php' );

// Подключение конфигурационного файла
if ( !file_exists( 'config.php' ) )
{
    Log::Error( 'Configuration file (config.php) not found' );
    die();
}
else
    require_once( 'config.php' );

$syncObj = new Sync( FTP_ADDR, FTP_USER, FTP_PASS );
$syncObj->sync( LOCAL_FILE, REMOTE_FILE );

?>