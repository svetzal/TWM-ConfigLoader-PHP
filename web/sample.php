<?php
  require_once("lib/config.inc.php");
  $CFG = new TWM_Config();
?>
<html>
<title><?= $CFG->data['default']['title'] ?></title>
<body>
<h1><?= $CFG->data['default']['heading'] ?></h1>
<p><?= $CFG->data['default']['message'] ?></p>
<p><?= $CFG->data['newsection']['stuff'] ?></p>
</body>
</html>
