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
    public function sync() : void
    {
        
    }
}

?>