$(document).ready(function(){
	if (window.PIE) {
        $('#flag_slider').each(function() {
            PIE.attach(this);
        });
        $('.slide-controll a').each(function() {
            PIE.attach(this);
        });
        $('.block-header').each(function() {
            PIE.attach(this);
        });
        $('.title').each(function() {
            PIE.attach(this);
        });
    }
	$('#tirazh-1').selectbox();
	$('.multiples-select td select').selectbox();
	var instanceOne = new ImageFlow();
	instanceOne.init({ ImageFlowID: 'flag_slider', reflectionPNG: true });
	if($('.ir-lotery').length){
		$('.ir-lotery .block').eq(1).addClass('dis');
		$('.ir-lotery .block .title').on('click', function(){
			var parent = $(this).parents('.block');
			if($(parent).hasClass('dis')){
				$('.ir-lotery .block').addClass('dis');
				$(parent).removeClass('dis');
			}
			return false;
		});
	}
	$('#tabs').tabs();
        $('#tabs li').on('click', function(){
            emptyBalls();
            if($("#tabs-straight").attr('aria-hidden')=="false"){
                $("#totalStakeLottery").val("");
              $(".lot-table input[name=selectionLine]:first").trigger("click");
              $(".lot-table").find(".stake_line:first").trigger("keyup");
              var par_obj = $(".lot-table .styled[value=0]").closest("tr");
              par_obj.find(".circle").each(function(){
                            $("#lottery_balls #ball-"+$(this).text()).addClass('active');
                            $("#lottery_balls #ball-"+$(this).text()).addClass('selected');
              });
              }
          else{
              var par_obj = $(".multiplice_last");
              var sum = 0;
              $(".multiples-select .posible_win").each(function(){
                      console.log($(this).val());
                   if ($(this).val() > 0){
                      sum += parseFloat($(this).val());
                      console.log(sum);
                   }
              });
              var all = sum*parseInt($("#cntDrawLottery option:selected").val());
             $("#totalStakeLottery").val(parseFloat(all).toFixed(2));
              par_obj.find(".circle").each(function(){
                            $("#lottery_balls #ball-"+$(this).text()).addClass('active');
                            $("#lottery_balls #ball-"+$(this).text()).addClass('selected');
              });
          }
            
        })
	$('.balls-g1').on('click', function(){
		if(!$(this).hasClass('active')){
			$('.balls-g1.active').removeClass('active');
			$(this).addClass('active');
		}
	});
	$('#clear-all').on('click', function(){
		$('.balls-g1.active').removeClass('active');
		return false;
	});
	$('.ball-item').on('click', function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
		}else{
			$(this).addClass('active');
		}
		return false;
	});
        
	$('.slide-controll a').on('click', function(){
		if(!$(this).hasClass('close')){
			$(this).prev('span').html('Развернуть');
			$(this).addClass('close');
			$(this).parents('.slide-block').animate({height: 40}, 600);
		}else{
			$(this).prev('span').html('Свернуть');
			$(this).removeClass('close');
			$(this).parents('.slide-block').animate({height: 382}, 600);
		}
		return false;
	});
	if($('.schedule').length){
		$('.schedule tbody:odd').addClass('odd');
	}
	$('li.sub > a').on('click',function(){
		$(this).toggleClass('opened');
		$(this).next('ul').slideToggle(500);
		return false;
	});
       $(".balls-g1").on("click",function(){
          hideMessage();
          var arr = [];
          var cnt = $(this).attr("param");
          var max_number = $("#lottery_balls a.ball").length;
          var r;
          $(".block-content #lottery_balls").find("a.selected").each(function(){
              $(this).trigger("click");
          });
          arr = getRandomNumbers(1, max_number, cnt);
          $(".block-content #lottery_balls a").each(function(){
              if ($.inArray(parseInt($(this).text()), arr) >= 0){
                  $(this).trigger("click");
              }
          });
          if($("#tabs-straight").attr('aria-hidden')=="false"){
          var par_obj = $(".block-content .lot-table input[name=selectionLine]:checked").closest("tr");
          }
          else{
                  var par_obj = $(".multiplice_last"); 
              }
              par_obj.find(".circle:empty").each(function(){
                        par_obj.find("#"+$(this).attr("param")).val("");
          });
      });    
        
         $(".block-content #lottery_balls .ball").on("click", function(){
             hideMessage();
          if($("#tabs-straight").attr('aria-hidden')=="false"){
                straighrBet($(this));
              }
          else{
              makeMultiples($(this));  
              BetTypeSelect();
          }
      });
      $(".lot-table .ball-item-line").on("click", function(){
          var number = $(this).text();
          var line = $(this).closest("tr").find("input[name=selectionLine]:checked").val();
          if(line){
                $("#lottery_balls .ball").each(function(){
                   if($(this).text() == number) $(this).trigger("click");
                });
          }
          else{
              return;
          }
      });
      $(".multiples-select .ball-item-line").on("click", function(){
          var number = $(this).text();
          var line = $(this).closest("tr");
          if(line){
                $("#lottery_balls .ball").each(function(){
                   if($(this).text() == number) $(this).trigger("click");
                });
          }
          else{
              return;
          }
      });
      
      $(".basket_line").on("click", function(){
          var par_obj = $(this).closest("tr");          
          var balls = [];
          var table_balls = $(".block-content #lottery_balls");
          table_balls.find("a.active").each(function(){
              $(this).removeClass("active");
              $(this).removeClass("selected");
          });
          par_obj.find(".circle a").each(function(){
              balls.push($(this).text());
          });
          table_balls.find("a.ball").each(function(){
              if($.inArray($(this).text(), balls) >= 0)
                  $(this).addClass("active");
          });
      });
     $("#cntDrawLottery").on("change", function(){
          if($("#tabs-straight").attr('aria-hidden')=="false"){
                $(".lot-table .stake_line:first").trigger("keyup");
              }
          else{
                $(".multiples-select .stake_line:first").trigger("keyup");
          }
      });
        $(".stake_line").on("keyup", function(){
          if($("#tabs-straight").attr('aria-hidden')=="false"){
          var tableLines = $(this).parents("table");
          var cntDraw = parseInt($("#cntDrawLottery option:selected").val());
          
          var sum = 0; 
          var re = new RegExp("[0-9]{1}[\\d]*[\.]{0,1}[\\d]{0,2}");
          var newVal = re.exec($(this).val());
          if ($(this).val() != newVal ) 
              $(this).val(newVal);
          tableLines.find(".stake_line").each(function(){
             if ($(this).val() > 0){
                    sum += parseFloat($(this).val());
              
             }
          });
          sum = sum * cntDraw ;
          $("#totalStakeLottery").val("");
          $("#totalStakeLottery").val(sum.toFixed(2));
          getPossibleLotteryWinSumm($(this).closest("tr"));
          }
              else{
                    count($(this));
              }
      });
});
function getPossibleLotteryWinSumm(obj){
    var cf = $("#cfLottery");
    var sum = obj.find(".stake_line").val();
    var cnt_balls = obj.find(".circle a").length;
    var withBonus = $("#lottery_bet_form input[name=withBonus]").val();
    var name = withBonus == 1 ? "b"+cnt_balls+"b" : "b"+cnt_balls;
    var pw = (cnt_balls > 0 && sum >0 ) ? 
        parseFloat( sum * cf.find("input[name="+name+"]").val()).toFixed(2) : "";
    obj.find("input[name=posible_win]").val(pw);
}
function getRandomNumbers(minNumber, maxNumber, cntNunber, repeat){
   var arrRandom = [];
   for ( var i = cntNunber; i--; ) {
       r = Math.floor(Math.random() * (maxNumber - minNumber + 1)) + minNumber;
       if (arrRandom.length == 0 && $.inArray(r, repeat) == -1)  arrRandom[i] = r;             
       else if ($.inArray(r, arrRandom) == -1 && $.inArray(r, repeat) == -1) arrRandom[i] = r;
       else{
           repeat = typeof(repeat) == "undefined" ? arrRandom : repeat;
           arrRandom[i] = getRandomNumbers(minNumber, maxNumber, 1, repeat);
       }
   } 
   if (arrRandom.length == 1)
       return arrRandom[0];
   else
       return arrRandom;
} 
function makeMultiples(obj){
   var par_obj = $(".multiples-select");
   var cntBalls = par_obj.find(".circle:empty").length;
   
   if (obj.hasClass("selected")){
       obj.removeClass("selected");
                movementBalls(par_obj.find(".multiplice_last .circle a[ball="+obj.text()+"]").parent());
       par_obj.find(".circle a").each(function(){
                    if($(this).text() == obj.html()){
                        $(this).text("");
                        par_obj.find("#"+$(this).parent().attr("param")).val("");
                        par_obj.find("div[param="+$(this).parent().attr("param")+"]").removeClass("active");
                        
                        $(this).remove();
                    }
                });  
   }
   else{
       obj.addClass("selected"); 
       var rowBall = par_obj.find(".circle:empty:first");
       par_obj.find(".circle[for="+rowBall.attr("for")+"]").each(function(){
                       $(this).html("");
                       par_obj.find("#"+$(this).parent().attr("param")).val("");
                       $(this).append(obj.clone()); 
                       par_obj.find("#"+$(this).attr("param")).val(obj.text());
                       $(this).addClass("active");

                });
   }
    if (par_obj.find(".circle a").length > 1) par_obj.find(".stake_line").attr("readonly", false).removeClass("gray");
    if (cntBalls == 0) {
                obj.removeClass("selected");  
                obj.removeClass("active");
                    $("#showErrorLottery").empty().html("<p>Нет свободного места в ячейке!</p>").show(); 
                    return;}
}
function straighrBet(obj){
    $(".lot-table .stake_line:first").trigger("keyup");
          var line = $(".lot-table input[name=selectionLine]:checked");
          var line_val = $(".lot-table input[name=selectionLine]:checked").val();
          if(!line.val()) {
              $(".lot-table input[name=selectionLine]:first").trigger("click");              
              line = $(".lot-table input[name=selectionLine]:checked");
          }
          
          var par_obj = line.closest("tr");
          var cntBalls = par_obj.find(".circle:empty").length;
          if (obj.hasClass("selected")){
                obj.removeClass("selected");  
                obj.removeClass("active");  
                par_obj.find(".circle a").each(function(){
                    if($(this).text() == obj.html()){
                        par_obj.find("#"+$(this).parent().attr("param")).val("");
                        par_obj.find("div[param="+$(this).parent().attr("param")+"]").removeClass("active");
                        movementBalls(par_obj.find("div[param="+$(this).parent().attr("param")+"]"));
                        $(this).remove();
                    }
                });
                
          $(".lot-table .stake_line[theme="+line_val+"]").trigger("keyup");
          }else{
                if (cntBalls == 0) {
                obj.removeClass("selected");  
                obj.removeClass("active");
                    $("#showErrorLottery").empty().html("<p>Нет свободного места в ячейке!</p>").show(); 
                    return;}
                var rowBall = par_obj.find(".circle:empty:first");
                obj.addClass("selected");    
                rowBall.append(obj.clone());            
                par_obj.find("#"+rowBall.attr("param")).val(obj.text());
                rowBall.addClass("active");
                
          $(".lot-table .stake_line[theme="+line_val+"]").trigger("keyup");
          }
          cntBalls = par_obj.find(".circle:empty").length;
          if (cntBalls == 5) par_obj.find(".stake_line").attr("readonly", true).addClass("gray");
          else if (cntBalls == 4) par_obj.find(".stake_line").attr("readonly", false).removeClass("gray");
}
function movementBalls(obj){

    if($("#tabs-straight").attr('aria-hidden')=="false"){
    var line = $(".lot-table input[name=selectionLine]:checked");
    var par_obj = line.closest("tr");
    
    par_obj.find(".circle a").each(function(){
                    if(obj.attr("param") < $(this).parent().attr("param")){
                       var some = $(this).parent().attr("param");
                       var current = parseInt($(this).parent().attr("position"))-1;
                       var previous = parseInt($(this).parent().attr("position"));
                       var key =  $(this).parent().attr("param").length-1;
                       var last = some.substring(0, key);
                       var next = last+current;
                       var prev = last+previous;
                        $(par_obj.find("div[param="+next+"]")).html(par_obj.find("div[param="+prev+"]").html());
                        $(par_obj.find("div[param="+next+"]")).addClass("active");
                        $(par_obj.find("div[param="+prev+"]")).html("");
                        $(par_obj.find("div[param="+prev+"]")).removeClass("active");
                        par_obj.find("#"+next).val($(this).text());
                        par_obj.find("#"+prev).val("");
                    }
                });
    }
      else{
          var par_obj = $(".multiples-select");
           par_obj.find(".multiplice_last .circle a").each(function(){
                    if(obj.attr("param") < $(this).parent().attr("param")){
                       var some = $(this).parent().attr("param");
                       var current = parseInt($(this).parent().attr("position"))-1;
                       var previous = parseInt($(this).parent().attr("position"));
                       var key =  $(this).parent().attr("param").length-1;
                       var last = some.substring(0, key);
                       var next = last+current;
                       var prev = last+previous;
                        $(par_obj.find("div[param="+next+"]")).html(par_obj.find("div[param="+prev+"]").html());
                        $(par_obj.find("div[param="+next+"]")).addClass("active");
                        $(par_obj.find("div[param="+prev+"]")).html("");
                        $(par_obj.find("div[param="+prev+"]")).removeClass("active");
                        par_obj.find("#"+next).val($(this).text());
                        par_obj.find("#"+prev).val("");
                    par_obj.find(".circle[position="+current+"]").each(function(){
                        $(this).html(par_obj.find("div[param="+next+"]").html());
                        $(this).addClass("active");
                        par_obj.find("#"+$(this).attr("param")).val(par_obj.find("div[param="+next+"]").text());
                      });
                    par_obj.find(".circle[position="+previous+"]").each(function(){
                        $(this).html("");
                        $(this).removeClass("active");
                        par_obj.find("#"+$(this).attr("param")).val("");
                      });
                    }
                });
    }
}
function emptyBalls(){
     var table_balls = $(".block-content #lottery_balls");
          table_balls.find("a.active").each(function(){
              $(this).removeClass("active");
              $(this).removeClass("selected");
          });
}
function hideMessage(){
    $("#showErrorLottery").empty(); 
}
function BetTypeSelect(){  
    $(".multiples-select tbody tr").each(function(){
                        var select = $(this).find("td select")
                        var link = select.parent('td').attr('url');
                        var count = $(this).find(".circle a").length;
                            $.ajax({
                                type: "POST",
                                url: link+"/"+count,
                                dataType: "html",
                                success: function(data){
                                    select.parent('td').html(data);
                                }
                });
                });
}
function count(obj){
    var tr = obj.parents("tr");
    var select = tr.find("td option:selected");
    var cntDraw = parseInt($("#cntDrawLottery option:selected").val());
    var table=$(".multiples-select");
    var sum=0;
    if(select.val()!=0){
            var betTypeId = select.val();
            var ballsCount = tr.find(".circle a").length;
            var summa = parseInt(obj.val());
            
            var line = $("#formula_table").find("tr[type="+betTypeId+"][number="+ballsCount+"]")
            
            var countBet = parseInt(line.find(".count_bets").text());
            
            var formula = line.find(".formula").text();
            for(var i = 1; i < 6; i++){
                var withBonus = $("#lottery_bet_form input[name=withBonus]").val();
                var name = withBonus == 1 ? "b"+i+"b" : "b"+i;
              var formula = formula.replace('%'+i+'%',$("input[name="+name+"]").val());
            }
            
            tr.find('.count_bets').text(countBet);
            tr.find('.posible_win').val(countBet*summa);
            tr.find('.bet_return').text(parseFloat(parseInt(eval(formula))*summa).toFixed(2));
        table.find(".posible_win").each(function(){
             if ($(this).val() > 0){
                    sum += parseFloat($(this).val());
              
             }
          });
          sum = sum * cntDraw ;
          $("#totalStakeLottery").val("");
          $("#totalStakeLottery").val(sum.toFixed(2));
    }
}