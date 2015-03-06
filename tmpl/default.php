<?php // no direct access
defined('_JEXEC') or die('Restricted access');

if ($phocagallery_module_width !='') {
	$pgWidth ='width:'.$phocagallery_module_width.'px;';
} else {
	$pgWidth = '';
}

?><div id ="phocagallery-module-ri" style="text-align:center; margin: 0 auto;<?php echo $pgWidth;?>"><?php
foreach ($output as $value) {
	echo $value;
}
?></div><div style="clear:both"></div><?php
if ($tmpl['detail_window'] == 6) {
	?><script type="text/javascript">
var gjaksMod<?php echo $randName ?> = new SZN.LightBox(dataJakJsMod<?php echo $randName ?>, optgjaksMod<?php echo $randName ?>);
</script><?php
}
?>