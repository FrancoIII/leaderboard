/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

$('#app-notifs div').each(function(){
    var type = $(this).data('type');

    var icon = '';
    if(type == 'success') icon = 'fas fa-check-circle';
    if(type == 'danger') icon = 'fas fa-exclamation-circle';
    if(type == 'info') icon = 'fas fa-info-circle';
    if(type == 'warning') icon = 'fas fa-exclamation-triangle';

    $.notify({
        icon: icon,
        message: $(this).html()
    },{
        type: $(this).data('type'),
        delay: 20000
    })
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});