var where = "";
let orderBy;
let orderWay;
let date1 = "";
let date2 = "";

/**
 * onclick to delete from favorites
 */

$(document).on("click", ".bi-heart", function () {
    let idEvent = $(this).parent().siblings(":first").html();
    callAjax('POST', 'favAction', { 'idEvent': idEvent }, false, function (data) {
        if (data['result']) {
            if (data['acction'] == "delete") {
                updateTable();
                return;
            }
        }
    });

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

function callAjax(type = 'POST', action = 'updateTable', params, bool = true, success, dataType = 'json', urlAjax = './ajax.php') {
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
 * refresh or hide table 
 * 
 * @param string orderBy
 * @param string orderWay
 */

function updateTable(orderBy = 'id', orderWay = 'ASC') {
    selectFavs();
    let selected = $('.form-select').val();
    let page = $('.active').text();
    console.log(where);
    if (where.length == 0) {
        return;
    }

    let params = { 'numSelector': selected, 'page': page, 'orderBy': orderBy, 'orderWay': orderWay, 'new_columns': new_col, 'date1': date1, 'date2': date2, 'nameTable': nameTable, 'fieldsTranslated': fieldsTranslated, 'rol': rol, 'where': where };
    callAjax('POST', 'updateTable', params, false, function (data) {
        if (data['error']) {
            return alert(data['error']);
        }

        if (data['tbody'] == "") {
            $('table').hide();
            $('.nav_pagination').hide();
            let button = "<br><button class='hide btn btn-outline-primary'>Reset busqueda</button>";
            let p = "<p class='hide'>Datos no encontrados</p>";
            $('.search').after(button);
            $('.search').after(p);
            return;
        }

        $('tbody').remove();
        $('thead').after(data['tbody']);
        $('.nav_pagination').remove();
        $('table').after(data['pages']);
        if (nameTable == "user") {
            $('.input-search').remove();
        }

    });

}

/**
 * onclick to change page
 */

$(document).on("click", ".page-item", function () {
    if ($(this).hasClass("active")) {
        return;
    }

    $('.active').removeClass("active");
    $($(this)).addClass("active");
    updateTable(orderBy, orderWay);
})

/**
 * onclick to sort by th
 */

$(document).on("click", "th", function () {
    th = $(this).html();
    var keys = Object.getOwnPropertyNames(fieldsTranslated);
    for (let i = 0; i < keys.length; i++) {
        if (fieldsTranslated[keys[i]].translator == th) {
            th = keys[i];
        }
    }

    if ($(this).find('.sort').length > 0) {
        let txt = $('.sort').html();
        if (txt.includes("↓")) {
            orderWay = "ASC";
            txt = "↑";
        } else {
            orderWay = "DESC";
            txt = "↓";
        }

        $('.sort').html(txt)
        updateTable(orderBy, orderWay);
        return;
    }

    $('.sort').remove();
    orderBy = th;
    orderWay = "ASC";
    txt = $(this).html() + "<div class='sort'>↑</div>";
    $(this).html(txt);
    updateTable(orderBy, orderWay);
})

/**
 * change table from event user and vice versa
 */

$(document).on('change', '.form-select', function () {
    updateTable(orderBy, orderWay);
});

/**
 * search data between dates
 */

$(document).on("click", ".search", function () {
    $('.hide').remove();
    if ($('#date1').val() == null || $('#date1').val() == "" || $('#date2').val() == null || $('#date2').val() == "" || $('#date2').val() < $('#date1').val()) {
        $('.search').after('<div class="hide"><p style="color:red">Incorrect dates</p></div>');
        return;
    }
    date1 = $('#date1').val().replaceAll("-", "");
    date2 = $('#date2').val().replaceAll("-", "");
    updateTable();

})

/**
 * onchange date to set min on other date 
 */

$(document).on("change", "#date1", function () {
    $('#date2').attr('min', $(this).val());
})

/**
 * onchange date to set max on other date 
 */

$(document).on("change", "#date2", function () {
    $('#date1').attr('max', $(this).val());
})

/**
 * onclick to reset the search
 */

$(document).on("click", ".hide", function () {
    $('#date1').val("");
    $('#date2').val("");
    date1 = "";
    date2 = "";
    $('.hide').remove();
    $('table').show();
    $('.nav_pagination').show();
})

/**
 * onclick to redirct to event.php 
 */

$(document).on("click", ".name", function () {
    let id = $(this).siblings(":first").text()
    location.href = `event.php/?id=${id}`;
})

/**
 * seach if user has favs and create a sentence where or hide table
 */

function selectFavs() {
    callAjax('POST', 'selectFavs', { "": "" }, false, function (data) {
        if (data['result']) {
            where = data['result'];
            return;
        }

        $('table').hide();
        $('.nav_pagination').hide();
        let button = "<br><a href='./index.php'><button class='hide btn btn-outline-primary'>Volver a eventos</button></a>";
        let p = "<p class='hide'>No tienes favoritos agregados</p>";
        $('.search').after(button);
        $('.search').after(p);
        where="";

    });
}