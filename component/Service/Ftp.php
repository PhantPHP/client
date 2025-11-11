<?php

declare(strict_types=1);

namespace Phant\Client\Service;

use FTP\Connection;
use Phant\Client\Port\Ftp\Exception\FtpClientException;
use Phant\Client\Port\Ftp\Exception\FtpConnectionException;
use Phant\Client\Port\Ftp\Exception\FtpLoginException;

/**
 * FTP Service for interacting with FTP servers.
 */
class Ftp implements \Phant\Client\Port\Ftp
{
    private Connection $connection;

    /**
     * @throws FtpConnectionException
     * @throws FtpLoginException
     */
    public function __construct(
        string $host,
        string $username,
        string $password,
        int $port = 21,
        bool $passiveMode = false
    ) {
        $this->connection = $this->connect(
            host: $host,
            username: $username,
            password: $password,
            port: $port
        );

        ftp_pasv($this->connection, $passiveMode);
    }

    private function connect(string $host, string $username, string $password, int $port): Connection
    {
        $ftp = ftp_connect($host, $port);
        if (!$ftp) {
            throw new FtpConnectionException($host);
        }

        $login = ftp_login($ftp, $username, $password);
        if (!$login) {
            throw new FtpLoginException($host);
        }

        return $ftp;
    }

    /**
     * @throws FtpClientException
     */
    public function listFiles(string $path): array
    {
        $list = ftp_nlist($this->connection, $path);
        if ($list === false) {
            throw new FtpClientException(error_get_last()['message'] ?? 'Unknown error during FTP listing');
        }

        $files = array_filter($list, fn ($resource) => !is_dir($resource));

        return $files;
    }

    /**
     * @throws FtpClientException
     */
    public function listFolders(string $path): array
    {
        $list = ftp_nlist($this->connection, $path);
        if ($list === false) {
            throw new FtpClientException(error_get_last()['message'] ?? 'Unknown error during FTP listing');
        }

        $folders = array_filter($list, fn ($resource) => is_dir($resource));

        return $folders;
    }

    /**
     * @throws FtpClientException
     */
    public function listAll(string $path): array
    {
        $list = ftp_nlist($this->connection, $path);
        if ($list === false) {
            throw new FtpClientException(error_get_last()['message'] ?? 'Unknown error during FTP listing');
        }

        if (false === $list) {
            return [];
        }

        return $list;
    }

    /**
     * Download a file from the FTP server. You can specify a local directory to save the file. If no directory is provided, the file will be saved in the system's temporary directory.
     * @param string $remotePath
     * @param ?string $localDirectory
     * @return string
     * @throws FtpClientException
     */
    public function download(string $remoteFilePath, ?string $localDirectory = null): string
    {
        if ($localDirectory) {
            $localFilePath = rtrim($localDirectory, '/') . '/' . basename($remoteFilePath);
        } else {
            $localFilePath = sys_get_temp_dir() . '/' . basename($remoteFilePath);
        }

        if (!is_dir(dirname($localFilePath))) {
            throw new FtpClientException("Local directory does not exist: " . dirname($localFilePath));
        }

        if (!ftp_get($this->connection, $localFilePath, $remoteFilePath, FTP_BINARY)) {
            throw new FtpClientException("Failed to download file from FTP: {$remoteFilePath}. Error: " . (error_get_last()['message'] ?? 'Unknown error'));
        }

        return $localFilePath;
    }

    /**
     * Delete a file from the FTP server.
     * @param string $path
     * @return bool
     * @throws FtpClientException
     */
    public function delete(string $path): bool
    {
        if (!ftp_delete($this->connection, $path)) {
            throw new FtpClientException("Failed to delete file from FTP: {$path}");
        }

        return true;
    }

    public function __destruct()
    {
        ftp_close($this->connection);
    }
}
