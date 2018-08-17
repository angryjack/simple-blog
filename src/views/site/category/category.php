<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 13:03
 */
?>
<div class="articles">
    <div class="articles__container">
        <div class="article-block" v-for="article in articles">
            <h3 class="article-block__title">{{article.title}}</h3>
            <div class="article-block__description">{{ (article.content.length > 500) ? article.content.substr(0, 50) +
                "..." :
                article.content}}
            </div>
            <div class="article-block__footer">
                <div class="article-block__category">{{article.category}}</div>
                <a class="article-block__button" :href="(article.url === null) ? '/article/' + article.id : article.url">Читать далее</a>
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
        },
        created: function () {
            this.getArticles();
        }
    })
</script>