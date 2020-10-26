import {WelcomeMsg} from "./Welcome.js";

"use strict"

/**
 * Class representing a navigation bar
 */
class NavBar {

    /**
     * Generate navigation bar.
     *
     * @return {HTMLElement} - The target HTML
     */
     getHtml() {
        let nav = `
             <nav class="nav">
                <input type="checkbox" id="nav-check">
                <div class="nav-header">
                    <div class="nav-title">
                        Test
                    </div>
                </div>
                <div class="nav-btn">
                    <label for="nav-check">
                        <span></span>
                        <span></span>
                        <span></span>
                    </label>
                </div>
        
                <div class="nav-links">
                    <div id="status"></div>
                    <a href="/logout" id="logout" data-link>Logout</a>
                </div>
            </nav>
        `;

        let msg = WelcomeMsg.showWelcomeMessage();

        let view = `
            ${nav}
            ${msg}
        `;

        return view
    }
}

export const NavigationBar = new NavBar();