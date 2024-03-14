<?php
header("Content-Type: application/javascript");
header("X-Robots-Tag: none");
header("Service-Worker-Allowed: /");

?>
importScripts("https://cdn.p-n.io/pushly-sw.min.js" + (self.location || {}).search || "");
