<ul class="nav" id="yw500">
<li class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="iconflags iconflags-<?=$currentLanguage;?>"></i><?=strtoupper($currentLanguage);?> <span class="caret"></span></a>
<ul id="yw501" class="dropdown-menu">
<?php
foreach($langs as $lang){
	$icon = '<i class="iconflags iconflags-' . $lang . '"></i>';
	if($currentLanguage != $lang){
		echo '<li>'.CHtml::link($icon.strtoupper($lang), $homeUrl.Yii::app()->urlManager->replaceLangUrl($cleanUrl, $lang)).'</li>';
	}
}	
?>  
</ul>
</li>
</ul>
   <!-- <ul class="nav" id="yw0">
		<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Dropdown <span class="caret"></span></a>
					<ul id="yw1" class="dropdown-menu">
						<li><a tabindex="-1" href="#">Action</a></li>
					</ul>
				</li>
				</ul>-->