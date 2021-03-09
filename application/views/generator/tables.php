<table class="w3-table-all">
	
<tr>
	<th>#</th>
	<th>Name</th>
	<th>Type</th>
</tr>
<?php foreach ($fields as $key => $field) { ?>
<tr>
	<td><?= $key +1 ?></td>
	<td><?= $field->name ?></td>
	<td><?= $field->type ?></td>
</tr>
<?php } ?>
</table>
<div>
	<br>
	<button class="w3-btn w3-green ">Generate</button>
</div>
