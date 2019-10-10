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
     * Запрос выбирающий данные.
     *
     * @param string $sql строка запроса SQL
     * @param string[] $values массив значений для подстановки в $sql
     * @return string[] массив с результатами
     */
    //public function rawSelectQuery($sql, $values);

    /**
     * Запрос SQL.
     *
     * @param string $sql строка запроса SQL
     * @param string[] $values массив значений для подстановки в $sql
     * @return resource|bool возвращенный результат или false в случае ошибки
     */
    //public function rawQuery($sql, $values);

    /**
     * Экранирование специальных символов для значений.
     *
     * @param string $val Строка для экранирования.
     * @return string
     */
    //public function quote($val);

    /**
     * Экранирование специальных символов для названий (таблиц, столбцов).
     *
     * @param string $name Строка для экранирования.
     * @return string
     */
    //public function quoteName($name);

    /**
     * Возвращает идентификатор последней операции по вставке.
     *
     * @return mixed
     */
    public function lastInsertId();

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

    /**
     * Возвращает максимальные длины текстовых колонок в таблице.
     *
     * @param string $table название таблицы
     * @param string[] $columns названия колонок
     * @return string[] длина колонок (в символах)
     */
    //public function getTextColumnMaximumLength($table, $columns);

    public function select(string $tableName, array $condition = [], array $toSelect = ['*']): ?array;

    //public function query($query, $params);

    public function insert(string $tableName, array $values): bool;

    public function update(string $tableName, array $values, array $condition): bool;

    public function delete(string $tableName, array $condition): bool;
}
