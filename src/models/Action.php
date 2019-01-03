<?php
/**
 * Created by angryjack
 * Date: 2018-12-23 15:26
 */

namespace Angryjack\models;

interface Action
{
    /**
     * Вывести список элементов
     * @param int $page - страница
     * @return array|null
     */
    public function getAll($page = 1) :? array;

    /**
     * Показать конкретный элемент
     * @param $id
     * @return array|null
     */
    public function get($id) :? object;

    /**
     * Создать
     * @param array $data
     * @return bool
     */
    public function create(array $data) : bool;

    /**
     * Отредактировать
     * @param $id
     * @param array $data
     * @return bool
     */
    public function edit($id, array $data) : bool;

    /**
     * Удалить
     * @param $id
     * @return bool
     */
    public function delete($id) : bool;

    /**
     * Поиск
     * @param array $data
     * @return array|null
     */
    public function search(array $data) :? array;
}
