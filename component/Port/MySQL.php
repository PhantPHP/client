<?php

declare(strict_types=1);

namespace Phant\Client\Port;

use Phant\Error\NotFound;

interface MySQL
{
    public function execute(
        string $query,
        array $values = []
    ): void;

    /**
     * @throws NotFound
     */
    public function getList(
        string $query,
        array $values = []
    ): array;

    /**
     * @throws NotFound
     */
    public function getListByColumnValue(
        string $table,
        string $column,
        string|int|float|bool $value
    ): array;

    /**
     * @throws NotFound
     */
    public function get(
        string $query,
        array $values = []
    ): array;

    /**
     * @throws NotFound
     */
    public function getByColumnValue(
        string $table,
        string $column,
        string|int|float|bool $value
    ): array;

    public function exist(
        string $query,
        array $values = []
    ): bool;

    public function existByColumnValue(
        string $table,
        string $column,
        string|int|float|bool $value
    ): bool;
}
