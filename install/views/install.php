<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <title>title</title>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.13.1/lodash.min.js"></script>
</head>
<body>

<div class="container align-self-center d-flex justify-content-center">
    <div class="card" style="width: 40rem; margin: 40px 0" id="install-site">
        <div class="card-header d-flex justify-content-between">
            <span>Установка сайта:</span><span>Шаг {{step}}</span>
        </div>
        <div class="card-body" v-if="step === 1">
            <div class="form-group">
                <label for="host">Хост:</label>
                <input type="text" name="host" class="form-control" v-model="host">
            </div>

            <div class="form-group">
                <label for="db-user">Пользователь:</label>
                <input type="text" name="db-user" class="form-control" v-model="dbUser">
            </div>

            <div class="form-group">
                <label for="db-password">Пароль:</label>
                <input type="password" name="db-password" class="form-control" v-model="dbPassword">
            </div>

            <div class="form-group">
                <label for="db-name">База данных:</label>
                <input type="text" name="db-name" class="form-control" v-model="dbName">
            </div>

            <div class="form-group">
                <button class="btn btn-primary" @click="install">Установить</button>
                <button class="btn btn-success" @click="check">Проверить подключение</button>
            </div>
        </div>
        <div class="card-body" v-else-if="step === 2">
            <div class="form-group">
                <label for="user">Пользователь:</label>
                <input type="text" name="host" class="form-control" v-model="user">
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" name="password" class="form-control" v-model="password">
            </div>
            <button class="btn btn-success" @click="createUser">Создать</button>
        </div>

        <div class="card-body" v-else-if="step === 3">
            <p>Установка прошла упешно.</p>
            <p>Теперь Вы можете удалить папку install</p>
            <button class="btn btn-danger" @click="this.delete">Удалить</button>
        </div>

        <div class="card-body" v-else>step 3</div>

        <div class="card-footer">
            <p class="card-text">{{result}}</p>
        </div>
    </div>
</div>

<script>
    let install = new Vue({
        el: '#install-site',
        data: {
            step: 1,
            host: 'localhost',
            dbUser: '',
            dbPassword: '',
            dbName: '',
            result: ''
        },
        methods: {
            check: function () {
                install.result = '';
                if(!this.host || !this.user || !this.password || !this.dbname){
                    this.result = "Заполните все обязательные поля!";
                    return false;
                }
                axios({
                    method: 'post',
                    url: "/install/index.php",
                    data: {
                        action: 'check',
                        host: this.host,
                        user: this.user,
                        password: this.password,
                        dbname: this.dbname
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        install.result = response.data.text;
                    } else {
                        install.result = response.data.text;
                    }
                }).catch(function (error) {});
            },
            install: function () {
                install.result = '';
                if(!this.host || !this.user || !this.password || !this.dbname){
                    this.result = "Заполните все обязательные поля!";
                    return false;
                }
                axios({
                    method: 'post',
                    url: "/install/index.php",
                    data: {
                        action: 'install',
                        host: this.host,
                        user: this.dbUser,
                        password: this.dbPassword,
                        dbname: this.dbName
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        install.step = 2;
                    } else {
                        install.result = response.data.text;
                    }
                }).catch(function (error) {});
            },
            user: function () {
                install.result = '';
            }
            delete: function () {
                install.result = '';
                axios({
                    method: 'post',
                    url: "/install/index.php",
                    data: {
                        action: 'delete'
                    }
                }).then(function (response) {
                    console.log(response);
                    if (response.data.status === "success") {
                        document.location = "/";
                    } else {
                        install.result = response.data.text;
                    }
                }).catch(function (error) {});
            }
        }
    })
</script>

