<?php

$heredoc = <<<HEREDOC

<div id="cgMessagesContainer" class="cg_hide cg_messages_container $cgFeControlsStyle $BorderRadiusClass">
   <div id="cgMessagesDiv">
       <div id="cgMessagesClose">
   
        </div>
       <div id="cgMessagesContent">
            Photo contest ist over
        </div>
    </div>
</div>

HEREDOC;

echo $heredoc;

$heredoc = <<<HEREDOC

<div id="cgMessagesContainerPro" class="cg_hide cg_messages_container_pro $cgFeControlsStyle $BorderRadiusClass">
   <div id="cgMessagesDiv">
       <div id="cgMessagesCloseProContainer">
           <div id="cgMessagesClose">
            </div>
       </div>
       <div id="cgMessagesContent">
            Photo contest ist over
        </div>
    </div>
</div>

HEREDOC;

echo $heredoc;


?>