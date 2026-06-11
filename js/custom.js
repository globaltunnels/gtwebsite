"use strict";


/********************
 * Contact Form Validation
 */

document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('.needs-validation');

    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);

            Array.prototype.slice.call(form.elements).forEach(function (input) {
                input.addEventListener('input', function () {
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                });
            });
        });
});

/*contact form validation */
function confirmSubmission() {
        return confirm("I am not a robot!");
}


