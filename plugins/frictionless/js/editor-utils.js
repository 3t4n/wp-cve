/**
 * This script is loaded with the hook "admin_enqueue_scripts".
 * As such it is only loaded in the backend
 */

const handleAccordions = () => {
  // Find all expandable sections
  const accordionTitles = document.querySelectorAll('.accordionTitle');

  accordionTitles.forEach((accordionTitle) => {
    accordionTitle.addEventListener('click', () => {
      // Target which div to toggle based on the ID of the accordion's title id
      const sectionToToggle = document.querySelector(`.${accordionTitle.id}`);

      if (sectionToToggle.classList.contains('is-open')) {
        sectionToToggle.classList.remove('is-open');
      } else {
        const accordionTitlesWithIsOpen = document.querySelectorAll('.is-open');
        accordionTitlesWithIsOpen.forEach((accordionTitleWithIsOpen) => {
          accordionTitleWithIsOpen.classList.remove('is-open');
        });

        sectionToToggle.classList.add('is-open');
      }
    });
  });
};

document.addEventListener('DOMContentLoaded', handleAccordions);
