<section class="hb-section element-<%= data.uuid %>
    <%= data.content_width_field %> <%= data.column_position_field %> <%= data.column_content_field %>
    <%= data.stax_adv_classes %>"
    <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
    <div class="section-content sq-container-fluid">
        <div class="sq-row">
            {{columns}}
        </div>
    </div>
</section>
