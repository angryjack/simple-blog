<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 12:50
 */

return array(

    'install/checkDb' => 'install/checkDb',
    'install/clearDb' => 'install/clearDb',
    'install/init' => 'install/init',
    'install/delete' => 'install/delete',
    'install/createUser' => 'install/createUser',
    'install' => 'install/index',

    'admin/login' => 'admin/login',
    'admin/doLogin' => 'admin/doLogin',

    'admin/article/getArticles' => 'articles/getArticles',
    'admin/article/getArticle' => 'articles/getArticle',
    'admin/article/addArticle' => 'articles/addArticle',
    'admin/article/editArticle' => 'articles/editArticle',
    'admin/article/deleteArticle' => 'articles/deleteArticle',
    'admin/article/search' => 'articles/search',
    'admin/article' => 'admin/article',

    'admin/category/getCategories' => 'categories/getCategories',
    'admin/category/getCategory' => 'categories/getCategory',
    'admin/category/addCategory' => 'categories/addCategory',
    'admin/category/editCategory' => 'categories/editCategory',
    'admin/category/deleteCategory' => 'categories/deleteCategory',
    'admin/category/search' => 'categories/search',
    'admin/category' => 'admin/category',

    'admin' => 'admin/article',

    'article/getArticles' => 'articles/getArticles',
    'article/search' => 'articles/search',
    'article/([0-9]+)' => 'site/article/$1',
    'category/([0-9]+)' => 'site/category/$1',
    '' => 'site/category'
);

