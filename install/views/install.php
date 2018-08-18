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
        <h5 class="card-header">Установка сайта:</h5>
        <div class="card-body">

            <div class="form-group">
                <label for="host">Хост:</label>
                <input type="text" name="host" class="form-control" v-model="host">
            </div>

            <div class="form-group">
                <label for="user">Пользователь:</label>
                <input type="text" name="user" class="form-control" v-model="user">
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" name="password" class="form-control" v-model="password">
            </div>

            <div class="form-group">
                <label for="dbname">База данных:</label>
                <input type="text" name="dbname" class="form-control" v-model="dbname">
            </div>

            <div class="form-group">
                <button class="btn btn-primary" @click="install">Установить</button>
                <button class="btn btn-success" @click="check">Проверить подключение</button>
            </div>

            <p class="card-text">{{result}}</p>
        </div>
    </div>
</div>

<script>
    let install = new Vue({
        el: '#install-site',
        data: {
            host: 'localhost',
            user: '',
            password: '',
            dbname: '',
            result: ''
        },
        methods: {
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
                        user: this.user,
                        password: this.password,
                        dbname: this.dbname
                    }
                }).then(function (response) {
                    console.log(response);
                    if (response.data.status === "success") {
                        document.location = "/";
                    } else {
                        install.result = response.data.text;
                    }
                }).catch(function (error) {});
            },
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
                    console.log(response);
                    if (response.data.status === "success") {
                        install.result = response.data.text;
                    } else {
                        install.result = response.data.text;
                    }
                }).catch(function (error) {});
            }
        }
    })
</script>

