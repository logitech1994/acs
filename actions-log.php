<?php
/**
 * Show the list of activities logged.
 *
 * @package		ProjectSend
 * @subpackage	Log
 *
 */
$footable_min = true; // delete this line after finishing pagination on every table
$load_scripts	= array(
						'footable',
					); 

$allowed_levels = array(9);
require_once('sys.includes.php');

$active_nav = 'tools';

$page_title = __('Тизимдаги барча ҳаракатлар ва уларни бошқариш панели','cftp_admin');

include('header.php');
?>

<div class="col-xs-12">
<?php
	/**
	 * Apply the corresponding action to the selected users.
	 */
	if (isset($_GET['action']) && $_GET['action'] != 'none') {
		/** Continue only if 1 or more users were selected. */
			switch($_GET['action']) {
				case 'delete':

					$selected_actions = $_GET['batch'];
					$delete_ids = implode( ',', $selected_actions );

					if ( !empty( $_GET['batch'] ) ) {
							$statement = $dbh->prepare("DELETE FROM " . TABLE_LOG . " WHERE FIND_IN_SET(id, :delete)");
							$params = array(
											':delete'	=> $delete_ids,
										);
							$statement->execute( $params );
						
							$msg = __('Танланганлар ўчирилди','cftp_admin');
							echo system_message('ok',$msg);
					}
					else {
						$msg = __('Камида биттасини танланг','cftp_admin');
						echo system_message('error',$msg);
					}
				break;
				case 'clear':
					$keep = '5,8,9';
					$statement = $dbh->prepare("DELETE FROM " . TABLE_LOG . " WHERE NOT ( FIND_IN_SET(action, :keep) ) ");
					$params = array(
									':keep'	=> $keep,
								);
					$statement->execute( $params );

					$msg = __('Архив тозаланди','cftp_admin');
					echo system_message('ok',$msg);
				break;
			}
	}

	$params	= array();

	/**
	 * Get the actually requested items
	 */
	$cq = "SELECT * FROM " . TABLE_LOG;

	/** Add the search terms */	
	if ( isset($_GET['search']) && !empty($_GET['search'] ) ) {
		$cq .= " WHERE (owner_user LIKE :owner OR affected_file_name LIKE :file OR affected_account_name LIKE :account)";
		$next_clause = ' AND';
		$no_results_error = 'search';
		
		$search_terms		= '%'.$_GET['search'].'%';
		$params[':owner']	= $search_terms;
		$params[':file']	= $search_terms;
		$params[':account']	= $search_terms;
	}
	else {
		$next_clause = ' WHERE';
	}

	/** Add the activities filter */	
	if (isset($_GET['activity']) && $_GET['activity'] != 'all') {
		$cq .= $next_clause. " action=:status";

		$status_filter		= $_GET['activity'];
		$params[':status']	= $status_filter;

		$no_results_error = 'filter';
	}
	
	/**
	 * Add the order.
	 * Defaults to order by: id, order: DESC
	 */
	$cq .= sql_add_order( TABLE_LOG, 'id', 'DESC' );

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
	$pagination_start			= ( $pagination_page - 1 ) * RESULTS_PER_PAGE_LOG;
	$params[':limit_start']		= $pagination_start;
	$params[':limit_number']	= RESULTS_PER_PAGE_LOG;

	$sql->execute( $params );
	$count = $sql->rowCount();
?>

	<div class="form_actions_left">
		<div class="form_actions_limit_results">
			<?php show_search_form('actions-log.php'); ?>

			<form action="actions-log.php" name="actions_filters" method="get" class="form-inline form_filters">
				<?php form_add_existing_parameters( array('activity') ); ?>
				<div class="form-group group_float">
					<label for="activity" class="sr-only"><?php _e('Саралаш','cftp_admin'); ?></label>
					<select name="activity" id="activity" class="form-control">
						<option value="all"><?php _e('Барча ҳаракатлар','cftp_admin'); ?></option>
							<?php
								global $activities_references;
								foreach ( $activities_references as $val => $text ) {
							?>
									<option value="<?php echo $val; ?>" <?php if ( isset( $_GET['activity'] ) && $_GET['activity'] == $val ) { echo 'selected="selected"'; } ?>><?php echo $text; ?></option>
							<?php
								}
							?>
					</select>
				</div>
				<button type="submit" id="btn_proceed_filter_clients" class="btn btn-sm btn-default"><?php _e('Саралаш','cftp_admin'); ?></button>
			</form>
		</div>
	</div>

	<form action="actions-log.php" name="actions_list" method="get" class="form-inline">
		<?php form_add_existing_parameters(); ?>
		<div class="form_actions_right">
			<div class="form_actions">
				<div class="form_actions_submit">
					<div class="form-group group_float">
						<label class="control-label hidden-xs hidden-sm"><i class="glyphicon glyphicon-check"></i> <?php _e('Харакатни танланг','cftp_admin'); ?>:</label>
						<select name="action" id="action" class="form-control">
								<?php
								$actions_options = array(
														'none'				=> __('Танланг','cftp_admin'),
														'log_download'		=> __('Excel га ўтказиш','cftp_admin'),
														'delete'			=> __('Ўчириш','cftp_admin'),
														'log_clear'			=> __('Тозалаш','cftp_admin'),
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
			<p><?php _e('Топилди','cftp_admin'); ?>: <span><?php echo $count_for_pagination; ?> <?php _e('та харакат','cftp_admin'); ?></span></p>
		</div>

		<div class="clear"></div>

		<?php
			if (!$count) {
				if (isset($no_results_error)) {
					switch ($no_results_error) {
						case 'filter':
							$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
							break;
					}
				}
				else {
					$no_results_message = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
				}
				echo system_message('error',$no_results_message);
			}
		?>

		<?php
		 	/**
			 * Generate the table using the class.
			 */
			$table_attributes	= array(
										'id'		=> 'activities_tbl',
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
											'sort_url'		=> 'timestamp',
											'sort_default'	=> true,
											'content'		=> __('Санаси','cftp_admin'),
										),
										array(
											'sortable'		=> true,
											'sort_url'		=> 'owner_id',
											'content'		=> __('Муаллифи','cftp_admin'),
										),
										array(
											'sortable'		=> true,
											'sort_url'		=> 'action',
											'content'		=> __('Харакат','cftp_admin'),
											'hide'			=> 'phone',
										),
										array(
											'content'		=> 'Гуруҳ',
											'hide'			=> 'phone',
										),
										array(
											'content'		=> '',
											'hide'			=> 'phone',
										),
										array(
											'content'		=> '',
											'hide'			=> 'phone',
										),
									);
			$table->thead( $thead_columns );
			
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			while ( $log = $sql->fetch() ) {

				$this_action = render_log_action(
									array(
										'action'				=> $log['action'],
										'timestamp'				=> $log['timestamp'],
										'owner_id'				=> $log['owner_id'],
										'owner_user'			=> $log['owner_user'],
										'affected_file'			=> $log['affected_file'],
										'affected_file_name'	=> $log['affected_file_name'],
										'affected_account'		=> $log['affected_account'],
										'affected_account_name'	=> $log['affected_account_name']
									)
				);
				$date = date("m/d/Y -- h:i:s",strtotime($log['timestamp']));

				$table->add_row();
				
				$tbody_cells = array(
										array(
												'checkbox'		=> true,
												'value'			=> $log["id"],
											),
										array(
												'content'		=> $date,
											),
										array(
												'content'		=> ( !empty( $this_action["1"] ) ) ? html_output( $this_action["1"] ) : '',
											),
										array(
												'content'		=> html_output( $this_action["text"] ),
											),
										array(
												'content'		=> ( !empty( $this_action["2"] ) ) ? html_output( $this_action["2"] ) : '',
											),
										array(
												'content'		=> ( !empty( $this_action["3"] ) ) ? html_output( $this_action["3"] ) : '',
											),
										array(
												'content'		=> ( !empty( $this_action["4"] ) ) ? html_output( $this_action["4"] ) : '',
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
									'link'		=> 'actions-log.php',
									'current'	=> $pagination_page,
									'pages'		=> ceil( $count_for_pagination / RESULTS_PER_PAGE_LOG ),
								);
			
			echo $table->pagination( $pagination_args );
		?>
	</form>
	
</div>

<?php
	include('footer.php');