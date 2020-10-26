import {HelperTable} from '../helper/GenerateTable.js';
import {USERS} from "./Users.js";

"use strict"

/**
 * Class representing the online users table list
 */
class UsersList {


    /**
     * Generate Table with list of online users.
     *
     * @return {HTMLElement} - The target HTML
     */
    async getHtml() {

        let view = ``;

        let res = await USERS.loadUsers();
        if (res) {
            let labelText = ["Username", "Login Time", "Last Update Time", "User IP"];
            let caption = 'Current online users list component';
            let contId = 'id';

            let generateTable = HelperTable.generateTable(caption, res, labelText, contId);
            view = `
                ${generateTable}
            `
        }

        return view
    }

}

export const UsersListDisplay = new UsersList();