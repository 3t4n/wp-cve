<div class="item sq-tabs element-<%= data.uuid %> <%= data.stax_adv_classes %>" <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %> role="tablist">
    <% _.each(data.tabs_field, function(item) { %>
    <div class="sq-toggle-tab <% if (item.active) { %> active <% } %>">
        <%= item.name %>
    </div>
    <div class="sq-inner-tab">
    <%= item.value %>
    </div>
    <% }) %>
</div>
