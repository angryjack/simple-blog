<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 01.08.2018 22:50
 */
?>
<div class="content__container">

    <div class="container__links">
        <?php if(isset($article->category)): ?>

            Категория: <a href="<?=
            isset($article->category_link)
                ? htmlspecialchars($article->category_link)
                : htmlspecialchars('/category/' . $article->category_id);

            ?>"><?= htmlspecialchars( $article->category) ?></a>

        <?php else: ?>
            Без категории
        <?php endif; ?>
    </div>

    <div class="container__text">
        <?= isset($article->content) ? \Angryjack\models\Site::replaceTags(htmlspecialchars($article->content)) : '' ?>
    </div>

</div>