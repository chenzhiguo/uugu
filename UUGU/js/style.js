$(document).ready(function () {
    var style = 'easeOutExpo';
    var default_left = Math.round($('#menu li.selected').offset().left - $('#menu').offset().left);
    var default_top = $('#menu li.selected').height();

    //Set the default position and text for the tooltips
    $('#box').css({
        left: default_left, 
        top: default_top
    });
    $('#box .head').html($('#menu li.selected').find('img').attr('alt'));				
		
    //if mouseover the menu item
    $('#menu li').hover(function () {
			
        left = Math.round($(this).offset().left - $('#menu').offset().left);

        //Set it to current item position and text
        $('#box .head').html($(this).find('img').attr('alt'));
        $('#box').stop(false, true).animate({
            left: left
        },{
            duration:500, 
            easing: style
        });	

		
    //if user click on the menu
    }).click(function () {
			
        //reset the selected item
        $('#menu li').removeClass('selected');	
			
        //select the current item
        $(this).addClass('selected');
	
    });
		
    //If the mouse leave the menu, reset the floating bar to the selected item
    $('#menu').mouseleave(function () {

        default_left = Math.round($('#menu li.selected').offset().left - $('#menu').offset().left);

        //Set it back to default position and text
        $('#box .head').html($('#menu li.selected').find('img').attr('alt'));				
        $('#box').stop(false, true).animate({
            left: default_left
        },{
            duration:1500, 
            easing: style
        });	
			
    });
		
});
$(document).ready(function(){
 
    $("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled - Adds empty span tag after ul.subnav
	
    $("ul.topnav li span").click(function() { //When trigger is clicked...
		
        //Following events are applied to the subnav itself (moving subnav up and down)
        $(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click
 
        $(this).parent().hover(function() {
            }, function(){	
                $(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up
            });
 
    //Following events are applied to the trigger (Hover events for the trigger)
    }).hover(function() { 
        $(this).addClass("subhover"); //On hover over, add class "subhover"
    }, function(){	//On Hover Out
        $(this).removeClass("subhover"); //On hover out, remove class "subhover"
    });
 
});
$(document).ready(function(){
    $("#featured > ul").tabs({
        fx:{
            opacity: "toggle"
        }
    }).tabs("rotate", 5000, true);
    });