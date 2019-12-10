<?php

namespace app\base;

/**
 * IDatabase интерфейс для работы с БД.
 *
 * @package base
 */
interface IDatabase
{
    /**
     * Возвращает идентификатор последней операции по вставке.
     *
     * @return mixed
     */
    public function lastInsertId();

    public function select(string $tableName, array $condition = [], array $toSelect = ['*']): ?array;

    public function insert(string $tableName, array $values): bool;

    public function update(string $tableName, array $values, array $condition): bool;

    public function delete(string $tableName, array $condition): bool;

    /**
     * Старт транзакции.
     *
     * @return bool признак успешности операции
     */
    //public function beginTransaction();

    /**
     * Конец транзакции.
     *
     * @return bool признак успешности операции
     */
    //public function commitTransaction();

    /**
     * Откат транзакции.
     *
     * @return bool признак успешности операции
     */
    //public function rollbackTransaction();
}
