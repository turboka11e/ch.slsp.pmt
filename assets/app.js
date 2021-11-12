/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';


// Use this jquery to sum two operands
$('body').on("keyup change", '.opHour', function () {
    var sum = 0;
    $(".opHour").each(function(index) {
        var text = $( this ).val();
        sum += Number(text);
    });

    if (isNaN(sum)) {
        document.getElementById('opHourSum').value = "Error";
    } else {
        document.getElementById('opHourSum').value = sum
    }
});

$('body').on("keyup change", '.projectHour', function () {
    var sum = 0;
    $(".projectHour").each(function(index) {
        var text = $( this ).val();
        sum += Number(text);
    });

    if (isNaN(sum)) {
        document.getElementById('projectHourSum').value = "Error";
    } else {
        document.getElementById('projectHourSum').value = sum
    }
});

$('body').on("keyup change", '.miscHour', function () {
    var sum = 0;
    $(".miscHour").each(function(index) {
        var text = $( this ).val();
        sum += Number(text);
    });

    if (isNaN(sum)) {
        document.getElementById('miscHourSum').value = "Error";
    } else {
        document.getElementById('miscHourSum').value = sum
    }
});


// qualify integer
function filterInt(value) {
    if (value.length == 0) {
        return 0;
    }
    if (/^(\-|\+)?([0-9]+|Infinity)$/.test(value))
        return Number(value);
    return NaN;
}



const addOperationFormDeleteLink = (operationFormLi) => {
    const removeFormTd = document.createElement('td')

    const removeFormButton = document.createElement('button')

    removeFormButton.classList = 'btn btn-outline-danger font-monospace'
    removeFormButton.innerText = '-'

    removeFormTd.append(removeFormButton);
    operationFormLi.append(removeFormTd);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault()

        operationFormLi.remove();
    })
}

/* Brauch man erst wenn bereits Rows vorhanden sind */
const operations = document.querySelectorAll('tr.deleteable')
operations.forEach((operation) => {
        addOperationFormDeleteLink(operation)
})

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('tr');

    item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);

    collectionHolder.appendChild(item);

    addOperationFormDeleteLink(item);

    collectionHolder.dataset.index++;
    
};

document.querySelectorAll('.add_item_link').forEach(btn => btn.addEventListener("click", addFormToCollection));