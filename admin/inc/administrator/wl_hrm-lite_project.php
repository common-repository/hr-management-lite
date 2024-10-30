<?php
defined( 'ABSPATH' ) or die();
require_once( WL_HRML_PLUGIN_DIR_PATH . '/admin/inc/helpers/wl-hrm-lite-helper.php' );
$staffs       = HRMLiteHelperClass::ehrm_get_staffs_list();
$all_projects = get_option( 'ehrm_projects_data' );
?>
<!-- partial -->
<div class="main-panel">
  	<div class="content-wrapper">
	    <div class="page-header">
	      	<h3 class="page-title">
	        	<span class="page-title-icon bg-gradient-primary text-white mr-2">
					<i class="fas fa-tasks"></i>                
	        	</span>
	        	<?php esc_html_e( 'Projects', 'hr-management-lite' ); ?>
	      	</h3>
	      	<nav aria-label="breadcrumb project">
	        	<ul class="breadcrumb">
	            <li class="breadcrumb-item active" aria-current="page">
	            	<button class="btn btn-block btn-lg btn-gradient-primary custom-btn" data-bs-toggle="modal" data-bs-target="#AddProjects">
	            		<i class="fas fa-plus"></i> <?php esc_html_e( 'Add Project', 'hr-management-lite' ); ?>
	            	</button>
	          	</li>
	        	</ul>
	      	</nav>
	    </div>
        <div class="row report_row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card table_card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="project_table" class="table table-striped report_table" cellspacing="0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Name', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Started On', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Member(s)', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Tags', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Actions', 'hr-management-lite' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="report_tbody">
                                <?php 
                                    if ( ! empty ( $all_projects ) ) {
                                    $sno = 1;
                                    foreach ( $all_projects as $key => $project ) {
										$members = unserialize( $project['members'] );
		                        ?>
								<tr>
									<td><?php echo esc_html( $sno ); ?>.</td>
									<td><?php echo esc_html( $project['name'] ); ?></td>
									<td><?php echo esc_html( date( HRMLiteHelperClass::get_date_format(), strtotime( $project['date'] ) ) ); ?></td>
									<td>
									<?php
										foreach ( $members as $member_key => $value ) {
											echo esc_html(HRMLiteHelperClass::get_current_user_data( $value, 'fullname' ) . ', ');
										}
									?>
									</td>
									<td class="project-token-tags">
										<?php $tags = explode( ",", $project['tags'] );
											foreach ( $tags as $tag_key => $value ) {
												echo '<span class="token-field-value-span">'.esc_html($value).'</span>';
											}
										?>
									</td>
									<td><?php echo esc_html( $project['status'] ); ?></td>
									<td class="designation-action-tools">
										<ul class="designation-action-tools-ul">
											<li class="designation-action-tools-li">
												<a href="#" title="<?php esc_html_e( 'View Tasks', 'hr-management-lite' ); ?>" class="designation-action-tools-a project-view-a" data-project="<?php echo esc_attr( $key ); ?>">
													<i class="fas fa-eye"></i>
												</a>
											</li>
											<li class="designation-action-tools-li">
												<a href="#" title="<?php esc_html_e( 'Edit', 'hr-management-lite' ); ?>" class="designation-action-tools-a project-edit-a" data-project="<?php echo esc_attr( $key ); ?>">
													<i class="fas fa-pencil-alt"></i>
												</a>
											</li>
											<li class="designation-action-tools-li">
												<a href="#" title="<?php esc_html_e( 'Delete', 'hr-management-lite' ); ?>" class="designation-action-tools-a project-delete-a" data-project="<?php echo esc_attr( $key ); ?>">
													<i class="far fa-window-close"></i>
												</a>
											</li>
										</ul>
									</td>
								</tr>
                                <?php $sno++; } } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th><?php esc_html_e( 'No.', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Name', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Started On', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Member(s)', 'hr-management-lite' ); ?></th>
										<th><?php esc_html_e( 'Tags', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></th>
                                        <th><?php esc_html_e( 'Actions', 'hr-management-lite' ); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Project Modal -->
		<div class="modal fade" id="AddProjects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Project Details', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_project_form">
	                  	<div class="form-group">
	                      <label for="project_name"><?php esc_html_e( 'Project Title', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="project_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="project_desc"><?php esc_html_e( 'Project Description', 'hr-management-lite' ); ?></label>
	                      <textarea class="form-control" rows="4" id="project_desc" name="project_desc" placeholder="<?php esc_html_e( 'Description....', 'hr-management-lite' ); ?>"></textarea>
	                    </div>
						<div class="form-group">
	                      	<label for="project_members"><?php esc_html_e( 'Select Members88', 'hr-management-lite' ); ?></label>
							  <select name="project_members" id="project_member" class="member-select" multiple data-live-search="true">
								<?php foreach ( $staffs as $key => $staff ) { ?>	
                                    <option value="<?php echo esc_attr( $staff['ID'] ); ?>"><?php echo esc_html( $staff['fullname'] ); ?></option>
                                <?php } ?>
							</select>
	                    </div>
						<div class="form-group">
	                      	<label for="project_tags"><?php esc_html_e( 'Tags', 'hr-management-lite' ); ?></label>
							<input type="text" class="form-control" id="project_tags" name="project_tags" placeholder="<?php esc_html_e( 'Type something and hit enter', 'hr-management-lite' ); ?>"/>
	                    </div>
	                    <div class="form-group">
	                      	<label for="project_status"><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></label>
	                      	<select name="project_status" id="project_status" class="form-control">
	                      		<option value="<?php echo esc_attr('Active'); ?>"><?php esc_html_e( 'Active', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Inactive'); ?>"><?php esc_html_e( 'Inactive', 'hr-management-lite' ); ?></option>
	                      	</select>
	                    </div>
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_project_btn" value="<?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- Edit Project Modal -->
		<div class="modal fade" id="EditProjects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Project Details', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_project_form">
	                  	<div class="form-group">
	                      <label for="edit_project_name"><?php esc_html_e( 'Project Title', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="edit_project_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_project_desc"><?php esc_html_e( 'Project Description', 'hr-management-lite' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_project_desc" name="edit_project_desc" placeholder="<?php esc_html_e( 'Description....', 'hr-management-lite' ); ?>"></textarea>
	                    </div>
						<div class="form-group">
	                      	<label for="edit_project_members"><?php esc_html_e( 'Select Members', 'hr-management-lite' ); ?></label>
							<select name="edit_project_members" id="edit_project_members" class="member-select" multiple data-live-search="true">
								<?php foreach ( $staffs as $key => $staff ) { ?>
                                    <option value="<?php echo esc_attr( $staff['ID'] ); ?>"><?php echo esc_html( $staff['fullname'] ); ?></option>
                                <?php } ?>
							</select>							
	                    </div>
						<div class="form-group">
	                      	<label for="edit_project_tags"><?php esc_html_e( 'Tags', 'hr-management-lite' ); ?></label>
							<input type="text" class="form-control" id="edit_project_tags" name="edit_project_tags" placeholder="<?php esc_html_e( 'Type something and hit enter', 'hr-management-lite' ); ?>"/>
	                    </div>
	                    <div class="form-group">
	                      	<label for="edit_project_status"><?php esc_html_e( 'Status', 'hr-management-lite' ); ?></label>
	                      	<select name="edit_project_status" id="edit_project_status" class="form-control">
	                      		<option value="<?php echo esc_attr('Active'); ?>"><?php esc_html_e( 'Active', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Inactive'); ?>"><?php esc_html_e( 'Inactive', 'hr-management-lite' ); ?></option>
	                      	</select>
						</div>
						<input type="hidden" name="project_key" id="project_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_project_btn" value="<?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- To show all tasks -->
		<div class="modal fade" id="ViewProjects" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-custom-lg modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
						<div class="page-header">
							<h3 class="page-title">
								<span class="page-title-icon bg-gradient-primary text-white mr-2">
								<i class="fas fa-tasks"></i>                  
								</span>
								<?php esc_html_e( 'Task List', 'hr-management-lite' ); ?>
							</h3>
							<nav aria-label="breadcrumb Tasks">
								<ul class="breadcrumb">
									<li class="breadcrumb-item active" aria-current="page">
										<button class="btn btn-block btn-lg btn-gradient-primary custom-btn task-add-btnn" data-project="" data-bs-toggle="modal" data-bs-target="#AddTasks">
											<i class="fas fa-plus"></i> <?php esc_html_e( 'Add Tasks', 'hr-management-lite' ); ?>
										</button>
									</li>
								</ul>
							</nav>
						</div>
						<div class="all-task-list-div">
							<ul class="project-task-ul">
							</ul>
						</div>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- Add Task Modal -->
		<div class="modal fade" id="AddTasks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-custom-lg-1 modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Task Details', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="add_task_form" autocomplete="off">
	                  	<div class="form-group">
	                      <label for="task_name"><?php esc_html_e( 'Task Title', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="task_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="task_desc"><?php esc_html_e( 'Task Description', 'hr-management-lite' ); ?></label>
	                      <textarea class="form-control" rows="4" id="task_desc" name="task_desc" placeholder="<?php esc_html_e( 'Description....', 'hr-management-lite' ); ?>"></textarea>
	                    </div>
						<div class="form-group">
	                      	<label for="task_members"><?php esc_html_e( 'Assigned to', 'hr-management-lite' ); ?></label>
							<select name="task_members" id="task_members" class="member-select" multiple >
								<?php foreach ( $staffs as $key => $staff ) { ?>
                                    <option value="<?php echo esc_attr( $staff['ID'] ); ?>"><?php echo esc_html( $staff['fullname'] ); ?></option>
                                <?php } ?>
							</select>
	                    </div>
	                    <div class="form-group">
	                      	<label for="task_priority"><?php esc_html_e( 'Priority', 'hr-management-lite' ); ?></label>
	                      	<select name="task_priority" id="task_priority" class="form-control">
	                      		<option value="<?php echo esc_attr('Low'); ?>"><?php esc_html_e( 'Low', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Medium'); ?>"><?php esc_html_e( 'Medium', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('High'); ?>"><?php esc_html_e( 'High', 'hr-management-lite' ); ?></option>
	                      	</select>
						</div>
						<div class="form-group">
	                      	<label for="task_progress"><?php esc_html_e( 'Progress', 'hr-management-lite' ); ?></label>
	                      	<select name="task_progress" id="task_progress" class="form-control">
	                      		<option value="<?php echo esc_attr('No Progress'); ?>"><?php esc_html_e( 'No Progress', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Completed'); ?>"><?php esc_html_e( 'Completed', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('In Progress'); ?>"><?php esc_html_e( 'In Progress', 'hr-management-lite' ); ?></option>
	                      	</select>
						</div>
						<div class="form-group">
							<label for="task_due"><?php esc_html_e( 'Due date', 'hr-management-lite' ); ?></label>
							<input type="text" class="form-control" id="task_due" placeholder="Format:- YYYY-MM-DD" data-toggle="datetimepicker" data-target="#task_due">
						</div>
						<input type="hidden" name="task_key" id="task_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="add_task_btn" value="<?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- Edit Task Modal -->
		<div class="modal fade" id="EditTasks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-custom-lg-1 modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
	                  <h4 class="card-title"><?php esc_html_e( 'Task Details', 'hr-management-lite' ); ?></h4>
	                  <form class="forms-sample" method="post" id="edit_task_form" autocomplete="off">
	                  	<div class="form-group">
	                      <label for="edit_task_name"><?php esc_html_e( 'Task Title', 'hr-management-lite' ); ?></label>
	                      <input type="text" class="form-control" id="edit_task_name" placeholder="<?php esc_html_e( 'Name', 'hr-management-lite' ); ?>">
	                    </div>
	                    <div class="form-group">
	                      <label for="edit_task_desc"><?php esc_html_e( 'Task Description', 'hr-management-lite' ); ?></label>
	                      <textarea class="form-control" rows="4" id="edit_task_desc" name="edit_task_desc" placeholder="<?php esc_html_e( 'Description....', 'hr-management-lite' ); ?>"></textarea>
	                    </div>
						<div class="form-group">
	                      	<label for="edit_task_members"><?php esc_html_e( 'Assigned to', 'hr-management-lite' ); ?></label>
							<select name="edit_task_members" id="edit_task_members" class="member-select" multiple data-live-search="true">
							</select>
	                    </div>
	                    <div class="form-group">
	                      	<label for="edit_task_priority"><?php esc_html_e( 'Priority', 'hr-management-lite' ); ?></label>
	                      	<select name="edit_task_priority" id="edit_task_priority" class="form-control">
	                      		<option value="<?php echo esc_attr('Low'); ?>"><?php esc_html_e( 'Low', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Medium'); ?>"><?php esc_html_e( 'Medium', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('High'); ?>"><?php esc_html_e( 'High', 'hr-management-lite' ); ?></option>
	                      	</select>
						</div>
						<div class="form-group">
	                      	<label for="edit_task_progress"><?php esc_html_e( 'Progress', 'hr-management-lite' ); ?></label>
	                      	<select name="edit_task_progress" id="edit_task_progress" class="form-control">
	                      		<option value="<?php echo esc_attr('No Progress'); ?>"><?php esc_html_e( 'No Progress', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('Completed'); ?>"><?php esc_html_e( 'Completed', 'hr-management-lite' ); ?></option>
	                      		<option value="<?php echo esc_attr('In Progress'); ?>"><?php esc_html_e( 'In Progress', 'hr-management-lite' ); ?></option>
	                      	</select>
						</div>
						<div class="form-group">
							<label for="edit_task_due"><?php esc_html_e( 'Due date', 'hr-management-lite' ); ?></label>
							<input type="text" class="form-control" id="edit_task_due" placeholder="Format:- YYYY-MM-DD" data-bs-toggle="datetimepicker" data-bs-target="#edit_task_due">
						</div>
						<input type="hidden" name="edit_task_key" id="edit_task_key">
						<input type="hidden" name="edit_project_key" id="edit_project_key">
	                    <input type="button" class="btn btn-gradient-primary mr-2" id="edit_task_btn" value="<?php esc_html_e( 'Submit', 'hr-management-lite' ); ?>">
	                  </form>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

		<!-- View Task Details Modal -->
		<div class="modal fade" id="ViewTaskDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-notify modal-lg modal-custom-lg modal-info">
		    <div class="modal-content">
		     	<div class="card">
	                <div class="card-body">
						<div class="page-header">
							<h3 class="page-title">
								<span class="page-title-icon bg-gradient-primary text-white mr-2">
								<i class="fas fa-tasks"></i>                  
								</span>
								<?php esc_html_e( 'Task Details', 'hr-management-lite' ); ?>
							</h3>
						</div>
						<div class="task_detail_result"></div>
	                </div>
	            </div>
		    </div>
		  </div>
		</div>

    </div>
</div>