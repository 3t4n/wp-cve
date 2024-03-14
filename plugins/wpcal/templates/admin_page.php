<?php /* Similar loading code in client/src/assets/css/admin_app.css */?>
<style>
@keyframes rotation {
  from {
    -webkit-transform: rotate(0deg);
  }
  to {
    -webkit-transform: rotate(359deg);
  }
}

#wpcal_admin_app.loading-indicator-initial::before {
  content: "";
  position: fixed;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 4px solid #567bf3;
  border-top-color: #fff;
  background-color: #fff;
  box-shadow: 0 0 0px 3px #fff, 0 0 7px rgba(0, 0, 0, 0.9);
  left: 50%;
  top: 80px;
  z-index: 2;
  margin-left: -10px;
  margin-top: -10px;
  -webkit-animation: rotation 0.8s infinite linear;
  animation: rotation 0.8s infinite linear;
}
</style>
<div id="wpcal_admin_app" class="loading-indicator-initial">
</div>
