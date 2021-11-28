var loadSubmission = function (e) {
    $.ajax({
        url: $(this).attr('href'),
        type: "POST",
        dataType: "json",
        data: {
            "year": $(this).attr('year'),
            "month": $(this).attr('month'),
        },
        async: true,
        success: function (data) {
            $('div#ajax-results').html(data.output);
            $(document).one('click', 'a.ajax', loadSubmission);
        }
    });
    e.stopImmediatePropagation();
    return false;
};

$(document).one('click', 'a.ajax', loadSubmission);
