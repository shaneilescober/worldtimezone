var dialogResizable = false;
var dialogModal = false;
var dialogDraggable = true;
var dialogCloseOnEscape = true;

jQuery(document).ready(function($) {
	var seq = $('[name=seq]').val();
	$.ajax({
		dataType: 'json',
		type: 'GET', 
		url: '/_sdk/api/Worldtimezone/GetSetting',
		data: "dSeq=" + seq, 
		success: function(info){
			if(info.Data.pws_timezone == null){
				$("#pg_worldtimezone_selectedtimezone").val("America/Blanc-Sablon");
			}else{
				$("#pg_worldtimezone_selectedtimezone").val(info.Data.pws_timezone);
			}
			if(info.Data.pws_format == 24){
				$("#pg_worldtimezone_timeformat24").attr("checked","true");
			}else{
				$("#pg_worldtimezone_timeformat12").attr("checked","true");
			}
		}
	});
});

var PLUGIN_Worldtimezone_setup = {
	openSearch : function(){
		 $("#pg_worldtimezone_searchtext").css('border', 'none');
		 $('.dropdown').css('display','none');
		 $("#pg_worldtimezone_searchresult").empty();
		sdk_popup.load('sdk_popup').skin('admin').layer({
			'width': 350,
			'title': "Change Time Zone",
			'classname': 'ly_set ly_editor'
		});
		var sDefaultval = "Input Country...";
		$("#pg_worldtimezone_searchtext").css({
			'font-style':'oblique',
			'background': '#F8F8F8 '
			
		});
		$("#pg_worldtimezone_searchtext").val(sDefaultval);
	},
	/*triggers when the search button is clicked returns an array list of the timezone*/
	search: function(){
		var searchText = $.trim($("#pg_worldtimezone_searchtext").val());
		var city = new Array();
		var searchedCountry = new Array();
		var searchedLocation = new Array();
		var string = "<ul>";
		
		if(searchText == 'Input Country...'){
			$("#pg_worldtimezone_searchtext").css('border', '2px solid #FF0000');
		}else{
			$.ajax({
				dataType: 'json',
				type: 'GET',
				url: '/_sdk/api/Worldtimezone/GetCity',
				data: "data=" + searchText,
				success: function(info){
					if(info.Data.length > 0){
						for(var ctr in info.Data)
						{
							city[ctr] = info.Data[ctr].city;
							searchedCountry[ctr] = info.Data[ctr].country;
							searchedLocation[ctr] = info.Data[ctr].location;
						}
						
						for(var ctr in city){
							string += '<li><span class="pg_desc"><a href="javascript:PLUGIN_Worldtimezone_setup.selectData(\'' + searchedLocation[ctr] + '\')">' + searchedCountry[ctr] + ' - ' + city[ctr] + '</a></span></li>';
						}
						
						string += '</ul>'
						$("#pg_worldtimezone_searchresult").append(string);
						$("#pg_worldtimezone_searchtext").val('');
						AutoSuggest.clearSuggest();
					}else{
						$("#pg_worldtimezone_searchresult").append("<p class='error_message'>No results found.<p>");
					}
				},
				error: function(e, xhr){
					
				}
			});
		}
		
	},
	selectData : function(timezone){
		$("#pg_worldtimezone_selectedtimezone").val(timezone);
		PLUGIN_Worldtimezone_setup.close_popup();
	},
	saveData : function(){
		var pg_worldtimezone_selectedtimezone = $("[name=pg_worldtimezone_selectedtimezone]").val();
		var savedTimezone = $("[name=savedTimezone]").val();
		var pg_worldtimezone_timeformat = $("[name=pg_worldtimezone_timeformat]:checked").val();
		var savedTimeformat = $("[name=savedTimeformat]").val();
		
		$("#pg_worldtimezone_selectedtimezone").removeAttr("disabled");
		
		if(pg_worldtimezone_selectedtimezone == savedTimezone && pg_worldtimezone_timeformat == savedTimeformat){
			alert("No data change.");
			$("#pg_worldtimezone_selectedtimezone").attr("disabled", "disabled");
		}else{
			$("[name=worldtimezone_form]").submit();
		}
		
	},
	/*close dialog box with popup class*/
	close_popup: function(){
		sdk_popup.close('sdk_popup');
	},
	
	/*reset all fields to its default value*/
	resetToDefault: function(){
	
		var sDefaultTimezone = "America/Blanc-Sablon";
		var sDefaultTimeFormat = "pg_worldtimezone_timeformat12";
	
		$("input:radio").attr("checked", false);
		$('#pg_worldtimezone_selectedtimezone').val(sDefaultTimezone);
		$("#"+sDefaultTimeFormat).attr("checked", true);
		
	}
};

var AutoSuggest = {
	//shows dropdown list of suggestion for inputted keyword
	suggest: function(dis, evt) {
	
		var val = $(dis).val();
		var keycode = evt.keyCode;
		
		var country = new Array();
		var location = new Array();
		
		var ul = $('<ul></ul>');
		
		if (val != "") {
			$("#pg_worldtimezone_searchtext").css('border-bottom', 'none');
			$.ajax({
				dataType : "json",
				type : "GET",
				data: "data=" + val,
				url : "/_sdk/api/Worldtimezone/GetCountry",
				success: function(info){
					if(info.Data.length > 0){
						for(var ctr in info.Data){
							//fetch data passed from API
							country[ctr] = info.Data[ctr].country;
						}
						var sorted_arr = country.sort();
						//shows autosuggestion dropdown
						if (keycode == 38 || keycode == 40 || keycode == 13) {
							AutoSuggest.navigate(dis, keycode);
						} else {
						   var str = "";
						   
						   $('.dropdown').css('display','none');
						   for(var ctr = 0; ctr < country.length; ctr++){
							   if(sorted_arr[ctr + 1] != sorted_arr[ctr]){
								  ul.append('<li onclick="AutoSuggest.selectValue(\''+country[ctr]+'\', this);"><a href="#">'+country[ctr]+'</a></li>');
							   }
						   }
						   $('#pg_worldtimezone_searchtext').css({
							"border-left" : "1px solid #61aef2",
							"border-top" : "1px solid #61aef2",
							"border-right" : "1px solid #61aef2"
						   });
						   $('.dropdown').html(ul);
						   $('.dropdown').find('li:first').attr("id","selected");
						   $('.dropdown').css('display','block');
						}
					}else{
						 $('.dropdown').css('display','none');
					}
				},
				error: function(e,xhr){
					//alert("error");
				}
			});
		} else {
			this.clearSuggest();
		}
	},

	navigate: function(dis, key) {
		var size = $('.dropdown').find('li').size();
		var selected = $('.dropdown').find('li#selected').index();
		var code = {up:38, down:40, enter:13};
		switch (key) {
			case code.up:
				if (selected == 0) return;
				var text = $('.dropdown').find("li:eq("+(selected-1)+")").text();
				$(dis).val(text);
				$('.dropdown').find("li:eq("+(selected-1)+")").attr('id', 'selected');
				break;
			case code.down:
				if ((selected+1) == size) return;
				var text = $('.dropdown').find("li:eq("+(selected+1)+")").text();
				$(dis).val(text);
				$('.dropdown').find("li:eq("+(selected+1)+")").attr('id', 'selected');
				break;
			case code.enter:
				var text = $('.dropdown').find("li:eq("+selected+")").text();
				$(dis).val(text);
				AutoSuggest.clearSuggest();
				break;
		}
		$('.dropdown').find("li:eq("+selected+")").attr('id', '');
	},

	clearSuggest: function() {
		$(".dropdown").hide();
		$('#pg_worldtimezone_searchtext').css("border","1px solid #cccccc");
	},

	closeSuggest: function() {
		setTimeout(function(){
			$(".dropdown").fadeOut();
		}, 500);
	},

	selectValue: function(val, evt) {
		$("#pg_worldtimezone_searchtext").val(val);
		AutoSuggest.clearSuggest();
	},
	textFocus: function(){
	$("#pg_worldtimezone_searchtext").val('');
		$("#pg_worldtimezone_searchtext").css({
					'font-style':'',
					'background': 'white'
					
				});
		$("#pg_worldtimezone_searchresult").empty();
				
	},
	textBlur: function(){
	var textbox = $('#pg_worldtimezone_searchtext').val();
		if(textbox == ""){
			var sDefaultval = "Input Country...";
				$("#pg_worldtimezone_searchtext").css({
					'font-style':'oblique',
					'background': '#F8F8F8 '
				});
			$("#pg_worldtimezone_searchtext").val(sDefaultval);
		
		}

	}
};
