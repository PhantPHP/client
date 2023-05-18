<?php

declare(strict_types=1);

namespace Phant\Client\Service\S3;

use Phant\Client\Service\S3;

class Object implements \Phant\Client\Port\S3\Object
{
    public function __construct(
        protected readonly S3 $client,
        protected readonly string $bucket
    ) {
    }

    public function set(
        string $key,
        string $body
    ): void {
        $this->client->setObject(
            $this->bucket,
            $key,
            $body,
        );
    }

    public function get(
        string $key
    ): string {
        return $this->client->getObject(
            $this->bucket,
            $key,
        );
    }

    public function delete(
        string $key
    ): void {
        $this->client->deleteObject(
            $this->bucket,
            $key,
        );
    }
}
