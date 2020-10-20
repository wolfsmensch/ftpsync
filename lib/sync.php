<?php

class Sync 
{
    private string $ftp_addr;
    private string $ftp_user;
    private string $ftp_pass;

    public function __construct(string $addr, string $user, string $pass)
    {
        $this->ftp_addr = trim( $addr );
        $this->ftp_user = trim( $user );
        $this->ftp_pass = trim( $pass );
    }

    /**
     * Синхронизация файлов
     */
    public function sync( string $localFile, string $remoteFile ) : void
    {
        // Проверка даты изменения и наличия файла
        // Локальный файл
        if ( !file_exists( $localFile ) )
        {
            Log::Error( 'Не найден локальный файл (' . $localFile . ')' );
            return;
        }

        $localFileCTime = filectime( $localFile );

        if ( ( gettype( $localFileCTime ) == "boolean" ) && ( $localFileCTime === FALSE ) )
        {
            Log::Error( 'Не удалось определить время изменения локального файла (' . $localFile . ')' );
            return;
        }

        // Удаленный файл
        $FtpConnection = NULL;

        try {
            $FtpConnection = FTP::getConnection( $this->ftp_addr, $this->ftp_user, $this->ftp_pass );
        } catch (\Throwable $th) {
            Log::Error( 'Ошибка FTP-подключения: ' . $th->getMessage() );

            return;
        }

        if ( ftp_chdir( $FtpConnection, static::getFileDir( $remoteFile ) ) === FALSE )
        {
            Log::Error( 'Не удалось сменить удаленную директорию на: ' . static::getFileDir( $remoteFile ) );
            return;
        }
        else
            Log::Info( 'Удаленная рабочая директория изменена на: ' . static::getFileDir( $remoteFile ) );
        
        if ( FTP::fileExists( $FtpConnection, static::getFileName( $remoteFile ) ) === FALSE )
        {
            Log::Error( 'Удаленный файл (' . static::getFileName( $remoteFile ) . ') не найден на сервере' );
            return;
        }

        $remoteFileCTime = ftp_mdtm( $FtpConnection, static::getFileName( $remoteFile ) );
        if ( $remoteFileCTime < 0 )
        {
            Log::Error( 'Не удалось получить время модификации удаленного файла (' . static::getFileName( $remoteFile ) . ')' );
            return;
        }

        // TODO: Сравнить время модификации и обновить нужный файл, в зависимости от того, на какой стороне файл новее
    }

    private static function getFileDir( string $filePath )
    {
        return dirname( $filePath );
    }

    private static function getFileName( string $filePath )
    {
        return basename( $filePath );
    }
}

?>