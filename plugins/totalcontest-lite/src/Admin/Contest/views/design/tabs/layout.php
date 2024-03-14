<customizer-control
        type="radioboxes"
        label="<?php esc_attr_e( 'Type', 'totalcontest' ); ?>"
        ng-model="$root.settings.design.layout.type"
        options="{'grid': 'Grid', 'list': 'List'}"></customizer-control>
<customizer-control
        type="number"
        label="<?php esc_attr_e( 'Columns', 'totalcontest' ); ?>"
        ng-model="$root.settings.design.layout.columns"
        options="{min: 1, max: 16, step: 1}"></customizer-control>
<customizer-control
        type="text"
        label="<?php  esc_html_e( 'Maximum width', 'totalcontest' ); ?>"
        ng-model="$root.settings.design.layout.maxWidth"></customizer-control>
<customizer-control
        type="text"
        label="<?php  esc_html_e( 'Gutter', 'totalcontest' ); ?>"
        ng-model="$root.settings.design.layout.gutter"></customizer-control>
<customizer-control
        type="text"
        label="<?php  esc_html_e( 'Border Radius', 'totalcontest' ); ?>"
        ng-model="$root.settings.design.layout.radius"></customizer-control>
