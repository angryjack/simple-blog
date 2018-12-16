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

    'admin/article/getArticles' => 'article/getArticles',
    'admin/article/getArticle' => 'article/getArticle',
    'admin/article/addArticle' => 'article/addArticle',
    'admin/article/editArticle' => 'article/editArticle',
    'admin/article/deleteArticle' => 'article/deleteArticle',
    'admin/article/search' => 'article/search',
    'admin/article' => 'admin/article',

    'admin/category/getCategories' => 'category/getCategories',
    'admin/category/getCategory' => 'category/getCategory',
    'admin/category/addCategory' => 'category/addCategory',
    'admin/category/editCategory' => 'category/editCategory',
    'admin/category/deleteCategory' => 'category/deleteCategory',
    'admin/category/search' => 'category/search',
    'admin/category' => 'admin/category',

    'admin' => 'admin/article',

    'article/getArticles' => 'article/getArticles',
    'article/search' => 'article/search',
    'article/([0-9]+)' => 'site/article/$1',
    'category/([0-9]+)' => 'site/category/$1',
    '' => 'site/category'
);
