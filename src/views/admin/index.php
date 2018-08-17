<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */
?>

<?php include(ROOT . "/src/views/admin/layouts/header.php"); ?>

<div class="container-fluid">
    <div class="row flex-xl-nowrap">

        <header class="col-sm">
            <?php include(ROOT . "/src/views/admin/layouts/sidebar.php"); ?>
        </header>

        <?php (!isset($main)) ?: include($main); ?>

    </div>
</div>

<?php include(ROOT . "/src/views/admin/layouts/footer.php"); ?>