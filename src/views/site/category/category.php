<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 13:03
 */
?>
<div class="content__container">
    <div class="articles">

        <?php if(isset($articles)) :?>
            <?php foreach ($articles as $items => $article): ?>

            <article class="article-block">
                <a class="article-block__title" href="/<?=
                    isset($article->url)
                    ? htmlspecialchars($article->url)
                    : htmlspecialchars("article/$article->id")
                ?>">
                    <?= htmlspecialchars($article->title) ?>
                </a>
                <div class="article-block__description">
                    <?= stristr(htmlspecialchars($article->content), '<br class="preview">', true) ?>
                </div>
                <div class="article-block__footer">
                    <div class="article-block__category">
                        <?php if(isset($article->category)): ?>

                            Категория: <a href="/<?=
                                isset($article->category_link)
                                ? htmlspecialchars($article->category_link)
                                : htmlspecialchars("category/$article->category_id");

                             ?>"><?= htmlspecialchars( $article->category) ?></a>

                        <?php else: ?>
                            Без категории
                        <?php endif; ?>
                    </div>
                </div>
            </article>

        <?php endforeach; ?>
        <?php endif; ?>

        <article class="article-block" v-for="article in articles">
            <a class="article-block__title" :href="(article.url === null) ? '/article/' + article.id : '/' + article.url">{{article.title}}</a>
            <div class="article-block__description">{{ (article.content.length > 250) ? article.content.substr(0, 250) +
                "..." :
                article.content}}
            </div>
            <div class="article-block__footer">
                <div class="article-block__category">
                    {{ article.category === null ? 'Без категории' : 'Категория:' + article.category }}
                </div>
            </div>
        </article>

    </div>
    <div class="buttons" v-if="showButton">
        <button class="buttons__load-more" @click="++page; getArticles();">{{buttonTitle}}</button>
    </div>
</div>
<script>
    let articles = new Vue({
        el: '.content__container',
        data: {
            page: 1,
            articles: [],
            category: '<?= isset($category->id) ? json_encode($category->id) : ""?>',
            showButton: true,
            buttonTitle: 'Загрузить еще'
        },
        methods: {
            getArticles: function (page) {
                header.isRotate = true;
                page = page || this.page;
                axios({
                    method: 'post',
                    url: "/article/getArticles",
                    data: {
                        page: page,
                        category: this.category
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        articles.articles = articles.articles.concat(response.data.answer.data);
                    } else {
                        articles.showButton = false;
                        //response.data.answer.text
                    }
                    this.buttonTitle = "Загрузить еще";
                    header.isRotate = false;
                }).catch(function (error) {});
            },
            loadMoreArticles: function () {
                this.getArticles(this.page);
            }
        }
    })
</script>