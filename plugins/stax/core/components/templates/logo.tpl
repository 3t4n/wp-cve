<div class="item sq-logo element-<%= data.uuid %> <%= data.stax_adv_classes %>" <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
    <a class="item-child" href="<%= data.url_field %>">
        <% if (data.logo_type_field == 'img') { %>
        <img src="<%= data.img_source_field %>" alt="<%= data.img_alt_field %>"
        <% if (data.img_resized_upload_field) { %> data-image-resized="<%= data.img_resized_upload_field %>" <% } %>
        <% if (data.img_transparent_upload_field) { %> data-image-transparent="<%= data.img_transparent_upload_field %>" <% } %>
        >
        <% } else { %>
        <% if (data.icon_show_field) { %>
        <span class="mdi <%= data.icon_field %> <%= data.icon_size_field %>"></span>
        <% } %>
        <% if (data.logo_text_field) { %>
        <span class="logo-text"><%= data.logo_text_field %></span>
        <% } %>
        <% } %>
    </a>
</div>
