
<script>
$("#add-category").on("click", function () {
    let token = getCookie("token");
    let title = $("#category-title").val();
    let alertType;
    let alertMessage;

    $.ajax({
        type: "POST",
        url: "/admin/news/action/addCategory",
        data: {
            title: title,
            token: token
        },
        dataType: "json",
        success: function (data) {
            alertType = "success";
            alertMessage = "Категория успешно добавлена";
            $("#category-title").val('');
        },
        error: function () {
            alertType = "danger";
            alertMessage = "Произошла ошибка!";
        },
        complete: function () {
            $("#result").html('<div class="alert alert-' + alertType + '" role="alert">' + alertMessage + '</div>');
        }
    });

});
</script>
