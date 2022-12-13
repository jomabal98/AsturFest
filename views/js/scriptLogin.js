$(document).on("click", ".btn", function (e) {
    e.preventDefault();
    $param = { 'name': $('#userName').val(), 'password': $('#pwd').val() };
    callAjax('POST', 'log', $param, function (data) {
        if (data['result'] === true) {
            location.href = 'controller.php';
            return;
        }

        return alert(data['result']);
    })
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