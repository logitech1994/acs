<?php
/*
Template name: Default
URI: http://www.projectsend.org/templates/default
Author: ProjectSend
Author URI: http://www.projectsend.org/
Author e-mail: contact@projectsend.org
Description: The default template uses the same style as the system backend, allowing for a seamless user experience
*/

$ld = 'cftp_template'; // specify the language domain for this template

define('TEMPLATE_RESULTS_PER_PAGE', 10);

if ( !empty( $_GET['category'] ) ) {
	$category_filter = $_GET['category'];
}

include_once(ROOT_DIR.'/templates/common.php'); // include the required functions for every template

$window_title = __('Ҳужжатни сақлаш','cftp_template');

$footable_min = true; // delete this line after finishing pagination on every table
$load_scripts	= array(
						'footable',
					); 

$body_class = array('template', 'default-template', 'hide_title');

include_once(ROOT_DIR.'/header.php');
?>

<div class="col-xs-12">
	<div id="wrapper">
		<?php /*
		<div id="left_column">
			<?php if ($logo_file_info['exists'] === true) { ?>
				<div id="current_logo">
					<img src="<?php echo TIMTHUMB_URL; ?>?src=<?php echo $logo_file_info['url']; ?>&amp;w=250" alt="<?php echo THIS_INSTALL_SET_TITLE; ?>" />
				</div>
			<?php } ?>
		</div> */ ?>
	
		<div id="right_column">
	
			<div class="form_actions_left">
				<div class="form_actions_limit_results">
					<?php show_search_form(); ?>

					<?php
						if ( !empty( $cat_ids ) ) {
					?>
							<form action="" name="files_filters" method="get" class="form-inline form_filters">
								<?php form_add_existing_parameters( array('category', 'action') ); ?>
								<div class="form-group group_float">
									<select name="category" id="category" class="txtfield form-control">
										<option value="0"><?php _e('Барчаси','cftp_admin'); ?></option>
										<?php
											$selected_parent = ( isset($category_filter) ) ? array( $category_filter ) : array();
											echo generate_categories_options( $get_categories['arranged'], 0, $selected_parent, 'include', $cat_ids );
										?>
									</select>
								</div>
								<button type="submit" id="btn_proceed_filter_files" class="btn btn-sm btn-default"><?php _e('Саралаш','cftp_admin'); ?></button>
							</form>
					<?php
						}
					?>
				</div>
			</div>
		
			<form action="" name="files_list" method="get" class="form-inline">
				<?php form_add_existing_parameters(); ?>
				<div class="form_actions_right">
					<div class="form_actions">
						<div class="form_actions_submit">
							<div class="form-group group_float">
								<label class="control-label hidden-xs hidden-sm"><i class="glyphicon glyphicon-check"></i> <?php _e('Ҳаракатни танланг','cftp_admin'); ?>:</label>
								<select name="action" id="action" class="txtfield form-control">
									<?php
										$actions_options = array(
																'none'	=> __('Танланг','cftp_admin'),
																'zip'	=> __('Архивга олиш','cftp_admin'),
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
		
				<div class="right_clear"></div><br />

				<div class="form_actions_count">
					<p class="form_count_total"><?php _e('Топилди','cftp_admin'); ?>: <span><?php echo $count_for_pagination; ?> <?php _e('та ҳужжат','cftp_admin'); ?></span></p>
				</div>
	
				<div class="right_clear"></div>
	
				<?php
					if (!$count_for_pagination) {
						if (isset($no_results_error)) {
							switch ($no_results_error) {
								case 'search':
									$no_results_message = __('Ҳатолик, қайта уриниб ўринг!!!','cftp_admin');
									break;
							}
						}
						else {
							$no_results_message = __('Ҳатолик, қайта уриниб ўринг!!!','cftp_template');
						}
						echo system_message('error',$no_results_message);
					}


					if ($count > 0) {
						/**
						 * Generate the table using the class.
						 */
						$table_attributes	= array(
													'id'		=> 'files_list',
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
														'sort_url'		=> 'filename',
														'content'		=> __('Ҳужжат номи','cftp_admin'),
													),
													array(
														'content'		=> __('Ҳужжат тури','cftp_admin'),
														'hide'			=> 'phone',
													),
													array(
														'sortable'		=> true,
														'sort_url'		=> 'description',
														'content'		=> __('Ҳужжат ҳақида маълумот','cftp_admin'),
														'hide'			=> 'phone',
														'attributes'	=> array(
																				'class'		=> array( 'description' ),
																			),
													),
													array(
														'content'		=> __('Хажми','cftp_admin'),
														'hide'			=> 'phone',
													),
													array(
														'sortable'		=> true,
														'sort_url'		=> 'timestamp',
														'sort_default'	=> true,
														'content'		=> __('Санаси','cftp_admin'),
													),
													array(
														'content'		=> __('Муддати','cftp_admin'),
														'hide'			=> 'phone',
													),
													array(
														'content'		=> __('Кўриниш','cftp_admin'),
														'hide'			=> 'phone,tablet',
													),
													array(
														'content'		=> __('Ҳужжатни сақлаш','cftp_admin'),
														'hide'			=> 'phone',
													),
												);
	
						$table->thead( $thead_columns );

						foreach ($my_files as $file) {
							$download_link = make_download_link($file);

							$table->add_row();

							/**
							 * Prepare the information to be used later on the cells array
							 */

							/** Checkbox */
							$checkbox = ($file['expired'] == false) ? '<input type="checkbox" name="files[]" value="' . $file["id"] . '" class="batch_checkbox" />' : null;

							/** File title */
							$file_title_content = '<strong>' . htmlentities($file['name']) . '</strong>';
							if ($file['expired'] == false) {
								$filetitle = '<a href="' . $download_link . '" target="_blank">' . $file_title_content . '</a>';
							}
							else {
								$filetitle = $file_title_content;
							}
							
							/** Extension */
							$pathinfo = pathinfo($file['url']);
							$extension = strtolower($pathinfo['extension']);
							$extension_cell = '<span class="label label-success label_big">' . $extension . '</span>';

							/** Description */
							$description = htmlentities_allowed($file['description']);
							
							/** File size */
							$file_size_cell = '-'; // default
							$file_absolute_path = UPLOADED_FILES_FOLDER . $file['url'];
							if ( file_exists( $file_absolute_path ) ) {
								$this_file_size = get_real_size(UPLOADED_FILES_FOLDER.$file['url']);
								$file_size_cell = format_file_size($this_file_size);
							}
							
							/** Date */
							$date = date(TIMEFORMAT_USE,strtotime($file['timestamp']));
							
							/** Expiration */
							if ( $file['expires'] == '1' ) {
								if ( $file['expired'] == false ) {
									$class = 'primary';
								} else {
									$class = 'danger';
								}
								
								$value = date( TIMEFORMAT_USE, strtotime( $file['expiry_date'] ) );
							} else {
								$class = 'success';
								$value = __('Белгиланмаган','cftp_template');
							}
							
							$expiration_cell = '<span class="label label-' . $class . ' label_big">' . $value . '</span>';

							/** Preview */
							$preview_cell = '';
							if ($file['expired'] == false) {
								$image_extensions = array('gif','jpg','pjpeg','jpeg','png');
								if ( in_array( $extension, $image_extensions ) ) {
									if ( file_exists( $file_absolute_path ) ) {
										$this_thumbnail_url = UPLOADED_FILES_URL.$file['url'];
										if (THUMBS_USE_ABSOLUTE == '1') {
											$this_thumbnail_url = BASE_URI.$this_thumbnail_url;
										}
										$preview_cell = '<img src="' . TIMTHUMB_URL . '?src=' . $this_thumbnail_url . '&amp;w=' . THUMBS_MAX_WIDTH . '&amp;q=' . THUMBS_QUALITY . '" class="thumbnail" alt="' . htmlentities($file['name']) .'" />';
									}
								}
							}

							/** Download */
							if ($file['expired'] == true) {
								$download_link		= 'javascript:void(0);';
								$download_btn_class	= 'btn btn-danger btn-sm disabled';
								$download_text		= __('Ҳужжатни муддати тугади','cftp_template');
							}
							else {
								$download_btn_class	= 'btn btn-primary btn-sm btn-wide';
								$download_text		= __('Сақлаш','cftp_template');
							}
							$download_cell = '<a href="' . $download_link . '" class="' . $download_btn_class . '" target="_blank">' . $download_text . '</a>';


							
							$tbody_cells = array(
													array(
														'content'		=> $checkbox,
													),
													array(
														'content'		=> $filetitle,
														'attributes'	=> array(
																				'class'		=> array( 'file_name' ),
																			),
													),
													array(
														'content'		=> $extension_cell,
														'attributes'	=> array(
																				'class'		=> array( 'extra' ),
																			),
													),
													array(
														'content'		=> $description,
														'attributes'	=> array(
																				'class'		=> array( 'description' ),
																			),
													),
													array(
														'content'		=> $file_size_cell,
													),
													array(
														'content'		=> $date,
													),
													array(
														'content'		=> $expiration_cell,
													),
													array(
														'content'		=> $preview_cell,
														'attributes'	=> array(
																				'class'		=> array( 'extra' ),
																			),
													),
													array(
														'content'		=> $download_cell,
														'attributes'	=> array(
																				'class'		=> array( 'text-center' ),
																			),
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
												'link'		=> 'my_files',
												'current'	=> $pagination_page,
												'pages'		=> ceil( $count_for_pagination / TEMPLATE_RESULTS_PER_PAGE ),
											);
						
						echo $table->pagination( $pagination_args );
						

					}
				?>
			</form>
		
		</div> <!-- right_column -->
	</div> <!-- wrapper -->
	
	<?php default_footer_info(); ?>

</div>
	<?php
		load_js_files();
	?>
</body>
</html>