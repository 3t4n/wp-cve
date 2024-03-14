<?php
function lcw_load_standings( $standings_list ){
  ob_start();
?>
    <table class="table table-filter">
          <thead>
            <tr>
              <th>Name</th>
              <th>POS</th>
              <th>Played</th>
              <th>Won</th>
              <th>Lost</th>
              <th class="hidden-xs">Tied</th>
              <th class="hidden-xs">Drawn</th>
              <th>Points</th>
              <th>NRR</th>
            </tr>
          </thead>
          <tbody>
          	<?php 
          		foreach ($standings_list->teams as $team ) { 
          	?>
            <tr>
              <td>
                <div class="media">
                    
                    <div class="media-body">
                      <img src="<?php echo $team->logoUrl; ?>" class="media-photo">
                        <span class="media-meta pull-right hidden-xs"><?php echo $team->groupName ?></span>
                        <h4 class="title">
                          <?php echo $team->shortName ?> <span class="hidden-xs">-</span> 
                        </h4>
                        <p class="summary hidden-xs"> <?php echo $team->name ?></p>
                    </div>
                  </div>
              </td>
              <td><?php echo $team->position ?></td>
              <td><?php echo $team->played?></td>
              <td><?php echo $team->won?></td>
              <td><?php echo $team->lost?></td>
              <td class="hidden-xs"><?php echo $team->tied?></td>
              <td class="hidden-xs"><?php echo $team->drawn?></td>
              <td><?php echo $team->points?></td>
              <td><?php echo $team->netRunRate?></td>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <th>Name</th>
              <th>POS</th>
              <th>Played</th>
              <th>Won</th>
              <th>Lost</th>
              <th class="hidden-xs">Tied</th>
              <th class="hidden-xs">Drawn</th>
              <th>Points</th>
              <th>NRR</th>
            </tr>
          </tfoot>
        </table>    
      <?php
}