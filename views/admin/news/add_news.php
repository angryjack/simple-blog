<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.06.2018 22:56
 */

?>
<div class="card">
    <div class="card-header">
        Создать новость
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="news-title">Заголовок новости</label>
            <input type="text" class="form-control" id="news-title">
        </div>
        <div class="form-group">
            <label for="news-body">Текст новости</label>
            <textarea class="form-control" id="news-body" rows="10"></textarea>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="news-category">Выберите категорию</label>
                <select class="form-control" id="news-category"></select>
            </div>
            <div class="form-group col-md-6">
                <label for="news-url">Ссылка на новость</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon3">https://site.ru/</span>
                    </div>
                    <input type="text" class="form-control" id="news-url" aria-describedby="basic-addon3">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="news-meta-description">Описание</label>
                <input type="text" class="form-control" id="news-meta-description">
            </div>
            <div class="form-group col-md-6">
                <label for="categories">Ключевые слова</label>
                <input type="text" class="form-control" id="news-meta-keywords">
            </div>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-success" id="add-news">Добавить</button>
        </div>
    </div>
    <div class="card-footer text-muted" id="result-news">
        Результат
    </div>
</div>

<script>

    $(document).ready(function () {
        getNewsList();
        getCategories();
    });

    $("#add-news").on("click", function () {
        let token = getCookie("token");
        let title = $("#news-title").val();
        let content = $("#news-body").val();
        let category = $("#news-category option:selected").attr("data-id");
        let url = $("#news-url").val();
        let description = $("#news-meta-description").val();
        let keywords = $("#news-meta-keywords").val();

        let alertState;
        let message;

        $.ajax({
            type: "POST",
            url: "/admin/news/action/addNews",
            data: {
                title: title,
                content: content,
                category: category,
                url: url,
                description: description,
                keywords: keywords,
                token: token
            },
            dataType: "json",
            success: function (data) {
                if (data.status === "success") {
                    alertState = "success";
                    message = data.text;
                    $("#news-title").val('');
                    $("#news-body").val('');
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
</script>