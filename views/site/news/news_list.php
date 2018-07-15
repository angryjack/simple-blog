<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 13:03
 */
?>
<div class="news-container">
    <?php foreach ($news as $items => $item) : ?>
        <div class="news-block">
            <h3 class="news-block__title"><?= $item['title']; ?></h3>
            <div class="news-block__description"><?= $item['content']; ?></div>
            <div class="news-block__footer">
                <div class="news-block__category"><?= $item['category']; ?></div>
                <a class="news-block__button" href="<?= $item['id']; ?>">Читать далее</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="buttons">
    <button class="buttons__load-more">+Загрузить еще</button>
</div>