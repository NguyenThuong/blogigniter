		<?php if($query->num_rows() > 1): ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<th>ID</th>
					<th>Titre</th>
					<th>Description</th>
					<th>Rubrique</th>
					<th>Date</th>
					<th>MAJ</th>
					<th></th>
					<th></th>
				</tr>
				<?php foreach($query->result() as $row): ?>
				<tr>
					<td><?php echo $row->c_id; ?></td>
					<td><a href="<?php echo base_url('admin/dashboard/edit/' . $row->c_id); ?>" title="Modifier"><?php echo $row->c_title; ?></a></td>
					<td><?php echo character_limiter($row->c_content, 64); ?></td>
					<td><?php echo $row->r_title; ?></td>
					<td><?php echo date("d/m/Y à H:i:s", strtotime($row->c_cdate)); ?></td>
					<td><?php echo date("d/m/Y à H:i:s", strtotime($row->c_udate)); ?></td>
					<td><a href="<?php echo base_url('admin/dashboard/edit/' . $row->c_id); ?>" title="Modifier"><i class="glyphicon glyphicon-pencil"></i></a></td>
					<td><a href="<?php echo base_url('admin/dashboard/delete/' . $row->c_id); ?>" onclick="return deleteConfirm()" title="Supprimer"><i class="glyphicon glyphicon-trash"></i></a></td>
				</tr>
				<?php endforeach; ?>
			</table><!-- end .table .table-hover -->
		</div><!-- end .table-responsive -->

		<p class="text-right"><em><?php echo $query->num_rows(); ?> article(s)</em></p>

		<script>
			function deleteConfirm() {
				var a = confirm("Etes-vous sur de vouloir supprimer cet article ?!");
				if (a){
					return true;
				}
				else{
					return false;
				}
			}
		</script>
		<?php else: ?>
			<p>Aucun article rédigé</p>
		<?php endif; ?>