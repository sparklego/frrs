<?php

/* @var $this yii\web\View */

$this->title = '基于知识图谱的出版物检索和推荐系统';
?>
<style>
    .jumbotron {
        margin-bottom: 0;
    }
    .jumbotron .zone {
        width: 40%;
        height: 4.6rem;
        margin-right: 1rem;
        font-size: 2rem;
    }
    .foo {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 0 4rem;
        margin-right: 1rem;
        width: 32%;
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
            <form class="form-inline" action="?r=site/search" method="post">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>" />
                <input type="text" class="zone form-control" placeholder="请输入出版物" name="pub">
                <button type="submit" class="btn btn-lg btn-success">开始检索</button>
            </form>
        </p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="foo col-lg-4">
                <h2 class="text-center">出版物检索</h2>

                <p>检索相关出版物的基本信息，并给出相关介绍网页的链接</p>
            </div>
            <div class="foo col-lg-4">
                <h2 class="text-center">语义推荐</h2>

                <p>基于知识图谱给出的语义，推荐用户可能感兴趣的其他出版物</p>
            </div>
            <div class="foo col-lg-4">
                <h2 class="text-center">数据可视化与分析</h2>

                <p>出版物相关数据的可视化分析与展示，包括统计分析</p>
            </div>
        </div>

    </div>
</div>
