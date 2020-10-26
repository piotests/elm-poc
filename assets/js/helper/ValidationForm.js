"use strict"

/**
 * Class representing the validation form
 */
class ValidationForm {

    /**
     * Inject error msg to DOM
     */
    displayValidationError(msg) {
        let pointElm = document.getElementById('form-error');
        if (pointElm) {
            let errorTag = document.createElement('p');
            errorTag.setAttribute('class', 'error');
            errorTag.innerText = msg;
            pointElm.appendChild(errorTag);
        }
    }

    /**
     * Check if form has error msg and remove it from DOM
     */
    removeValidationError() {
        let errorDiv = document.querySelector('#form-error p.error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    /**
     * Check if email is valid
     *
     * @prop {String} email
     *
     * @returns {Boolean}
     */
    emailIsValid (email) {
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
}

export const Validation_Form = new ValidationForm();