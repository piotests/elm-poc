import AbstractView from "./AbstractView.js";
import {USERS} from "../components/Users.js";

"use strict"

/**
 * Class representing the view of Logout page
 */
export class Logout extends AbstractView {

    constructor(params) {
        super(params);
        this.setTitle("Logout");
    }

    async render() {
        let view = ``;
        await USERS.logout();
        return view;
    }

    async after_render() {

    }
}

export default Logout;