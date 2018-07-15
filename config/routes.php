<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 12:50
 */

return array(
    'admin/action/login' => 'request/adminLogin',
    'admin/login' => 'admin/login',

    'admin/news/action/getNewsList' => 'request/getAsideNewsList',
    'admin/news/action/addNews' => 'request/addNews',
    'admin/news/action/editNews' => 'request/editNews',
    'admin/news/action/deleteNews' => 'request/deleteNews',

    'admin/news/action/getCategories' => 'request/getCategories',
    'admin/news/action/addCategory' => 'request/addCategory',
    'admin/news/action/editCategory' => 'request/editCategory',
    'admin/news/action/deleteCategory' => 'request/deleteCategory',

    'admin/news/([0-9]+)' => 'admin/editNews/$1',
    'admin/news/add' => 'admin/addnews',
    'admin/news/category/([0-9]+)' => 'admin/editCategory/$1',
    'admin/news/categories' => 'admin/listcats',
    'admin/news' => 'admin/listnews',
    'admin' => 'admin/index',

    'news/([0-9]+)' => 'news/getNewsByID/$1',
    'news' => 'request/getNewsList',
    '' => 'news/index',
);

