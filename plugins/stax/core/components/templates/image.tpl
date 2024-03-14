<div class="item sq-image element-<%= data.uuid %> <%= data.stax_adv_classes %>" <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
    <a class="item-child" href="<%= data.url_field %>" target="<%= data.url_target_field %>">
        <img src="<%= data.image_source_field %>" class="item-image" alt="<%= data.image_alt_field %>">
    </a>
</div>
