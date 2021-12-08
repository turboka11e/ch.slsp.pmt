import { updateAllSums } from "./submission";

var loadSubmission = function (e) {

    let spinner = e.target.parentElement.querySelector('#spinner');
    spinner.style.visibility = 'visible';

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
            spinner.style.visibility = 'hidden';
            $('div#ajax-results').html(data.output);
            calcSubmission();
            updateAllSums();
            $(document).one('click', 'a.ajax', loadSubmission);
        }
    });
    e.stopImmediatePropagation();
    return false;
};

$(document).one('click', 'a.ajax', loadSubmission);
