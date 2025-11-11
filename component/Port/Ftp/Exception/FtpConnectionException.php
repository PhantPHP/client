<?php

declare(strict_types=1);

namespace Phant\Client\Port\Ftp\Exception;

class FtpConnectionException extends FtpException
{
    public function __construct(string $host)
    {
        parent::__construct("Failed to connect to FTP server: {$host}");
    }
}
