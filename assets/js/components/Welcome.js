import {Local_Storage} from "../services/LocalStorage.js";

"use strict"

/**
 * Class representing a welcome massage
 */
class Welcome {

    /**
     * Display welcome msg and hide it after 10 sec
     *
     * @return {HTMLElement} - The target HTML
     */
    showWelcomeMessage() {
        if (Local_Storage.getItem('user')) {
            let username = Local_Storage.getItem('user', 'name') ?? "";
            const welcomeDiv = document.createElement("div");
            welcomeDiv.setAttribute("id", "welcomeMessage");
            welcomeDiv.innerHTML = `Welcome ${username}`;

            setTimeout(() => {
                let welcomeTag = document.getElementById('welcomeMessage');
                if (welcomeTag) {
                    welcomeTag.style.display = 'none';
                }
                // Local_Storage.editItem('user', 'name', '');
            }, 10000);

            return welcomeDiv.outerHTML;
        }
    }
}

export const WelcomeMsg = new Welcome();