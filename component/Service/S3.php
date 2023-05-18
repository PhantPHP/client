<?php

declare(strict_types=1);

namespace Phant\Client\Service;

use Aws\S3\S3Client;
use Phant\Error\NotFound;

class S3 implements \Phant\Client\Port\S3
{
    protected S3Client $S3Client;

    public function __construct(
        string $region,
        string $endpoint,
        string $accessKey,
        string $secretKey,
        string $version = '2006-03-01'
    ) {
        $this->S3Client = new S3Client([
            'region' => $region,
            'endpoint' => $endpoint,
            'credentials' => [
                'key'		=> $accessKey,
                'secret'	=> $secretKey,
            ],
            'version' => $version,
            'use_path_style_endpoint' => true
        ]);
    }

    public function setObject(
        string $bucket,
        string $key,
        string $body
    ): void {
        $this->S3Client->putObject([
            'Bucket'	=> $bucket,
            'Key'		=> $key,
            'Body'		=> $body,
        ]);
    }

    public function getObject(
        string $bucket,
        string $key
    ): string {
        try {
            $object = $this->S3Client->getObject([
                'Bucket'	=> $bucket,
                'Key'		=> $key,
            ]);

            return $object['Body']->getContents();
        } catch (\Exception $e) {
            throw new NotFound('Object : ' . $key);
        }
    }

    public function deleteObject(
        string $bucket,
        string $key
    ): void {
        try {
            $this->S3Client->deleteObject([
                'Bucket'	=> $bucket,
                'Key'		=> $key,
            ]);
        } catch (\Exception $e) {
            throw new NotFound('Object : ' . $key);
        }
    }
}
