$(document).ready(function(){
	$('#myTable').dataTable( {
		     "processing":true,
             "serverside" : true,
            "lengthMenu": [ 5, 10, 15, 25, 50, 75, 100 ],
           
          });
});