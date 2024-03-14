<div class="item sq-button element-<%= data.uuid %> <%= data.stax_adv_classes %>" <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
    <a class="item-child" href="<%= data.url_field %>" target="<%= data.url_target_field %>">
        <% if (data.icon_show_field) { %>
        <span class="mdi <%= data.icon_type_field %> <%= data.icon_size_field %>"></span>
        <% } %>

        <% if (data.icon_text_field) { %>
        <span class="item-text"><%= data.icon_text_field %></span>
        <% } %>
    </a>
</div>
