function cluevoInitDatabaseUpgradePage() {
  const { __ } = wp.i18n;
  const displayError = function (title, message, type) {
    const el = document.querySelector("#cluevo-db-upgrade-notice-container");
    if (!el) return;
    const notice = el.querySelector(".cluevo-notice");
    if (!notice) return;
    notice.classList = ["cluevo-notice"];
    if (type) {
      switch (type) {
        case "error":
          notice.classList.add("cluevo-notice-error");
          break;
        case "warning":
          notice.classList.add("cluevo-notice-warning");
          break;
        case "success":
          notice.classList.add("cluevo-notice-success");
          break;
        default:
          notice.classList.add("cluevo-notice-warning");
      }
    }
    const titleElement = notice.querySelector(".cluevo-notice-title");
    const contentElement = titleElement?.nextElementSibling;
    if (titleElement) {
      titleElement.textContent = title;
    }
    if (contentElement) {
      contentElement.textContent = message;
    }
    el.style.display = "block";
  };

  document.querySelectorAll(".cluevo-db-upgrade-link")?.forEach?.((el) => {
    el.addEventListener("click", async (e) => {
      e.preventDefault();
      disableLinks();
      const result = await run(e.currentTarget);
      if (result) {
        enableLinks();
      }
    });
  });

  document.querySelectorAll(".cluevo-run-all-upgrades")?.forEach?.((el) => {
    el.addEventListener("click", async (e) => {
      if (
        confirm(
          __(
            "This will run all database upgrades in sequence. Are you sure you've fully backed up your site AND your database?",
            "cluevo",
          ),
        )
      ) {
        e.preventDefault();
        document
          .querySelectorAll(".cluevo-run-all-upgrades")
          ?.forEach?.((b) => (b.disabled = true));
        disableLinks();
        const links = document.querySelectorAll(".cluevo-db-upgrade-link");
        let errors = false;
        for (let link of links) {
          const result = await run(link);
          if (!result) {
            errors = true;
            break;
          }
        }
        if (!errors) {
          window.location.reload();
        }
      }
    });
  });

  const displaySpinner = function (td) {
    if (!td) return;
    const tpl = `<div class="cluevo-spinner cluevo-spinner-small">
      <div class="cluevo-spinner-segment cluevo-spinner-segment-pink"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-purple"></div>
      <div class="cluevo-spinner-segment cluevo-spinner-segment-teal"></div>
    </div>`;
    td.innerHTML = tpl;
  };

  const displayStatus = function (td, status) {
    if (!td) return;
    if (status === "success") {
      td.innerHTML = '<span class="dashicons dashicons-saved"></span>';
    } else if (status === "error") {
      td.innerHTML = '<span class="dashicons dashicons-no"></span>';
    } else {
      td.innerHTML = '<span class="dashicons dashicons-minus"></span>';
    }
  };

  const disableLinks = function () {
    document
      .querySelectorAll(".cluevo-db-upgrade-link")
      ?.forEach?.((l) => l.classList.add("disabled"));
  };

  const enableLinks = function () {
    document
      .querySelectorAll(".cluevo-db-upgrade-link")
      ?.forEach?.((l) => l.classList.remove("disabled"));
  };

  const run = async function (link) {
    link?.scrollIntoViewIfNeeded?.();
    const td =
      link?.parentElement?.tagName === "TD" ? link.parentElement : null;
    if (td === null) return;
    try {
      displaySpinner(td);
      const resp = await fetch(link.href);
      const result = await resp.json();
      if (Array.isArray(result)) {
      } else {
        if (result?.result) {
          displayStatus(td, "success");
          return true;
        } else {
          displayStatus(td, "error");
        }
      }
    } catch (error) {
      displayStatus(td, "error");
      displayError(
        __("Error", "cluevo"),
        __("Database Upgrade Job Failed", "cluevo"),
        "error",
      );
      console.error(error);
    }
    return false;
  };
}
document.addEventListener("DOMContentLoaded", cluevoInitDatabaseUpgradePage);
