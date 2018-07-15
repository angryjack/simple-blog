let page = 0;

$(".buttons__load-more").on("click", function () {
    ++page;

    $.ajax({
        type: "POST",
        url: "/news",
        data: {
            page: page
        },
        dataType: "json",
        success: function (data) {
            if(data.status === "success"){
                alertState = "success";
                $(".news-container").append(data.news);
            }else{
                alertState = "danger";
                message = data.error_text;
            }
        },
        error: function () {
            alertState = "danger";
            message = "Произошла ошибка!";
        }
    });
});