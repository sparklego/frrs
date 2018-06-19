<?php

/* @var $this yii\web\View */
// use app\assets\AppAsset;

$this->title = $pubname;
?>
<style>
	header {
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
</style>
<div class="site-info">
	<?php if(empty($info)) { ?>
	<h2><?= $pubname ?></h2>
	<?php } else { ?>
	<header>
		<h2 class="text-center">
			<a id="link" target="_blank" href="<?= $info['uri'] ?>"><?= $pubname ?></a>
		</h2>
	</header>
	<div class="body-content row">
		<div class="col-md-12">
			<p class="infobox">
				<span id="abstract" class="label label-success">简介</span>
				<?= $info['abstract'] ?>
			</p>
		</div>
		<div class="col-md-12">
			<h3><?= $title ?></h3>
		</div>
	</div>
	<?php } ?>
</div>