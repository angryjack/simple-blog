<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.06.2018 22:56
 */
?>

<form>
    <h4>Категория <span class="badge badge-secondary">#<?= $category->id; ?></span></h4>
    <div class="form-group">
        <label for="category-title">Заголовок категори</label>
        <input type="text" class="form-control" id="category-title" value="<?= $category->title; ?>">
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-primary" id="edit-category" data-id="<?= $category->id; ?>">
            Изменить
        </button>
        <button type="button" class="btn btn-danger" id="delete-category" data-id="<?= $category->id; ?>">
            Удалить
        </button>
    </div>
    <div class="form-group" id="result"></div>
</form>
<script>
    $("#edit-category").on("click", function () {
        let token = getCookie("token");
        let title = $("#category-title").val();
        let id = $("#edit-category").attr('data-id');
        let alertState;
        let message;

        $.ajax({
            type: "POST",
            url: "/admin/news/action/editCategory",
            data: {
                id: id,
                title: title,
                token: token,
            },
            dataType: "json",
            success: function (data) {
                if (data.status === "success") {
                    alertState = "success";
                    message = data.text;
                } else {
                    alertState = "danger";
                    message = data.error_text;
                }
            },
            error: function () {
                alertState = "danger";
                message = "Произошла ошибка!";
            },
            complete: function () {
                alertResult(alertState, message);
            }
        });
    });


    $("#delete-category").on("click", function () {
        let token = getCookie("token");
        let id = $("#edit-category").attr('data-id');
        let alertState;
        let message;

        $.ajax({
            type: "POST",
            url: "/admin/news/action/deleteCategory",
            data: {
                id: id,
                token: token,
            },
            dataType: "json",
            success: function (data) {
                if (data.status === "success") {
                    alertState = "success";
                    message = data.text;
                } else {
                    alertState = "danger";
                    message = data.error_text;
                }
            },
            error: function () {
                alertState = "danger";
                mssage = "Произошла ошибка!";
            },
            complete: function () {
                alertResult(alertState, message);
            }
        });
    });
</script>

