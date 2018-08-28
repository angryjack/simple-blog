<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 13:03
 */
?>
<div class="articles__container">
    <div class="articles">

        <?php if(isset($articles)) :?>
            <?php foreach ($articles as $items => $article): ?>

            <article class="article-block">
                <a class="article-block__title" href="<?php
                if (isset($article->url)) {
                    echo $article->url;
                } else {
                    echo "/article/$article->id";
                }
                ?>">
                    <?= $article->title ?>
                </a>
                <div class="article-block__description">
                    <?= htmlspecialchars($article->content); ?>
                </div>
                <div class="article-block__footer">
                    <div class="article-block__category">
                        <?php if(isset($article->category)): ?>
                            Категория: <a href="<?php
                            if (isset($article->category_link)) {
                                echo $article->category_link;
                            } else {
                                echo '/category/' . $article->category_id;
                            } ?>">
                                <?= $article->category ?>
                            </a>
                        <?php else: ?>
                            Без категории
                        <?php endif; ?>
                    </div>
                </div>
            </article>

        <?php endforeach; ?>
        <?php endif; ?>

        <article class="article-block" v-for="article in articles">
            <a class="article-block__title" :href="(article.url === null) ? '/article/' + article.id : article.url">{{article.title}}</a>
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
        el: '.articles__container',
        data: {
            page: 1,
            articles: [],
            search: '',
            category: '<?php if(isset($category->id)) echo $category->id; ?>',
            showButton: true,
            buttonTitle: 'Загрузить еще'
        },
        watch: {
            search: function () {
                if (this.search.length > 2) {
                    this.searchArticles();
                } else if (this.search.length < 1) {
                    this.articles.length = 0;
                    this.getArticles();
                }
            }
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
            },
            searchArticles: _.debounce(
                function () {
                    axios({
                        method: 'post',
                        url: "/article/searchArticles",
                        data: {
                            search: this.search
                        }
                    }).then(function (response) {
                        if (response.data.status === "success") {
                            this.articles = response.data.answer.data;
                        } else {
                            //response.data.answer.text
                        }
                    }).catch(function (error) {
                    });
                }, 500),
        }
    })
</script>