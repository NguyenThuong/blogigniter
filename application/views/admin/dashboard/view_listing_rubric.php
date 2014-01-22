	<?php if($query->num_rows() > 1): ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<th>ID</th>
					<th>Titre</th>
					<th>Description</th>
					<th></th>
					<th></th>
				</tr>
				<?php foreach($query->result() as $row): ?>
				<tr>
					<td><?php echo $row->r_id; ?></td>
					<td><?php echo $row->r_title; ?></td>
					<td><?php echo character_limiter($row->r_description, 64); ?></td>
					<td><a href="<?php echo base_url('admin/dashboard/edit_rubric/' . $row->r_id); ?>" title="Modifier"><i class="glyphicon glyphicon-pencil"></i></a></td>
					<td><a href="<?php echo base_url('admin/dashboard/delete_rubric/' . $row->r_id); ?>" onclick="return deleteConfirm()" title="Supprimer" ><i class="glyphicon glyphicon-trash"></i></a></td>
				</tr>
				<?php endforeach; ?>
			</table><!-- end .table .table-hover -->
		</div><!-- end .table-responsive -->
		
		<script>
			function deleteConfirm() {
				var a = confirm("Etes-vous sur de vouloir supprimer cette catégorie ?!");
				if (a){
					return true;
				}
				else{
					return false;
				}
			}
		</script>

		<?php endif; ?>