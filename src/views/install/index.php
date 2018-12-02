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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
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
                <input type="text" name="host" class="form-control" v-model="db.host">
            </div>

            <div class="form-group">
                <label for="db-user">Пользователь:</label>
                <input type="text" name="db-user" class="form-control" v-model="db.user">
            </div>

            <div class="form-group">
                <label for="db-password">Пароль:</label>
                <input type="password" name="db-password" class="form-control" v-model="db.password">
            </div>

            <div class="form-group">
                <label for="db-name">База данных:</label>
                <input type="text" name="db-name" class="form-control" v-model="db.name">
            </div>

            <div class="form-group">
                <button class="btn btn-dark" @click="install">Установить</button>
                <button class="btn btn-secondary" @click="checkDb">Проверить подключение</button>
                <button class="btn btn-danger" @click="clearDb">Очистить БД</button>
            </div>
        </div>
        <div class="card-body" v-else-if="step === 2">
            <div class="form-group">
                <label for="user">Пользователь:</label>
                <input type="text" name="user-login" class="form-control" v-model="user.login">
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" name="user-password" class="form-control" v-model="user.password">
            </div>
            <button class="btn btn-success" @click="createUser">Создать</button>
        </div>

        <div class="card-body" v-else-if="step === 3">
            <p>Установка прошла упешно.</p>
            <p>Теперь Вы можете удалить папку install</p>
            <button class="btn btn-dark" @click="deleteInstallator">Удалить установщик и перейти на сайт</button>
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
            result: '',
            db: {
                host: 'localhost',
                user: '',
                password: '',
                name: ''
            },
            user: {
                login: '',
                password: '',
            }
        },
        methods: {
            checkDb: function () {
                this.result = '';
                if (! this.validateDbFields() ) return;
                axios({
                    method: 'post',
                    url: "/install/checkDb",
                    data: {
                        db: this.db
                    }
                }).then((response) => {
                    if (response.data.status === "success") {
                        this.result = response.data.message;
                    } else {
                        this.result = response.data.message;
                    }
                }).catch(function (error) {});
            },
            install: function () {
                this.result = '';
                if (! this.validateDbFields() ) return;
                axios({
                    method: 'post',
                    url: "/install/init",
                    data: {
                        db: this.db
                    }
                }).then((response) => {
                    if (response.data.status === "success") {
                        install.step = 2;
                    } else {
                        this.result = response.data.message;
                    }
                }).catch(function (error) {});
            },
            createUser: function () {
                this.result = '';
                if (! this.validateUserFields() ) return;
                axios({
                    method: 'post',
                    url: "/install/createUser",
                    data: {
                        user: this.user
                    }
                }).then((response) => {
                    if (response.data.status === "success") {
                        install.step = 3;
                    } else {
                        this.result = response.data.message;
                    }
                }).catch(function (error) {});
            },
            deleteInstallator: function () {
                install.result = '';
                axios({
                    method: 'post',
                    url: "/install/delete",
                }).then((response) => {
                    if (response.data.status === "success") {
                        document.location = "/";
                    } else {
                        this.result = response.data.message;
                    }
                }).catch(function (error) {});
            },
            clearDb: function () {
                this.result = '';
                if (! this.validateDbFields() ) return;
                if (! confirm("Очистка Базы Данных привет к полному удалению всех данных из нее. Вы уверены?")) {
                    return;
                }
                axios({
                    method: 'post',
                    url: "/install/clearDb",
                    data: {
                        db: this.db
                    }
                }).then((response) =>{
                    this.result = response.data.message;
                }).catch(function (error) {});
            },
            validateDbFields: function () {
                if (! this.db.host.trim() ||
                    ! this.db.user.trim() ||
                    ! this.db.password.trim() ||
                    ! this.db.name.trim()) {
                        this.result = "Заполните все обязательные поля!";
                        return false;
                }
                return true;
            },
            validateUserFields: function () {
                if (! this.user.login.trim() || ! this.user.password.trim()) {
                    this.result = "Заполните все обязательные поля!";
                    return false;
                }
            }
        }
    })
</script>

