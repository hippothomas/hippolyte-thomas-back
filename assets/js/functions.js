/**
 * Loads scripts if the specified element exists in the document.
 * @param {string} pageName - id of the page name.
 * @param {Function} f - The function to be executed if the element exists.
 */
export function loadScripts(pageName, f) {
    if (document.getElementById(pageName) !== null) {
        f()
    }
}