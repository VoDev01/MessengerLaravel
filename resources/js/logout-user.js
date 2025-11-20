$(window).on('unload', function (e) {
    $.ajax({
        url: '/logout',
        method: 'POST',
        dataType: 'json'
    });
});