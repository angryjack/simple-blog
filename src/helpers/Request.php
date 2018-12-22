<?php
/**
 * Created by angryjack
 * Date: 2018-12-22 16:54
 */

namespace Angryjack\helpers;

trait Request
{
    /**
     * Получаем данные из POST
     * @param bool $assoc тип возращаемого значения
     * @return mixed
     */
    public static function getData($assoc = false)
    {
        $data = file_get_contents('php://input');
        return json_decode($data, $assoc);
    }
}
