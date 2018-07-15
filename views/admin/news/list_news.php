<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 03.07.2018 23:52
 */

?>
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">Дата создания</th>
        <th scope="col">Заголовок</th>
        <th scope="col">Категория</th>
        <th scope="col">Изменить</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($news) : ?>
        <?php foreach ($news as $key => $value) : ?>
            <tr>
                <td>25.02.2018 14:55</td>
                <td><?= $value['title']; ?></td>
                <td><?php
                    if ($value['category']) {
                        echo $value['category'];
                    } else {
                        echo "Без категории";
                    } ?>
                </td>
                <td><a href="/admin/news/<?= $value['id']; ?>">Изменить</a></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">Новостей нет</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>