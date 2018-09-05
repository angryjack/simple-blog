<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 14.07.2018 21:53
 */
?>

<header class="header">
    <div class="header__container">
        <ul class="header__menu">
            <li><a href="/" class="header__logo">cnde</a></li>
            <li><svg class="feather feather-search sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="-6 -9 38 38" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></li>
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