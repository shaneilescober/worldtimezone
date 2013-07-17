<?php
class frontPageWorldTimezone extends Controller_Front
{
	protected function run($aArgs)
	{
		require_once('builder/builderInterface.php');
		usbuilder()->init($this, $aArgs);
  
		$aOption['seq'] = $this->getSequence();
		$fetchedData = common()->modelContents()->getSetting($aOption['seq']);
		$iResultCount = common()->modelContents()->checkdb($aOption['seq']);

		$location = '<input class="pg_worldtimezone_location" type="hidden" value="'. $fetchedData['pws_timezone'] .'" />';
		$format = '<input class="pg_worldtimezone_format" type="hidden" value="'. $fetchedData['pws_format'] .'" />';
		$prev = '<input class="pg_worldtimezone_prev" type="image" src="/_sdk/img/worldtimezone/pg_worldtimezone_prev.gif" style = "position:absolute;left:0;top:0;margin-top:40px;margin-left:30px;z-index:1000" >';
		$next = '<input class="pg_worldtimezone_next" type="image" src="/_sdk/img/worldtimezone/pg_worldtimezone_next.gif" style = "position:absolute;right:0;top:0;margin-top:40px;margin-right:30px;" >';
		$search = '
			<div style = "width:248px;margin:0 auto;">
				<div style = "float:left;">
					<div style = "width:189px;height:21px;background:url(/_sdk/img/worldtimezone/pg_worldtimezone_search_box.gif) left top no-repeat;">
						<input class = "pg_worldtimezone_text" type="text" style = "width:179px;height:17px;margin-top:2px;margin-right:0px;margin-left:5px;font:10px Arial, Helvetica, sans-serif;color:#777;border:0;outline:0" />
					</div>
				</div>
				<div style = "float:left;">
					<input class = "pg_worldtimezone_searchbtn" type="submit" value="" style = "width:43px;height:21px;margin-left:5px;background:url(/_sdk/img/worldtimezone/pg_worldtimezone_search_btn.gif) left top no-repeat;border:0;outline:0;cursor:pointer"/>
				</div>
				<div style = "clear:both"></div>
			</div>';
		$more = '
			<a class="pg_worldtimezone_more" style = "float:right;margin-top:5px;margin-bottom:-5px;margin-right:10px;color:#568ee1;text-decoration:none;display:none;font-size:11px;cursor:pointer;">
				<small style = "float:left;margin-top:0px;margin-right:3px;">&raquo;</small>
				<span>Less</span> 
			</a>
		';
		
		$this->assign('location', $location);
		$this->assign('format', $format);
		$this->assign('prev', $prev);
		$this->assign('next', $next);
		$this->assign('search_area', $search);
		$this->assign('time_area', 'time_area');
		$this->assign('search', 'search');
		$this->assign('others', 'pg_worldtimezone_others');
		$this->assign('more', $more);

		$this->writeJs('
			sdk_Module("'.usbuilder()->getModuleSelector().'").ready(function($M) {
				$M(".pg_worldtimezone_more").click(function() {
                    PLUGIN_Worldtimezone_front.showMore();
                });
				
				$M(".pg_worldtimezone_searchbtn").click(function() {
                    PLUGIN_Worldtimezone_front.searchTimezone();
                });
				
				var globalGmt = 0;
				var globalFormat = "12";
				var globalPeriod = "";
				var globalLocation = "";
				var ticker = "";
				var storeGmt = "";
				var	storeTimezone = "";
				var allowStore = true;
				var globalTimeout;

				var PLUGIN_Worldtimezone_front = {
					storeData : function(sLocation){
						allowStore = true;
						this.getTime(sLocation);
					},

					getTime : function(sLocation){
						var obj = PLUGIN_Worldtimezone_front;
						var sUrl = usbuilder.getUrl("apiGetDatetime");
						globalFormat = $M(".pg_worldtimezone_format").val();
						
						$.ajax({
							dataType: "json",
							type: "GET",
							url: sUrl,
							data: "data=" + sLocation,
							success: function(info){
								$M(".time_area").empty();
								clearInterval(ticker);
								var timezone = info.Data.tz;
								var datetime = info.Data.datetime;
								var hour = obj.formatTime(info.Data.hour,2,0);
								var minute = obj.formatTime(info.Data.minute,2,0);
								var second = obj.formatTime(info.Data.second,2,0);
								
								if (globalFormat == "12"){
									if (hour > 12){
										hour = obj.formatTime(hour - 12,2,0);
										globalPeriod = "PM";
									}
									else {
										globalPeriod = "AM";
									}

									if (hour == "00") hour = "12";
								}else{
									if (hour > 12){
										globalPeriod = "PM";
									}
									else {
										globalPeriod = "AM";
									}
								}

								var string = "<div class = \'pg_worldtimezone_info\' style = \'position:relative;overflow:hidden;width:100%;padding:5px 5px 0px 5px !important;margin:0px 0 0 -5px !important;font-size:10px;text-align:center;\'>";
								string += "<p style = \'color:#686868;\'><span class=\'pg_worldtimezone\' style = \'font-size:12px;font-weight:bold;color:#333\'>" + datetime.substring(0, 16) + " " + datetime.substring(26, 32) + "</span><br /><strong style = \'font-size:25px;color:#333;\'>";
								string += "<span class=\'pg_worldtimezone_tickerhour\'>" + hour + "</span>";
								string += ":<span class=\'pg_worldtimezone_tickerminute\'>" + minute + "</span>";
								string += ":<span class=\'pg_worldtimezone_tickersecond\'>" + second + "</span>";
								string += " <span class=\'pg_worldtimezone_tickerperiod\'>" + globalPeriod + "</span>";
								string += "</strong><br /><span class=\'pg_worldtimezone_timezone\' style = \'font-size:12px;color:#333\'>";
								string += timezone + "</span></p>";
								string += "</div>";

								$M(".time_area").append(string);

								var time = datetime.substring(27, 28) == "0" ? datetime.substring(28, 29) : datetime.substring(27, 29);
								var gmt = datetime.substring(26, 27) == "+" ? "+" + time : "-" + time;

								if (allowStore == true){
									storeGmt = gmt;
									storeTimezone = sLocation;
								}

								globalGmt = gmt;
								obj.getRealtime();
				
								
							}
						});
					},

					changeGmt : function(selector, action){
						allowStore = false;
						var sUrl = usbuilder.getUrl("apiGetGmt");
						var gmt = action == "next" ? parseInt(globalGmt) + 1 : parseInt(globalGmt) - 1;

						if (gmt < -11){
							gmt = 14;
						}
						else if (gmt > 14){
							gmt = -11;
						}

						gmt = gmt + "";
						gmt = gmt.substring(0, 1) == "-" ? gmt : "+" + gmt;
				
						$.ajax({
							dataType: "json",
							type: "GET",
							url: sUrl,
							data: "data=" + encodeURIComponent(gmt),
							success: function(info){
								if (gmt == storeGmt){
									PLUGIN_Worldtimezone_front.storeData(storeTimezone);
								}
								else {
									PLUGIN_Worldtimezone_front.storeData(info.Data[0].location);
								}
							}
						});
					},

					searchTimezone : function(){
						var searchText = $.trim($M(".pg_worldtimezone_text").val());

						if (searchText == ""){
									$M(".pg_worldtimezone_more").hide();
									$M(".pg_worldtimezone_others").empty().hide();
									$M(".pg_worldtimezone_others").append("<span class=\'pg_worldtimezone_result\'>Please input search field.</span>").slideDown("fast").show();
									$M(".pg_worldtimezone_text").val("");
									$M(".pg_worldtimezone_text").focus();
						}
						else {
				
							var city = new Array();
							var searchedCountry = new Array();
							var searchedLocation = new Array();
							var sUrl = usbuilder.getUrl("apiGetFrontSearch");

							$.ajax({
								dataType: "json",
								type: "GET",
								url: sUrl,
								data: "data=" + searchText,
								success: function(info){
									for(var ctr in info.Data){
										city[ctr] = info.Data[ctr].city;
										searchedCountry[ctr] = info.Data[ctr].country;
										searchedLocation[ctr] = info.Data[ctr].location;
									}
									var string = "<ul class=pg_worldtimezone_details style = \'width:90%;overflow:auto;height:100px;\'>";

									if (info.Data != false){
										
										clearTimeout(globalTimeout);
										$M(".pg_worldtimezone_more").hide();
										$M(".pg_worldtimezone_others").empty();
										for(var ctr in city){
											string += "<li style = \'border-bottom:1px solid #e7e7e7;font-size:11px;line-height:22px;display:inline-block;width:90%;\'><span class=\'pg_desc\' id = \'"+searchedLocation[ctr]+"\' style=\'float:left;background:url(../images/blue/pg_worldtimezone_bullet1.jpg) no-repeat left;padding-left:8px !important;color:#999;cursor:pointer\'>" + searchedCountry[ctr] + " - " + city[ctr] + "</span></li>";
										}
										string += "</ul>";
										$M(".pg_worldtimezone_others").append(string).slideDown();
										$M(".pg_desc").click(function(){
											var pg_desc_id = $M(this).attr("id");
											PLUGIN_Worldtimezone_front.storeData(pg_desc_id);
										});
										
										$M(".pg_worldtimezone_more").show();
										$M(".pg_worldtimezone_more span").text("Less");
									}
									else{
										$M(".pg_worldtimezone_more").hide();
										if ($M(".pg_worldtimezone_others").is(":hidden") == true){
											$M(".pg_worldtimezone_others").empty().hide();
											$M(".pg_worldtimezone_others").append("<span class=\'pg_worldtimezone_result\'>The are no results found.</span>").slideDown("fast").show();

										}
										else {
											$M(".pg_worldtimezone_more, .pg_worldtimezone_others").hide();
											$M(".pg_worldtimezone_others").empty();
											$M(".pg_worldtimezone_others").append("<span class=\'pg_worldtimezone_result\'>The are no results found.</span>").show();
										}
									}
								}
							});
						}
					},

					showMore : function(){
						if ($M(".pg_worldtimezone_others").is(":hidden") == true){
							$M(".pg_worldtimezone_others").slideDown("fast");
							$M(".pg_worldtimezone_more span").text("Less");
						}
						else {
							$M(".pg_worldtimezone_others").slideUp("fast");
							$M(".pg_worldtimezone_more span").text("More");
						}
					},

					getRealtime : function(){
						var hour = $M(".pg_worldtimezone_tickerhour").text();
						var minute = $M(".pg_worldtimezone_tickerminute").text();
						var second = $M(".pg_worldtimezone_tickersecond").text();
						var period = $M(".pg_worldtimezone_tickerperiod").text();
						
						ticker = setInterval(function(){
							minute = second >= 59 ? parseInt(minute) + 1 : minute;
							second = second >= 59 ? "00" : parseInt(second) + 1;

							if (minute > 59){
								minute = "00";
								hour++;
								if (hour == 12 && globalFormat == "12"){
									PLUGIN_Worldtimezone_front.getTime($M(".pg_worldtimezone_timezone").text());
								}
							}

							if (hour > 23){
								PLUGIN_Worldtimezone_front.getTime($M(".pg_worldtimezone_timezone").text());
							}


							$M(".pg_worldtimezone_tickerhour").text(PLUGIN_Worldtimezone_front.formatTime(hour,2,0));
							$M(".pg_worldtimezone_tickerminute").text(PLUGIN_Worldtimezone_front.formatTime(minute,2,0));
							$M(".pg_worldtimezone_tickersecond").text(PLUGIN_Worldtimezone_front.formatTime(second,2,0));
						}, 1000);
					},

					formatTime : function(num, totalChars, padWith){
							num = num + "";
							padWith = (padWith) ? padWith : "0";

							if (num.length < totalChars) {
								while (num.length < totalChars){
									num = padWith + num;
								}
							}

							if (num.length > totalChars) {
								num = num.substring((num.length - totalChars), totalChars);
							}

							return num;
					}
				}

					$M(".pg_worldtimezone_more span").text("");
					$M(".pg_worldtimezone_more small").text("");
					PLUGIN_Worldtimezone_front.getTime($M(".pg_worldtimezone_location").val());
				
					$M(".pg_worldtimezone_next").click(function() {
		                PLUGIN_Worldtimezone_front.changeGmt("'.usbuilder()->getModuleSelector().'", "next");
		            });
					$M(".pg_worldtimezone_prev").click(function() {
		                PLUGIN_Worldtimezone_front.changeGmt("'.usbuilder()->getModuleSelector().'", "prev");
		            });
				});
		');
	}
}