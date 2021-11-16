import { updateAllSums } from "./submission";

$(document).one('click', 'a.ajax', function (e) {
    $.ajax({
        url: "submissions",
        type: "POST",
        dataType: "json",
        data: {
            "year": $(this).attr('year'),
            "month": $(this).attr('month'),
        },
        async: true,
        success: function (data) {
            $('div#ajax-results').html(data.output);
            calcSubmission();
            updateAllSums();
        }
    });
    return false;
});