<div class="item sq-accordion element-<%= data.uuid %> <%= data.stax_adv_classes %>" <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %> role="tablist">
    <% _.each(data.tabs_field, function(item) { %>
    <div class="sq-toggle-acc <% if (item.active) { %> active <% } %>"
         data-icon-minimized="<%= data.icon_minimized_field %>" data-icon-maximized="<%= data.icon_maximized_field %>">
        <span class="acc-icon mdi <% if (item.active) { %> <%= data.icon_minimized_field %> <% } else { %> <%= data.icon_maximized_field %> <% } %> mdi-18px"></span>
        <%= item.name %>
    </div>
    <div class="sq-inner-acc"
    <% if (item.active) { %> style="display: block;" <% } %>>
    <%= item.value %>
    </div>
    <% }) %>
</div>
