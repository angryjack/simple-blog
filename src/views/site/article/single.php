<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 01.08.2018 22:50
 */
?>
<div class="container__article">

    <div class="container__links">
        <?php if(isset($article->category)): ?>
            Категория: <?= substr($article->category, 0, 50); ?>
        <?php else: ?>
            Без категории
        <?php endif; ?>
    </div>

    <div class="container__text">
        <?= $article->content ?>
    </div>

</div>