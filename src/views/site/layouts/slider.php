<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 03.08.2018 22:36
 */
?>

<div class="slider__container" v-bind:style="background">
    {{title}}
</div>

<script>
    let slider = new Vue({
        el: '.slider__container',
        data: {
            title: document.title,
            colors : [
                '#8a2b2baa',
                '#8a2b6daa',
                '#4b2b8ac2',
                '#2b488aaa',
                '#2b678aaa',
                '#2b8a86aa',
                '#2b8a5eaa',
                '#3e8a2baa',
                '#898a2baa'
            ]
        },
        computed: {
            background: function () {
                let rand = Math.floor(Math.random() * this.colors.length);
                return { background: this.colors[rand] }
            }
        },
    });
</script>