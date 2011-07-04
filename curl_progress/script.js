$.global = {};
$.global.cont = true;
function display() {
      $.ajax({
            cache: false,
            type: 'post',
            url: 'display.php',
	 dataType: 'json',
            success: function(response) {
		inen = parseInt(response.inen);
		devam = parseInt(response.devam);
		toplam = parseInt(response.toplam);
		oran = Math.ceil((inen/toplam)*100);
		inenmb = (Math.ceil(((inen)/(1024*1024))*10))/10;
		toplammb = (Math.ceil(((toplam)/(1024*1024))*10))/10;
                  $("#progressbar").progressbar({value:oran});
			$("#progresstext").html("%"+oran);
                  $("#yazi").html(inenmb+" MB /"+toplammb+" MB");
			if($.global.cont && oran<100) {
				setTimeout(display, 600);
			}
            }
      });
}

$(document).ready(function(){
	devam=true;
      $("#progressbar").progressbar({ value: 0 });
      $("#yazi").text("%0")
      $('#start').click(function(){
            $.global.cont = true;
            $.ajax({
                  cache: false,
                  type: 'post',
                  url: 'process.php'
            });
            setTimeout(display, 1000);
      });
      $('#buton').click(function(){
            devam=false;
            $.global.cont=false;
            $.post('finish.php',{bitir:true});
      });

});




