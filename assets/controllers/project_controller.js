import { Controller } from 'stimulus';
import 'jquery-ui/ui/widgets/autocomplete';
import 'jquery-ui/themes/base/all.css';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    // connect() {
    //     var availableTags = [{ label: "Project A", value: 1}, { label: "Project B", value: 2}];
    //     // this.element.innerHTML = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
    //     $(this.element).autocomplete({
    //         source: availableTags,
    //         select: function(a, b) {
    //             $(this.element).val(b.item.value);
    //         }
    //     });
    // }
}
