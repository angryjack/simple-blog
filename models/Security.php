<?php

/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 11.07.2018 23:13
 */


class Security
{
    /**
     * Получаем токен пользователя
     * @return string|bool возвращаем токен или false если оного нет
     */
    public static function getToken()
    {
        if (isset($_POST['token'])) {
            if (isset($_POST['token'])) {
                return $_POST['token'];
            }
        }
        return false;
    }

}