jQuery(document).ready(function() {
  jQuery(
    ".cluevo-module-link.cluevo-module-mode-lightbox[data-module-type='pdf']"
  ).click(function(e) {
    e.preventDefault();
    var data = jQuery(this).data();
    cluevoOpenLightbox(data);
    cluevoShowLightbox();
    var itemId = data.itemId;
    var moduleId = data.moduleId;
    var allowDownload = data.allowPdfDownload == 1 ? true : false;
    const url =
      cluevoWpApiSettings.root + "cluevo/v1/items/" + parseInt(itemId, 10);
    jQuery.ajax({
      url: url,
      method: "GET",
      contentType: "application/json",
      dataType: "json",
      beforeSend: function(xhr) {
        xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
      },
      success: function(response) {
        const viewer = Vue.createApp(CluevoPdfViewerApp, {
          document: response.iframe_index,
          moduleId: parseInt(moduleId, 10),
          itemId: parseInt(itemId, 10),
          allowDownload: allowDownload,
        });
        jQuery("#cluevo-module-lightbox-overlay").append(
          jQuery('<div id="cluevo-pdf-viewer-container"></div>')
        );
        viewer.mount("#cluevo-pdf-viewer-container");
        cluevoHideLightboxSpinner();
        jQuery(window).on("cluevo_closing_lightbox", function(e) {
          viewer.unmount();
        });
      },
      error: function(xhr) {
        if (xhr.responseJSON && xhr.responseJSON.message) {
          cluevoCloseLightbox(true);
          cluevoAlert(
            cluevoStrings.message_title_error,
            xhr.responseJSON.message,
            "error"
          );
        } else {
          cluevoAlert(
            cluevoStrings.message_title_error,
            cluevoStrings.message_unknown_error,
            "error"
          );
        }
      },
    });
  });
  jQuery(".cluevo-pdf-target-container").each(function(container) {
    var data = jQuery(this).data();
    console.log(data);
    var itemId = data.itemId;
    var moduleId = data.moduleId;
    var allowDownload = data.allowPdfDownload == 1 ? true : false;
    const url =
      cluevoWpApiSettings.root + "cluevo/v1/items/" + parseInt(itemId, 10);
    jQuery.ajax({
      url: url,
      method: "GET",
      contentType: "application/json",
      dataType: "json",
      beforeSend: function(xhr) {
        xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
      },
      success: function(response) {
        const viewer = Vue.createApp(CluevoPdfViewerApp, {
          propsData: {
            document: response.iframe_index,
            moduleId: parseInt(moduleId, 10),
            itemId: parseInt(itemId, 10),
            allowDownload: allowDownload,
          },
        });
        let el = viewer.$mount();
        jQuery(".cluevo-pdf-target-container").append(viewer.$el);
      },
    });
  });
});

const CluevoPdfViewerApp = {
  name: "cluevo-pdf-viewer",
  props: ["document", "itemId", "moduleId", "allowDownload"],
  data: function() {
    return {
      timerId: null,
      curPage: 1,
      pageRendering: false,
      pageNumPending: null,
      scale: 1.0,
      xDown: null,
      yDown: null,
      scales: [
        {
          value: 0.5,
          text: "50%",
        },
        {
          value: 0.8,
          text: "80%",
        },
        {
          value: 1,
          text: "100%",
        },
        {
          value: 1.5,
          text: "150%",
        },
        {
          value: 2,
          text: "200%",
        },
      ],
      pdfLib: null,
      pdfDoc: null,
    };
  },
  async mounted() {
    this.pdfLib = window["pdfjs-dist/build/pdf"];
    this.pdfLib.GlobalWorkerOptions.workerSrc = cluevoPdf.workerSrc;
    await this.loadProgress();
    this.initPdf();
    window.addEventListener("beforeunload", this.updateProgress);
    document.addEventListener("touchstart", this.handleTouchStart, false);
    document.addEventListener("touchmove", this.handleTouchMove, false);
  },
  async beforeDestroy() {
    await this.updateProgress();
    window.removeEventListener("cluevo_closing_lightbox", this.updateProgress);
    window.removeEventListener("beforeunload", this.updateProgress);
  },
  computed: {
    numPages() {
      if (!this.pdfDoc) return 0;
      if (!this.pdfDoc.numPages) return 0;
      return this.pdfDoc.numPages;
    },
  },
  methods: {
    async loadProgress() {
      const apiUrl =
        cluevoWpApiSettings.root +
        "cluevo/v1/modules/" +
        parseInt(this.moduleId, 10) +
        "/progress";
      const response = await fetch(apiUrl, {
        method: "GET",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "X-WP-Nonce": cluevoWpApiSettings.nonce,
        },
      });
      if (response) {
        try {
          const data = await response.json();
          let page = parseInt(data.score_raw, 10);
          if (isNaN(page)) {
            const storedPage = Number(
              localStorage.getItem(
                `cluevo-pdf-module-${Number(this.moduleId)}-page`
              )
            );
            if (storedPage && !isNaN(storedPage) && storedPage >= 1) {
              page = storedPage;
            } else {
              page = 1;
            }
          }
          this.curPage = page;
        } catch (e) {
          this.curPage = 1;
          console.warn("no page progress");
        }
      }
    },
    initPdf() {
      const vm = this;
      this.pdfLib.getDocument(this.document).promise.then(function(pdfDoc_) {
        vm.pdfDoc = pdfDoc_;
        if (!vm.curPage || vm.curPage < 1 || vm.curPage > vm.numPages) {
          vm.curPage = 1;
        }
        vm.renderPage();
      });
    },
    queueRenderPage(num) {
      if (this.pageRendering) {
        this.pageNumPending = parseInt(num, 10);
      } else {
        this.renderPage(parseInt(num, 10));
      }
    },
    renderPage() {
      this.pageRendering = true;
      const vm = this;
      this.pdfDoc.getPage(parseInt(this.curPage, 10)).then(function(page) {
        const viewport = page.getViewport({ scale: vm.scale });
        const outputScale = window.devicePixelRatio || 1;
        const canvas = vm.$refs.cluevo_pdf_canvas;
        const ctx = canvas.getContext("2d");

        canvas.height = Math.floor(viewport.height * outputScale);
        canvas.width = Math.floor(viewport.width * outputScale);
        canvas.style.width = Math.floor(viewport.width) + "px";
        canvas.style.height = Math.floor(viewport.height) + "px";

        const transform =
          outputScale !== 1 ? [outputScale, 0, 0, outputScale, 0, 0] : null;

        const renderContext = {
          canvasContext: ctx,
          transform: transform,
          viewport: viewport,
        };
        const renderTask = page.render(renderContext);

        // Wait for rendering to finish
        renderTask.promise.then(function() {
          vm.pageRendering = false;
          if (vm.pageNumPending !== null) {
            vm.renderPage();
            vm.pageNumPending = null;
          }
        });
      });
    },
    gotoPage() {
      if (this.curPage <= 0) this.curPage = 1;
      if (this.curPage > this.numPages) this.curPage = this.numPages;
      this.storeCurPage();
      this.queueRenderPage(this.curPage);
    },
    prevPage() {
      if (this.curPage <= 1) {
        return;
      }
      this.curPage--;
      this.storeCurPage();
      this.queueRenderPage(this.curPage);
    },
    storeCurPage() {
      localStorage.setItem(
        `cluevo-pdf-module-${Number(this.moduleId)}-page`,
        Number(this.curPage)
      );
    },
    nextPage() {
      if (this.curPage >= this.numPages) {
        return;
      }
      this.curPage++;
      this.storeCurPage();
      this.queueRenderPage(this.curPage);
    },
    gotoFirstPage() {
      this.curPage = 1;
      this.storeCurPage();
      this.queueRenderPage(this.curPage);
    },
    gotoLastPage() {
      this.curPage = this.numPages;
      this.storeCurPage();
      this.queueRenderPage(this.curPage);
    },
    changeScale(value) {
      this.scale = value;
      this.queueRenderPage(this.curPage);
    },
    async updateProgress() {
      const type = this.itemId ? "items" : "modules";
      const id = this.itemId ? this.itemId : this.moduleId;
      const data = {
        id: parseInt(id, 10),
        max: parseInt(this.numPages, 10),
        score: parseInt(this.curPage, 10),
      };

      const url =
        cluevoWpApiSettings.root +
        `cluevo/v1/${type}/` +
        parseInt(id, 10) +
        "/progress";
      await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json; charset=utf-8",
          "X-WP-Nonce": cluevoWpApiSettings.nonce,
        },
        body: JSON.stringify(data),
      });
    },
    queueUpdateProgress() {
      if (this.timerId) clearTimeout(this.timerId);
      this.timerId = setTimeout(this.updateProgress);
    },
    getTouches(e) {
      return (
        e.touches || // browser API
        e.originalEvent.touches
      ); // jQuery
    },
    handleTouchStart(e) {
      const firstTouch = this.getTouches(e)[0];
      this.xDown = firstTouch.clientX;
      this.yDown = firstTouch.clientY;
    },
    handleTouchMove(e) {
      if (!this.xDown || !this.yDown) {
        return;
      }

      const xUp = e.touches[0].clientX;
      const yUp = e.touches[0].clientY;

      const xDiff = this.xDown - xUp;
      const yDiff = this.yDown - yUp;
      if (Math.abs(xDiff) < 150) return;

      if (Math.abs(xDiff) > Math.abs(yDiff)) {
        if (xDiff > 0) {
          this.nextPage();
        } else {
          this.prevPage();
        }
      }
      this.xDown = null;
      this.yDown = null;
    },
  },
  template: `
  <div class="cluevo-pdf-viewer">
    <canvas ref="cluevo_pdf_canvas" id="cluevo-pdf-container" />
    <div class="cluevo-pdf-pagination-container">
      <span @click="gotoFirstPage" class="dashicons dashicons-controls-skipback"></span>
      <span @click="prevPage" class="dashicons dashicons-controls-back"></span>
      <div class="cluevo-page-input">
        <input
          v-model="curPage"
          type="number"
          min="1"
          :max="numPages"
          step="1"
          @input="gotoPage"
        /> / <span>{{ numPages }}</span>
      </div>
      <span @click="nextPage" class="dashicons dashicons-controls-forward"></span>
      <span @click="gotoLastPage" class="dashicons dashicons-controls-skipforward"></span>
      <select v-model="scale" @change="queueRenderPage">
        <option v-for="(s, i) of scales" :key="i" :value="s.value">{{ s.text }}</option>
      </select>
      <a :href="document" v-if="allowDownload">
        <i class="fas fa-download"></i>
      </a>
    </div>
  </div>`,
};

// const CluevoPdfViewer = Vue.extend(CluevoPdfViewerComponent);
