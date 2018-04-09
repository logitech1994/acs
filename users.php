<?php
/**
 * Show the list of current users.
 *
 * @package		ProjectSend
 * @subpackage	Users
 *
 */
$footable_min = true; // delete this line after finishing pagination on every table
$load_scripts	= array(
						'footable',
					); 

$allowed_levels = array(9);
require_once('sys.includes.php');

if(!check_for_admin()) {
    return;
}

$active_nav = 'users';

$page_title = __('Тизим фойдаланувчиларни бошқариш панели','cftp_admin');
include('header.php');
?>

<div class="col-xs-12">
<?php

	/**
	 * Apply the corresponding action to the selected users.
	 */
	if(isset($_GET['action'])) {
		/** Continue only if 1 or more users were selected. */
		if(!empty($_GET['batch'])) {
			$selected_users = $_GET['batch'];
			$users_to_get = implode( ',', array_map( 'intval', array_unique( $selected_users ) ) );

			/**
			 * Make a list of users to avoid individual queries.
			 */
			$sql_user = $dbh->prepare( "SELECT id, name FROM " . TABLE_USERS . " WHERE FIND_IN_SET(id, :users)" );
			$sql_user->bindParam(':users', $users_to_get);
			$sql_user->execute();
			$sql_user->setFetchMode(PDO::FETCH_ASSOC);
			while ( $data_user = $sql_user->fetch() ) {
				$all_users[$data_user['id']] = $data_user['name'];
			}


			$my_info = get_user_by_username(get_current_user_username());
			$affected_users = 0;

			switch($_GET['action']) {
				case 'activate':
					/**
					 * Changes the value on the "active" column value on the database.
					 * Inactive users are not allowed to log in.
					 */
					foreach ($selected_users as $work_user) {
						$this_user = new UserActions();
						$hide_user = $this_user->change_user_active_status($work_user,'1');
					}
					$msg = __('Танланган фойдаланувчилар фаол ҳолатда','cftp_admin');
					echo system_message('ok',$msg);
					$log_action_number = 27;
					break;

				case 'deactivate':
					/**
					 * Reverse of the previous action. Setting the value to 0 means
					 * that the user is inactive.
					 */
					foreach ($selected_users as $work_user) {
						/**
						 * A user should not be able to deactivate himself
						 */
						if ($work_user != $my_info['id']) {
							$this_user = new UserActions();
							$hide_user = $this_user->change_user_active_status($work_user,'0');
							$affected_users++;
						}
						else {
							$msg = __('Сиз ўз саҳифангизни деактив қила олмайсиз','cftp_admin');
							echo system_message('error',$msg);
						}
					}

					if ($affected_users > 0) {
						$msg = __('Танланган фойдаланувчилар фаол ҳолатда','cftp_admin');
						echo system_message('ok',$msg);
						$log_action_number = 28;
					}
					break;

				case 'delete':		
					foreach ($selected_users as $work_user) {
						/**
						 * A user should not be able to delete himself
						 */
						if ($work_user != $my_info['id']) {
							$this_user = new UserActions();
							$delete_user = $this_user->delete_user($work_user);
							$affected_users++;
						}
						else {
							$msg = __('Сиз ўз саҳифангизни ўчира олмайсиз','cftp_admin');
							echo system_message('error',$msg);
						}
					}
					
					if ($affected_users > 0) {
						$msg = __('Танланган фойдаланувчилар ўчирилган','cftp_admin');
						echo system_message('ok',$msg);
						$log_action_number = 16;
					}
				break;
			}

			/** Record the action log */
			foreach ($selected_users as $user) {
				$new_log_action = new LogActions();
				$log_action_args = array(
										'action' => $log_action_number,
										'owner_id' => CURRENT_USER_ID,
										'affected_account_name' => $all_users[$user]
									);
				$new_record_action = $new_log_action->log_action_save($log_action_args);
			}
		}
		else {
			$msg = __('Камида биттасини танланг','cftp_admin');
			echo system_message('error',$msg);
		}
	}

	$params	= array();

	$cq = "SELECT * FROM " . TABLE_USERS . " WHERE level != '0'";

	/** Add the search terms */	
	if ( isset( $_GET['search'] ) && !empty( $_GET['search'] ) ) {
		$cq .= " AND (name LIKE :name OR user LIKE :user OR email LIKE :email)";
		$no_results_error = 'search';

		$search_terms		= '%'.$_GET['search'].'%';
		$params[':name']	= $search_terms;
		$params[':user']	= $search_terms;
		$params[':email']	= $search_terms;
	}

	/** Add the role filter */	
	if ( isset( $_GET['role'] ) && $_GET['role'] != 'all' ) {
		$cq .= " AND level=:level";
		$no_results_error = 'filter';

		$params[':level']	= $_GET['role'];
	}
	
	/** Add the active filter */	
	if ( isset( $_GET['active'] ) && $_GET['active'] != '2' ) {
		$cq .= " AND active = :active";
		$no_results_error = 'filter';

		$params[':active']	= (int)$_GET['active'];
	}

	/**
	 * Add the order.
	 * Defaults to order by: name, order: ASC
	 */
	$cq .= sql_add_order( TABLE_USERS, 'name', 'asc' );

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
?>

	<div class="form_actions_left">
		<div class="form_actions_limit_results">
			<?php show_search_form('users.php'); ?>

			<form action="users.php" name="users_filters" method="get" class="form-inline">
				<?php form_add_existing_parameters( array('active', 'role', 'action') ); ?>
				<div class="form-group group_float">
					<select name="role" id="role" class="txtfield form-control">
						<?php
							$roles_options = array(
													'all'	=> __('Барча ҳуқуқлар','cftp_admin'),
													'9'		=> USER_ROLE_LVL_9,
													'8'		=> USER_ROLE_LVL_8,
													'7'		=> USER_ROLE_LVL_7,
												);
							foreach ( $roles_options as $val => $text ) {
						?>
								<option value="<?php echo $val; ?>" <?php if ( isset( $_GET['role'] ) && $_GET['role'] == $val ) { echo 'selected="selected"'; } ?>><?php echo $text; ?></option>
						<?php
							}
						?>
					</select>
				</div>

				<div class="form-group group_float">
					<select name="active" id="active" class="txtfield form-control">
						<?php
							$status_options = array(
													'2'		=> __('Барчаси','cftp_admin'),
													'1'		=> __('Фаол','cftp_admin'),
													'0'		=> __('Фаол эмас','cftp_admin'),
												);
							foreach ( $status_options as $val => $text ) {
						?>
								<option value="<?php echo $val; ?>" <?php if ( isset( $_GET['active'] ) && $_GET['active'] == $val ) { echo 'selected="selected"'; } ?>><?php echo $text; ?></option>
						<?php
							}
						?>
					</select>
				</div>
				<button type="submit" id="btn_proceed_filter_clients" class="btn btn-sm btn-default"><?php _e('Саралаш','cftp_admin'); ?></button>
			</form>
		</div>
	</div>

	<form action="users.php" name="users_list" method="get" class="form-inline">
		<?php form_add_existing_parameters(); ?>
		<div class="form_actions_right">
			<div class="form_actions">
				<div class="form_actions_submit">
					<div class="form-group group_float">
						<label class="control-label hidden-xs hidden-sm"><i class="glyphicon glyphicon-check"></i> <?php _e('Ҳаракатларни танланг','cftp_admin'); ?>:</label>
						<select name="action" id="action" class="txtfield form-control">
							<?php
								$actions_options = array(
														'none'			=> __('Танланг','cftp_admin'),
														'activate'		=> __('Фаоллаштириш','cftp_admin'),
														'deactivate'	=> __('Деактив қилиш','cftp_admin'),
														'delete'		=> __('Ўчириш','cftp_admin'),
													);
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
		<div class="clear"></div>

		<div class="form_actions_count">
			<p><?php _e('Топилди','cftp_admin'); ?>: <span><?php echo $count_for_pagination; ?> <?php _e('та фойдаланувчи','cftp_admin'); ?></span></p>
		</div>

		<div class="clear"></div>

		<?php
			if (!$count) {
				switch ($no_results_error) {
					case 'search':
						$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
						break;
					case 'filter':
						$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
						break;
				}
				echo system_message('error',$no_results_message);
			}
			
			if ($count > 0) {
				/**
				 * Generate the table using the class.
				 */
				$table_attributes	= array(
											'id'		=> 'users_tbl',
											'class'		=> 'footable table',
										);
				$table = new generateTable( $table_attributes );

				$thead_columns		= array(
											array(
												'select_all'	=> true,
												'attributes'	=> array(
																		'class'		=> array( 'td_checkbox' ),
																	),
											),
											array(
												'sortable'		=> true,
												'sort_url'		=> 'name',
												'sort_default'	=> true,
												'content'		=> __('ФИШ','cftp_admin'),
											),
											array(
												'sortable'		=> true,
												'sort_url'		=> 'user',
												'content'		=> __('Логин','cftp_admin'),
												'hide'			=> 'phone',
											),
											array(
												'sortable'		=> true,
												'sort_url'		=> 'email',
												'content'		=> __('E-mail','cftp_admin'),
												'hide'			=> 'phone',
											),
											array(
												'sortable'		=> true,
												'sort_url'		=> 'level',
												'content'		=> __('Мақоми','cftp_admin'),
												'hide'			=> 'phone',
											),
											array(
												'sortable'		=> true,
												'sort_url'		=> 'active',
												'content'		=> __('Ҳолати','cftp_admin'),
											),
											array(
												'sortable'		=> true,
												'sort_url'		=> 'max_file_size',
												'content'		=> __('Максимум хужжат хажми','cftp_admin'),
												'hide'			=> 'phone',
											),
											array(
												'sortable'		=> true,
												'sort_url'		=> 'timestamp',
												'sort_default'	=> true,
												'content'		=> __('Қўшилгин санаси','cftp_admin'),
												'hide'			=> 'phone,tablet',
											),
											array(
												'content'		=> __('Ҳаракатлар','cftp_admin'),
												'hide'			=> 'phone',
											),
										);
				$table->thead( $thead_columns );

				$sql->setFetchMode(PDO::FETCH_ASSOC);
				while ( $row = $sql->fetch() ) {
					$table->add_row();

					/**
					 * Prepare the information to be used later on the cells array
					 * 1- Get the role name
					 */
					switch( $row["level"] ) {
						case '9': $role_name = USER_ROLE_LVL_9; break;
						case '8': $role_name = USER_ROLE_LVL_8; break;
						case '7': $role_name = USER_ROLE_LVL_7; break;
					}
					 
					/**
					 * 2- Get active status
					 */
					$status_hidden	= __('Фаол эмас','cftp_admin');
					$status_visible	= __('Фаол','cftp_admin');
					$label			= ($row['active'] == 0) ? $status_hidden : $status_visible;
					$class			= ($row['active'] == 0) ? 'danger' : 'success';
					
					/**
					 * 3- Get account creation date
					 */
					$date = date( "d/m/Y h:i:s", strtotime( $row['timestamp'] ) );


					/**
					 * Add the cells to the row
					 */
					if ( $row['id'] == 1 ) {
						$cell = array( 'content' => '' );
					}
					else {
						$cell = array(
									'checkbox'		=> true,
									'value'			=> $row["id"],
									);
					}
					$tbody_cells = array(
											$cell,
											array(
													'content'		=> html_output( $row["name"] ),
												),
											array(
													'content'		=> html_output( $row["user"] ),
												),
											array(
													'content'		=> html_output( $row["email"] ),
												),
											array(
													'content'		=> $role_name,
												),
											array(
													'content'		=> '<span class="label label-' . $class . '">' . $label . '</span>',
												),
											array(
													'content'		=> ( $row["max_file_size"] == '0' ) ? __('Белгиланмаган','cftp_admin') : $row["max_file_size"] . 'mb',
												),
											array(
													'content'		=> $date,
												),
											array(
													'actions'		=> true,
													'content'		=>  '<a href="users-edit.php?id=' . html_output( $row["id"] ) . '" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i><span class="button_label">' . __('Тахрирлаш','cftp_admin') . '</span></a>' . "\n"
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
										'link'		=> 'users.php',
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