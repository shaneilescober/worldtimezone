<?php 
class adminExecWorldTimezone extends Controller_AdminExec
{
	public function run($aArgs)
	{
		require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
		 
		$connection = new modelWorldtimezone();
		$aData['seq'] = $aArgs['seq'];
		$aData['pg_worldtimezone_selectedtimezone'] = $aArgs['pg_worldtimezone_selectedtimezone'];
		$aData['pg_worldtimezone_timeformat'] = $aArgs['pg_worldtimezone_timeformat'];
		
		$check = $connection->checkdb($aData['seq']);
		if($check['cSeq'] == "0"){
			$bSave = $connection->insertData($aData);
		}else{
			$bSave = $connection->updateData($aData);
		}
		
		if($bSave === true){
			usbuilder()->message($sMessage, $sType = 'sucess');
			usbuilder()->message('Saved succesfully');
		}else{
			usbuilder()->message('Oops. Something went wrong.', 'warning');
		}
		
		usbuilder()->jsMove($aArgs['return_url']);
		//usbuilder()->vd($check);
	}
}