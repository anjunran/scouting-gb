<?php
    MyPackage::import(ROUTE_PARAMS_FETCHER);
    $pagetitle = new ParamGrab;
    $pagetitle = $pagetitle->getUrlParam();
    $pagetitle = $pagetitle === "" ? "HOME" : strtoupper($pagetitle);
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scouting <?= $pagetitle ?></title>
</head>