<?php
/**
 * Allows to hide, show or delete the files assigend to the
 * selected client.
 *
 * @package ProjectSend
 */
$footable_min = true; // delete this line after finishing pagination on every table
$load_scripts	= array(
						'footable',
					); 

$allowed_levels = array(9,8,7,0);
require_once('sys.includes.php');

$active_nav = 'files';

$page_title = __('Ҳужжатларни бошқариш: ','cftp_admin');

$current_level = get_current_user_level();

/**
 * Used to distinguish the current page results.
 * Global means all files.
 * Client or group is only when looking into files
 * assigned to any of them.
 */
$results_type = 'global';

/**
 * The client's id is passed on the URI.
 * Then get_client_by_id() gets all the other account values.
 */
if (isset($_GET['client_id'])) {
	$this_id = $_GET['client_id'];
	$this_client = get_client_by_id($this_id);
	/** Add the name of the client to the page's title. */
	if(!empty($this_client)) {
		$page_title .= ' '.__('аудит учун','cftp_admin').' '.html_entity_decode($this_client['name']);
		$search_on = 'client_id';
		$name_for_actions = $this_client['username'];
		$results_type = 'client';
	}
}

/**
 * The group's id is passed on the URI also.
 */
if (isset($_GET['group_id'])) {
	$this_id = $_GET['group_id'];


	$sql_name = $dbh->prepare("SELECT name from " . TABLE_GROUPS . " WHERE id=:id");
	$sql_name->bindParam(':id', $this_id, PDO::PARAM_INT);
	$sql_name->execute();							

	if ( $sql_name->rowCount() > 0) {
		$sql_name->setFetchMode(PDO::FETCH_ASSOC);
		while( $row_group = $sql_name->fetch() ) {
			$group_name = $row_group["name"];
		}
		/** Add the name of the client to the page's title. */
		if(!empty($group_name)) {
			$page_title .= ' '.__('бўлим номи => ','cftp_admin').' '.html_entity_decode($group_name);
			$search_on = 'group_id';
			$name_for_actions = html_entity_decode($group_name);
			$results_type = 'group';
		}
	}
}

/**
 * Filtering by category
 */
if (isset($_GET['category'])) {
	$this_id = $_GET['category'];
	$this_category = get_category($this_id);
	/** Add the name of the client to the page's title. */
	if(!empty($this_category)) {
		$page_title .= ' '.__('гуруҳ учун','cftp_admin').' '.html_entity_decode($this_category['name']);
		$name_for_actions = $this_category['name'];
		$results_type = 'category';
	}
}

include('header.php');
?>

<div class="col-xs-12">
	<?php
		/**
		 * Apply the corresponding action to the selected files.
		 */
		if(isset($_GET['action'])) {
			/** Continue only if 1 or more files were selected. */
			if(!empty($_GET['batch'])) {
				$selected_files = array_map('intval',array_unique($_GET['batch']));
				$files_to_get = implode(',',$selected_files);
				/**
				 * Make a list of files to avoid individual queries.
				 * First, get all the different files under this account.
				 */
				$sql_distinct_files = $dbh->prepare("SELECT file_id FROM " . TABLE_FILES_RELATIONS . " WHERE FIND_IN_SET(id, :files)");
				$sql_distinct_files->bindParam(':files', $files_to_get);
				$sql_distinct_files->execute();
				$sql_distinct_files->setFetchMode(PDO::FETCH_ASSOC);
				
				while( $data_file_relations = $sql_distinct_files->fetch() ) {
					$all_files_relations[] = $data_file_relations['file_id']; 
					$files_to_get = implode(',',$all_files_relations);
				}
				
				/**
				 * Then get the files names to add to the log action.
				 */
				$sql_file = $dbh->prepare("SELECT id, filename FROM " . TABLE_FILES . " WHERE FIND_IN_SET(id, :files)");
				$sql_file->bindParam(':files', $files_to_get);
				$sql_file->execute();
				$sql_file->setFetchMode(PDO::FETCH_ASSOC);

				while( $data_file = $sql_file->fetch() ) {
					$all_files[$data_file['id']] = $data_file['filename'];
				}
				switch($_GET['action']) {
					case 'hide':
						/**
						 * Changes the value on the "hidden" column value on the database.
						 * This files are not shown on the client's file list. They are
						 * also not counted on the home.php files count when the logged in
						 * account is the client.
						 */
						foreach ($selected_files as $work_file) {
							$this_file = new FilesActions();
							$hide_file = $this_file->change_files_hide_status('1', $work_file, $_GET['modify_type'], $_GET['modify_id']);
						}
						$msg = __('Танланган ҳужжатлар яширин ҳолатда!','cftp_admin');
						echo system_message('ok',$msg);
						$log_action_number = 21;
						break;

					case 'show':
						/**
						 * Reverse of the previous action. Setting the value to 0 means
						 * that the file is visible.
						 */
						foreach ($selected_files as $work_file) {
							$this_file = new FilesActions();
							$show_file = $this_file->change_files_hide_status('0', $work_file, $_GET['modify_type'], $_GET['modify_id']);
						}
						$msg = __('Танланган ҳужжатлар ошкора ҳолатда!','cftp_admin');
						echo system_message('ok',$msg);
						$log_action_number = 22;
						break;

					case 'unassign':
						/**
						 * Remove the file from this client or group only.
						 */
						foreach ($selected_files as $work_file) {
							$this_file = new FilesActions();
							$unassign_file = $this_file->unassign_file($work_file, $_GET['modify_type'], $_GET['modify_id']);
						}
						$msg = __('Танланган ҳужжатлар аудитга йўналтирилмаган!','cftp_admin');
						echo system_message('ok',$msg);
						if ($search_on == 'group_id') {
							$log_action_number = 11;
						}
						elseif ($search_on == 'client_id') {
							$log_action_number = 10;
						}
						break;

					case 'delete':
						$delete_results	= array(
												'ok'		=> 0,
												'errors'	=> 0,
											);
						foreach ($selected_files as $index => $file_id) {
							$this_file		= new FilesActions();
							$delete_status	= $this_file->delete_files($file_id);

							if ( $delete_status == true ) {
								$delete_results['ok']++;
							}
							else {
								$delete_results['errors']++;
								unset($all_files[$file_id]);
							}
						}

						if ( $delete_results['ok'] > 0 ) {
							$msg = __('Танланган ҳужжатлар ўчирилган!','cftp_admin');
							echo system_message('ok',$msg);
							$log_action_number = 12;
						}
						if ( $delete_results['errors'] > 0 ) {
							$msg = __('Баъзи бир ҳужжатларни ўчиришни иложи бўлмади!','cftp_admin');
							echo system_message('error',$msg);
						}
						break;
				}

				/** Record the action log */
				foreach ($all_files as $work_file_id => $work_file) {
					$new_log_action = new LogActions();
					$log_action_args = array(
											'action' => $log_action_number,
											'owner_id' => CURRENT_USER_ID,
											'affected_file' => $work_file_id,
											'affected_file_name' => $work_file
										);
					if (!empty($name_for_actions)) {
						$log_action_args['affected_account_name'] = $name_for_actions;
						$log_action_args['get_user_real_name'] = true;
					}
					$new_record_action = $new_log_action->log_action_save($log_action_args);
				}
			}
			else {
				$msg = __('Please select at least one file.','cftp_admin');
				echo system_message('error',$msg);
			}
		}
		
		/**
		 * Global form action
		 */
		$query_table_files = true;

		if (isset($search_on)) {
			$params = array();
			$rq = "SELECT * FROM " . TABLE_FILES_RELATIONS . " WHERE $search_on = :id";
			$params[':id'] = $this_id;

			/** Add the status filter */	
			if (isset($_GET['hidden']) && $_GET['hidden'] != 'all') {
				$set_and = true;
				$rq .= " AND hidden = :hidden";
				$no_results_error = 'filter';
				
				$params[':hidden'] = $_GET['hidden'];
			}

			/**
			 * Count the files assigned to this client. If there is none, show
			 * an error message.
			 */
			$sql = $dbh->prepare($rq);
			$sql->execute( $params );
			
			if ( $sql->rowCount() > 0) {
				/**
				 * Get the IDs of files that match the previous query.
				 */
				$sql->setFetchMode(PDO::FETCH_ASSOC);
				while( $row_files = $sql->fetch() ) {
					$files_ids[] = $row_files['file_id'];
					$gotten_files = implode(',',$files_ids);
				}
			}
			else {
				$count = 0;
				$no_results_error = 'filter';
				$query_table_files = false;
			}
		}

		if ( $query_table_files === true ) {
			/**
			 * Get the files
			 */
			$params = array();
			
			/**
			 * Add the download count to the main query.
			 * If the page is filtering files by client, then
			 * add the client ID to the subquery.
			 */
			$add_user_to_query = '';
			if ( isset($search_on) && $results_type == 'client' ) {
				$add_user_to_query = "AND user_id = :user_id";
				$params[':user_id'] = $this_id;
			}
			$cq = "SELECT files.*, ( SELECT COUNT(file_id) FROM " . TABLE_DOWNLOADS . " WHERE " . TABLE_DOWNLOADS . ".file_id=files.id " . $add_user_to_query . ") as download_count FROM " . TABLE_FILES . " files";
	
			if ( isset($search_on) && !empty($gotten_files) ) {
				$conditions[] = "FIND_IN_SET(id, :files)";
				$params[':files'] = $gotten_files;
			}
	
			/** Add the search terms */	
			if(isset($_GET['search']) && !empty($_GET['search'])) {
				$conditions[] = "(filename LIKE :name OR description LIKE :description)";
				$no_results_error = 'search';
	
				$search_terms			= '%'.$_GET['search'].'%';
				$params[':name']		= $search_terms;
				$params[':description']	= $search_terms;
			}

			/**
			 * Filter by uploader
			 */	
			if(isset($_GET['uploader']) && !empty($_GET['uploader'])) {
				$conditions[] = "uploader = :uploader";
				$no_results_error = 'filter';
	
				$params[':uploader'] = $_GET['uploader'];
			}


			/**
			 * If the user is an uploader, or a client is editing his files
			 * only show files uploaded by that account.
			*/
			$current_level = get_current_user_level();
			if ($current_level == '7' || $current_level == '0') {
				$conditions[] = "uploader = :uploader";
				$no_results_error = 'account_level';
	
				$params[':uploader'] = $global_user;
			}
			
			/**
			 * Add the category filter
			 */
			if ( isset( $results_type ) && $results_type == 'category' ) {
				$files_id_by_cat = array();
				$statement = $dbh->prepare("SELECT file_id FROM " . TABLE_CATEGORIES_RELATIONS . " WHERE cat_id = :cat_id");
				$statement->bindParam(':cat_id', $this_category['id'], PDO::PARAM_INT);
				$statement->execute();
				$statement->setFetchMode(PDO::FETCH_ASSOC);
				while ( $file_data = $statement->fetch() ) {
					$files_id_by_cat[] = $file_data['file_id'];
				}
				$files_id_by_cat = implode(',',$files_id_by_cat);
	
				/** Overwrite the parameter set previously */
				$conditions[] = "FIND_IN_SET(id, :files)";
				$params[':files'] = $files_id_by_cat;
				
				$no_results_error = 'category';
			}
	
			/**
			 * Build the final query
			 */
			if ( !empty( $conditions ) ) {
				foreach ( $conditions as $index => $condition ) {
					$cq .= ( $index == 0 ) ? ' WHERE ' : ' AND ';
					$cq .= $condition;
				}
			}

			/**
			 * Add the order.
			 * Defaults to order by: date, order: ASC
			 */
			$cq .= sql_add_order( TABLE_FILES, 'timestamp', 'desc' );

			/**
			 * Pre-query to count the total results
			*/
			$count_sql = $dbh->prepare( $cq );
			$count_sql->execute($params);
			$count_for_pagination = $count_sql->rowCount();
		
			/**
			 * Repeat the query but this time, limited by pagination
			 */
			$cq .= " LIMIT :limit_start, :limit_number";
			$sql = $dbh->prepare( $cq );
		
			$pagination_page			= ( isset( $_GET["page"] ) ) ? $_GET["page"] : 1;
			$pagination_start			= ( $pagination_page - 1 ) * RESULTS_PER_PAGE;
			$params[':limit_start']		= $pagination_start;
			$params[':limit_number']	= RESULTS_PER_PAGE;
		
			$sql->execute( $params );
			$count = $sql->rowCount();
	
			/** Debug query */
			//echo $cq;
			//print_r( $conditions );
		}
		else {
			$count_for_pagination = 0;
		}
	?>
		<div class="form_actions_left">
			<div class="form_actions_limit_results">
				<?php show_search_form('manage-files.php'); ?>

				<?php
					if( $current_level != '0' && $results_type == 'global') {
				?>
					<form action="manage-files.php" name="files_filters" method="get" class="form-inline form_filters">
						<?php form_add_existing_parameters( array('hidden', 'action', 'uploader') ); ?>
						<div class="form-group group_float">
							<select name="uploader" id="uploader" class="txtfield form-control">
								<?php
									$status_options = array(
															'0'		=> __('Танланг','cftp_admin'),
														);
									$sql_uploaders = $dbh->prepare("SELECT uploader FROM " . TABLE_FILES . " GROUP BY uploader");
									$sql_uploaders->execute();
									$sql_uploaders->setFetchMode(PDO::FETCH_ASSOC);

									while( $data_uploaders = $sql_uploaders->fetch() ) {
										$status_options[$data_uploaders['uploader']] = $data_uploaders['uploader'];
									}

									foreach ( $status_options as $val => $text ) {
								?>
										<option value="<?php echo $val; ?>" <?php if ( isset( $_GET['uploader'] ) && $_GET['uploader'] == $val ) { echo 'selected="selected"'; } ?>><?php echo $text; ?></option>
								<?php
									}
								?>
							</select>
						</div>
						<button type="submit" id="btn_proceed_filter_clients" class="btn btn-sm btn-default"><?php _e('Саралаш','cftp_admin'); ?></button>
					</form>
				<?php
					}

					/** Filters are not available for clients */
					if($current_level != '0' && $results_type != 'global') {
				?>
						<form action="manage-files.php" name="files_filters" method="get" class="form-inline form_filters">
							<?php form_add_existing_parameters( array('hidden', 'action', 'uploader') ); ?>
							<div class="form-group group_float">
								<select name="hidden" id="hidden" class="txtfield form-control">
									<?php
										$status_options = array(
																'2'		=> __('Барча ҳолатлар','cftp_admin'),
																'0'		=> __('Ошкора','cftp_admin'),
																'1'		=> __('Яширин','cftp_admin'),
															);
										foreach ( $status_options as $val => $text ) {
									?>
											<option value="<?php echo $val; ?>" <?php if ( isset( $_GET['hidden'] ) && $_GET['hidden'] == $val ) { echo 'selected="selected"'; } ?>><?php echo $text; ?></option>
									<?php
										}
									?>
								</select>
							</div>
							<button type="submit" id="btn_proceed_filter_clients" class="btn btn-sm btn-default"><?php _e('Саралаш','cftp_admin'); ?></button>
						</form>
				<?php
					}
				?>
			</div>
		</div>


		<form action="manage-files.php" name="files_list" method="get" class="form-inline">
			<?php form_add_existing_parameters( array( 'modify_id', 'modify_type' ) ); ?>
			<?php
				/** Actions are not available for clients */
				if($current_level != '0' || CLIENTS_CAN_DELETE_OWN_FILES == '1') {
			?>
					<div class="form_actions_right">
						<div class="form_actions">
							<div class="form_actions_submit">
								<div class="form-group group_float">
									<label class="control-label hidden-xs hidden-sm"><i class="glyphicon glyphicon-check"></i> <?php _e('Ҳаракатни танланг','cftp_admin'); ?>:</label>
									<?php
										if (isset($search_on)) {
									?>
										<input type="hidden" name="modify_type" id="modify_type" value="<?php echo $search_on; ?>" />
										<input type="hidden" name="modify_id" id="modify_id" value="<?php echo $this_id; ?>" />
									<?php
										}
									?>
									<select name="action" id="action" class="txtfield form-control">
										<?php
											$actions_options = array(
																	'none'			=> __('Танланг','cftp_admin'),
																);

											/** Options only available when viewing a client/group files list */
											if (isset($search_on)) {
												$actions_options['hide']		= __('Яшириш','cftp_admin');
												$actions_options['show']		= __('Кўрсатиш','cftp_admin');
												$actions_options['unassign']	= __('Йўналтирмаслик','cftp_admin');
											}
											else {
												$actions_options['delete']		= __('Ўчириш','cftp_admin');
											}

											foreach ( $actions_options as $val => $text ) {
										?>
												<option value="<?php echo $val; ?>"><?php echo $text; ?></option>
										<?php
											}
										?>
									</select>
								</div>
								<button type="submit" id="do_action" class="btn btn-sm btn-default"><?php _e('Бажариш','cftp_admin'); ?></button>
							</div>
						</div>
					</div>
			<?php
				}
			?>

			<div class="clear"></div>
	
			<div class="form_actions_count">
				<p class="form_count_total"><?php _e('Топилди','cftp_admin'); ?>: <span><?php echo $count_for_pagination; ?> <?php _e('та ҳужжат','cftp_admin'); ?></span></p>
			</div>
	
			<div class="clear"></div>

			<?php
				if (!$count) {
					if (isset($no_results_error)) {
						switch ($no_results_error) {
							case 'search':
								$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
								break;
							case 'category':
								$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
								break;
							case 'filter':
								$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
								break;
							case 'none_assigned':
								$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
								break;
							case 'account_level':
								$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
								break;
						}
					}
					else {
						$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
					}
					echo system_message('error',$no_results_message);
				}

				if ( $count_for_pagination > 0 ) {
					/**
					 * Generate the table using the class.
					 */
					$table_attributes	= array(
												'id'		=> 'files_tbl',
												'class'		=> 'footable table',
											);
					$table = new generateTable( $table_attributes );
					
					/**
					 * Set the conditions to true or false once here to
					 * avoid repetition
					 * They will be used to generate or no certain columns
					 */
					$conditions = array(
										'select_all'		=> ( $current_level != '0' || CLIENTS_CAN_DELETE_OWN_FILES == '1' ) ? true : false,
										'is_not_client'		=> ( $current_level != '0' ) ? true : false,
										'total_downloads'	=> ( $current_level != '0' && !isset( $search_on ) ) ? true : false,
										'is_search_on'		=> ( isset( $search_on ) ) ? true : false,
									);
	
					$thead_columns		= array(
												array(
													'select_all'	=> true,
													'attributes'	=> array(
																			'class'		=> array( 'td_checkbox' ),
																		),
													'condition'		=> $conditions['select_all'],
												),
												array(
													'sortable'		=> true,
													'sort_url'		=> 'timestamp',
													'sort_default'	=> true,
													'content'		=> __('Сақланган сана','cftp_admin'),
													'hide'			=> 'phone',
												),
												array(
													'content'		=> __('Тури','cftp_admin'),
													'hide'			=> 'phone,tablet',
												),
												array(
													'sortable'		=> true,
													'sort_url'		=> 'filename',
													'content'		=> __('Ҳужжат номи','cftp_admin'),
												),
												array(
													'content'		=> __('Ҳужжат хажми','cftp_admin'),
												),
												array(
													'sortable'		=> true,
													'sort_url'		=> 'uploader',
													'content'		=> __('Сақлаган аудит','cftp_admin'),
													'hide'			=> 'phone,tablet',
													'condition'		=> $conditions['is_not_client'],
												),
												array(
													'content'		=> __('Йўналтирилганми','cftp_admin'),
													'hide'			=> 'phone',
													'condition'		=> ( $conditions['is_not_client'] && !$conditions['is_search_on'] ),
												),
												array(
													'sortable'		=> true,
													'sort_url'		=> 'public_allow',
													'content'		=> __('Ҳужжат ошкоралиги','cftp_admin'),
													'hide'			=> 'phone',
													'condition'		=> $conditions['is_not_client'],
												),
												array(
													'content'		=> __('Муддати','cftp_admin'),
													'hide'			=> 'phone',
													'condition'		=> $conditions['is_not_client'],
												),
												array(
													'content'		=> __('Ҳолати','cftp_admin'),
													'hide'			=> 'phone',
													'condition'		=> $conditions['is_search_on'],
												),
												array(
													'sortable'		=> true,
													'sort_url'		=> 'download_count',
													'content'		=> __('Юкланишлар сони','cftp_admin'),
													'hide'			=> 'phone',
													'condition'		=> $conditions['is_search_on'],
												),
												array(
													'sortable'		=> true,
													'sort_url'		=> 'download_count',
													'content'		=> __('Умумий юкланишлар сони','cftp_admin'),
													'hide'			=> 'phone',
													'condition'		=> $conditions['total_downloads'],
												),
												array(
													'content'		=> __('Ҳаракатлар','cftp_admin'),
													'hide'			=> 'phone',
												),
											);
	
											//echo '<pre>'; 
											//print_r($thead_columns);
											//echo '</pre>';
	
					$table->thead( $thead_columns );
	
	
					$sql->setFetchMode(PDO::FETCH_ASSOC);
					while ( $row = $sql->fetch() ) {
						$table->add_row();
	
						/**
						 * Prepare the information to be used later on the cells array
						 */
						$file_id = $row['id'];
						$download_link = make_download_link( array( 'id' => $file_id ) );
	
						/**
						 * Visibility is only available when filtering by client or group.
						 */
						$params = array();
						$query_this_file = "SELECT * FROM " . TABLE_FILES_RELATIONS . " WHERE file_id = :file_id";
						$params[':file_id'] = $row['id'];
	
						$sql_this_file = $dbh->prepare($query_this_file);
						$sql_this_file->execute( $params );
						$sql_this_file->setFetchMode(PDO::FETCH_ASSOC);
	
						$count_assignations = $sql_this_file->rowCount();
	
						while( $data_file = $sql_this_file->fetch() ) {
							$file_id = $data_file['id'];
							$hidden = $data_file['hidden'];
						}
	
						$date = date("d/m/Y h:i:s",strtotime($row['timestamp']));
	
						/**
						 * Get file size only if the file exists
						 */
						$this_file_absolute = UPLOADED_FILES_FOLDER . $row['url'];
						if ( file_exists( $this_file_absolute ) ) {
							$this_file_size = get_real_size($this_file_absolute);
							$formatted_size = html_output(format_file_size($this_file_size));
						}
						else {
							$this_file_size = '0';
							$formatted_size = '-';
						}
	
						/***/
						$pathinfo = pathinfo($row['url']);
						$extension = ( !empty( $pathinfo['extension'] ) ) ? strtolower($pathinfo['extension']) : '';
	
						/** Is file assigned? */
						$assigned_class		= ($count_assignations == 0) ? 'danger' : 'success';
						$assigned_status	= ($count_assignations == 0) ? __('Йўқ','cftp_admin') : __('Ҳа','cftp_admin');
	
						/**
						 * Visibility
						 */
						if ($row['public_allow'] == '1') {
							$visibility_link	= '<a href="javascript:void(0);" class="btn btn-primary btn-sm public_link" data-type="file" data-id="' . $row['id'] .'" data-token="' . html_output($row['public_token']) .'">';
							$visibility_label	= __('Сақлаш','cftp_admin');
						}
						else {
							if ( ENABLE_LANDING_FOR_ALL_FILES == '1' ) {
								$visibility_link	= '<a href="javascript:void(0);" class="btn btn-default btn-sm public_link" data-type="file" data-id="' . $row['id'] .'" data-token="' . html_output($row['public_token']) .'">';
								$visibility_label	= __('Маълумот олиш','cftp_admin');
							}
							else {
								$visibility_link	= '<a href="javascript:void(0);" class="btn btn-default btn-sm disabled" title="">';
								$visibility_label	= __('Белгиланмаган','cftp_admin');
							}
						}
	
						/**
						 * Expiration
						 */
						if ($row['expires'] == '0') {
							$expires_button	= 'success';
							$expires_label	= __('Санаси ошкор қилинмаган','cftp_admin');
						}
						else {
							$expires_date = date( TIMEFORMAT_USE, strtotime ($row['expiry_date'] ) );
	
							if (time() > strtotime($row['expiry_date'])) {
								$expires_button	= 'danger';
								$expires_label	= __('Киритилган санаси','cftp_admin') . ' ' . $expires_date;
							}
							else {
								$expires_button	= 'info';
								$expires_label	= __('Ушбу санагача','cftp_admin') . ' ' . $expires_date;
							}
						}
	
						/**
						 * Visibility
						 */
						$status_label = '';
						$status_class = '';
						if ( isset( $search_on ) ) {
							$status_class	= ($hidden == 1) ? 'danger' : 'success';
							$status_label	= ($hidden == 1) ? __('Яширин','cftp_admin') : __('Ошкора','cftp_admin');
						}
	
						/**
						 * Download count when filtering by group or client
						 */
						if ( isset( $search_on ) ) {
							$download_count_content = $row['download_count'] . ' ' . __('та','cftp_admin');
	
							switch ($results_type) {
								case 'client':
									break;
								case 'group':
								case 'category':
									$download_count_class	= ( $row['download_count'] > 0 ) ? 'downloaders btn-primary' : 'btn-default disabled';
									$download_count_content	= '<a href="' . BASE_URI .'download-information.php?id=' . $row['id'] .'" class="' . $download_count_class . ' btn btn-sm" title="' . html_output( $row['filename'] ) . '">' . $download_count_content . '</a>';
								break;
							}
						}
						
						/**
						 * Download count and link on the unfiltered files table
						 * (no specific client or group selected)
						 */
						if ( !isset( $search_on ) ) {
							if ($current_level != '0') {
								if ( $row["download_count"] > 0 ) {
									$btn_class		= 'downloaders btn-primary';
								}
								else {
									$btn_class		= 'btn-default disabled';
								}
	
								$downloads_table_link = '<a href="' . BASE_URI .'download-information.php?id=' . $row['id'] .'" class="' . $btn_class .' btn btn-sm" title="' .html_output($row['filename']) .'">' . $row["download_count"] . ' ' . __(' та','cftp_admin') .'</a>';
							}
						}
	
						/**
						 * Add the cells to the row
						 */
						$tbody_cells = array(
												array(
													'checkbox'		=> true,
													'value'			=> $row["id"],
													'condition'		=> $conditions['select_all'],
												),
												array(
													'content'		=> $date,
												),
												array(
													'content'		=> html_output( $extension ),
												),
												array(
													'attributes'	=> array(
																			'class'		=> array( 'file_name' ),
																		),
													'content'		=> '<a href="' . $download_link . '" target="_blank">' . html_output($row['filename']) . '</a>',
												),
												array(
													'content'		=> $formatted_size,
												),
												array(
													'content'		=> html_output( $row['uploader'] ),
													'condition'		=> $conditions['is_not_client'],
												),
												array(
													'content'		=> '<span class="label label-' . $assigned_class .'">' . $assigned_status . '</span>',
													'condition'		=> ( $conditions['is_not_client'] && !$conditions['is_search_on'] ),
												),
												array(
													'attributes'	=> array(
																			'class'		=> array( 'col_visibility' ),
																		),
													'content'		=> $visibility_link . $visibility_label . '</a>',
													'condition'		=> $conditions['is_not_client'],
												),
												array(
													'content'		=> '<a href="javascript:void(0);" class="btn btn-' . $expires_button . ' disabled btn-sm" rel="" title="">' . $expires_label . '</a>',
													'condition'		=> $conditions['is_not_client'],
												),
												array(
													'content'		=> '<span class="label label-' . $status_class .'">' . $status_label . '</span>',
													'condition'		=> $conditions['is_search_on'],
												),
												array(
													'content'		=> ( !empty( $download_count_content ) ) ? $download_count_content : false,
													'condition'		=> $conditions['is_search_on'],
												),
												array(
													'content'		=> ( !empty( $downloads_table_link ) ) ? $downloads_table_link : false,
													'condition'		=> $conditions['total_downloads'],
												),
												array(
													'content'		=> '<a href="edit-file.php?file_id=' . $row["id"] .'" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i><span class="button_label">' . __('Тахрирлаш','cftp_admin') . '</span></a>',
												),
									);
	
						foreach ( $tbody_cells as $cell ) {
							$table->add_cell( $cell );
						}
		
						$table->end_row();
					}
	
	
					echo $table->render();
	
					/**
					 * PAGINATION
					 */
					$pagination_args = array(
											'link'		=> 'manage-files.php',
											'current'	=> $pagination_page,
											'pages'		=> ceil( $count_for_pagination / RESULTS_PER_PAGE ),
										);
					
					echo $table->pagination( $pagination_args );
				}
			?>
		</form>
</div>

<?php
	include('footer.php');
