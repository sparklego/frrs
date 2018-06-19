<?php

/* @var $this yii\web\View */
use app\assets\HighchartsAsset;
HighchartsAsset::register($this);

$this->title = $keyword . '_搜索结果_' . Yii::$app->params['name'];
$tabs = array_keys($data);
$first = array_shift($tabs);
?>
<style>
	header {
		margin-bottom: 1.5rem;
	}
	#keyword {
		width: 50%;
        height: 3.8rem;
        margin-right: 1rem;
        font-size: 2rem;
    }
	.body-content {
		border: 1px solid #ddd;
		border-radius: 5px;
	}
	#myTabContent {
		padding: 2rem 2rem 0;
	}
</style>
<div class="site-result">
	<header class="row">
		<div class="col-md-12">
			<form class="form-inline" action="?r=site/search" method="post">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>" />
                <input id="keyword" type="text" class="form-control" placeholder="请输入关键词" value="<?= $keyword ?>" name="keyword">
                <button id="search" type="submit" class="btn btn-lg btn-success">开始检索</button>
            </form>
		</div>
	</header>
	<div class="body-content">
		<div class="row">
			<div id="content" class="col-md-12">
				
				<ul id="myTab" class="nav nav-tabs">
					<li class="active">
						<a href="#<?= $first ?>" data-toggle="tab"><?= $labels[$first] ?></a>
					</li>
					<?php foreach($tabs as $tab): ?>
					<li><a href="#<?= $tab ?>" data-toggle="tab"><?= $labels[$tab] ?></a></li>
					<?php endforeach; ?>
					<li><a href="#data-analysis" data-toggle="tab">数据可视化</a></li>
				</ul>

				<div id="myTabContent" class="tab-content">

					<?php foreach($data as $key => $items): ?>
					<?php if($key == $first) { ?>
					<div class="tab-pane fade in active" id="<?= $key ?>">
					<?php } else { ?>
					<div class="tab-pane fade in" id="<?= $key ?>">
					<?php } ?>
						<?php if(empty($items)) { ?>
						<p>没有搜索到相关信息</p>
						<?php } else { ?>
						<table class="table table-hover">
							<thead>
								<tr>
									<th width="5%">序号</th>
									<th width="12%">名称</th>
									<th>简介</th>
								</tr>
							</thead>
							<tbody>
								<?php $tmp = 1; ?>
								<?php foreach($items as $item): ?>
								<tr>
									<td><?= $tmp ?></td>
									<td>
										<a target="_blank" href="<?= "?r=site/info&type=$key&lang=$lang&pid=$item[pid]" ?>"><?= $item['name'] ?></a>
									</td>
									<td><?= mb_substr($item['abstract'], 0, 500) . ' ......' ?></td>
								</tr>
								<?php $tmp++; ?>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php } ?>
					</div>
					<?php endforeach; ?>

					<div class="tab-pane fade in" id="data-analysis">
						<?php if($is_plot) { ?>
						<div class="col-md-6">
							<div id="bar-container"></div>
						</div>
						<div class="col-md-6"><div id="pie-container"></div></div>
						<?php } else { ?>
						<p>没有相关数据</p>
						<?php } ?>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>

<?php

$js = <<<JS

// Build the pie chart
Highcharts.chart('bar-container', {
    chart: {
        type: 'bar'
    },
    title: {
        text: '出版物检索结果'
    },
    exporting:{
        enabled:false
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: ['电影', '图书', '游戏']
    },
    yAxis: {
        min: 0,
        title: {
            text: '检索结果'
        }
    },
    legend: {
        reversed: true,
        enabled: false
    },
    plotOptions: {
        series: {
            stacking: 'normal'
        }
    },
    series: [{
    	name: '数量',
        data: [ $nums[Film], $nums[Book], $nums[Game] ]
    }]
});

// Build the pie chart
Highcharts.chart('pie-container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '出版物类别占比'
    },
    exporting:{
        enabled:false
    },
    credits: {
        enabled: false
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: '比例',
        colorByPoint: true,
        data: [{
            name: '电影',
            y: $nums[Film]
        }, {
            name: '图书',
            y: $nums[Book]
        }, {
            name: '游戏',
            y: $nums[Game]
        }]
    }]
});

$('#search').click(function(){
    var keyword = $('#keyword').val().trim();
    if(keyword == '') {
        alert('关键字不能为空！');
        return false;
    } else {
        $('form').submit();
    }
})
JS;

if($is_plot) {
	$this->registerJs($js);
}

