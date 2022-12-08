let orderBy;
let orderWay;
let id;
let date1 = "";
let date2 = "";
user = {
    'id': {
        'translator': 'Identificador'
    },
    'rol': {
        'translator': 'Rol'
    },
    'name': {
        'translator': 'Nombre',
        'type': 'text'
    },
    'mail': {
        'translator': 'Mail',
        'type': 'text'
    },
    'age': {
        'translator': 'Edad',
        'type': 'number'
    },
    'favs': {
        'translator': 'Favoritos',
    }
};
evento = {
    'id': {
        'translator': 'Identificador'
    },
    'name': {
        'translator': 'Nombre',
        'type': 'text'
    },
    'date_init': {
        'translator': 'Fecha de inicio',
        'type': 'date'
    },
    'date_end': {
        'translator': 'Fecha de finalizacion',
        'type': 'number'
    },
    'place': {
        'translator': 'Lugar',
        'type': 'text'
    },
    'type': {
        'translator': 'Tipo',
        'type': 'text'
    },
    'photo': {
        'translator': 'Imagen',
        'type': 'text'
    }
};

/**
 * funciton onclick used when u change the select´s value
 */

$(document).on('change', '.form-select', function () {
    updateTable(orderBy, orderWay);
});

/**
 * update the table and pagination if it´s necessary
 */

function updateTable(orderBy = 'id', orderWay = 'ASC') {
    let selected = $('.form-select').val();
    let page = $('.active').text();
    if (nameTable == "event") {
        fieldsTranslated = evento;
    } else {
        fieldsTranslated = user;
    }
    let params = { 'numSelector': selected, 'page': page, 'orderBy': orderBy, 'orderWay': orderWay, 'new_columns': new_col, 'date1': date1, 'date2': date2, 'nameTable': nameTable, 'fieldsTranslated': fieldsTranslated };
    callAjax('POST', 'updateTable', params, function (data) {
        if (data['error']) {
            return alert(data['error']);
        }

        if (data['tbody'] == "") {
            $('table').hide();
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

    });

}

/**
 * onclick function used when u click on a different page to change the table
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
 * onclick function used when u click on a different page to change the table
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
    orderBy = $(this).html();
    orderWay = "ASC";
    txt = $(this).html() + "<div class='sort'>↑</div>";
    $(this).html(txt);
    updateTable(orderBy, orderWay);
})

/**
 * onclick function used when u click on delete button 
 */

$(document).on("click", ".delete", function () {
    id = $(this).parent().siblings(":first").text();
    if (id < 1) {
        return;
    }

    $param = { 'id': id, 'nameTable': nameTable };
    callAjax('GET', 'delete', $param, function (data) {
        if (data['result'] === true) {
            updateTable(orderBy, orderWay);
            return;
        }

        return alert(data['result']);
    })
})

/**
 * Ajax calls builder
 * 
 * @param string type 
 * @param string action 
 * @param object params 
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

$(document).on("click", ".search", function () {
    if ($('#date1').val() == null || $('#date1').val() == "" || $('#date2').val() == null || $('#date2').val() == "" || $('#date2').val() < $('#date1').val()) {
        $('.search').after('<p style="color:red">Incorrect dates</p>');
        return;
    }
    date1 = $('#date1').val().replaceAll("-", "");
    date2 = $('#date2').val().replaceAll("-", "");
    updateTable();

})

$(document).on("change", "#date1", function () {
    $('#date2').attr('min', $(this).val());
})

$(document).on("change", "#date2", function () {
    $('#date1').attr('max', $(this).val());
})

$(document).on("click", ".event", function () {
    changeTable("event");
})

$(document).on("click", ".user", function () {
    changeTable("user");
})

function changeTable(table) {
    if (table == "event") {
        fieldsTranslated = evento;
    } else {
        fieldsTranslated = user;
    }
    let page = $('.active').text();
    nameTable = table;
    let params = { 'page': page, 'new_columns': new_col, 'nameTable': nameTable, 'limit': 5, 'fieldsTranslated': fieldsTranslated };
    callAjax('POST', 'changeTable', params, function (data) {
        if (data['error']) {
            return alert(data['error']);
        }

        $('.container').remove();
        $('nav').after(data['table']);
    });

}

$(document).on("click", ".hide", function () {
    $('.hide').remove();
    $('table').show();
})