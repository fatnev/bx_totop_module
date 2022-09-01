/*
 * Файл local/modules/totop/install/assets/scripts/script.js
 */

$(function() {

    var params = $('#totop-params').data().params;

    if (params.switch_on == 'Y') {

        var style  = 'width: ' + params.width + 'px;';
            style += 'height: ' + params.height + 'px;';
            style += 'border-radius: ' + params.radius + 'px;';
            style += 'background-color: ' + params.color + ';';
            style += 'bottom: ' + params.indent_bottom + 'px;';
            style += params.side + ': ' + params.indent_side + 'px;';

        var speed = 600;
        if (params.speed == 'slow') {
            speed = 300;
        } else if (params.speed == 'fast'){
            speed = 1000;
        };

        $('body').append('<div id="totop-button" style="' + style +'"></div>');

        var button = $('#totop-button');
        $(window).on('load', function() {
            if ($(this).scrollTop() > 300) {
                button.fadeIn(600);
            }
        });

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                button.fadeIn(600);
            } else {
                button.fadeOut(600);
            };
        });

        button.on('click', function() {
            $('html, body').animate({
                scrollTop: 0
            }, speed);
        });

    };

});