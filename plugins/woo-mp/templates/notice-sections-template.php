<?php defined( 'ABSPATH' ) || die; ?>

<script type="text/template" id="woo-mp-template-notice-sections">
    {% sections.forEach(function (section) { %}
        <div class="notice-card">
            {% if (section.title !== undefined) { %}
                <div class="notice-card-header">
                    <strong>{{ section.title }}</strong>
                </div>
            {% } %}
            <div class="notice-card-body">
                {% if (section.text !== undefined) { %}
                    {{ section.text }}
                {% } %}
                {% if (section.HTML !== undefined) { %}
                    {! section.HTML !}
                {% } %}
                {% if (section.code !== undefined) { %}
                    <pre class="code notice-card-code-block">{{
                          typeof section.code === 'object' ? JSON.stringify(section.code, null, 2)
                        : typeof section.code === 'symbol' ? section.code.toString()
                        : section.code
                    }}</pre>
                {% } %}
            </div>
        </div>
    {% }); %}
</script>
