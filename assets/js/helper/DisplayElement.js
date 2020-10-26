"use strict"
/**
 * Class representing a display element
 */
class DisplayElement {

    /**
     * Display element
     */
    show(elm) {
        if(elm) {
            elm.style.display = 'block'
        }
    }

    /**
     * Hide element
     */
    hide(elm) {
        if(elm) {
            elm.style.display = 'none'
        }
    };

    /**
     * Remove element
     */
    remove(elm) {
        if(elm) {
            elm.remove()
        }
    };
}

export const Display_Element = new DisplayElement();