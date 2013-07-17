<?php 
class apiGetSetting extends Controller_Api
{
	protected function get($aArgs)
	{
		require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
		
		$seq = $aArgs['dSeq'];
		$data = common()->modelContents()->getSetting($seq);
		
		return $data;
	}
}