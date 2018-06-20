<?php

/* @var $this yii\web\View */
// use app\assets\AppAsset;

$this->title = $pubname;
?>
<style>
	#tit {
		margin-bottom: 2rem;
	}
	#link {
		text-decoration: none;
	}
	#abstract {
		font-size: 1.8rem;
	}
	.infobox {
		font-size: 1.6rem;
		line-height: 2.4rem;
	}

	#rec {
		margin: 2rem 0 2rem;
	}
</style>
<div class="site-info">
	<?php if(empty($info)) { ?>
	<h2><?= $pubname ?></h2>
	<?php } else { ?>
	<div class="body-content row">

		<div class="col-md-8">
			<h2 id="tit" class="text-center">
				<a id="link" target="_blank" href="<?= $info['uri'] ?>"><?= $pubname ?></a>
			</h2>
			<p class="infobox">
				<span id="abstract" class="label label-success">简介</span>
				<?= $info['abstract'] ?>
			</p>
		</div>
		
		<?php if(!empty($recommends)) { ?>
		<div class="col-md-4">
			<h3 id="rec"><?= $title ?></h3>
			<?php foreach($recommends as $item): ?>
			<a href="<?= $item['url'] ?>" target="_blank" class="list-group-item">
				<?= $item['name'] ?>
			</a>
			<?php endforeach; ?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>