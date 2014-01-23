		<?php if ($query->num_rows() > 0): ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<th>ID</th>
					<th>Nom</th>
					<th>Statut</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				</tr>
				<?php foreach ($query->result() as $row): ?>
				<tr>
					<td><?php echo $row->u_id; ?></td>
					<td><?php echo $row->u_login; ?></td>
					<td><?php echo $row->u_level; ?></td>
					<td><a href="<?php echo base_url('admin/dashboard/edit_user/' . $row->u_id); ?>" title="Modifier"><i class="glyphicon glyphicon-pencil"></i></a></td>
					<td><a href="<?php echo base_url('admin/dashboard/delete_user/' . $row->u_id); ?>" onclick="return deleteConfirm()" title="Supprimer"><i class="glyphicon glyphicon-trash"></i></a></td>
				</tr>
				<?php endforeach; ?>
			</table><!-- end .table .table-hover -->
		</div><!-- end .table-responsive -->

		<p class="text-right"><em><?php echo $query->num_rows(); ?> utilisateur(s)</em></p>

		<script>
			function deleteConfirm() {
				var a = confirm("Etes-vous sur de vouloir supprimer cet utilisateur ?!");
				if (a){
					return true;
				}
				else{
					return false;
				}
			}
		</script>

		<?php else: ?>
			<p>Aucun utilisateur</p>
		<?php endif; ?>
