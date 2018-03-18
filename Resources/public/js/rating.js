$(function () {
    $('.discutea-rating-container').on('click', 'a', function (e) {
        e.preventDefault();
        var link = $(this);
        $.ajax(link.attr('href'))
            .done(function(data) {
                link.parent('.discutea-rating-container').html(data);
            });
    });
});
