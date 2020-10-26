import AbstractView from "./AbstractView.js";
import {Local_Storage} from "../services/LocalStorage.js";
import {Site_Path} from "../global/Constants.js";
import {USERS} from "../components/Users.js";

"use strict"

/**
 * Class representing the view of Login page
 */
class Login extends AbstractView {

    constructor(params) {
        super(params);
        this.setTitle("Login");
    }

    async render() {
        if ( Local_Storage.getItem('user') && Local_Storage.getItem('user', 'isLoggedIn') ) {
            window.location.assign(`${Site_Path}`);
        }

        let view = `
             <div class="login-box">
                <h2>Login</h2>
                <form>
                    <div class="user-box">
                        <input type="text" name="user" id="user" required>
                        <label>Username</label>
                    </div>
                    <div class="user-box">
                        <input type="password" name="pass" id="pass" required>
                        <label>Password</label>
                    </div>
                    <div class="user-box" id="form-error"></div>
                    <a href="#" id="login-submit">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        Submit
                    </a>
                </form>
            </div>
        `

        return view
    }

    async after_render() {
        const loginForm = null || document.getElementById('login-submit');
        if ( loginForm ) {
            loginForm.addEventListener('click', (e) => {
                e.preventDefault();
                USERS.login();
            });

            document.addEventListener("keydown", (e) => {
                if (e.defaultPrevented) {
                    return;
                }
                let key = e.key || e.keyCode;
                if (key === 'Enter' || key === 13) {
                    loginForm.click();
                    return false;
                }
            });
        }
    }
}

export default Login;