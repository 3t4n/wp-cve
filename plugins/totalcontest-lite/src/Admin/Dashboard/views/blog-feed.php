<script type="text/ng-template" id="dashboard-blog-feed-component-template">
    <div class="totalcontest-box totalcontest-blog">
        <div class="totalcontest-box-section">
            <div class="totalcontest-box-title"><?php  esc_html_e( 'Picks from our blog', 'totalcontest' ); ?></div>
        </div>
        <div class="totalcontest-box-links">
            <a class="totalcontest-box-links-item" href="{{ post.url }}" target="_blank" title="{{post.title}}" ng-repeat="post in $ctrl.posts">
                <div>
                    <h4 class="totalcontest-box-links-item-title">{{ post.title }}</h4>
                    <p class="totalcontest-box-links-item-description">{{ post.excerpt }}</p>
                </div>
                <img ng-src="{{ post.thumbnail }}" alt="{{ post.title }}">
            </a>
        </div>
    </div>
</script>
