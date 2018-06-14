<?php

/* @var $this yii\web\View */

$this->title = '出版物检索和推荐系统';
?>
<style>
    .jumbotron {
        margin-bottom: 0;
    }
    .foo {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 0 3rem;
    }
    .foo h2 {
        margin-bottom: 2rem;
    }
</style>
<div class="site-index">

    <div class="jumbotron">
        <h1>Welcome!</h1>

        <p class="lead">基于知识图谱的出版物检索和推荐系统</p>

        <p>
            <a class="btn btn-lg btn-success" href="http://www.yiiframework.com">开始检索</a>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="foo col-lg-4">
                <h2 class="text-center">出版物检索</h2>

                <p>检索到相关出版物的基本信息，并展现出来，包括给出相关介绍网页的链接</p>
            </div>
            <div class="foo col-lg-4">
                <h2 class="text-center">语义推荐</h2>

                <p>基于知识图谱给出的语义，推荐用户可能感兴趣的其他出版物</p>
            </div>
            <div class="foo col-lg-4">
                <h2 class="text-center">数据可视化与分析</h2>

                <p>出版物相关数据的可视化分析与展示，包括检索结果中部分数据的可视化展示以及对于出版物的统计分析</p>
            </div>
        </div>

    </div>
</div>
