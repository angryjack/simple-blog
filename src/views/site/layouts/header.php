<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 30.06.2018 12:40
 */
?>

<!doctype html>
<html lang="ru">
<head>
    <title><?= isset($title) ? htmlspecialchars($title) : 'cnde.ru' ?></title>
    <meta charset="utf-8">
    <meta name="description" content="<?= isset($description) ? htmlspecialchars($description) : 'Сайт с заметками о php, mysql, js, css и linux' ?>">
    <meta name="keywords" content="<?= isset($keywords) ? htmlspecialchars($keywords) : 'Заметки по php; mysql; js; css; linux' ?>">
    <meta name="author" content="angryjack">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/web/css/main.css">

    <script src="/web/js/build.js"></script>
    <script src="/web/js/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>