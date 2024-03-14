/**
 * Decode HTML from string function.
 *
 * @since 4.6.0
 *
 * @param {string} text - The HTML-encoded string to be decoded.
 * @returns {string} - The decoded string without HTML entities.
 */
export const decodeHTML = (text: string) => {
  let div = document.createElement('div');
  div.innerHTML = text;

  return div.textContent || div.innerText;
};

/**
 * Checks whether a string contains HTML elements.
 *
 * @since 4.6.0
 *
 * @param {string} text - The string to be checked.
 * @returns {boolean} - Returns `true` if the string contains HTML elements, and `false` otherwise.
 */
export const isHTML = (text: string) => {
  const htmlRegex = /<[^>]+>|&[a-z]+;/i;

  return htmlRegex.test(text);
};
