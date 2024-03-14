<div class="item sq-map element-<%= data.uuid %> <%= data.stax_adv_classes %>" <% if (data.stax_adv_id) { %> id="<%= data.stax_adv_id %>" <% } %>>
    <iframe class="is-clicked" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
            src="https://maps.google.com/maps?q=<%= data.address_field %>&t=m&z=<%= data.zoom_field %>&output=embed&iwloc=near"></iframe>
</div>
