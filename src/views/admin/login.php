<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */
?>
<?php include(ROOT . "/src/views/admin/layouts/header.php"); ?>

<div class="container align-self-center d-flex justify-content-center mt-4">
    <div class="card" style="width: 25rem" id="admin-login">
        <h5 class="card-header">Admin panel</h5>
        <div class="card-body">
            <div class="form-group">
                <label for="admin-login">Login:</label>
                <input type="text" class="form-control" placeholder="place for login" v-model="login">
            </div>
            <div class="form-group">
                <label for="admin-passwd">Password:</label>
                <input type="password" class="form-control" placeholder="place for paasword" v-model="password">
            </div>
            <div class="form-group">
                <button class="btn btn-primary" @click="doLogin">Say Friend and enter</button>
            </div>
            <p class="card-text">{{result}}</p>
        </div>
    </div>
</div>

<script>
    let login = new Vue({
        el: '#admin-login',
        data: {
            login: '',
            password: '',
            result: ''
        },
        methods: {
            doLogin: function () {
                if(!this.login || !this.password){
                    this.result = "Поля логин и пароль обязательны для заполнения!";
                    return false;
                }
                axios({
                    method: 'post',
                    url: "/admin/doLogin",
                    data: {
                        login: this.login,
                        password: this.password
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        document.cookie = "token=" + response.data.answer.data + "; path=/admin; expires=20160";
                        document.location = "/admin/";
                    } else {
                        login.result = response.data.answer.text;
                    }
                }).catch(function (error) {});
            }
        },
        created: function () {
            document.cookie = "token=; path=/admin; expires=-1";
        }
    })
</script>

<?php include(ROOT . "/src/views/admin/layouts/footer.php"); ?>
