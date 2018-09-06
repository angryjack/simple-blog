<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 14.07.2018 21:53
 */
?>

<header class="header">
    <div class="header__container">
        <div class="header__logo"><a href="/" class="header__logo">cnde</a></div>
        <ul class="header__menu">
            <li><a href="/css">CSS</a></li>
            <li><a href="/js">JS</a></li>
            <li><a href="/php">PHP</a></li>
            <li><a href="/other">Other</a></li>
        </ul>
    </div>
</header>
<script>
    let header = new Vue({
        el: 'header.header',
        data: {
            articles: [],
            search: ''
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
            searchArticles: _.debounce(
                function () {
                    axios({
                        method: 'post',
                        url: "/article/search",
                        data: {
                            search: this.search
                        }
                    }).then(function (response) {
                        if (response.data.status === "success") {
                            this.articles = response.data.answer.data;
                            console.log(response);
                        } else {
                            //response.data.answer.text
                        }
                    }).catch(function (error) {
                    });
                }, 500),
        }
    })
</script>