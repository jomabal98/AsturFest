let orderBy;
let orderWay;
let id;
let date1 = "";
let date2 = "";

evento = {
    'id': {
        'translator': 'Identificador'
    },
    'name': {
        'translator': 'Nombre',
        'type': 'text'
    },
    'date_init': {
        'translator': 'Fecha_de_inicio',
        'type': 'date'
    },
    'date_end': {
        'translator': 'Fecha_de_finalizacion',
        'type': 'date'
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
    let params = { 'numSelector': selected, 'page': page, 'orderBy': orderBy, 'orderWay': orderWay, 'new_columns': new_col, 'date1': date1, 'date2': date2, 'nameTable': nameTable, 'fieldsTranslated': fieldsTranslated, 'rol': rol };
    callAjax('POST', 'updateTable', params, function (data) {
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
    orderBy = th;
    orderWay = "ASC";
    txt = $(this).html() + "<div class='sort'>↑</div>";
    $(this).html(txt);
    updateTable(orderBy, orderWay);
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
    $('.hide').remove();
    if ($('#date1').val() == null || $('#date1').val() == "" || $('#date2').val() == null || $('#date2').val() == "" || $('#date2').val() < $('#date1').val()) {
        $('.search').after('<div class="hide"><p style="color:red">Incorrect dates</p></div>');
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

$(document).on("click", ".hide", function () {
    $('#date1').val("");
    $('#date2').val("");
    $('.hide').remove();
    $('table').show();
    $('.nav_pagination').show();
})

$(document).on("click", ".name", function () {
    let id = $(this).siblings(":first").text()
    location.href = `event.php/?id=${id}&`;
})

let user = "user_nr"
$(document).on("click", ".bi-heart", function () {
    if (user == "user_r") {
        $(this).html("<path fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z'/>");
    } else {
        alert("No estas registrado");
    }
})