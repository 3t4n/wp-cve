<div id="mapPopup">
    <span class="helper"></span>
    <div class="mapContainer">
        <div class="popupCloseButton">&times;</div>
        <div class="mapPopupContent">
            <div id="placeMapHere" style="height: 100%; width: 100%;" ></div>
            <div class="menu-control">
                <button class="menu-control-button"><?php echo __("Menu","epakapl")?></button>
            </div>
            <div class="menu">
                <div style="padding: 0.6rem 1.6rem 0.6rem 1.6rem;" class="menu-content">
                    <div class="localise-me" style="display: flex; justify-content: left;">
                        <i class="epaka-icons icon-sniper"></i>
                        <span class="font-weight-bold"><?php echo __("Zlokalizuj mnie","epakapl")?></span>
                    </div>
                    <div class="input-container">
                        <input id="search-input" placeholder="<?php echo __("Wpisz kod pocztowy, miasto lub punkt","epakapl")?>" style="font-size: 12px;" autocomplete="off" class="input-field" type="text"/>
                        <span id="search" class="search-button icon-wrap">
                            <i class="epaka-icons icon-search icon"></i>
                        </span>
                    </div>
                </div>
                <div class="pointsList" style="margin: 0px;">
                    <div class="choose-point-info choose-point-start" style="display: none;">
                        <?php echo __("Wyszukaj lub wybierz lokalizację","epakapl")?><br /><?php echo __("aby wyświetlić listę punktów","epakapl")?>
                    </div>
                    <div class="choose-point-info choose-point-searching" style="display: none;">
                        <?php echo __("Trwa wyszukiwanie punktów, proszę czekać...","epakapl")?><br />
                        <br />
                        <div class="lds-roller-container" style="display: none;">
                            <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                        </div>
                    </div>
                    <div class="choose-point-info choose-point-empty" style="display: none;">
                        <?php echo __("Brak punktów w wybranym obszarze. Zmień obszar lub zmniejsz przybliżenie mapy.","epakapl")?>
                    </div>
                    <div class="choose-point-info choose-point-notfound" style="display: none;">
                        <?php echo __("Nie znaleziono wybranej lokalizacji","epakapl")?>
                        <br />
                        <br />
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.mapBind();
</script>