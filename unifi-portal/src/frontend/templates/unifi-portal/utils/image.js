/**
 * Constructs the full URL for an image by combining the base URL and the provided image path.
 * If the path is empty, it returns a default placeholder URL.
 *
 * @param {string} path - The relative path to the image (e.g., 'icons/ajax_spinner.svg').
 * @returns {string} The full URL of the image or a default placeholder URL if the path is empty.
 */
export function img(path) {
  if (!path || typeof path !== "string" || path.trim() === "") {
    console.warn("Image path is empty. Returning default placeholder.");
    return `${UnifiPortalData.image.url}/icons/placeholder.svg`;
  }

  return `${UnifiPortalData.image.url}/${path}`;
}
