<div id="pg_worldtimezone_setuppanel">
	<!-- message box -->		
			<!-- message box -->			
			<div id="sdk_message_box"></div>		
			<!-- // message box -->
			<form name = "worldtimezone_form" method = "POST">
				<input type="hidden" name="return_url" value="<?php echo $sUrl; ?>" />
				<input type = "hidden" name = "seq" value = "<?php echo $seq; ?>" />
				<input type = "hidden" name = "savedTimezone" value = "<?php echo $aData['pws_timezone']?>"/>
				<input type = "hidden" name = "savedTimeformat" value = "<?php echo $aData['pws_format']?>"/>
				<table border="1" cellspacing="0" class="table_input_vr">
					<colgroup>
						<col width="115px" />
						<col width="*" />
					</colgroup>
					<tr>
						<th><label for="module_label">Timezone</label></th>
						<td>
							<span>
								<input type="text"  class="fix" id="pg_worldtimezone_selectedtimezone"  name="pg_worldtimezone_selectedtimezone" disabled /> 
								<a id="upload_image" title="Click To Change Timezone" class="btn_nor_01 btn_width_st2" onclick="PLUGIN_Worldtimezone_setup.openSearch()" href="javascript:void(0)">Change Timezone</a>
							</span>
						</td>
					</tr>
					<tr>
						<th>Time Format</th>
						<td>
							<table border="0" cellspacing="0" cellpadding="0" >
								<tr>
									<td valign="top" >
										<input type="radio" value="12" name="pg_worldtimezone_timeformat" id="pg_worldtimezone_timeformat12" class="input_rdo table_display_type"/>
										<label for="pg_worldtimezone_timeformat12" class="lbl_check table_display_type"  >12 Hours</label>
									</td>
									<td>
										<input type="radio" id="pg_worldtimezone_timeformat24" value="24" name="pg_worldtimezone_timeformat" class="input_rdo table_display_type"/>
										<label for="pg_worldtimezone_timeformat24" class="lbl_check table_display_type">24 Hours</label>	
									</td>
								</tr>
							</table>
						</td>
					</tr>	
				</table>
				<div class="tbl_lb_wide_btn">
					<a href="#" class="btn_apply" title="Save changes" onclick="PLUGIN_Worldtimezone_setup.saveData()">Save</a>
					<a href="#" class="add_link" title="Reset to default" oncliCk="PLUGIN_Worldtimezone_setup.resetToDefault()" >Reset to Default</a>
					<?php if ($bExtensionView === 1){ ?>
			            <?php echo '<a href="/admin/sub/?module=ExtensionPageManage&code=' . ucfirst(APP_ID) . '&etype=MODULE" class="add_link" title="Return to Manage Worltimezone Widget">Return to Manage Worltimezone Widget</a>
			            <a href="/admin/sub/?module=ExtensionPageMyextensions" class="add_link" title="Return to My Extensions">Return to My Extensions</a>'; ?>
			        <?php } ?>
				</div>
			</form>
</div>



<div id='sdk_popup' style='display:none'>
	<div class="admin_popup_contents">
		<div id ="pg_worldtimezone_dialog-search" title="Change Timezone" >
			<div class="g_worldtimezone_searchcontent">
				<label>Search:</label>
				<input id="pg_worldtimezone_searchtext" type="text" onblur="AutoSuggest.textBlur()" onfocus="AutoSuggest.textFocus()" onkeyup="AutoSuggest.suggest(this,event)" maxlength="30" size="30" autocomplete="off" style="background: none repeat scroll 0% 0% rgb(248, 248, 248); border-left: 1px solid rgb(97, 174, 242); border-width: 1px; border-style: solid; border-color: rgb(97, 174, 242) rgb(97, 174, 242) rgb(204, 204, 204); -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-image: none; font-style: oblique;">
				<span class="dropdown"></span>
			</div>
			<div id="pg_worldtimezone_searchresult">
			</div><br />
			<center>
				<input class="btn_apply" type="button" value="Search" onclick="PLUGIN_Worldtimezone_setup.search()"/> &nbsp 
				<input class="btn_apply" type="button" value="Cancel" onclick="PLUGIN_Worldtimezone_setup.close_popup()"/>
			</center>
		</div>
	</div>
</div>


