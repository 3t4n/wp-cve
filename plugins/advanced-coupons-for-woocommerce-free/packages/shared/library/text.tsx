/**
 * Slugify function.
 *
 * @since 4.6.0
 *
 * @param {string} text
 * @return {string}
 */
export const slugify = (text: string) => {
  return text
    .toLowerCase() // Convert to lowercase
    .trim() // Trim leading and trailing spaces
    .replace(/\s+/g, '-') // Replace spaces with hyphens
    .replace(/[^\w-]+/g, '') // Remove non-word characters except hyphens
    .replace(/--+/g, '-'); // Replace multiple hyphens with a single hyphen
};
