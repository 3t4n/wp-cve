<div class="<%= data.container_for %>-item sq-column sq-col element-<%= data.uuid %> <%= data.stax_adv_classes %>
    <%= data.shrink_field %> <%= data.v_align_field %>
    <% if (!data.shrink_field) { %> <%= data.h_align_field %> <% } %>
    <% if (data.css_class_advanced) { %> <%= data.css_class_advanced %> <% } %>"
    <% if (data.css_id_advanced) { %> id="<%= data.css_id_advanced %>" <% } %>
    data-item-type="column" data-element-id="<%= data.uuid %>" data-element-context="Column"
    <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
    {{columnOverlay}}
</div>
