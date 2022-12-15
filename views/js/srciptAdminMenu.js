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
            $('.searched').remove();
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

/**
 * onclick to seach data between dates
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
 * onchange date to set min on other date 
 */

$(document).on("change", ".Fecha_de_inicio", function () {
    $('.Fecha_de_finalizacion').attr('min', $(this).val());
})

/**
 * onchange date to set max on other date 
 */

$(document).on("change", ".Fecha_de_finalizacion", function () {
    $('.Fecha_de_inicio').attr('max', $(this).val());
})

/**
 * onclick to change to event table
 */

$(document).on("click", ".event", function () {
    changeTable("event");
})

/**
 * onclick to change to user table
 */

$(document).on("click", ".user", function () {
    changeTable("user");
})

/**
 * set new name table and change it
 * 
 * @param string table 
 */

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
            $('.searched').remove();
        }
    });

}

/**
 * onclick to reset table
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

let error = false;

/**
 * onclick insert new event or user
 */

$(document).on("click", ".send", function () {
    error = false;
    let params;
    if (nameTable == "user") {
        validateEmail($('.Mail-exampleModal').val())
        validatePass($('.Contraseña-exampleModal').val());
        if ($('.Edad').val() < 1 && $('.Nombre-exampleModal').val().length <= 0) {
            error = true;
        }

        if (error) {
            alert("datos mal introducidos");
            return;
        }

        params = { 'name': $('.Nombre-exampleModal').val(), 'age': $('.Edad-exampleModal').val(), 'mail': $('.Mail-exampleModal').val(), 'nameTable': nameTable, 'password': $('.Contraseña-exampleModal').val() };
    } else {
        let dateInit = $('.Fecha_de_inicio').val().replaceAll("-", "");
        let dateEnd = $('.Fecha_de_finalizacion').val().replaceAll("-", "");
        if ($('.Nombre-exampleModal').val().length <= 0 || $('.Lugar-exampleModal').val().length <= 0 || $('.Tipo-exampleModal').val().length <= 0 || $('.Imagen-exampleModal').val().length <= 0) {
            error = true;
        }

        validateFileType($('.Imagen-exampleModal').val());
        if (error) {
            alert("datos mal introducidos");
            return;
        }

        params = { 'name': $('.Nombre-exampleModal').val(), 'photo': $('.Imagen-exampleModal').val(), 'place': $('.Lugar-exampleModal').val(), 'type': $('.Tipo-exampleModal').val(), 'nameTable': nameTable, 'date_init': dateInit, 'date_end': dateEnd };
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

/**
 * validate email
 * 
 * @param string value 
 */
function validateEmail(value) {
    if (!(/^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/.test(value))) {
        error = true;
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
    }

}

if (nameTable == "user") {
    $('.searched').remove();
}

/**
 * validate file type
 * 
 * @param string value 
 */

function validateFileType($value) {
    var fileName = $value;
    var idxDot = fileName.lastIndexOf(".") + 1;
    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
    if (extFile != "jpg" && extFile != "jpeg" && extFile != "png") {
        error = true;
    }

}

/**
 * onclick open modal and autocomplete form
 */

$(document).on("click", ".name", function () {
    id = $(this).siblings(":first").text();
    let name = $(this).text()
    $('.Nombre-exampleModal2').val(name);
    if (nameTable == "user") {
        let pass = $(this).siblings(":nth-child(4)").text();
        let mail = $(this).siblings(":nth-child(5)").text();
        let age = $(this).siblings(":nth-child(6)").text();
        $('.Contraseña-exampleModal2').val(pass);
        $('.Edad-exampleModal2').val(age);
        $('.Mail-exampleModal2').val(mail);
    } else {
        let fecha1 = $(this).siblings(":nth-child(3)").text();
        let fecha2 = $(this).siblings(":nth-child(4)").text();
        let lugar = $(this).siblings(":nth-child(5)").text();
        let tipo = $(this).siblings(":nth-child(6)").text();
        let imagen = $(this).siblings(":nth-child(7)").html().split('"');
        imagen = imagen[1];
        $('.Fecha_de_inicio-exampleModal2').val(fecha1);
        $('.Fecha_de_finalizacion-exampleModal2').val(fecha2);
        $('.Lugar-exampleModal2').val(lugar);
        $('.Tipo-exampleModal2').val(tipo);
        $('.Imagen-exampleModal2').val(imagen);
    }
    $('#exampleModal2').modal('show');
})

/**
 * onclick to edit a user or event
 */

$(document).on("click", ".edit", function () {
    error = false;
    let params;
    let post = "";
    if (nameTable == "user") {
        validateEmail($('.Mail-exampleModal2').val())
        validatePass($('.Contraseña-exampleModal2').val());
        if ($('.Edad-exampleModal2').val() < 1 && $('.Nombre-exampleModal2').val().length <= 0) {
            error = true;
        }

        if (error) {
            alert("datos mal introducidos");
            return;
        }

        params = { 'name': $('.Nombre-exampleModal2').val(), 'age': $('.Edad-exampleModal2').val(), 'mail': $('.Mail-exampleModal2').val(), 'nameTable': nameTable, 'password': $('.Contraseña-exampleModal2').val(), 'id': id };
        post = "editUser";
    } else {
        let dateInit = $('.Fecha_de_inicio-exampleModal2').val().replaceAll("-", "");
        let dateEnd = $('.Fecha_de_finalizacion-exampleModal2').val().replaceAll("-", "");
        if ($('.Nombre-exampleModal2').val().length <= 0 || $('.Lugar-exampleModal2').val().length <= 0 || $('.Tipo-exampleModal2').val().length <= 0 || $('.Imagen-exampleModal2').val().length <= 0) {
            error = true;
        }

        validateFileType($('.Imagen-exampleModal2').val());
        if (error) {
            alert("datos mal introducidos");
            return;
        }

        params = { 'name': $('.Nombre-exampleModal2').val(), 'photo': $('.Imagen-exampleModal2').val(), 'place': $('.Lugar-exampleModal2').val(), 'type': $('.Tipo-exampleModal2').val(), 'nameTable': nameTable, 'date_init': dateInit, 'date_end': dateEnd, 'id': id };
        post = "editEvent";
    }

    callAjax('POST', post, params, function (data) {
        if (data['error']) {
            return alert(data['error']);
        }
        $('.ins').remove();
        $('.insert-button').after("<p class='ins'><b>Insertado correctamente</b></p>");
        $('#exampleModal2').modal('hide');
        updateTable();
        $('input').val("");
    })
})