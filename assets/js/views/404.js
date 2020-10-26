import AbstractView from "./AbstractView.js";
import {Modal_box} from "../helper/Modal.js";

"use strict"

/**
 * Class representing the view of 404 page
 */
export default class extends AbstractView {

    constructor(params) {
        super(params);
        this.setTitle("Error 404 - page not found");
    }

    async render() {
        let view = ``
        let html = `
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h1>404 Error</h1>
                <p>Page Not Found</p>
                <p>Please go to <a href="/">home page</a></p>
            </div>
        `;
        await Modal_box.display('error-modal', html);

        return view
    }

    async after_render() {

    }
}