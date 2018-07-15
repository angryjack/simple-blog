<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */
?>
<?php include(ROOT . "/views/admin/layouts/header.php"); ?>

<div class="container">
    <div class="form-group">
        <label for="admin-login">Введите логин</label>
        <input type="text" class="form-control" id="admin-login" placeholder="Enter login">
    </div>
    <div class="form-group">
        <label for="admin-passwd">Введите пароль</label>
        <input type="password" class="form-control" id="admin-passwd" placeholder="Password">
    </div>
    <div class="form-group">
        <button class="btn btn-primary">Войти</button>
    </div>
    <div class="form-group" id="result"></div>
</div>

<?php include(ROOT . "/views/admin/layouts/scripts.php"); ?>

<script>
    $(document).ready(function () {
        document.cookie = "token=; path=/admin; expires=-1";

        $("button").on("click", function () {
            let login = $("#admin-login").val();
            let passwd = $("#admin-passwd").val();
            let alertState = 'danger';
            let message = 'Заполните, пожалуйста, все поля';

            $("#result").html();

            if (login.length > 1 && passwd.length > 1) {
                $.ajax({
                    type: "POST",
                    url: "/admin/action/login",
                    data: {
                        login: login,
                        passwd: passwd
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.status === 'success') {
                            document.cookie = "token=" + data.token + "; path=/admin; expires=20160";
                            document.location = "http://test.com/admin/";
                        } else {
                            message = data.error_text;
                        }
                    },
                    error: function () {
                        message = 'Произошла ошибка!';
                    },
                    complete: function () {
                        alertResult(alertState, message);
                    }
                });
            } else {
                alertResult(alertState, message);
            }
        });
    });
</script>

<?php include(ROOT . "/views/admin/layouts/footer.php"); ?>
