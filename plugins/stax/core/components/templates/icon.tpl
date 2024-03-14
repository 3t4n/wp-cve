<div class="item sq-icon element-<%= data.uuid %> <%= data.stax_adv_classes %>" <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
    <a class="item-child" href="<%= data.url_field %>" target="<%= data.url_target_field %>">
        <span class="mdi <%= data.icon_type_field %> <%= data.icon_size_field %>"></span>
    </a>
</div>
