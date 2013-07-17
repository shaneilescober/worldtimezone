var globalGmt = 0;
var globalFormat = "12";
var globalPeriod = "";
var globalLocation = "";
var ticker = "";
var storeGmt = "";
var	storeTimezone = "";
var allowStore = true;
var globalTimeout;

jQuery(document).ready(function($) {
	PLUGIN_Worldtimezone_front.getTime($(".pg_worldtimezone_location").val());
	$(".pg_worldtimezone_more span").text("");
	$(".pg_worldtimezone_more small").text("");
});

var PLUGIN_Worldtimezone_front = {
	
	storeData : function(sLocation){
		allowStore = true;
		this.getTime(sLocation);
	},
	
	getTime : function(sLocation){
		var obj = PLUGIN_Worldtimezone_front;
		var sUrl = usbuilder.getUrl('apiGetDatetime');
		globalFormat = $(".pg_worldtimezone_format").val();
		
		$.ajax({
			dataType: 'json',
			type: 'GET',
			url: sUrl,
			data: "data=" + sLocation,
			success: function(info){
				$(".pg_worldtimezone_info").empty();
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
				}
				
				var string = '<div class = "pg_worldtimezone_info">';
				string += '<input class="pg_worldtimezone_prev"  type="image" src="/_sdk/img/worldtimezone/pg_worldtimezone_prev.gif" onclick="PLUGIN_Worldtimezone_front.changeGmt(\'prev\')" style="position:absolute;left:5px;top:5px;margin-top:30px;margin-left:30px;width:7px;height:10px;">';
				string += '<p><span class="pg_worldtimezone">' + datetime.substring(0, 16) + ' ' + datetime.substring(26, 32) + '</span><br /><strong>';
				string += '<span id="pg_worldtimezone_tickerhour">' + hour + '</span>';
				string += ':<span id="pg_worldtimezone_tickerminute">' + minute + '</span>';
				string += ':<span id="pg_worldtimezone_tickersecond">' + second + '</span>';
				string += ' <span id="pg_worldtimezone_tickerperiod">' + globalPeriod + '</span>';
				string += '</strong><br /><span id="pg_worldtimezone_timezone">';
				string += timezone + '</span></p><input class="pg_worldtimezone_next" type="image" src="/_sdk/img/worldtimezone/pg_worldtimezone_next.gif" onclick="PLUGIN_Worldtimezone_front.changeGmt(\'next\')" style="position:absolute;right:5px;top:5px;margin-top:30px;margin-right:30px;width:7px;height:10px;"/>';
				string += '</div>';
				
				$(".pg_worldtimezone_info").append(string);
				
				var time = datetime.substring(27, 28) == "0" ? datetime.substring(28, 29) : datetime.substring(27, 29);
				var gmt = datetime.substring(26, 27) == "+" ? "+" + time : "-" + time;
				
				if (allowStore == true){
					storeGmt = gmt;
					storeTimezone = sLocation;
				}

				globalGmt = gmt;
				obj.getRealtime();
			},
			error: function(e, xhr){
				
			}
		});
		
	},
	
	changeGmt : function(action){
		allowStore = false;
		var sUrl = usbuilder.getUrl('apiGetGmt');
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
			dataType: 'json',
			type: 'GET',
			url: sUrl,
			data: "data=" + encodeURIComponent(gmt),
			success: function(info){
				if (gmt == storeGmt){
					PLUGIN_Worldtimezone_front.getTime(storeTimezone);
				}
				else {
					PLUGIN_Worldtimezone_front.getTime(info.Data[0].location);
					
				}
			},error: function(e, xhr){
				//alert("error");
			}
		});
	},
	
	searchTimezoneKeyup : function(){
		var e = window.event;
		var code = (e.keyCode ? e.keyCode : e.which);

		if (code == 13 && $(".pg_worldtimezone_text").focus()){
			this.searchTimezone();
		}
	}, 
	
	searchTimezone : function(){
		var searchText = $.trim($(".pg_worldtimezone_text").val());
		
		if (searchText == ""){
				
					$(".pg_worldtimezone_more").hide();
						
						$("#pg_worldtimezone_others").empty().hide();
						$("#pg_worldtimezone_others").append('<span class="pg_worldtimezone_result">Please input search field.</span>').slideDown("fast").show();
						/*globalTimeout = setTimeout(function(){
										$("#pg_worldtimezone_others").slideUp();
										$(".pg_worldtimezone_more span").text("More");
									},3000);*/
					
					$(".pg_worldtimezone_text").val('');
					$(".pg_worldtimezone_text").focus();
			
				
		}
		else {
			var city = new Array();
			var searchedCountry = new Array();
			var searchedLocation = new Array();
			var sUrl = usbuilder.getUrl('apiGetFrontSearch');
			
			$.ajax({
				dataType: 'json',
				type: 'GET',
				url: sUrl,
				data: "data=" + searchText,
				success: function(info){
					for(var ctr in info.Data){
						city[ctr] = info.Data[ctr].city;
						searchedCountry[ctr] = info.Data[ctr].country;
						searchedLocation[ctr] = info.Data[ctr].location;
					}
					var string = '<ul class="pg_worldtimezone_details">';
					
					if (info.Data != false){
						
						clearTimeout(globalTimeout);
						$(".pg_worldtimezone_more").hide();
						$(".pg_worldtimezone_others").empty();
						for(var ctr in city){
							string += '<li><span class="pg_desc"><a href="javascript:PLUGIN_Worldtimezone_front.storeData(\'' + searchedLocation[ctr] + '\')">' + searchedCountry[ctr] + ' - ' + city[ctr] + '</a></span></li>';
						}
						string += '</ul>';
						$(".pg_worldtimezone_others").append(string).slideDown();
						$(".pg_worldtimezone_more").show();
						$(".pg_worldtimezone_more span").text("Less");
					}
					else{
						$(".pg_worldtimezone_more").hide();
						if ($(".pg_worldtimezone_others").is(":hidden") == true){
							$(".pg_worldtimezone_others").empty().hide();
							$(".pg_worldtimezone_others").append('<span class="pg_worldtimezone_result">The are no results found.</span>').slideDown("fast").show();
							/*globalTimeout = setTimeout(function(){
								$("#pg_worldtimezone_others").slideUp();
								$(".pg_worldtimezone_more span").text("More");
							},3000);*/
						}
						else {
							$(".pg_worldtimezone_more, #pg_worldtimezone_others").hide();
							$(".pg_worldtimezone_others").empty();
							$(".pg_worldtimezone_others").append('<span class="pg_worldtimezone_result">The are no results found.</span>').show();
							/*globalTimeout = setTimeout(function(){
								$("#pg_worldtimezone_others").slideUp();
								$(".pg_worldtimezone_more span").text("More");
							},3000);*/
						}
					}
				},error: function(e, xhr){
					//alert("error");
				}
			});
		}
	},

	showMore : function(){
		if ($(".pg_worldtimezone_others").is(":hidden") == true){
			$(".pg_worldtimezone_others").slideDown("fast");
			$(".pg_worldtimezone_more span").text("Less");
		}
		else {
			$(".pg_worldtimezone_more span").text("More");
			$(".pg_worldtimezone_others").slideUp("fast");
		}
	},
	
	getRealtime : function(){
		var hour = $(".pg_worldtimezone_tickerhour").text();
		var minute = $(".pg_worldtimezone_tickerminute").text();
		var second = $(".pg_worldtimezone_tickersecond").text();
		var period = $(".pg_worldtimezone_tickerperiod").text();
		
		ticker = setInterval(function(){
			
			minute = second >= 59 ? parseInt(minute) + 1 : minute;
			second = second >= 59 ? "00" : parseInt(second) + 1;

			if (minute > 59){
				minute = "00";
				hour++;
				if (hour == 12 && globalFormat == "12"){
					PLUGIN_Worldtimezone_front.getTime($(".pg_worldtimezone_timezone").text());
				}
			}

			if (hour > 23){
				PLUGIN_Worldtimezone_front.getTime($(".pg_worldtimezone_timezone").text());
			}
			

			$(".pg_worldtimezone_tickerhour").text(PLUGIN_Worldtimezone_front.formatTime(hour,2,0));
			$(".pg_worldtimezone_tickerminute").text(PLUGIN_Worldtimezone_front.formatTime(minute,2,0));
			$(".pg_worldtimezone_tickersecond").text(PLUGIN_Worldtimezone_front.formatTime(second,2,0));
			
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



