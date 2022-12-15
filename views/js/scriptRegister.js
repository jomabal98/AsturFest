/**
 * onclick try to register user 
 */

$(document).on("click", ".btn", function (e) {
    $('.border-danger').removeClass('border-danger');
    error = false;
    msg = "";
    e.preventDefault();
    validateName();
    validateEmail($('#mail').val())
    validatePass($('#pwd').val());
    if ($('#age').val() < 1) {
        error = true;
        $("input[name='age']").addClass('border border-danger rounded');
        msg += " age"
    }

    if (error) {
        alert("datos mal introducidos:" + msg);
        return;
    }

    let params = { 'name': $('#userName').val(), 'age': $('#age').val(), 'mail': $('#mail').val(), 'nameTable': "user", 'password': $('#pwd').val() };




    callAjax('POST', 'insertUser', params, true, function (data) {
        if (data['result'] === true) {
            location.href = 'login.php';
            return;
        }

        return alert(data['result']);
    })

})

/**
 * this function make ajax calls
 * 
 * @param string type 
 * @param string action 
 * @param string params 
 * @param bool bool
 * @param function success 
 * @param string dataType 
 * @param string urlAjax 
 */

function callAjax(type = 'POST', action = 'updateTable', params, bool = true, success, dataType = 'json', urlAjax = 'ajax.php') {
    $.ajax({
        type,
        url: urlAjax + "?action=" + action,
        data: (params),
        async: bool,
        dataType,
        success
    });

}

/**
 * validate email
 * 
 * @param string value 
 */

function validateEmail(value) {
    if (!(/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/.test(value))) {
        error = true;
        $("input[name='mail']").addClass('border border-danger rounded');
        msg += " mail"
    }

}

/**
 * validate password
 * 
 * @param string value 
 */

function validatePass(value) {
    if (!(/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4,15}$/.test(value))) {
        error = true;
        $("input[name='password']").addClass('border border-danger rounded');
        msg += " password"
    }

}

/**
 * validate name 
 */

function validateName() {
    if ($('#userName').val().length <= 0) {
        error = true;
        $("input[name='userName']").addClass('border border-danger rounded');
        msg += " name"
        return;
    }

    callAjax('POST', 'validateName', { 'name': $('#userName').val() }, false, function (data) {
        if (data['result'] == true) {
            error = true;
            $("input[name='userName']").addClass('border border-danger rounded');
            msg += " name"
        }

    })

}

