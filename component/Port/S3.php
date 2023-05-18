<?php

declare(strict_types=1);

namespace Phant\Client\Port;

interface S3
{
    public function setObject(
        string $bucket,
        string $key,
        string $body
    ): void;

    public function getObject(
        string $bucket,
        string $key
    ): string;

    public function deleteObject(
        string $bucket,
        string $key
    ): void;
}
