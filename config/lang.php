<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 01.07.2018 15:03
 */

//Сообщения сайта
define('ERROR_DATABASE_CONNECTION', 'Ошибка подключения к базе данных');

// Сообщения админ панели
define('ACCESS_DENIED', 'Доступ запрещен');
define('EMPTY_LOGIN_FIELDS', 'Заполните поля логина и пароля');
define('AUTH_ERROR', 'Неправильно указан логин или пароль');

define('CREATE_USER_ERROR_INVALID_EMAIL_FORMAT', 'Неправильный формат электронно почты!');
define('CREATE_USER_ERROR_USER_ALREADY_EXIST', 'Пользователь с таким логином уже существует!');

define('NEWS_CREATE_SUCCESS', 'Новость успешно добавлена');
define('NEWS_CREATE_ERROR', 'Произошла ошибка при добавлении новости');
define('NEWS_EDIT_SUCCESS', 'Новость успешно отредактирована');
define('NEWS_EDIT_ERROR', 'Произошла ошибка при редактировании новости');
define('NEWS_DELETE_SUCCESS', 'Новость успешно удалена');
define('NEWS_DELETE_ERROR', 'Произошла ошибка при удалении новости');
define('NO_NEWS', 'Список новостей пуст!');
define('NEWS_EMPTY_FIELDS', 'Заполните все поля!');
define('NEWS_CREATE_ERROR_TITLE_LENGTH', 'Заголовок не может быть короче 2-х символов!');
define('NEWS_CREATE_ERROR_CONTENT_LENGTH', 'Содержание не может быть короче 2-х символов!');
define('NEWS_NO_ID_SELECTED', 'Не указан ID новости');

define('NO_CATEGORIES', 'Список категорий пуст!');
define('CATEGORY_CREATE_SUCCESS', 'Категория успешно добавлена');
define('CATEGORY_CREATE_ERROR', 'Ошибка создания категории!');
define('CATEGORY_EDIT_SUCCESS', 'Категория успешно отредактирована');
define('CATEGORY_EDIT_ERROR', 'Ошибка редактирования категории');
define('CATEGORY_DELETE_SUCCESS', 'Новость успешно удалена');
define('CATEGORY_DELETE_ERROR', 'Произошла ошибка при удалении новости');
define('CATEGORY_CREATE_ERROR_TITLE_LENGTH', 'Заголовок не может быть короче 2-х символов!');
define('CATEGORY_NO_ID_SELECTED', 'Не указан ID категории');
