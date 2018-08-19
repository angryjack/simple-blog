<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.06.2018 22:56
 */
?>

<aside class="col-sm px-4" id="categories-aside" v-bind:class="{loading: isLoading}">
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Поиск по категориям"
               v-model="search">
    </div>
    <button type="button" class="btn btn-success btn-block mb-3" id="prepare-for-add-category"
            v-if="editMode"
            @click="editMode = !editMode; clearFields();">Создать Категорию
    </button>
    <div id="categories-list">
        <div class="aside-category-block" v-for="category in categories">
            <a class="category-title" @click="getCategory(category.id)">{{category.title}}</a>
        </div>
    </div>
    <button type="button" class="btn btn-secondary btn-block my-4" id="load-more-categories"
            v-if="showButton"
            @click="++page; loadMoreCategories();">Загрузить еще
    </button>
</aside>

<main class="col-7">

    <div class="card" id="category" v-bind:class="{loading: isLoading}">
        <div class="card-header">
            {{create ? "Создать новую категорию" : "Редактирование"}}
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="category-title">Заголовок</label>
                <input type="text" name="category-title" class="form-control" v-model="title">
            </div>
            <div class="form-group">
                <label for="category-content">Текст</label>
                <textarea class="form-control" name="category-content" rows="10" v-model="content"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="category-description">Описание</label>
                    <input type="text" class="form-control" name="category-description"
                           v-model="description">
                </div>
                <div class="form-group col-md-6">
                    <label for="category-keywords">Ключевые слова</label>
                    <input type="text" class="form-control" name="category-keywords" v-model="keywords">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="category-url">Короткая ссылка</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://site.ru/</span>
                        </div>
                        <input type="text" class="form-control" name="category-url" aria-describedby="basic-addon3"
                               v-model="url">
                    </div>
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
        <div class="card-footer text-muted" id="result-category">
            {{result}}
        </div>
    </div>

</main>
<script>
    let aside = new Vue({
        el: '#categories-aside',
        data: {
            page: 1,
            categories: [],
            search: '',
            editMode: false,
            isLoading: false,
            showButton: true
        },
        watch: {
            search: function () {
                if (aside.search.length > 2) {
                    this.searchCategories();
                } else if(aside.search.length < 1){
                    this.categories.length = 0;
                    this.getCategories();
                }
            }
        },
        methods: {
            getCategories: function () {
                this.isLoading = true;
                axios({
                    method: 'post',
                    url: "/admin/category/getCategories",
                    data: {
                        page: this.page,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        aside.categories = aside.categories.concat(response.data.answer.data);
                    } else {
                        aside.showButton = false;
                        //response.data.answer.text
                    }
                    aside.isLoading = false;
                }).catch(function (error) {
                });
            },
            getCategory: function (id) {
                aside.editMode = true;
                category.isLoading = true;
                axios({
                    method: 'post',
                    url: "/admin/category/getCategory",
                    data: {
                        id: id,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        category.create = false;
                        category.id = response.data.answer.data.id;
                        category.title = response.data.answer.data.title;
                        category.content = response.data.answer.data.content;
                        category.description = response.data.answer.data.description;
                        category.keywords = response.data.answer.data.keywords;
                        category.url = response.data.answer.data.url;
                    } else {
                        //response.data.answer.text
                    }
                    category.isLoading = false;
                }).catch(function (error) {
                });
            },
            loadMoreCategories: function () {
                this.getCategories(this.page);
            },
            searchCategories: _.debounce(
                function () {
                    this.isLoading = true;
                    axios({
                        method: 'post',
                        url: "/admin/category/searchCategories",
                        data: {
                            search: this.search
                        }
                    }).then(function (response) {
                        if (response.data.status === "success") {
                            aside.categories = response.data.answer.data;
                        } else {
                            //response.data.answer.text
                        }
                        aside.isLoading = false;
                    }).catch(function (error) {
                    });
                }, 500),
            clearFields: function () {
                category.clearFields();
            },
        },
        created: function () {
            this.getCategories();
        }
    })

    let category = new Vue({
        el: '#category',
        data: {
            create: true,
            isLoading: false,
            id: '',
            title: '',
            content: '',
            description: '',
            keywords: '',
            url: '',
            result: '',
        },
        methods: {
            addArticle: function () {
                category.isLoading = true;
                axios({
                    method: 'post',
                    url: "/admin/category/addCategory",
                    data: {
                        title: category.title,
                        description: category.description,
                        url: category.url,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        aside.categories = [];
                        aside.getCategories(1);
                        category.clearFields();
                        category.result = 'Успешно!';
                    } else {
                        category.result = response.data.answer.text;
                    }
                    category.isLoading = false;
                }).catch(function (error) {
                });
            },
            editArticle: function () {
                category.isLoading = true;
                axios({
                    method: 'post',
                    url: "/admin/category/editCategory",
                    data: {
                        id: category.id,
                        title: category.title,
                        content: category.content,
                        description: category.description,
                        keywords: category.keywords,
                        url: category.url,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        aside.categories = [];
                        aside.getCategories(1);
                        category.result = 'Успешно!';
                    } else {
                        category.result = response.data.answer.text;
                    }
                    category.isLoading = false;
                }).catch(function (error) {
                });
            },
            deleteArticle: function () {
                category.isLoading = true;
                axios({
                    method: 'post',
                    url: "/admin/category/deleteCategory",
                    data: {
                        id: category.id,
                        token: getCookie("token")
                    }
                }).then(function (response) {
                    if (response.data.status === "success") {
                        aside.categories = [];
                        aside.getCategories(1);
                        category.clearFields();
                        category.result = 'Успешно!';
                    } else {
                        category.result = response.data.answer.text;
                    }
                    category.isLoading = false;
                }).catch(function (error) {
                });
            },
            clearFields: function () {
                category.create = true;
                category.id = '';
                category.title = '';
                category.content = '';
                category.description = '';
                category.keywords = '';
                category.url = '';
            }
        }
    })
</script>