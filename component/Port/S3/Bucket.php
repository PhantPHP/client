<?php

declare(strict_types=1);

namespace Phant\Client\Port\S3;

interface Bucket
{
    public function set(
        string $key,
        string $body
    ): void;

    public function get(
        string $key
    ): string;

    public function delete(
        string $key
    ): void;
}
