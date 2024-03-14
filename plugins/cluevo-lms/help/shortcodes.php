<div class="cluevo-help-tab-container">
  <h2><?php esc_html_e("Shortcodes", "cluevo"); ?></h2>
  <p><?php esc_html_e("CLUEVO provides various shortcodes to display your LMS content on any page.", "cluevo"); ?></p>
  <h3>[cluevo]</h3>
  <p><?php esc_html_e("This shortcodes display any cluevo learning tree item", "cluevo"); ?></p>
  <h4><?php esc_html_e("Attributes", "cluevo") ?></h4>
  <table>
    <thead>
      <tr>
        <th><?php esc_html_e("Attribute", "cluevo"); ?></th>
        <th><?php esc_html_e("Description", "cluevo"); ?></th>
        <th><?php esc_html_e("Example", "cluevo"); ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>item</td>
        <td><?php esc_html_e("Sets which item to display", "cluevo"); ?></td>
        <td>
          <pre>[cluevo item="1"]</pre>
        </td>
      </tr>
      <tr>
        <td>row</td>
        <td><?php esc_html_e("Displays items as rows", "cluevo"); ?></td>
        <td>
          <pre>[cluevo item="1" row]</pre>
        </td>
      </tr>
      <tr>
        <td>tile</td>
        <td><?php esc_html_e("Displays items as tiles. This is the default.", "cluevo"); ?></td>
        <td>
          <pre>[cluevo item="1" tile]</pre>
        </td>
      </tr>
    </tbody>
  </table>
  <h3>[cluevo-toc]</h3>
  <p><?php esc_html_e("Displays CLUEVO LMS content in a table of contents type style. Each item and its children is output separately.", "cluevo"); ?></p>
  <h4><?php esc_html_e("Attributes", "cluevo") ?></h4>
  <table>
    <thead>
      <tr>
        <th><?php esc_html_e("Attribute", "cluevo"); ?></th>
        <th><?php esc_html_e("Description", "cluevo"); ?></th>
        <th><?php esc_html_e("Example", "cluevo"); ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>id</td>
        <td><?php esc_html_e("The id of the element you want to display", "cluevo"); ?></td>
        <td>
          <pre>[cluevo id="1"]</pre>
        </td>
      </tr>
      <tr>
        <td>open-all</td>
        <td><?php esc_html_e("Outputs all items opened", "cluevo"); ?></td>
        <td>
          <pre>[cluevo id="1, 2, 3" open-all]</pre>
        </td>
      </tr>
      <tr>
        <td>open</td>
        <td><?php esc_html_e("A comma-separated list of items you want to display as opened", "cluevo"); ?></td>
        <td>
          <pre>[cluevo id="1, 2, 3" open="2, 3"]</pre>
        </td>
      </tr>
      <tr>
        <td>stars</td>
        <td><?php esc_html_e("Display the item rating as stars (if available)", "cluevo"); ?></td>
        <td>
          <pre>[cluevo id="1, 2, 3" stars]</pre>
        </td>
      </tr>
      <tr>
        <td>ratings</td>
        <td><?php esc_html_e("Display the rating as value (if available)", "cluevo"); ?></td>
        <td>
          <pre>[cluevo id="1, 2, 3" ratings]</pre>
        </td>
      </tr>
      <tr>
        <td>hide-meta</td>
        <td><?php esc_html_e("Hide item metadata", "cluevo"); ?></td>
        <td>
          <pre>[cluevo id="1, 2, 3" hide-meta]</pre>
        </td>
      </tr>
      <tr>
        <td>hide-count</td>
        <td><?php esc_html_e("Hide item counts", "cluevo"); ?></td>
        <td>
          <pre>[cluevo id="1, 2, 3" hide-count]</pre>
        </td>
      </tr>
    </tbody>
  </table>
  <h3>[cluevo-guideline]</h3>
  <p><?php esc_html_e("Displays CLUEVO LMS content in a list style. Each item will be output separately without any children.", "cluevo"); ?></p>
  <h4><?php esc_html_e("Attributes", "cluevo") ?></h4>
  <table>
    <thead>
      <tr>
        <th><?php esc_html_e("Attribute", "cluevo"); ?></th>
        <th><?php esc_html_e("Description", "cluevo"); ?></th>
        <th><?php esc_html_e("Example", "cluevo"); ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>items</td>
        <td><?php esc_html_e("A comma separated list of items you want to display", "cluevo"); ?></td>
        <td>
          <pre>[cluevo items="1, 2, 3"]</pre>
        </td>
      </tr>
      <tr>
        <td>open-all</td>
        <td><?php esc_html_e("Outputs all items opened", "cluevo"); ?></td>
        <td>
          <pre>[cluevo items="1, 2, 3" open-all]</pre>
        </td>
      </tr>
      <tr>
        <td>open</td>
        <td><?php esc_html_e("A comma-separated list of items you want to display as opened", "cluevo"); ?></td>
        <td>
          <pre>[cluevo items="1, 2, 3" open="2, 3"]</pre>
        </td>
      </tr>
      <tr>
        <td>stars</td>
        <td><?php esc_html_e("Display the item rating as stars (if available)", "cluevo"); ?></td>
        <td>
          <pre>[cluevo items="1, 2, 3" stars]</pre>
        </td>
      </tr>
      <tr>
        <td>ratings</td>
        <td><?php esc_html_e("Display the rating as value (if available)", "cluevo"); ?></td>
        <td>
          <pre>[cluevo items="1, 2, 3" ratings]</pre>
        </td>
      </tr>
      <tr>
        <td>hide-meta</td>
        <td><?php esc_html_e("Hide item metadata", "cluevo"); ?></td>
        <td>
          <pre>[cluevo items="1, 2, 3" hide-meta]</pre>
        </td>
      </tr>
      <tr>
        <td>hide-count</td>
        <td><?php esc_html_e("Hide item counts", "cluevo"); ?></td>
        <td>
          <pre>[cluevo items="1, 2, 3" hide-count]</pre>
        </td>
      </tr>
    </tbody>
  </table>
</div>
