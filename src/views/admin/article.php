<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.06.2018 22:56
 */

?>
<aside class="col-12 col-md-4 bg-light pt-2" id="articles-aside" v-bind:class="{loading: isLoading}">
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Поиск по статьям"
               v-model="search">
    </div>
    <button type="button" class="btn btn-success btn-block mb-3" id="prepare-for-add-article"
            v-if="editMode"
            @click="editMode = !editMode; clearFields();">Создать Статью
    </button>
    <div id="articles-list">
        <div class="aside-article-block" v-for="article in articles">
            <strong><a class="article-title" @click="getArticle(article.id)">{{article.title}}</a></strong>
            <p class="article-content">{{ (article.content.length > 50) ? article.content.substr(0, 50) + "..." :
                article.content}}</p>
        </div>
    </div>
    <button type="button" class="btn btn-secondary btn-block mb-4" id="load-more-articles"
            v-if="showButton"
            @click="++page; loadMoreArticles();">{{buttonTitle}}
    </button>
</aside>

<main class="col-12 col-md-8">
    <div id="article" class="card" v-bind:class="{loading: isLoading}">
        <div class="card-header">
            {{create ? "Создать новую статью" : "Редактирование"}}
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="article-title">Заголовок</label>
                <input type="text" class="form-control" id="article-title" v-model="title">
            </div>
            <div class="form-group row-m-12">
                <span class="badge badge-primary" v-on:click="pasteTags('html')">html</span>
                <span class="badge badge-danger" v-on:click="pasteTags('css')">css</span>
                <span class="badge badge-warning" v-on:click="pasteTags('js')">js</span>
                <span class="badge badge-success" v-on:click="pasteTags('php')">php</span>
            </div>

            <div class="form-group">
                <label for="article-body">Текст</label>
                <textarea class="form-control" id="article-body" rows="10" v-model="body"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="article-category">Категория</label>
                    <select class="form-control" id="article-category" v-model="category">
                        <option v-for="category in categories" v-bind:value="category.id">{{category.title}}</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="article-url">Короткая ссылка</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">https://site.ru/</span>
                        </div>
                        <input type="text" class="form-control" id="aricle-url" aria-describedby="basic-addon3"
                               v-model="url">
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="article-meta-description">Описание</label>
                    <input type="text" class="form-control" id="article-meta-description"
                           v-model="meta_description">
                </div>
                <div class="form-group col-md-6">
                    <label for="article-meta-keywords">Ключевые слова</label>
                    <input type="text" class="form-control" id="article-meta-keywords" v-model="meta_keywords">
                </div>
            </div>
            <div class="form-group">

                <button type="button" class="btn btn-success"
                        v-if="create"
                        v-on:click="addArticle()">Добавить
                </button>

                <button type="button" class="btn btn-warning"
                        v-if="!create"
                        v-on:click="editArticle()">Изменить
                </button>
                <button type="button" class="btn btn-danger"
                        v-if="!create"
                        v-on:click="deleteArticle()">Удалить
                </button>

            </div>
        </div>
        <div class="card-footer text-muted" id="result-article">
            {{result}}
        </div>
    </div>

</main>
<script>
    let aside = new Vue({
        el: '#articles-aside',
        data: {
            page: 1,
            articles: [],
            search: '',
            editMode: false,
            isLoading: false,
            showButton: true,
            buttonTitle: 'Загрузить еще'
        },
        watch: {
            search: function () {
                if (aside.search.length > 2) {
                    this.searchArticles();
                } else if (aside.search.length < 1) {
                    this.articles.length = 0;
                    this.getArticles();
                }
            }
        },
        methods: {
            getArticles: function (page) {
                this.isLoading = true;
                page = page || this.page;
                axios({
                    method: 'post',
                    url: "/admin/article/getArticles",
                    data: {
                        page: page,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        aside.articles = aside.articles.concat(response.data.answer.data);
                    } else {
                        aside.showButton = false;
                        //response.data.answer.text
                    }
                    this.buttonTitle = "Загрузить еще";
                    aside.isLoading = false;
                }).catch(function (error) {
                });
            },
            getArticle: function (id) {
                aside.editMode = true;
                article.isLoading = true;
                axios({
                    method: 'post',
                    url: "/admin/article/getArticle",
                    data: {
                        id: id,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        article.create = false;
                        article.id = response.data.answer.data.id;
                        article.title = response.data.answer.data.title;
                        article.body = response.data.answer.data.content;
                        article.category = response.data.answer.data.category_id;
                        article.url = response.data.answer.data.url;
                        article.description = response.data.answer.data.meta_description;
                        article.keywords = response.data.answer.data.meta_keywords;
                    } else {
                        //response.data.answer.text
                    }
                    article.isLoading = false;
                }).catch(function (error) {
                });
            },
            loadMoreArticles: function () {
                this.getArticles(this.page);
            },
            searchArticles: _.debounce(
                function () {
                    aside.isLoading = true;
                    axios({
                        method: 'post',
                        url: "/admin/article/searchArticles",
                        data: {
                            search: this.search
                        }
                    }).then(function (response) {
                        if (response.data.status === "success") {
                            aside.articles = response.data.answer.data;
                        } else {
                            //response.data.answer.text
                        }
                        aside.isLoading = false;
                    }).catch(function (error) {
                    });
                }, 500),
            clearFields: function () {
                article.clearFields();
            }
        },
        created: function () {
            this.getArticles();
        }
    })

    let article = new Vue({
        el: '#article',
        data: {
            create: true,
            isLoading: false,
            id: '',
            title: '',
            body: '',
            category: '',
            url: '',
            meta_description: '',
            meta_keywords: '',
            result: '',
            categories: []
        },
        methods: {
            getCategories: function (page) {
                axios({
                    method: 'post',
                    url: "/admin/category/getCategories",
                    data: {
                        page: page,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        article.categories = response.data.answer.data;
                    } else {
                        aside.showButton = false;
                        //response.data.answer.text
                    }
                }).catch(function (error) {
                });
            },
            addArticle: function () {
                article.isLoading = true;
                axios({
                    method: 'post',
                    url: "/admin/article/addArticle",
                    data: {
                        title: article.title,
                        content: article.body,
                        category: article.category,
                        url: article.url,
                        description: article.meta_description,
                        keywords: article.meta_keywords,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        aside.articles = [];
                        aside.getArticles(1);
                        article.clearFields();
                        article.result = 'Успешно!';
                    } else {
                        article.result = response.data.answer.text;
                    }
                    article.isLoading = false;
                }).catch(function (error) {
                });
            },
            editArticle: function () {
                article.isLoading = true;
                axios({
                    method: 'post',
                    url: "/admin/article/editArticle",
                    data: {
                        id: article.id,
                        title: article.title,
                        content: article.body,
                        category: article.category,
                        url: article.url,
                        description: article.meta_description,
                        keywords: article.meta_keywords,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        aside.articles = [];
                        aside.getArticles(1);
                        article.result = 'Успешно!';
                    } else {
                        article.result = response.data.answer.text;
                    }
                    article.isLoading = false;
                }).catch(function (error) {
                });
            },
            deleteArticle: function () {
                article.isLoading = true;
                axios({
                    method: 'post',
                    url: "/admin/article/deleteArticle",
                    data: {
                        id: article.id,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        aside.articles = [];
                        aside.getArticles(1);
                        article.clearFields();
                        article.result = 'Успешно!';
                    } else {
                        article.result = response.data.answer.text;
                    }
                    article.isLoading = false;
                }).catch(function (error) {
                });
            },
            clearFields: function () {
                article.create = true;
                article.id = '';
                article.title = '';
                article.body = '';
                article.url = '';
                article.meta_description = '';
                article.meta_keywords = '';
            },
            pasteTags: function (tags) {
                article.body += '\n<pre><code class="' + tags + '"> </code></pre>';
            }
        },
        created: function () {
            this.getCategories(1);
        }
    })
</script>