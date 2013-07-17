(function($){
	$.fn.interval = function(settings){
		ticker = setInterval(function(){
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


			$M(".pg_worldtimezone_tickerhour").text(PLUGIN_Worldtimezone_front.formatTime(settings.hour,2,0));
			$M(".pg_worldtimezone_tickerminute").text(PLUGIN_Worldtimezone_front.formatTime(settings.minute,2,0));
			$M(".pg_worldtimezone_tickersecond").text(PLUGIN_Worldtimezone_front.formatTime(settings.second,2,0));
		}, 1000);
	}
})( jQuery );
