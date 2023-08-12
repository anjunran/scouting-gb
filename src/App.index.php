<!DOCTYPE html>
<html lang="en">
<?php MyPackage::import(INDEX_HTML_HEAD) ?>

<body>
    <?php
    session_start();
    MyPackage::import(isset($_SESSION['userId']) ? AUTH_NAVBAR : HOME_NAVBAR);
    ?>
    <div class="App">
        <?php
        MyPackage::import(APP_ROUTER, $props);
        ?>
    </div>
    <?php MyPackage::import(JS_BUNDLES) ?>
</body>

</html>