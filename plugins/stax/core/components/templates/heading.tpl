<div class="item sq-heading element-<%= data.uuid %> <%= data.stax_adv_classes %>" <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
    <<%= data.tag_field %> class="heading-title">
        <% if (data.href_field) { %>
            <a href="<%= data.href_field %>" target="<%= data.href_target_field %>">
                <%= data.text_field %>
            </a>
        <% } else { %>
            <%= data.text_field %>
        <% } %>
    </<%= data.tag_field %>>
</div>
