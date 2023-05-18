<?php

declare(strict_types=1);

namespace Phant\Client\Port;

interface MySQL
{
    public function execute(
        string $query,
        array $values = []
    ): void;

    public function getList(
        string $query,
        array $values = []
    ): array;

    public function getListByColumnValue(
        string $table,
        string $column,
        string|int|float|bool $value
    ): array;

    public function get(
        string $query,
        array $values = []
    ): array;

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
