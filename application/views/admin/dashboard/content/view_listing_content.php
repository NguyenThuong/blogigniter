		<?php if($query->num_rows() > 0): ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<tr>
					<th>ID</th>
					<th>Titre</th>
					<th>Description</th>
					<th>Rubrique</th>
					<th>Date</th>
					<th>MAJ</th>
					<?php if ($page == 'home'): ?>
					<th>Auteur</th>
					<?php endif; ?>
					<th>Etat</th>
					<th>Commentaires</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				<?php foreach($query->result() as $row): ?>
				<tr>
					<td><?php echo $row->c_id; ?></td>
					<td><a href="<?php echo base_url('admin/dashboard/edit/' . $row->c_id); ?>" title="Modifier"><?php echo $row->c_title; ?></a></td>
					<td><?php echo character_limiter($row->c_content, 64); ?></td>
					<td><a href="<?php echo base_url('admin/dashboard/edit_rubric/' . $row->r_id); ?>"><?php echo $row->r_title; ?></a></td>
					<td><?php echo date("d/m/Y à H:i:s", strtotime($row->c_cdate)); ?></td>
					<td><?php echo date("d/m/Y à H:i:s", strtotime($row->c_udate)); ?></td>
					<?php if ($page == 'home'): ?>
					<td><a href="<?php echo base_url('admin/dashboard/author/' . $row->u_id); ?>" title="Afficher tous ces articles"><?php echo $row->u_login; ?></a></td>
					<?php endif; ?>
					<td>
					<?php 
					switch ($row->c_state) {
						case '0':
							echo 'Brouillon';
							break;

						case '1':
							echo 'Publié';
							break;
						
						default:
							echo 'Error';
							break;
					}
					?>
					</td>
					<td><?php echo ($this->model_comment->get_comment($row->c_id)->num_rows); ?></td>
					<td><a href="<?php echo base_url('admin/dashboard/edit/' . $row->c_id); ?>" title="Modifier"><i class="glyphicon glyphicon-pencil"></i></a></td>
					<td><a href="<?php echo base_url('admin/dashboard/delete/' . $row->c_id); ?>" onclick="return deleteConfirm()" title="Supprimer"><i class="glyphicon glyphicon-trash"></i></a></td>
					<td><a href="<?php echo base_url($row->r_url_rw . '/' . $row->c_url_rw); ?>" title="Aperçu" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a></td>
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