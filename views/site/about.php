<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode('关于') ?></h1>

    <p>
        在当前知识爆炸的时代，每年都有大量的电影、图书和游戏等出版物发行，但是如何方便的找到用户感兴趣的出版物是一个未能很好解决的问题。
    </p>

    <p>
    	为了解决相关问题，充分利用链接开放数据的信息和知识图谱检索和推荐相关知识，构建了一个基于知识图谱的出版物检索和推荐系统。
    </p>

	<ul class="list-group col-md-8" style="margin-top: 1rem;">
		<li class="list-group-item">
			<span>
				<strong>出版物检索：</strong>数据源为DBpedia链接开放数据，检索出版物的基本信息，并给出相关wiki链接
			</span>
		</li>
		<li class="list-group-item">
			<span>
				<strong>语义推荐：</strong>基于知识图谱给出的语义，推荐用户可能感兴趣的其他出版物
			</span>
		</li>
		<li class="list-group-item">
			<span>
				<strong>数据可视化与分析：</strong>出版物相关数据的可视化分析与展示，包括统计分析
			</span>
		</li>
	</ul>
</div>
