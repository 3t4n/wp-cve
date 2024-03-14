      <template class="sb-csp-modal-temp">
        <article class="sb-csp-modal-wrapper">
          <button type="button" class="sb-csp-modal-close">Close</button>
          <figure class="sb-csp-modal">
            <div class="sb-csp-modal-pic-wrapper">
              <div class="sb-csp-modal-pictures-root" style="--sb-csp-width-base: 1440px; --sb-csp-width-min: 1440px; --sb-csp-columns-min: 1;">
                <ul class="sb-csp-modal-pictures">
                </ul>
              </div>
            </div>
            <div class="sb-csp-modal-attributes">
              <header class="sb-csp-modal-header">
                <a href="https://www.instagram.com/{{username}}/" target="_blank" rel="noopener">
                  <img src="{{profilePicture}}" alt="Profile picture of @{{username}}">
                  <b>{{account}}</b>
                </a>
              </header>
              <main class="sb-csp-modal-main">
                <p class="sb-csp-modal-caption">
                  {{caption}}
                </p>
                <div class="sb-csp-modal-anchor-button-wrapper">
                  <a href="{{permalink}}" target="_blank" rel="noopener" class="sb-csp-modal-anchor-button">{{openInstagram}}</a>
                </div>
              </main>
              <?php echo $content; ?>
              <footer class="sb-csp-modal-footer">
                <ul class="sb-csp-modal-impressions">
                  <li class="sb-csp-modal-like-count">
                    <b>{{likeCount}}</b>
                  </li>
                  <li class="sb-csp-modal-comments-count">
                    <b>{{commentsCount}}</b>
                  </li>
                  <li class="sb-csp-modal-time">
                    <time datetime="{{timestamp}}">{{time}}</time>
                  </li>
                </ul>
              </footer>
            </div>
          </figure>
        </article>
      </template>
