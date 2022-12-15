/**
 * onclick to try to log
 */

$(document).on("click", ".btn", function (e) {
    $('.border-danger').removeClass('border-danger');
    e.preventDefault();
    $param = { 'name': $('#userName').val(), 'password': $('#pwd').val() };
    callAjax('POST', 'log', $param, function (data) {
        if (data['result'] === true) {
            location.href = 'controller.php';
            return;
        }

        $("input[name='userName']").addClass('border border-danger rounded');
        $("input[name='password']").addClass('border border-danger rounded');
        alert(data['result']);
        return
    })
})

/**
 * this function make ajax calls
 * 
 * @param string type 
 * @param string action 
 * @param string params 
 * @param function success 
 * @param string dataType 
 * @param string urlAjax 
 */

function callAjax(type = 'POST', action = 'updateTable', params, success, dataType = 'json', urlAjax = 'ajax.php') {
    $.ajax({
        type,
        url: urlAjax + "?action=" + action,
        data: (params),
        dataType,
        success
    });
}