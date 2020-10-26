"use strict"

/**
 * Class generate table
 */
class GenerateTable {

    /**
     * Generate table
     *
     * create table if not exists and update it's rows
     *
     * @prop {String} captionText - content for caption
     * @param {Json} data - The array of cell header names
     * @prop {Array} labelText - content for table head
     * @prop {String} contId - cell to continue
     *
     * @returns {HTMLTableElement} - The target HTML table
     */
    generateTable(captionText, data, labelText, contId) {
        let table = document.querySelector("table#users-online");
        if (!table) {
            table = document.createElement('table');
            table.setAttribute('id', 'users-online')
            let caption = table.createCaption();
            caption.textContent = captionText;
            this.generateTableHead(table, labelText);
        }
        let tbodyContent = this.generateTableTbody(table, data, labelText, contId);
        let tbody = table.getElementsByTagName('tbody')[0];
        if (!tbody) {
            table.appendChild(tbodyContent);
        } else {
            tbody.innerHTML = tbodyContent.outerHTML;
        }

        return table.outerHTML;
    }

    /**
     * Generate table head
     *
     * @param {HTMLTableElement} table - The target HTML table
     * @param {Json} data - The array of cell header names
     *
     * @returns {HTMLTableHElement} - The target HTML table head
     */
    generateTableHead(table, data) {
        let thead = table.createTHead();
        let row = thead.insertRow();
        for (let key of data) {
            let th = document.createElement("th");
            let text = document.createTextNode(key);
            th.appendChild(text);
            row.appendChild(th);
        }

        return table.outerHTML;
    }

    /**
     * Generate table tbody
     *
     * @param {HTMLTableElement} table - The target HTML table
     * @param {Json} data - The array of cell tbody names
     * @prop {Array} labelText - content for table head
     * @prop {String} contId - cell to continue
     *
     * @returns {HTMLTableHElement} - The target HTML table tbody col
     */
    generateTableTbody(table, data, labelText, contId) {
        if (data) {
            let tbody = document.createElement("tbody");
            for (let element of data) {
                let row = this.addRow(table, element, labelText, contId);
                tbody.appendChild(row);
            }
            return tbody;
        }
    }

    /**
     * Generate table row
     *
     * @param {HTMLTableElement} table - The target HTML table
     * @param {Json} data - The array of cell tbody names
     * @prop {Array} labelText - content for table head
     * @prop {String} contId - cell to continue
     *
     * @returns {HTMLTableRowElement} - The target HTML table row
     */
    addRow(table, element, labelText, contId) {
        let row = table.insertRow();
        row.setAttribute("id", element.id);
        let i = 0;
        for (let key in element) {
            if (key == contId) {
                continue;
            }
            let cell = row.insertCell();
            cell.setAttribute("scope", "row");
            cell.setAttribute("data-label", labelText[i]);
            let text = document.createTextNode(element[key]);
            cell.appendChild(text);
            i++;
        }
        return row;
    }
}

export const HelperTable = new GenerateTable();