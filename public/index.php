<?php
require_once("./loader.php");
$URLWatcher = new URLWatcher($PageRequest);
$URLWatcher->fetchPage();