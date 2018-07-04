var img_ajaxLoader = "/eaudit/recursos/imatges/ajax-loader.gif";
var URL_SERVICE_CENTROS = '/eaudit/recursos/ws/centros.php';
var URL_SERVICE_GROUPSC = '/eaudit/recursos/ws/gruposCentros.php';


var link_eaudit = '/eaudit';
var link = {
    ws: '/eaudit/recursos/ws',
    scripts: '/eaudit/recursos/services',
    gdocs: '/eaudit/gdocs',
    imgs: '/eaudit/recursos/imatges',
    data: '/eaudit/recursos/data'
};
var table;

$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (!results) {
        return '';
    }
    return results[1] || 0;
};

var urlExists = function(url, callback) {

    if ( ! $.isFunction(callback)) {
        throw Error('Not a valid callback');
    }

    $.ajax({
        type: 'HEAD',
        url: url,
        success: $.proxy(callback, this, true),
        error: $.proxy(callback, this, false)
    });

};


function confirmDelete(button) {
    if (!confirm("¿Esta seguro que desea eliminar el registro?.")) {
        return false;
    } else {
        window.location.replace($(button).attr('href'));
        return true;
    }
}

// Confirmación para eliminar un registro
function aviso(url) {
    if (!confirm("¿Esta seguro que desea eliminar el registro?.")) {
        return false;
    }
    else {
        document.location = url;
        return true;
    }
}

//------------------------------------------------------------------
//Confirmación de aceptación de solución
function accept_activ(url) {
    if (!confirm("¿Ha sido satisfactoria la actividad desarrollada?.")) {
        document.location = url + "&status=0";
    }
    else {
        document.location = url + "&status=1";

    }
}

function ajaxReq(url, data, funSuccess) {
    $.ajax({
        url: url,
        type: "get",
        data: data,
        success: funSuccess,
        error: function (xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
            alert("Ha ocurrido un error");
        }
    });
}

var msgs = {
    mostra_error: function (msg) {
        var ini = "<div class='alert alert-danger fade in' role='alert'>" +
            "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
            "<span aria-hidden='true'>&times;</span></button><ul><li>";
        var fi = '</li></ul></div>';
        $('#msgs').append(ini + msg + fi);
    },
    mostra_ok: function (msg) {
        var ini = "<div class='alert alert-success fade in' role='alert'>" +
            "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
            "<span aria-hidden='true'>&times;</span></button><ul><li>";
        var fi = '</li></ul></div>';
        $('#msgs').append(ini + msg + fi);
    }
};

$(document).ready(function () {
    $(document).on('click', 'button.delete', function () {
        var isDis = $(this).hasClass('disabled');
        if (!isDis) { //Returns bool
            confirmDelete(this);
        }
    });

    // $('li.folderTree').click(function () {
    //     $(this).children("div.folderContent").toggle();
    // });
    //
    // $('button.centros').click(function () {
    //     $('div.modal-centros').load($(this).attr('href'));
    //
    // });
    $(".del-alert").click(function (event) {
        $(".notif-container").dropdown("toggle");
        var id_alert = $(this).val();
        var li_alert = "#notif_" + id_alert;
        var url = "/eaudit/recursos/ws/revAlert.php";
        $.ajax({
            url: url,
            type: "get",
            data: {
                id: id_alert
            },
            success: function (result) {
                $(li_alert).remove();
                var num_notif = $("#notif-list li").length;
                $("#notif-title").html('Notificationes (' + num_notif + ')');
                $("#notif-icon").attr('data-count', num_notif);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                alert("Ha ocurrido un error");
            }
        });
    });

    $(document).on('click', "#btn-showModalEdit", function (event) {
        $(".modal-toShowEdit").modal();
    });

    $(document).on('click', ".link", function (event) {
        var url = $(this).attr("data-urlLink");
        console.log(url);
        document.location.href = url;
    });
});