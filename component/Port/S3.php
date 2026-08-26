<?php

declare(strict_types=1);

namespace Phant\Client\Port;

use Phant\Error\NotFound;

interface S3
{
    public function setObject(
        string $bucket,
        string $key,
        string $body
    ): void;

    /**
     * @throws NotFound
     */
    public function getObject(
        string $bucket,
        string $key
    ): string;

    /**
     * @throws NotFound
     */
    public function deleteObject(
        string $bucket,
        string $key
    ): void;
}
