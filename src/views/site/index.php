<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */
?>

<?php include(ROOT . "/src/views/site/layouts/header.php"); ?>
<?php include(ROOT . "/src/views/site/layouts/menu.php"); ?>

<main class="main">

    <?php (!isset($slider)) ?: include($slider); ?>

    <?php (!isset($content)) ?: include($content); ?>

    <?php (!isset($aside)) ?: include($aside); ?>

    <?= (!isset($message)) ?: ($message); ?>

</main>

<?php include(ROOT . "/src/views/site/layouts/footer.php"); ?>

