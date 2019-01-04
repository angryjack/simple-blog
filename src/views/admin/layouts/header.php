<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 30.06.2018 12:40
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/public/css/admin.css">
    <link rel="stylesheet" href="/public/css/main.css">
    <title>Admin panel</title>
    <script src="/public/js/build.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#">Админ панель</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="/admin/article">Статьи</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/category">Категории</a>
            </li>
        </ul>
        <ul class="navbar-nav my-2 my-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="/" target="_blank">На сайт</a>
            </li>
            <li class="nav-item">
                <strong><a class="nav-link" href="/admin/login">Выйти</a></strong>
            </li>
        </ul>
    </div>
</nav>