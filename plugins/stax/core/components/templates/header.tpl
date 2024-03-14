<div class="header-section stax-hs
        element-<%= data.uuid %>
        <%= data.stax_adv_classes %>
        <%= data.type_field %>
        <% if (data.sticky_field) { %> <%= data.sticky_field %> <% } %>
        <% if (data.resize_field) { %> <%= data.resize_field %> <% } %>
        <% if (data.slide_up_field) { %> <%= data.slide_up_field %> <% } %>
        <% if (data.transparent_field) { %> <%= data.transparent_field %> <% } %>"
        <% if (data.resize_field) { %>
        data-resize-height="<%= data.resize_height_field %>"
        data-resize-offset="<%= data.resize_offset_field %>"
        <% } %>
        <% if (data.slide_up_field) { %>
        data-slideup-offset="<%= data.slide_up_offset_field %>"
        <% } %>
        <% if (data.transparent_field) { %>
        data-transparent="<%= data.transparent_offset_field %>"
        <% } %>
        data-height="<%= data.height_field %>"
        <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
        <div class="header-content sq-container-fluid">
            <div class="sq-row">
                {{columns}}
            </div>
        </div>
</div>
