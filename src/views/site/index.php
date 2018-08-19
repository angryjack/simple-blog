<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */
?>

<?php include(ROOT . "/src/views/site/layouts/header.php"); ?>
<?php include(ROOT . "/src/views/site/layouts/menu.php"); ?>

<div class="container">

    <?php (!isset($slider)) ?: include($slider); ?>

    <?php (!isset($main)) ?: include($main); ?>

    <?php (!isset($aside)) ?: include($aside); ?>

    <?php if(isset($message)):?>
        <div class="container__slider" style="background: #ddd">
            <?= $message; ?>
        </div>
    <?php endif; ?>
</div>

<?php include(ROOT . "/src/views/site/layouts/footer.php"); ?>

