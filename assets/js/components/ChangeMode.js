import {Local_Storage} from "../services/LocalStorage.js";
import {Display_Element} from "../helper/DisplayElement.js";
import {Site_Services_Path} from "../global/Constants.js";

"use strict"

/**
 * Class representing a user status
 */
class ChangeMode {

    /**
     * Check if user is online and update his status
     * This function used if user reload the page or reopened closed tab
     */
    checkUserStatus() {
        if ( !Local_Storage.getItem('user') ) {
            return false;
        }

        let userLoggedIn = Local_Storage.getItem('user', 'isLoggedIn');
        let userIsOnline = Local_Storage.getItem('user', 'isOnline');
        let logoutLink = document.getElementById('logout');
        if (logoutLink && userLoggedIn) {
            Display_Element.show(logoutLink);
        } else {
            Display_Element.hide(logoutLink);
        }

        if (userLoggedIn && !userIsOnline) {
            Change_Mode.logVisit();
        }
    }

    /**
     * Check if user is online and update his status
     */
    logVisit() {

        let state = "";
        let url = `${Site_Services_Path}ChangeModeService.php`;
        let action = "change_status";
        let uid = Local_Storage.getItem('user', 'uid');
        let isLoggedIn = Local_Storage.getItem('user', 'isLoggedIn');
        let token = Local_Storage.getItem('user','token');
        if ( !Local_Storage.getItem('user') ) {
            return false;
        }

        if (isLoggedIn && uid != '') {

            if (document.visibilityState === 'visible' || !document.hidden) {
                state = 'online';
            } else {
                state = 'offline';
            }

            let checkModalVisibility = document.querySelector('.modal');
            if (checkModalVisibility && state == "online") {
                checkModalVisibility.remove();
            }


            let params = new URLSearchParams({action: action, id: uid, state: state, token: token})
            let res = navigator.sendBeacon(url, params);
            if (res) {
                let status = state == "online" ? true : false;
                let isOnline = Local_Storage.getItem('user', 'isOnline');
                if (isOnline != status) {
                    Local_Storage.editItem('user','isOnline', status);
                }
            }
        }
    }

    /**
     * Add event "visibilitychange" to change user status when the user closed current tab or display another tab
     */
    addListener() {
        document.addEventListener('visibilitychange', this.logVisit);
    }
}

export const Change_Mode = new ChangeMode();