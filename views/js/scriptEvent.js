$(document).on("click", ".bi-heart", function () {
    console.log(idEvent);
    if (rol == "user_r") {
        callAjax('POST', 'favAction', { 'idEvent': idEvent }, function (data) {
            if (data['result']) {
                updateTable();
            }

        });

    } else {
        alert("No estas registrado");
    }
})

function callAjax(type = 'POST', action = 'updateTable', params, success, dataType = 'json', urlAjax = 'ajax.php') {
    $.ajax({
        type,
        url: urlAjax + "?action=" + action,
        data: (params),
        dataType,
        success
    });
}
