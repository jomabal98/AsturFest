let orderBy;
let orderWay;
let id;
let date1 = "";
let date2 = "";
let rol = "admin";
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
    'password': {
        'translator': 'Contraseña',
        'type': 'password'
    },
    'mail': {
        'translator': 'Mail',
        'type': 'text'
    },
    'age': {
        'translator': 'Edad',
        'type': 'number'
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

$(document).on("change", ".Fecha_de_inicio", function () {
    $('.Fecha_de_finalizacion').attr('min', $(this).val());
})

$(document).on("change", ".Fecha_de_finalizacion", function () {
    $('.Fecha_de_inicio').attr('max', $(this).val());
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

    nameTable = table;
    let params = { 'page': 1, 'new_columns': new_col, 'nameTable': nameTable, 'limit': 5, 'fieldsTranslated': fieldsTranslated, 'rol': rol };
    callAjax('POST', 'changeTable', params, function (data) {
        if (data['error']) {
            return alert(data['error']);
        }


        $('.container').remove();
        $('nav').after(data['table']);
        if (nameTable == "user") {
            $('.input-search').remove();
        }
    });

}

$(document).on("click", ".hide", function () {
    $('#date1').val("");
    $('#date2').val("");
    $('.hide').remove();
    $('table').show();
    $('.nav_pagination').show();
})

let error = false;
$(document).on("click", ".send", function () {
    error = false;
    let params;
    if (nameTable == "user") {
        validateEmail($('.Mail').val())
        validatePass($('.Contraseña').val());
        if ($('.Edad').val() < 1 && $('.Nombre').val().length <= 0) {
            error = true;
        }

        if (error) {
            alert("datos mal introducidos");
            return;
        }

        params = { 'name': $('.Nombre').val(), 'age': $('.Edad').val(), 'mail': $('.Mail').val(), 'nameTable': nameTable, 'password': $('.Contraseña').val() };
    } else {
        let dateInit = $('.Fecha_de_inicio').val().replaceAll("-", "");
        let dateEnd = $('.Fecha_de_finalizacion').val().replaceAll("-", "");
        if ($('.Nombre').val().length <= 0 || $('.Lugar').val().length <= 0 || $('.Tipo').val().length <= 0 || $('.Imagen').val().length <= 0) {
            error = true;
        }

        validateFileType();
        if (error) {
            alert("datos mal introducidos");
            return;
        }

        params = { 'name': $('.Nombre').val(), 'photo': $('.Imagen').val(), 'place': $('.Lugar').val(), 'type': $('.Tipo').val(), 'nameTable': nameTable, 'date_init': dateInit, 'date_end': dateEnd };
    }

    let post = "";
    if (nameTable == "event") {
        post = "insertEvent";
    } else {
        post = "insertUser";
    }

    callAjax('POST', post, params, function (data) {
        if (data['error']) {
            return alert(data['error']);
        }
        $('.ins').remove();
        $('.insert-button').after("<p class='ins'><b>Insertado correctamente</b></p>");
        $('#exampleModal').modal('hide');
        updateTable();
        $('input').val("");
    })

})

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

if (nameTable == "user") {
    $('.input-search').remove();
}

function validateFileType() {
    var fileName = $(".Imagen").val();
    var idxDot = fileName.lastIndexOf(".") + 1;
    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
    if (extFile != "jpg" && extFile != "jpeg" && extFile != "png") {
        error = true;
    }

}
