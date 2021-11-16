document.addEventListener("DOMContentLoaded", function() {
    updateAllSums();
});

function updateAllSums() {
    updateSum("opHour");
    updateSum("projectHour");
    updateSum("miscHour");
}

// Use this jquery to sum two operands

$('body').on("keyup change", '.opHour', {name: "opHour"}, (event) => updateSum(event.data.name));
$('body').on("keyup change", '.projectHour', {name: "projectHour"}, (event) => updateSum(event.data.name));
$('body').on("keyup change", '.miscHour', {name: "miscHour"}, (event) => updateSum(event.data.name));

function updateSum(s) {
    var sum = 0;
    $(".".concat(s)).each(function (index) {
        var text = $(this).val();
        sum += Number(text);
    });

    if (isNaN(sum)) {
        document.getElementById(s.concat('Sum')).value = "Error";
    } else {
        document.getElementById(s.concat('Sum')).value = sum
    }
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
        updateAllSums();
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