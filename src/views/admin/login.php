<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */
?>

<div class="login-form__wrapper" id="loginForm">
    <div class="login-form" @keyup.enter="signIn">
        <div class="login-form__header">Admin panel</div>
        <div class="login-form__block">
            <label for="login" class="login-form__label">Login:</label>
            <input id="login" type="text" class="login-form__input" placeholder="Your login"
                   v-model="login">
        </div>
        <div class="login-form__block">
            <label for="login" class="login-form__label">Password:</label>
            <input id="login" type="text" class="login-form__input" placeholder="Your password"
                   v-model="password">
        </div>
        <button class="login-form__button" @click="signIn">Sign in</button>
        <div class="login-form__result" v-if="result && result.length > 0">{{result}}</div>
    </div>
</div>
<script>
    let login = new Vue({
        el: '#loginForm',
        data: {
            login: '',
            password: '',
            result: ''
        },
        methods: {
            signIn: function () {
                this.result = '';
                if (this.login.length < 1 || this.password.length < 1) {
                    this.result = "Поля логин и пароль обязательны для заполнения!";
                    return false;
                }
                axios({
                    method: 'post',
                    url: "/admin/signIn",
                    data: {
                        login: this.login,
                        password: this.password
                    }
                }).then(function (response) {
                    if (response.data.token) {
                        document.cookie = "token=" + response.data.token + "; path=/admin; expires=20160";
                        document.location = "/admin/";
                    } else {
                        login.result = "Введен неправильный логин или пароль.";
                    }
                }).catch(function (error) {});
            }
        },
        created: function () {
            document.cookie = "token=; path=/admin; expires=-1";
        }
    })
</script>
<style>
    *{
        box-sizing: border-box;
    }
    body{
        margin: 0;
        font-family: Arial;
    }
    .login-form__wrapper{
        background: #f5f6f9;
        width: 100vw;
        height: 100vh;
        min-height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 50px 0;
    }
    .login-form{
        background: #fff;
        max-width: 320px;
        width: 100%;
        min-height: 400px;
        border-radius: 6px;
        padding: 20px;
        border: 2px solid #e0e0e4;
    }
    .login-form__header{
        margin-bottom: 20px;
        text-align: center;
        font-size: 3.5em;
        font-weight: bold;
        text-transform: uppercase;
    }
    .login-form__block{
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }
    .login-form__label{
        margin-bottom: 5px;
    }
    .login-form__input,
    .login-form__button{
        border: 2px solid #e0e0e4;
        height: 40px;
        border-radius: 3px;
        font-size: 1em;
    }
    .login-form__input{
        text-indent: 10px;
    }
    .login-form__button{
        background: #6371C5;
        width: 100%;
        color: #fff;
        border: none;
        outline: none;
    }
    .login-form__button:hover{
        background: #fff;
        color: #6371C5;
        border: 2px solid #6371C5;
    }
    .login-form__result{
        margin-top: 10px;
    }
</style>
