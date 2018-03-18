$(function () {
    $(document).on('click', '.discutea-rating-container a', function (event) {
        event.preventDefault();
        var link = $(this);
        $.ajax(link.attr('href'))
            .done(function(data) {
                link.parent('.discutea-rating-container').html(data);
            });
    });
});