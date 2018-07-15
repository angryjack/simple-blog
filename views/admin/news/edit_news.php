<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.06.2018 22:56
 */
?>

<form>
    <h4>Новость <span class="badge badge-secondary">#<?= $news->id; ?></span></h4>
    <div class="form-group">
        <label for="news-title">Заголовок новости</label>
        <input type="text" class="form-control" id="news-title" value="<?= $news->title; ?>">
    </div>
    <div class="form-group">
        <label for="news-body">Текст новости</label>
        <textarea class="form-control" id="news-body" rows="10"><?= $news->content; ?></textarea>
    </div>

    <div class="form-group">
        <label for="news-category">Выберите категорию</label>
        <select class="form-control" id="categories">
        </select>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary" id="edit-news" data-id="<?= $news->id; ?>">
            Изменить
        </button>
        <button type="button" class="btn btn-danger" id="delete-news" data-id="<?= $news->id; ?>">
            Удалить
        </button>
    </div>
    <div class="form-group" id="result"></div>
</form>
<script>
    $(document).ready(function () {
        getNewsList();
        getCategories();

    });

    $("#edit-news").on("click", function () {
        let token = getCookie("token");
        let title = $("#news-title").val();
        let content = $("#news-body").val();
        let category = $("#categories option:selected").attr("data-id");
        let id = $("#edit-news").attr('data-id');
        let alertState;
        let message;

        $.ajax({
            type: "POST",
            url: "/admin/news/action/editNews",
            data: {
                id: id,
                title: title,
                content: content,
                category: category,
                token: token,
            },
            dataType: "json",
            success: function (data) {
                if(data.status === "success"){
                    alertState = "success";
                    message = data.text;
                    getNewsList();
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


    $("#delete-news").on("click", function () {
        let token = getCookie("token");
        let id = $("#edit-news").attr('data-id');
        let alertType;
        let message;

        $.ajax({
            type: "POST",
            url: "/admin/news/action/deleteNews",
            data: {
                id: id,
                token: token,
            },
            dataType: "json",
            success: function (data) {
                if(data.status === "success"){
                    alertState = "success";
                    message = data.text;
                    getNewsList();
                } else {
                    alertState = "danger";
                    message = data.error_text;
                }
                getNewsList();
            },
            error: function () {
                alertState = "danger";
                message = "Произошла ошибка!";
            },
            complete: function () {
                alertResult(alertType, message);
            }
        });

    });
</script>

