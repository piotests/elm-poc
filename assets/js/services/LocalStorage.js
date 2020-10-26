"use strict"

/**
 * Class representing the localStorage
 */
class LocalStorage {

    /**
     * Add item localStorage
     */
    setItem(obj_name, props) {
        window.localStorage.setItem(obj_name, JSON.stringify(props));
    }

    /**
     * Get item from localStorage
     */
    getItem(obj_name, prop_name = '') {
        let get = window.localStorage.getItem(obj_name);
        let res = JSON.parse(get);
        if (prop_name !== '') {
            return res[prop_name];
        }
        return res;
    }

    /**
     * Edit item from localStorage
     */
    editItem(obj_name, prop_name, prop_value) {
        let get_json_object = this.getItem(obj_name);
        if (get_json_object) {
            get_json_object[prop_name] = prop_value;
            this.setItem(obj_name, get_json_object);
        }
    }

    /**
     * Remove item from localStorage
     */
    removeItem(obj_name) {
        window.localStorage.removeItem(obj_name);
    }

    /**
     * Clear all items from localStorage
     */
    clear() {
        window.localStorage.clear();
    }
}

export const Local_Storage = new LocalStorage();