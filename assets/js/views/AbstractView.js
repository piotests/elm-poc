"use strict"

/**
 * Class representing the abstract structure of view page
 */
export default class {
    constructor(params) {
        this.params = params;
    }

    setTitle(title) {
        document.title = title;
    }

    async render() {
        return "";
    }

    async after_render() {
        return "";
    }
}