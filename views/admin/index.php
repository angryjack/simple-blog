<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */
?>

<?php include(ROOT . "/views/admin/layouts/header.php"); ?>
<?php include(ROOT . "/views/admin/layouts/menu.php"); ?>

<div class="container-fluid">
    <div class="row flex-xl-nowrap">

        <div class="col-12 col-md-3 col-xl-2 bd-sidebar">
            <?php include(ROOT . "/views/admin/layouts/sidebar.php"); ?>
        </div>

        <main class="col-12 col-md-9 col-xl-8 py-md-3 pl-md-5 bd-content" role="main">
            <?php (!isset($main)) ?: include($main); ?>
        </main>

        <?php if (isset($aside)): ?>
            <aside class="col-12 col-md-3 col-xl-2">
                <?php (!isset($aside)) ?: include($aside); ?>
            </aside>
        <?php endif; ?>
    </div>
</div>

<?php include(ROOT . "/views/admin/layouts/footer.php"); ?>