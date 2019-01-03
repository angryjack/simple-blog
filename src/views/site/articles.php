<?php
/**
 * Created by angryjack
 * Date: 2019-01-03 14:59
 */

if (! empty($data)) :
    foreach ($data as $article) : ?>
        <article>
            <h2><a href="/article/<?=$article->id?>"><?=$article->title?></a></h2>
            <span><?=$article->content?></span>
        </article>
    <?php endforeach;
else :
    echo 'Статей нет.';
endif;
