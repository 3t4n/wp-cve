<script type="text/template" id="notice-template">
    <div class="notice notice-{{ type }} {{ isDismissible ? 'is-dismissible' : '' }}">
        <div class="notice-main">
            <div class="notice-content">
                {% if (raw) { %}
                    {! message !}
                {% } else { %}
                    <p>{! message !}</p>
                {% } %}
            </div>
            {% if (details) { %}
                <div>
                    <button type="button" class="details-button button collapse-arrow" data-toggle="collapse" aria-controls="notice-details-{{ id }}" aria-expanded="false">Details</button>
                </div>
            {% } %}
        </div>
        {% if (details) { %}
            <div id="notice-details-{{ id }}" class="notice-details" hidden>
                <div class="notice-details-border"></div>

                {! details !}
            </div>
        {% } %}
        {% if (isDismissible) { %}
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        {% } %}
    </div>
</script>
