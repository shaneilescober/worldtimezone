<?php
class apiGetFrontSearch extends Controller_Api
{
	protected function get($aArgs)
	{
		require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
		
		$fetchedData = common()->modelContents()->frontSearch($aArgs['data']);
		
		foreach($fetchedData as $val){
			$a['country'] = $val['country'];
			$a['city'] = $val['city'];
			$a['location'] = $val['location'];
			
			$aData[] = $a;
		}
		
		if($aData[0]['country'] != null){
			return $aData;
		}
		else{
			return false;
		}
	}
}