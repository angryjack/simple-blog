<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 13:03
 */
?>
<div class="articles">
    <div class="articles__container">

        <?php foreach ($articles as $items => $article): ?>

            <div class="article-block">
                <a class="article-block__title" href="<?php
                    if (isset($article->url)) {
                        echo $article->url;
                    } else {
                        echo "/article/$article->id";
                    }
                    ?>">
                    <?= substr($article->title, 0, 50); ?>
                </a>
                <div class="article-block__description">
                    <?= htmlspecialchars( substr($article->content, 0, 240) ); ?>
                </div>
                <div class="article-block__footer">
                    <div class="article-block__category">
                        <?php if(isset($article->category)): ?>
                            Категория: <?= substr($article->category, 0, 50); ?>
                        <?php else: ?>
                            Без категории
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

        <div class="article-block" v-for="article in articles">
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
        </div>
    </div>
    <div class="buttons" v-if="showButton">
        <button class="buttons__load-more" @click="++page; getArticles();">{{buttonTitle}}</button>
    </div>
</div>
<script>
    let articles = new Vue({
        el: '.articles',
        data: {
            page: 1,
            articles: [],
            search: '',
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