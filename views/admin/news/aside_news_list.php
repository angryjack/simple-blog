<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 07.07.2018 0:53
 */

?>

<a class="bd-toc-link">Список новостей</a>
<div id="news-list"></div>
<button type="button" class="btn btn-secondary" id="load-more">+Показать еще</button>
<script>
    let page = 1;
    $("#load-more").on("click", function () {
        ++page;

        $.ajax({
            type: "POST",
            url: "/admin/news/action/getNewsList",
            data: {
                page: page
            },
            dataType: "json",
            success: function (data) {
                if (data.status === "success") {
                    $("#news-list").append(data.news);
                } else {
                    $("#load-more").css('display', 'none');
                }
            },
            error: function () {
                $("#news-list").html("Произошла ошибка при загрузке новостей");
            }
        });
    });
</script>