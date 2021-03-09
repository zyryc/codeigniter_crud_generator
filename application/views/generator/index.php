<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<title>Generate code</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
	<div class="w3-row">
		<div class="w3-col l3 m3 s4  w3-center"><p>&nbsp;</p></div>
		<div class="w3-col l6 m6 s4  w3-center">

	<div class="w3-container w3-padding">
		
	<select name="" id="select" class="w3-select w3-border">
		<option value="">Select a table</option>
		<?php foreach ($tables as $table) { ?>
		<option value="<?= base_url('welcome/table_data/'.$table); ?>"><?= $table ?></option>
		<?php } ?>
	</select>
	</div>
		</div>
		<div class="w3-col l3 m3 s4 w3-center"><p>&nbsp;</p></div>
	</div>
	<div class="w3-container w3-padding" id="showTables">
		
	</div>
	<script>
		 $(document).ready(function(){
		  $("#select").on('change', function() {
			  // alert( this.value );
			  // this.
			  var table = this.value;
			  if (table.length > 0) {
			  $.get(table, function(data, status){
			    // alert("Data: " + data + "\nStatus: " + status);
			    // data
			    $("#showTables").html(data)
			    console.log(data)
			  });
			}else{
				$("#showTables").html("")
			}
			  $(this).attr('selected');

			});
		});
	</script>	
</body>
</html>
