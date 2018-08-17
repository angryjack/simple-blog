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
            <li><a href="/css">CSS</a></li>
            <li><a href="/js">JS</a></li>
            <li v-bind:class="{ rotate: isRotate }"><a href="/"><img class="header__menu-logo" src="/web/images/logo.png"></a></li>
            <li><a href="/php">PHP</a></li>
            <li><a href="/linux">Linux</a></li>
        </ul>
    </div>
</header>

<script>
    let header = new Vue({
        el: ".header",
        data: {
            isRotate: false,
        }
    });
</script>