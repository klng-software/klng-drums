$(document).ready(function () {
    $('#slider1').on('slid.bs.carousel', function (el) {
        var slided_image = $('.carousel-item.active>img').attr('src');
        $('#header').css('background-image', "url("+slided_image+")");
    });
});