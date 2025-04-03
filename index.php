<?php	
require_once "controllers/template.controller.php";

require_once "extensions/vendor/autoload.php";

require_once "controllers/employees.controller.php";
require_once "models/employees.model.php";

require_once "controllers/userrights.controller.php";
require_once "models/userrights.model.php";

require_once "controllers/clients.controller.php";
require_once "models/clients.model.php";

require_once "controllers/home.controller.php";
require_once "models/home.model.php";

$template = new ControllerTemplate();
$template -> ctrTemplate();