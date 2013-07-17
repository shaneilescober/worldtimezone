<?php 
class apiGetGmt extends Controller_Api
{
	protected function get($aArgs)
	{
		require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
		
		if($aArgs['data'] != null){
			$iGMT = $aArgs['data'];
		}else{
			$iGMT = "-8";
		}
		
		if (strlen($iGMT) == 2){
			$iGMT = substr($iGMT, 0, 1) . "0" . substr($iGMT, 1, 2) . "00";
		}
		else {
			$iGMT = substr($iGMT, 0, 1) . substr($iGMT, 1, 2) . "00";
		}
		
		$fetchedData = common()->modelContents()->getGmt($iGMT);
		
		foreach($fetchedData as $val){
			$a['country'] = $val['country'];
			$a['city'] = $val['city'];
			$a['location'] = $val['location'];
			
			$aData[] = $a;
		}
		
		return $aData;
	}
}
