<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 30.06.2018 12:40
 */

if (!isset($title)) {
    $title = 'cnde.ru';
}
if (!isset($description)) {
    $description = 'сайт cnde.ru';
}
if (!isset($keywords)) {
    $keywords = 'cnde.ru';
}

?>

<!doctype html>
<html lang="ru">
<head>
    <title><?= $title ?></title>
    <meta charset="utf-8">
    <meta name="description" content="<?= $description ?>">
    <meta name="keywords" content="<?= $keywords ?>">
    <meta name="author" content="angryjack">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css">
    <link rel="stylesheet" href="/web/css/main.css">

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="/web/js/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.13.1/lodash.min.js"></script>
</head>
<body>