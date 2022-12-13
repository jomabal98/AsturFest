$(document).on("click", ".btn", function (e) {
    console.log();
    e.preventDefault();
    error = false;
    validateEmail($('#mail').val())
    validatePass($('#pwd').val());
    if ($('#age').val() < 1 && $('#userName').val().length <= 0) {
        error = true;
    }

    if (error) {
        alert("datos mal introducidos");
        return;
    }

    let params = { 'name': $('#userName').val(), 'age': $('#age').val(), 'mail': $('#mail').val(), 'nameTable': "user", 'password': $('#pwd').val() };




    callAjax('POST', 'insertUser', params, function (data) {
        if (data['result'] === true) {
            location.href = 'login.php';
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

function validateEmail(value) {
    if (!(/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/.test(value))) {
        error = true;
    }

}

function validatePass(value) {
    if (!(/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4,15}$/.test(value))) {
        error = true;
    }

}