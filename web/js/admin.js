function getNewsList() {
    $.ajax({
        type: "POST",
        url: "/admin/news/action/getNewsList",
        data: {},
        dataType: "json",
        success: function (data) {
            if (data.status === "success") {
                $("#news-list").prepend(data.news);
            } else {
                $("#news-list").html(data.error_text);
            }
        },
        error: function () {
            $("#news-list").html("Произошла ошибка при загрузке новостей");
        }
    });
}

function getCategories() {
    $.ajax({
        type: "POST",
        url: "/admin/news/action/getCategories",
        data: {},
        dataType: "json",
        success: function (data) {
            if (data.status === "success") {
                $("#news-category").html(data.categories);
            } else {
                $("#news-category").html(data.error_text);
            }
        },
        error: function () {
            $("#news-category").html("Произошла ошибка при загрузке новости");
        }
    });
}

function alertResult(alertState, message) {
    $("#result").html('<div class="alert alert-' + alertState + '" role="alert">' + message + '</div>');
}

// возвращает cookie с именем name, если есть, если нет, то undefined
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}