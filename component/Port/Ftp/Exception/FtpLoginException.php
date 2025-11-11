<?php

declare(strict_types=1);

namespace Phant\Client\Port\Ftp\Exception;

class FtpLoginException extends FtpException
{
    public function __construct(string $host)
    {
        parent::__construct("Failed to log in to FTP server: {$host}");
    }
}
