import AbstractView from "./AbstractView.js";
import { Site_Path_Login, Site_Path_Logout } from '../global/Constants.js';
import {Local_Storage} from "../services/LocalStorage.js";
import {Change_Mode} from "../components/ChangeMode.js";
import {UsersListDisplay} from "../components/UsersList.js";
import {NavigationBar} from "../components/NavBar.js";
import {USERS} from "../components/Users.js";

"use strict"

/**
 * Class representing the view of Dashboard page
 */
class Dashboard extends AbstractView {
    inc = 0;
    usersList;

    constructor(params) {
        super(params);
        this.setTitle("Dashboard");
    }

    async render() {
        if (Local_Storage.getItem('user') && !Local_Storage.getItem('user', 'isLoggedIn') || !Local_Storage.getItem('user')) {
            window.location.assign(`${Site_Path_Login}`);
        }

        let view = ``;

        let navBar = NavigationBar.getHtml();

        Change_Mode.checkUserStatus();

        if (typeof this.usersList === "undefined" && this.inc === 0) {
            this.usersList = await UsersListDisplay.getHtml();
        }
        if (typeof this.usersList !== "undefined") {

            const interval = setInterval(async () => {
                let userIsLoggedIn = Local_Storage.getItem('user', 'isLoggedIn');
                let userIsOnline = Local_Storage.getItem('user', 'isOnline');

                if (userIsLoggedIn && userIsOnline) {
                    this.usersList = await UsersListDisplay.getHtml();
                    this.inc++;
                }
                if (!userIsLoggedIn) {
                    clearInterval(interval);
                    window.location.assign(`${Site_Path_Login}`);
                }
            }, 3000);

            view = ` 
            ${navBar}
            ${this.usersList}
        `;

        }

        return view
    }

    async after_render() {
        Change_Mode.addListener();

        const logoutLink = null || document.getElementById('logout');
        if ( logoutLink ) {
            logoutLink.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.assign(`${Site_Path_Logout}`);
            });
        }

        const viewUser = null || document.querySelector('table#users-online  > tbody');
        if ( viewUser ) {
            viewUser.addEventListener('click', (e) => {
                e.preventDefault();
                let target = e.target;
                Local_Storage.editItem('user','loadUid', target.parentElement.id);
                USERS.loadUser(target.parentElement.id);
            });
        }
    }
}

export default Dashboard;