<?php

/* @var $this yii\web\View */

$this->title = $keyword . '_搜索结果 - ' . Yii::$app->params['name'];
$tabs = array_keys($data);
$first = array_shift($tabs);
?>
<style>
	header h2 {
		margin-bottom: 1.8rem;
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
	<header>
		<h2>搜索结果</h2>
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
									<th width="12%">名称</th>
									<th>简介</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($items as $item): ?>
								<tr>
									<td>
										<a target="_blank" href="<?= $item['pub'] ?>"><?= $item['name'] ?></a>
									</td>
									<td><?= mb_substr($item['abstract'], 0, 500) . ' ......' ?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php } ?>
					</div>
					<?php endforeach; ?>
				</div>

			</div>
		</div>
	</div>
</div>