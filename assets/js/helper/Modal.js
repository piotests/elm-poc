"use strict"

/**
 * Class representing a modal box
 */
class Modal {

    /**
     * Generate and display modal
     *
     * @prop {String} - The attribute id
     * @prop {HTMLDivElement} - The target HTML modal
     *
     * @returns {void}
     */
    async modelBox(id, html) {
        const modal = document.createElement('div');
        modal.setAttribute("id", id);
        modal.setAttribute("class", "modal");
        modal.innerHTML = html;
        const elm = document.querySelector('body');
        document.body.appendChild(modal);
        let mbox = document.getElementById(id);
        mbox.style.display = 'block';
        this.hideListener(mbox);
    }

    /**
     * Add event 'click' for close the modal box
     */
    hideListener(mbox) {
        document.addEventListener('click', e => {
            if(e.target.classList.contains('close-modal')) {
                mbox.style.display = "none";
                mbox.remove();
            }
            if(e.target == mbox) {
                mbox.style.display = "none";
                mbox.remove();
            }
        });
    };

    /**
     * Generate and display modal
     *
     * @prop {String} - The attribute id
     * @prop {HTMLDivElement} - The target HTML modal
     *
     * @returns {void}
     */
    display(id, html) {
        return this.modelBox(id, html);
    }
}

export const Modal_box = new Modal();