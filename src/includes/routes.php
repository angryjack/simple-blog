<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 12:50
 */

return array(

    /* Articles */
    'articles' => 'article/index',
    'article/([0-9]+)' => 'article/show/$1',
    'article/store' => 'article/store',
    'article/update/([0-9]+)' => 'article/update/$1',
    'article/destroy/([0-9]+)' => 'article/destroy/$1',
    'article/search' => 'article/search',

    /* Admin panel */
    'admin/articles' => 'admin/articles',
    'admin/categories' => 'admin/categories',
    'admin/login' => 'admin/login',
    'admin' => 'admin/index',

    /* Install */
    'install/checkDb' => 'install/checkDb',
    'install/clearDb' => 'install/clearDb',
    'install/init' => 'install/init',
    'install/delete' => 'install/delete',
    'install/createUser' => 'install/createUser',
    'install' => 'install/index',

    '' => 'article/index'
);
