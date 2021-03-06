/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
// start the Stimulus application

$('.custom-file-input').on('change', function (e) {
    var inputFile = e.currentTarget;
    $(inputFile).parent().find('.custom-file-label').html(inputFile.files[0].name);
});

$(window).scroll(function () {
    if ($(window).scrollTop() >= 300) {
        $('.navbar').addClass('fixed-header');
        $('.arrow-page-up').addClass('visible');
    } else {
        $('.navbar').removeClass('fixed-header');
        $('.arrow-page-up').removeClass('visible');
    }
});