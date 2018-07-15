<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */
?>

<?php include(ROOT . "/views/site/layouts/header.php"); ?>
<?php include(ROOT . "/views/site/layouts/menu.php"); ?>

<div class="container">

    <?php (!isset($main)) ?: include($main); ?>

    <?php if (isset($aside)): ?>

        <?php (!isset($aside)) ?: include($aside); ?>

    <?php endif; ?>
</div>

<?php include(ROOT . "/views/site/layouts/scripts.php"); ?>

<?php if (isset($scripts)): ?>
    <script>
        <?php (!isset($scripts)) ?: include($scripts); ?>
    </script>
<?php endif; ?>

<?php include(ROOT . "/views/site/layouts/footer.php"); ?>

