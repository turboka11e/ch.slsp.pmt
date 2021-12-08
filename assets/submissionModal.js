import { updateAllSums } from "./submission";

var loadSubmission = function (e) {
    $.ajax({
        url: $(this).attr('href'),
        type: "POST",
        dataType: "json",
        data: {
            "userId": $(this).attr('userId'),
            "year": $(this).attr('year'),
            "month": $(this).attr('month'),
        },
        async: true,
        success: function (data) {
            $('div#ajax-result').html(data.output);
            calcSubmission();
            updateAllSums();
            $(document).one('click', 'button.ajax', loadSubmission);
        }
    });
    e.stopImmediatePropagation();
    return false;
};

$(document).one('click', 'button.ajax', loadSubmission);
