<div class="item sq-search element-<%= data.uuid %> <%= data.stax_adv_classes %>" <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
    <div class="item-child">
        <form class="floating-placeholder" action="<%= data.form_action %>" method="GET">
            <span class="mdi mdi-magnify <%= data.icon_size_field %>"></span>
            <input type="text" name="s" class="input-text" placeholder="<%= data.placeholder_text_field %>" value="" required>
            <% if (data.label_field) { %>
            <label for="search" class="input-label">Search for</label>
            <% } %>
        </form>
    </div>
</div>
