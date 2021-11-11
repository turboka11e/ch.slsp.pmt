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
$(".op").on("keyup change", function () {
    var value1 = document.getElementById('fname').value;
    var value2 = document.getElementById('lname').value;
    var sum = filterInt(value1) + filterInt(value2);
    if (isNaN(sum)) {
        document.getElementById('rname').value = "Error";
    } else {
        document.getElementById('rname').value = sum;
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

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('tr');

    item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;
};

document.querySelectorAll('.add_item_link').forEach(btn => btn.addEventListener("click", addFormToCollection));