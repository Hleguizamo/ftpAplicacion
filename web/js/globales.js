function AlertaPersonalizable(mensaje, tiempo, tipo){
    return noty({
        layout : 'center',
        text: mensaje,
        modal : true,
        type : tipo,
        buttons: false,
        timeout: tiempo
    });
}
function MensajeConfirmacionError(mensaje){
    noty({
        layout : 'center',
        text: '<div style="text-align:justify;">'+mensaje+'</div>',
        modal : true,
        buttons: [
            {addClass: 'uk-button uk-button-danger', text: 'Aceptar', onClick: function($noty) {
                $noty.close();
            }}
        ],
        animation: {
            open: 'animated bounceInLeft', // Animate.css class names
            close: 'animated bounceOutLeft', // Animate.css class names
            easing: 'swing', // unavailable - no need
            speed: 500 // unavailable - no need
        },
        type : 'error'
      });
}
function MensajeConfirmacion(mensaje){
    noty({
        layout : 'center',
        text: '<div style="text-align:justify;">'+mensaje+'</div>',
        modal : true,
        buttons: [
            {addClass: 'uk-button uk-button-primary', text: 'Aceptar', onClick: function($noty) {
                $noty.close();
            }}
        ],
        type : 'confirm',
        animation: {
            open: 'animated bounceInLeft', // Animate.css class names
            close: 'animated bounceOutLeft', // Animate.css class names
            easing: 'swing', // unavailable - no need
            speed: 500 // unavailable - no need
        }
      });
}
function Cargando(msg){
    return noty({
        layout: 'center',
        text: msg,
        modal: true,
        type: 'alert',
        timeout: false,
        closeWith: ['none']
    });
    
}
function LoaderWapp(msg){
    return noty({
        layout: 'center',
        text:msg,
        modal: true,
        type: 'alert',
        timeout: false,
        closeWith: ['none']
    });
    
}
$(function($){
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
});