"use strict"

/**
 * Represents a service.
 * @class DataService
 */
class DataService {

    /**
     * Request function
     *
     * @prop {String} method
     * @prop {String} link
     * @prop {Array} formData
     *
     * @returns {Json}
     */
    async request(method, link, formData = null) {
        let res;
        const options = {
            method: method,
            headers: {
                'Cache-Control': 'no-cache'
            },
            body: formData ?? ""
        };
        try {
            const response = await fetch(link, options);
            // if (response.status >= 400) {
            //     throw new Error("Bad response from server");
            // }
            const json = await response.json();
            res = json;
        } catch (err) {
            res = err;
        }
        return res;
    }

}

export const Data_Service = new DataService();