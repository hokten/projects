            $(function() { 

$('.modular_form_file').change(function() {
	gelen=$(this).val();
	console.log(gelen);
	console.log($(this).next().find(':first-child'));
	$(this).next().find(':first-child').val(gelen);

});           
                    $('#uploadForm').ajaxForm({
beforeSubmit: function(a,f,o) {
o.dataType = 'html';
$('#uploadOutput').html('<img src="http://dl.dropbox.com/u/157561/external/modular_form_loading.gif" />');
},
success: function(data) {
   var $out = $('#uploadOutput');
   $out.html('');
if (typeof data == 'object' && data.nodeType)
data = elementToString(data.documentElement, true);
else if (typeof data == 'object')
data = objToString(data);
            $out.append('<div><pre>'+ data +'</pre></div>');
            }
            });                                
                    });            

