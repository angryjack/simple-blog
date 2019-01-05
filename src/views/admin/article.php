<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.06.2018 22:56
 */

?>

<div class="wrapper">
    <aside class="aside" id="aside">
        <ul v-for="article in articles">
            <li>{{article.title}}</li>
        </ul>
    </aside>
    <main class="main" id="article">
        <div class="main-block">
            <div class="panel-form-block">
                <label for="articleTitle" class="panel-label">Title</label>
                <input type="text" class="panel-input" id="articleTitle"
                       v-model="article.title">
            </div>

            <div class="panel-form-block">
                <label for="articleContent" class="panel-label">Content</label>
                <textarea name="articleContent" id="articleContent" cols="30" rows="10"
                          v-model="article.content">
                </textarea>
            </div>

            <div class="panel-form-block">
                <label for="articleTags" class="panel-label">Tags</label>
                <input type="text" class="panel-input" id="articleTags"
                       v-model="article.tags">
            </div>

            <div class="panel-form-block">
                <label for="articleMetaDescription" class="panel-label">Meta Description</label>
                <input type="text" class="panel-input" id="articleMetaDescription"
                       v-model="article.metaDescription">
            </div>

            <div class="panel-form-block">
                <label for="articleMetaKeyword" class="panel-label">Meta Keywords</label>
                <input type="text" class="panel-input" id="articleMetaKeyword"
                       v-model="article.metaKeywords">
            </div>

            <button @click="store">Create</button>
        </div>
    </main>
</div>

<script>
    let aside = new Vue({
        el: '#aside',
        data: {
            articles: []
        },
        methods: {
            getArticles: function () {
                axios({
                    method: 'post',
                    url: "/articles/listing",
                    data: {
                        page: 1
                    }
                }).then((response) => {
                    if (response.data) {
                        console.log(response.data);
                        this.articles = this.articles.concat(response.data);
                    }
                }).catch(function (error) {});
            }
        },
        created: function () {
            this.getArticles();
        }
    });

    let article = new Vue({
        el: '#article',
        data: {
            article: {
                title: '',
                content: '',
                metaDescription: '',
                metaKeywords: [],
                tags: [],
            }
        },
        methods: {
            store: function () {
                axios({
                    method: 'post',
                    url: "/article/store",
                    data: {
                        token: this.getToken(),
                        article: this.article
                    }
                }).then((response) => {

                }).catch(function (error) {});
            },
            update: function () {

            },
            destroy: function () {

            },
            getToken: function () {
                let name = 'token';
                let matches = document.cookie.match(new RegExp("(?:^|; )" +
                    name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
                return matches ? decodeURIComponent(matches[1]) : undefined;
            }
        }
    });
</script>

<style>
    body{
        margin: 0;
    }
    .wrapper{
        display: flex;
    }
    .aside{
        background: pink;
        width: 250px;
    }
    .main{
        background: #ddd;
        flex: auto;
    }
</style>
