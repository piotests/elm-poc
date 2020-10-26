import {Site_Path, Site_Path_Login, Site_Services_Path} from '../global/Constants.js';
import {Local_Storage} from '../services/LocalStorage.js';
import {Data_Service} from '../services/DataService.js';
import {Validation_Form} from "../helper/ValidationForm.js";
import {Modal_box} from "../helper/Modal.js";

"use strict"

/**
 * Class representing the users function
 */
class Users {

    constructor() {
        this.method = "POST";
        this.link = `${Site_Services_Path}UserServices.php`;
    }

    async loadUser(id = '') {

        let token = Local_Storage.getItem('user','token');
        let loadUserId = id;
        if (!loadUserId) {
            let getLoadUid  = Local_Storage.getItem('user','loadUid');
            if (getLoadUid) {
                loadUserId = getLoadUid;
            }
        }

        let formData = new FormData();
        formData.append('sec', "user_details");
        formData.append('id', loadUserId);
        formData.append('token',  token);

        let res = await Data_Service.request(this.method, this.link, formData);
        if (res) {
            if (res.hasOwnProperty('status')) {
                let html = `
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <h1>User Error</h1>
                        <p>${res.msg}</p>
                    </div>
                `;
                await Modal_box.display('error-modal', html);
            } else {
                let html = `
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <h1>User Details</h1>
                        <p>User-Agent: ${res.user_agent}</p>
                        <p>Register time: ${res.created_at}</p>
                        <p>Logins count: ${res.logins_count}</p>
                    </div>
                `;
                await Modal_box.display('user-modal', html);
            }
        }

        return false;
    }

    async loadUsers() {
        let token = Local_Storage.getItem('user','token');
        let formData = new FormData();
        formData.append('sec', "user_list");
        formData.append('token', token);

        let res = await Data_Service.request(this.method, this.link, formData);
        if (res) {
            if (!res.hasOwnProperty('status')) {
                return res;
            } else {
                let html = `
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <h1>User Error</h1>
                        <p>${res.msg}</p>
                    </div>
                `;
                await Modal_box.display('error-modal', html);
            }
        }

        return false;

    }

    async login() {
        Validation_Form.removeValidationError();

        let user = document.getElementById('user').value;
        let pass = document.getElementById('pass').value;

        if (user === '') {
            Validation_Form.displayValidationError('Pleas enter username');
            return false;
        }
        if (!Validation_Form.emailIsValid(user)) {
            Validation_Form.displayValidationError(`${user} is not a valid email address`);
            return false;
        }

        if (pass === '') {
            Validation_Form.displayValidationError('Pleas enter password');
            return false;
        }

        let formData = new FormData();
        formData.append('sec', "login");
        formData.append('user', user);
        formData.append('pass', pass);

        let res = await Data_Service.request(this.method, this.link, formData);
        if (res.status) {
            let user_state = {isLoggedIn: true, isOnline: true, name: res.name, uid: res.uid, loadUid: "", token: res.token};
            Local_Storage.setItem('user', user_state);
            if (res.uid) {
                window.location.assign(`${Site_Path}`);
            }
        } else {
            Validation_Form.displayValidationError(res.msg);
            return false;
        }

    }

    async logout() {

        let uid = Local_Storage.getItem('user', 'uid');
        let token = Local_Storage.getItem('user','token');

        let formData = new FormData();
        formData.append('sec', "logout");
        formData.append('id', uid);
        formData.append('token', token);
        
        let res = await Data_Service.request(this.method, this.link, formData);
        if (res.status) {
            Local_Storage.clear();
            let user_state = {isLoggedIn: false, isOnline: false, name: "", uid: "", loadUid: "", token: ""};
            Local_Storage.setItem('user',user_state);
            window.location.assign(`${Site_Path_Login}`);
        } else {
            let html = `
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <h1>User Error</h1>
                        <p>${res.msg}</p>
                    </div>
                `;
            await Modal_box.display('error-modal', html);
        }

    }
}

export const USERS = new Users();

