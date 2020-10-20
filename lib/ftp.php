<?php

class FTP
{
    public static function getConnection( string $addr, string $user, string $pass )
    {
        Log::Info( 'Подключение к FTP-серверу: ftp://' . $this->ftp_addr );

        $connFTP = ftp_connect( $this->ftp_addr );
        if ( ( gettype( $connFTP ) == "boolean" ) && ( $connFTP === FALSE ) )
            throw new Exception( 'Не удалось подключитсья' );

        if ( ftp_login( $connFTP, $user, $pass ) === FALSE )
            throw new Exception( 'Ошибка авторизации' );
        
        return $connFTP;
    }

    public static function fileExists( $ftpStream, string $filePath )
    {
        return ( ftp_size( $ftpStream, $filePath ) > (-1) );
    }
}

?>