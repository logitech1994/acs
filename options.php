<?php
/**
 * Options page and form.
 *
 * @package ProjectSend
 * @subpackage Options
 */
$load_scripts	= array(
						'jquery_tags_input',
						'spinedit',
					); 

$allowed_levels = array(9);
require_once('sys.includes.php');

$section = ( !empty( $_GET['section'] ) ) ? $_GET['section'] : $_POST['section'];

switch ( $section ) {
	case 'general':
		$section_title	= __('Умумий созланмалар','cftp_admin');
		$checkboxes		= array(
								'footer_custom_enable',
								'files_descriptions_use_ckeditor',
								'use_browser_lang',
							);
		break;
	case 'clients':
		$section_title	= __('Аудитлар','cftp_admin');
		$checkboxes		= array(
								'clients_can_register',
								'clients_auto_approve',
								'clients_can_upload',
								'clients_can_delete_own_files',
								'clients_can_set_expiration_date',
							);
		break;
	case 'privacy':
		$section_title	= __('Ягоналик','cftp_admin');
		$checkboxes		= array(
								'privacy_noindex_site',
								'enable_landing_for_all_files',
								'public_listing_page_enable',
								'public_listing_logged_only',
								'public_listing_show_all_files',
								'public_listing_use_download_link',
							);
		break;
	case 'email':
		$section_title	= __('E-mail хабарнома','cftp_admin');
		$checkboxes		= array(
								'mail_copy_user_upload',
								'mail_copy_client_upload',
								'mail_copy_main_user',
							);
		break;
	case 'security':
		$section_title	= __('Хавфсизлик','cftp_admin');
		$checkboxes		= array(
								'pass_require_upper',
								'pass_require_lower',
								'pass_require_number',
								'pass_require_special',
								'recaptcha_enabled',
							);
		break;
	case 'thumbnails':
		$section_title	= __('Thumbnails','cftp_admin');
		$checkboxes		= array(
								'thumbnails_use_absolute',
							);
		break;
	case 'branding':
		$section_title	= __('Тизим логотипи','cftp_admin');
		$checkboxes		= array(
							);
		break;
	case 'social_login':
		$section_title	= __('Social Login','cftp_admin');
		$checkboxes		= array(
							);
		break;
	default:
		$location = BASE_URI . 'options.php?section=general';
		header("Манзил: $location");
		die();
		break;
}

//$page_title = __('System options','cftp_admin');
$page_title = $section_title;

$active_nav = 'options';
include('header.php');

$logo_file_info = generate_logo_url();

/** Form sent */
if ($_POST) {
	/**
	 * Escape all the posted values on a single function.
	 * Defined on functions.php
	 */
	/** Values that can be empty */
	$allowed_empty_values	= array(
								'mail_copy_addresses',
								'mail_smtp_host',
								'mail_smtp_port',
								'mail_smtp_user',
								'mail_smtp_pass',
							);

	if ( empty( $_POST['google_signin_enabled'] ) ) {
		$allowed_empty_values[] = 'google_client_id';
		$allowed_empty_values[] = 'google_client_secret';
	}
	if ( empty( $_POST['recaptcha_enabled'] ) ) {
		$allowed_empty_values[] = 'recaptcha_site_key';
		$allowed_empty_values[] = 'recaptcha_secret_key';
	}

	foreach ($checkboxes as $checkbox) {
		$_POST[$checkbox] = (empty($_POST[$checkbox]) || !isset($_POST[$checkbox])) ? 0 : 1;
	}

	$keys = array_keys($_POST);
	 
	$options_total = count($keys);
	$options_filled = 0;
	$query_state = '0';

	/**
	 * Check if all the options are filled.
	 */
	for ($i = 0; $i < $options_total; $i++) {
		if (!in_array($keys[$i], $allowed_empty_values)) {
			if (empty($_POST[$keys[$i]]) && $_POST[$keys[$i]] != '0') {
				$query_state = '3';
			}
			else {
				$options_filled++;
			}
		}
	}
	
	/** If every option is completed, continue */
	if ($query_state == '0') {
		$updated = 0;
		for ($j = 0; $j < $options_total; $j++) {
			$save = $dbh->prepare( "UPDATE " . TABLE_OPTIONS . " SET value=:value WHERE name=:name" );
			$save->bindParam(':value', $_POST[$keys[$j]]);
			$save->bindParam(':name', $keys[$j]);
			$save->execute();

			if ($save) {
				$updated++;
			}
		}
		if ($updated > 0){
			$query_state = '1';
		}
		else {
			$query_state = '2';
		}
	}

	/** If uploading a logo on the branding page */
	if ( !empty($_FILES['select_logo']['name']) ) {
		/** Valid file extensions (images) */
		$image_file_types = "/^\.(jpg|jpeg|gif|png){1}$/i";
	
		if (is_uploaded_file($_FILES['select_logo']['tmp_name'])) {
	
			$this_upload = new PSend_Upload_File();
			$safe_filename = $this_upload->safe_rename($_FILES['select_logo']['name']);
			/**
			 * Check the file type for allowed extensions.
			 *
			 * @todo Use the file upload class file type validation function.
			 */
			if (preg_match($image_file_types, strrchr($safe_filename, '.'))) {
	
				/**
				 * Move the file to the destination defined on sys.vars.php. If ok, add the
				 * new file name to the database.
				 */
				if (move_uploaded_file($_FILES['select_logo']['tmp_name'],LOGO_FOLDER.$safe_filename)) {
					$sql = $dbh->prepare( "UPDATE " . TABLE_OPTIONS . " SET value=:value WHERE name='logo_filename'" );
					$sql->execute(
								array(
									':value'	=> $safe_filename
								)
							);
					
					$logo_status = '1';
	
					/** Record the action log */
					$new_log_action = new LogActions();
					$log_action_args = array(
											'action' => 29,
											'owner_id' => CURRENT_USER_ID
										);
					$new_record_action = $new_log_action->log_action_save($log_action_args);
				}
				else {
					$logo_status = '2';
				}
			}
			else {
				$logo_status = '3';
			}
		}
		else {
			$logo_status = '4';
		}
	}

	/** Redirect so the options are reflected immediatly */
	while (ob_get_level()) ob_end_clean();
	$section_redirect = html_output($_POST['section']);
	$location = BASE_URI . 'options.php?section=' . $section_redirect;

	if ( !empty( $query_state ) ) {
		$location .= '&status=' . $query_state;
	}

	if ( !empty( $logo_status ) ) {
		$location .= '&logo_status=' . $logo_status;
	}
	header("Манзил: $location");
	die();
}

/**
 * Replace | with , to use the tags system when showing
 * the allowed filetypes on the form. This value comes from
 * site.options.php
*/
$allowed_file_types = str_replace('|',',',$allowed_file_types);
/** Explode, sort, and implode the values to list them alphabetically */
$allowed_file_types = explode(',',$allowed_file_types);
sort($allowed_file_types);

/** If .php files are allowed, set the flag for the warning message */
if ( in_array( 'php', $allowed_file_types ) ) {
	$php_allowed_warning = true;
}

$allowed_file_types = implode(',',$allowed_file_types);

?>

<div class="col-xs-12 col-sm-12 col-lg-12">
	<?php
		if (isset($_GET['status'])) {
			switch ($_GET['status']) {
				case '1':
					$msg = __('Ўзгартиришлар сақланди','cftp_admin');
					echo system_message('ok',$msg);
					break;
				case '2':
					$msg = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
					echo system_message('error',$msg);
					break;
				case '3':
					$msg = __('Ҳатолик, қайта уриниб кўринг!!!','cftp_admin');
					echo system_message('error',$msg);
					$show_options_form = 1;
					break;
			}
		}

		/** Logo uploading status */
		if (isset($_GET['logo_status'])) {
			switch ($_GET['logo_status']) {
				case '1':
					break;
				case '2':
					$msg = __('The file could not be moved to the corresponding folder.','cftp_admin');
					$msg .= __("This is most likely a permissions issue. If that's the case, it can be corrected via FTP by setting the chmod value of the",'cftp_admin');
					$msg .= ' '.LOGO_FOLDER.' ';
					$msg .= __('directory to 755, or 777 as a last resource.','cftp_admin');
					$msg .= __("If this doesn't solve the issue, try giving the same values to the directories above that one until it works.",'cftp_admin');
					echo system_message('error',$msg);
					break;
				case '3':
					$msg = __('The file you selected is not an allowed image format. Please upload your logo as a jpg, gif or png file.','cftp_admin');
					echo system_message('error',$msg);
					break;
				case '4':
					$msg = __('There was an error uploading the file. Please try again.','cftp_admin');
					echo system_message('error',$msg);
					break;
			}
		}
	?>

	<div class="white-box">
		<div class="white-box-interior">

			<script type="text/javascript">
				$(document).ready(function() {
					$('#notifications_max_tries').spinedit({
						minimum: 1,
						maximum: 100,
						step: 1,
						value: <?php echo NOTIFICATIONS_MAX_TRIES; ?>,
						numberOfDecimals: 0
					});
	
					$('#notifications_max_days').spinedit({
						minimum: 0,
						maximum: 365,
						step: 1,
						value: <?php echo NOTIFICATIONS_MAX_DAYS; ?>,
						numberOfDecimals: 0
					});
	
					$('#allowed_file_types').tagsInput({
						'width'			: '95%',
						'height'		: 'auto',
						'defaultText'	: '',
					});
	
					$("form").submit(function() {
						clean_form(this);
	
						is_complete_all_options(this,'<?php _e('Илтимос, ҳамма майдонларни тўлдиринг','cftp_admin'); ?>');
	
						// show the errors or continue if everything is ok
						if (show_form_errors() == false) { alert('<?php _e('Илтимос, ҳамма майдонларни тўлдиринг','cftp_admin'); ?>'); return false; }
					});
				});
			</script>

			<form action="options.php" name="optionsform" method="post" enctype="multipart/form-data" class="form-horizontal">
				<input type="hidden" name="section" value="<?php echo $section; ?>">
								
					<?php
						switch ( $section ) {
							case 'general':
					?>
								<h3><?php _e('Умумий созланмалар','cftp_admin'); ?></h3>
								<p><?php _e('Созланмаларни амалга оширинг','cftp_admin'); ?></p>
	
								<div class="form-group">
									<label for="this_install_title" class="col-sm-4 control-label"><?php _e('Тизим номи','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="this_install_title" id="this_install_title" class="form-control" value="<?php echo html_output(THIS_INSTALL_SET_TITLE); ?>" />
									</div>
								</div>
	
								<div class="form-group">
									<label for="timezone" class="col-sm-4 control-label"><?php _e('Вақт зонаси','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<?php
											/** 
											 * Generates a select field.
											 * Code is stored on a separate file since it's pretty long.
											 */
											include_once('includes/timezones.php');
										?>
									</div>
								</div>
	
								<div class="form-group">
									<label for="timeformat" class="col-sm-4 control-label"><?php _e('Вақт формати','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="timeformat" id="timeformat" value="<?php echo TIMEFORMAT_USE; ?>" />
										<p class="field_note"><?php _e('Санани кўриниши d/m/Y h:i:s форматда, Шу ҳолатда чиқади : ','cftp_admin'); ?> <strong><?php echo date('d/m/Y h:i:s'); ?></strong>
										</p>
									</div>
								</div>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="footer_custom_enable">
											<input type="checkbox" value="1" name="footer_custom_enable" id="footer_custom_enable" class="checkbox_options" <?php echo (FOOTER_CUSTOM_ENABLE == 1) ? 'checked="checked"' : ''; ?> /> <?php _e("Маҳсус матндан фойдаланинг",'cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<label for="footer_custom_content" class="col-sm-4 control-label"><?php _e('Тизимнинг пастки қисм матни','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="footer_custom_content" id="footer_custom_content" class="form-control" value="<?php echo html_output(FOOTER_CUSTOM_CONTENT); ?>" />
									</div>
								</div>
	
								<div class="options_divide"></div>
	
								<h3><?php _e('Хужжатлар','cftp_admin'); ?></h3>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="files_descriptions_use_ckeditor">
											<input type="checkbox" value="1" name="files_descriptions_use_ckeditor" id="files_descriptions_use_ckeditor" class="checkbox_options" <?php echo (DESCRIPTIONS_USE_CKEDITOR == 1) ? 'checked="checked"' : ''; ?> /> <?php _e("Ҳужжатларни визуал тарзда тахрирлаш мумкин",'cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="options_divide"></div>
	
								<h3><?php _e('Тизим тили','cftp_admin'); ?></h3>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="use_browser_lang">
											<input type="checkbox" value="1" name="use_browser_lang" id="use_browser_lang" class="checkbox_options" <?php echo (USE_BROWSER_LANG == 1) ? 'checked="checked"' : ''; ?> /> <?php _e("Фойдаланувчи бровзер тилини аниқлаши",'cftp_admin'); ?>
											<p class="field_note"><?php _e("Тизим томонидан бачр аудит ва фойдаланувчиларга таъсир қилиши мумкин",'cftp_admin'); ?></p>
										</label>
									</div>
								</div>
	
								<div class="options_divide"></div>
	
								<h3><?php _e('Тизим URL манзили','cftp_admin'); ?></h3>
								<p class="text-warning"><?php _e('Киритаётган маълумотларингизни яхшилаб тахрирлаб кўринг!!!','cftp_admin'); ?></p>
	
								<div class="form-group">
									<label for="base_uri" class="col-sm-4 control-label"><?php _e('Тизим URL манзили','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="base_uri" id="base_uri" value="<?php echo BASE_URI; ?>" />
									</div>
								</div>
					<?php
							break;
							case 'clients':
					?>
								<h3><?php _e('Янги рўйҳатга олиш','cftp_admin'); ?></h3>
								<p><?php _e('Фақат ўзидан рўйҳатга олиш мумкин','cftp_admin'); ?></p>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="clients_can_register">
											<input type="checkbox" value="1" name="clients_can_register" id="clients_can_register" class="checkbox_options" <?php echo (CLIENTS_CAN_REGISTER == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Аудитлар ўзлари рўйҳатдан ўтишлари мумкин','cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="clients_auto_approve">
											<input type="checkbox" value="1" name="clients_auto_approve" id="clients_auto_approve" class="checkbox_options" <?php echo (CLIENTS_AUTO_APPROVE == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Автоматик рухсат бериш','cftp_admin'); ?>
										</label>
									</div>
								</div>
								
								<div class="form-group">
									<label for="clients_auto_group" class="col-sm-4 control-label"><?php _e('Аудитни ушбу гуруҳга қўшинг:','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<select class="form-control" name="clients_auto_group" id="clients_auto_group">
											<option value="0"><?php _e('Белгиланмаган','cftp_admin'); ?></option>
											<?php
												/** Fill the groups array that will be used on the form */
												$get_groups		= new GroupActions;
												$arguments		= array();
												$groups 		= $get_groups->get_groups($arguments);
												
												foreach ( $groups as $group ) {
											?>
														<option value="<?php echo filter_var($group["id"],FILTER_VALIDATE_INT); ?>"
															<?php
																if (CLIENTS_AUTO_GROUP == $group["id"]) {
																	echo 'selected="selected"';
																}
															?>
															><?php echo html_output($group["name"]); ?>
														</option>
													<?php
												}
											?>
										</select>
										<p class="field_note"><?php _e('Янги аудитлар автоматик тарзда чиз танлаган бўлимга бириктирилади','cftp_admin'); ?></p>
									</div>
								</div>
	
								<div class="form-group">
									<label for="clients_can_select_group" class="col-sm-4 control-label"><?php _e('Бўлимларга аъзолик ҳуқуқига руҳсат бермаслик, аудтлар учун:','cftp_admin'); ?></label> 
									<div class="col-sm-8">
										<select class="form-control" name="clients_can_select_group" id="clients_can_select_group">
											<?php
												$pub_groups_options = array(
																			'none'		=> __("Белгиланмаган",'cftp_admin'),
																			'public'	=> __("Ошкора бўлимлар",'cftp_admin'),
																			'all'		=> __("Барча бўлимлар",'cftp_admin'),
																		);
												foreach ( $pub_groups_options as $value => $label ) {
											?>
													<option value="<?php echo $value; ?>" <?php if (CLIENTS_CAN_SELECT_GROUP == $value) { echo 'selected="selected"'; } ?>><?php echo $label; ?></option>
											<?php
												}
											?>
										</select>
										<p class="field_note"><?php _e('Қачонки аудитлар рўйҳатдан ўтказилса уларга бўлимга қўшилиш ёки қўшилмаслик сўроғини бериш','cftp_admin'); ?></p>
									</div>
								</div>

								<div class="options_divide"></div>
	
								<h3><?php _e('Ҳужжатлар','cftp_admin'); ?></h3>
								<?php
									/*<p><?php _e('Options related to the files that clients upload themselves.','cftp_admin'); ?></p>
									*/
								?>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="clients_can_upload">
											<input type="checkbox" value="1" name="clients_can_upload" id="clients_can_upload" class="checkbox_options" <?php echo (CLIENTS_CAN_UPLOAD == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Аудитлар ҳужжат юклай олишлари мумкин','cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="clients_can_delete_own_files">
											<input type="checkbox" value="1" name="clients_can_delete_own_files" id="clients_can_delete_own_files" class="checkbox_options" <?php echo (CLIENTS_CAN_DELETE_OWN_FILES == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Аудитлар ўзлари сақлаган ҳужжатларни ўчира билишлари мумкин','cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="clients_can_set_expiration_date">
											<input type="checkbox" value="1" name="clients_can_set_expiration_date" id="clients_can_set_expiration_date" class="checkbox_options" <?php echo (CLIENTS_CAN_SET_EXPIRATION_DATE == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Аудитлар ўзлари юклаган ҳужжатларига муддат белгилай олишлари мумкин','cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<label for="expired_files_hide" class="col-sm-4 control-label"><?php _e('Қачон ҳужжатни муддати тугайди:','cftp_admin'); ?></label> 
									<div class="col-sm-8">
										<select class="form-control" name="expired_files_hide" id="expired_files_hide">
											<option value="1" <?php echo (EXPIRED_FILES_HIDE == '1') ? 'selected="selected"' : ''; ?>><?php _e("Хужжатлар рўйҳатидан кўрсатмаслик",'cftp_admin'); ?></option>
											<option value="0" <?php echo (EXPIRED_FILES_HIDE == '0') ? 'selected="selected"' : ''; ?>><?php _e("Ҳужжат кўрсатилсин аммо юклаб билишмасин",'cftp_admin'); ?></option>
										</select>
										<p class="field_note"><?php _e('Ушбу ҳужжат фақат аудитлар учун лекин администратор ҳар дойим юклай олиши мумкин','cftp_admin'); ?></p>
									</div>
								</div>
					<?php
							break;
							case 'privacy':
					?>
								<h3><?php _e('Маҳвфийлик','cftp_admin'); ?></h3>

								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="privacy_noindex_site">
											<input type="checkbox" value="1" name="privacy_noindex_site" id="privacy_noindex_site" class="checkbox_options" <?php echo (PRIVACY_NOINDEX_SITE == 1) ? 'checked="checked"' : ''; ?> /> <?php _e("Қидирув тизимларида яширин ҳолаьда сақлаш",'cftp_admin'); ?>
										</label>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="enable_landing_for_all_files">
											<input type="checkbox" value="1" name="enable_landing_for_all_files" id="enable_landing_for_all_files" class="checkbox_options" <?php echo (ENABLE_LANDING_FOR_ALL_FILES == 1) ? 'checked="checked"' : ''; ?> /> <?php _e("Шаҳсий ҳужжатлар учун ошкора қилиш",'cftp_admin'); ?>
											<p class="field_note"><?php _e("Агар ушбу фунқцияни танласангиз, барча ҳужжатлар ошкора ҳолатда бўлади",'cftp_admin'); ?></p>
										</label>
									</div>
								</div>

								<div class="options_divide"></div>

								<h3><?php _e('Очиқ бўлимлар ва ҳужжатлар рўйҳати','cftp_admin'); ?></h3>

								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="public_listing_page_enable">
											<input type="checkbox" value="1" name="public_listing_page_enable" id="public_listing_page_enable" class="checkbox_options" <?php echo (PUBLIC_LISTING_ENABLE == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Саҳифа очиқ ҳолатди','cftp_admin'); ?>
										</label>
										<p class="field_note"><?php _e('URL манзил','cftp_admin'); ?><br>
										<a href="<?php echo PUBLIC_LANDING_URI; ?>" target="_blank">
											<?php echo PUBLIC_LANDING_URI; ?>
										</a></p>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="public_listing_logged_only">
											<input type="checkbox" value="1" name="public_listing_logged_only" id="public_listing_logged_only" class="checkbox_options" <?php echo (PUBLIC_LISTING_LOGGED_ONLY == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Фақат рўйҳатдан ўткан аудитлар учун','cftp_admin'); ?>
										</label>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="public_listing_show_all_files">
											<input type="checkbox" value="1" name="public_listing_show_all_files" id="public_listing_show_all_files" class="checkbox_options" <?php echo (PUBLIC_LISTING_SHOW_ALL_FILES == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Ошкора қилиб белгиланмаган ҳужжатларни бўлим ичида кўрсатиш','cftp_admin'); ?>
										</label>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="public_listing_use_download_link">
											<input type="checkbox" value="1" name="public_listing_use_download_link" id="public_listing_use_download_link" class="checkbox_options" <?php echo (PUBLIC_LISTING_USE_DOWNLOAD_LINK == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Ошкора ҳужжатларни юклаб олиш','cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="options_divide"></div>

					<?php
							break;
							case 'email':
					?>
								<h3><?php _e('Тизим электрон почтаси','cftp_admin'); ?></h3>
	
								<div class="form-group">
									<label for="admin_email_address" class="col-sm-4 control-label"><?php _e('E-mail','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="admin_email_address" id="admin_email_address" class="form-control" value="<?php echo html_output(ADMIN_EMAIL_ADDRESS); ?>" />
									</div>
								</div>
	
								<div class="form-group">
									<label for="mail_from_name" class="col-sm-4 control-label"><?php _e('Тизим номи','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="mail_from_name" id="mail_from_name" class="form-control" value="<?php echo html_output(MAIL_FROM_NAME); ?>" />
									</div>
								</div>
	
								<div class="options_divide"></div>
	
								<h3><?php _e('Ҳужжатларни нусҳалаш','cftp_admin'); ?></h3>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="mail_copy_user_upload">
											<input type="checkbox" value="1" name="mail_copy_user_upload" id="mail_copy_user_upload" <?php echo (COPY_MAIL_ON_USER_UPLOADS == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Қачонки тизим фойдаланувчиси ҳужжат юкласа','cftp_admin'); ?>
										</label>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="mail_copy_client_upload">
											<input type="checkbox" value="1" name="mail_copy_client_upload" id="mail_copy_client_upload" <?php echo (COPY_MAIL_ON_CLIENT_UPLOADS == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Қачонки тизим аудитори ҳужжат юкласа','cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="options_nested_note">
									<p><?php _e('Сиз шунингдек ҳужжатни бошқа E-mail га ҳам юбориб қўйишингиз мумкин.','cftp_admin'); ?></p>
								</div>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="mail_copy_main_user">
											<input type="checkbox" value="1" name="mail_copy_main_user" class="mail_copy_main_user" <?php echo (COPY_MAIL_MAIN_USER == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Киритилган E-mail манзилга','cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<label for="mail_copy_addresses" class="col-sm-4 control-label"><?php _e('Қўшимча манзил','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="mail_copy_addresses" id="mail_copy_addresses" class="mail_data empty form-control" value="<?php echo html_output(COPY_MAIL_ADDRESSES); ?>" />
										<p class="field_note"><?php _e('Вергул ёрдамида E-mail ни рўйҳатга қўшиб қўйиш мумкин.','cftp_admin'); ?></p>
									</div>
								</div>
	
								<div class="options_divide"></div>
	
								<h3><?php _e('Муддати','cftp_admin'); ?></h3>
	
								<div class="form-group">
									<label for="notifications_max_tries" class="col-sm-4 control-label"><?php _e('Максимал юборишлар','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="notifications_max_tries" id="notifications_max_tries" class="form-control" value="<?php echo NOTIFICATIONS_MAX_TRIES; ?>" />
										<p class="field_note"><?php _e('Вақт белгиланса огоҳлантириш кетиб туради.','cftp_admin'); ?></p>
									</div>
								</div>
	
								<div class="form-group">
									<label for="notifications_max_days" class="col-sm-4 control-label"><?php _e('Муддат тугашидан олдин','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="notifications_max_days" id="notifications_max_days" class="form-control" value="<?php echo NOTIFICATIONS_MAX_DAYS; ?>" />
										<p class="field_note"><?php _e('Огоҳлантиришлар юбориб туриши','cftp_admin'); ?><br /><strong><?php _e('0 қилинса авто кетади','cftp_admin'); ?></strong></p>
									</div>
								</div>
	
								<div class="options_divide"></div>
	
								<h3><?php _e('E-mail га юбориш созламаси','cftp_admin'); ?></h3>
								<p><?php _e('...','cftp_admin'); ?></p>
	
								<div class="form-group">
									<label for="mail_system_use" class="col-sm-4 control-label"><?php _e('E-mail почта протаколи','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<select class="form-control" name="mail_system_use" id="mail_system_use">
											<option value="mail" <?php echo (MAIL_SYSTEM == 'mail') ? 'selected="selected"' : ''; ?>>PHP Mail (basic)</option>
											<option value="smtp" <?php echo (MAIL_SYSTEM == 'smtp') ? 'selected="selected"' : ''; ?>>SMTP</option>
											<option value="gmail" <?php echo (MAIL_SYSTEM == 'gmail') ? 'selected="selected"' : ''; ?>>Gmail</option>
											<option value="sendmail" <?php echo (MAIL_SYSTEM == 'sendmail') ? 'selected="selected"' : ''; ?>>Sendmail</option>
										</select>
									</div>
								</div>
	
								<div class="options_divide"></div>
	
								<h3><?php _e('SMTP & Gmail орқали юбориш','cftp_admin'); ?></h3>
								<p><?php _e('...','cftp_admin'); ?></p>
	
								<div class="form-group">
									<label for="mail_smtp_user" class="col-sm-4 control-label"><?php _e('Логин','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="mail_smtp_user" id="mail_smtp_user" class="mail_data empty form-control" value="<?php echo html_output(SMTP_USER); ?>" />
									</div>
								</div>
	
								<div class="form-group">
									<label for="mail_smtp_pass" class="col-sm-4 control-label"><?php _e('Парол','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="password" name="mail_smtp_pass" id="mail_smtp_pass" class="mail_data empty form-control" value="<?php echo html_output(SMTP_PASS); ?>" />
									</div>
								</div>
								
								<div class="options_divide"></div>
	
								<h3><?php _e('SMTP созламаси','cftp_admin'); ?></h3>
								<p><?php _e('SMTP ни созлаш','cftp_admin'); ?></p>
								
								<div class="form-group">
									<label for="mail_smtp_host" class="col-sm-4 control-label"><?php _e('Host','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="mail_smtp_host" id="mail_smtp_host" class="mail_data empty form-control" value="<?php echo html_output(SMTP_HOST); ?>" />
									</div>
								</div>
								
								<div class="form-group">
									<label for="mail_smtp_port" class="col-sm-4 control-label"><?php _e('Port','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="mail_smtp_port" id="mail_smtp_port" class="mail_data empty form-control" value="<?php echo html_output(SMTP_PORT); ?>" />
									</div>
								</div>
								
								<div class="form-group">
									<label for="mail_smtp_auth" class="col-sm-4 control-label"><?php _e('Рўйҳатдан ўтиш','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<select class="form-control" name="mail_smtp_auth" id="mail_smtp_auth">
											<option value="none" <?php echo (SMTP_AUTH == 'none') ? 'selected="selected"' : ''; ?>><?php _e('Танланг','cftp_admin'); ?></option>
											<option value="ssl" <?php echo (SMTP_AUTH == 'ssl') ? 'selected="selected"' : ''; ?>>SSL</option>
											<option value="tls" <?php echo (SMTP_AUTH == 'tls') ? 'selected="selected"' : ''; ?>>TLS</option>
										</select>
									</div>
								</div>
					<?php
							break;
							case 'security':
					?>
								<h3><?php _e('Руҳсат этилган ҳужжат кенгайтмалари','cftp_admin'); ?></h3>
								<p><?php _e('Ҳужжат форматларини олиб ташлашининг мумкин.','cftp_admin'); ?><br />
								<strong><?php _e('Эслатма','cftp_admin'); ?></strong>: <?php _e('Форматларни ўчиришдан олдин ишонч ҳосил қилинг!!!','cftp_admin'); ?></p>
	
							   <div class="form-group">
								   <label for="file_types_limit_to" class="col-sm-4 control-label"><?php _e('Кимларга','cftp_admin'); ?></label>
								   <div class="col-sm-8">
										<select class="form-control" name="file_types_limit_to" id="file_types_limit_to">
											<option value="noone" <?php echo (FILE_TYPES_LIMIT_TO == 'noone') ? 'selected="selected"' : ''; ?>><?php _e('Хеч кимга','cftp_admin'); ?></option>
											<option value="all" <?php echo (FILE_TYPES_LIMIT_TO == 'all') ? 'selected="selected"' : ''; ?>><?php _e('Барчага','cftp_admin'); ?></option>
											<option value="clients" <?php echo (FILE_TYPES_LIMIT_TO == 'clients') ? 'selected="selected"' : ''; ?>><?php _e('Аудитларга','cftp_admin'); ?></option>
										</select>
								   </div>
								</div>
	
							   <div class="form-group">
									<input name="allowed_file_types" id="allowed_file_types" value="<?php echo $allowed_file_types; ?>" />
								</div>
								
								<?php
									if ( isset( $php_allowed_warning ) && $php_allowed_warning == true ) {
										$msg = __('Эслатма: ички каталогларни бир текшириб чиқинг','cftp_admin');
										echo system_message('error',$msg);
									}
								?>
	
								<div class="options_divide"></div>
	
								<h3><?php _e('Пароллар','cftp_admin'); ?></h3>
								<p><?php _e('Ушбу созламани ишлатсангиз, камида биттасини танлашингиз керак!','cftp_admin'); ?></p>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="pass_require_upper">
											<input type="checkbox" value="1" name="pass_require_upper" id="pass_require_upper" class="checkbox_options" <?php echo (PASS_REQ_UPPER == 1) ? 'checked="checked"' : ''; ?> /> <?php echo $validation_req_upper; ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="pass_require_lower">
											<input type="checkbox" value="1" name="pass_require_lower" id="pass_require_lower" class="checkbox_options" <?php echo (PASS_REQ_LOWER == 1) ? 'checked="checked"' : ''; ?> /> <?php echo $validation_req_lower; ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="pass_require_number">
											<input type="checkbox" value="1" name="pass_require_number" id="pass_require_number" class="checkbox_options" <?php echo (PASS_REQ_NUMBER == 1) ? 'checked="checked"' : ''; ?> /> <?php echo $validation_req_number; ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="pass_require_special">
											<input type="checkbox" value="1" name="pass_require_special" id="pass_require_special" class="checkbox_options" <?php echo (PASS_REQ_SPECIAL == 1) ? 'checked="checked"' : ''; ?> /> <?php echo $validation_req_special; ?>
										</label>
									</div>
								</div>
	
								<!-- <div class="options_divide"></div>
	
								<div class="options_divide"></div> -->
	
								<!-- <h3><?php _e('reCAPTCHA','cftp_admin'); ?></h3>
								<p><?php _e('Helps prevent SPAM on your registration form.','cftp_admin'); ?></p>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="recaptcha_enabled">
											<input type="checkbox" value="1" name="recaptcha_enabled" id="recaptcha_enabled" class="checkbox_options" <?php echo (RECAPTCHA_ENABLED == 1) ? 'checked="checked"' : ''; ?> /> <?php _e('Use reCAPTCHA','cftp_admin'); ?>
										</label>
									</div>
								</div>
	
								<div class="form-group">
									<label for="recaptcha_site_key" class="col-sm-4 control-label"><?php _e('Site key','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="recaptcha_site_key" id="recaptcha_site_key" class="form-control empty" value="<?php echo html_output(RECAPTCHA_SITE_KEY); ?>" />
									</div>
								</div>
	
								<div class="form-group">
									<label for="recaptcha_secret_key" class="col-sm-4 control-label"><?php _e('Secret key','cftp_admin'); ?></label>
									<div class="col-sm-8">
										<input type="text" name="recaptcha_secret_key" id="recaptcha_secret_key" class="form-control empty" value="<?php echo html_output(RECAPTCHA_SECRET_KEY); ?>" />
									</div>
								</div>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<a href="<?php echo LINK_DOC_RECAPTCHA; ?>" class="external_link" target="_blank"><?php _e('How do I obtain this credentials?','cftp_admin'); ?></a>
									</div>
								</div> -->


					<?php
							break;
							case 'thumbnails':
					?>
								<h3><?php _e('Thumbnails','cftp_admin'); ?></h3>
								<p><?php _e("Thumbnails are used on files lists. It is recommended to keep them small, unless you are using the system to upload only images, and will change the default client's template accordingly.",'cftp_admin'); ?></p>
	
								<div class="options_column">
									<div class="options_col_left">
										<div class="form-group">
											<label for="max_thumbnail_width" class="col-sm-6 control-label"><?php _e('Max width','cftp_admin'); ?></label>
											<div class="col-sm-6">
												<input type="text" name="max_thumbnail_width" id="max_thumbnail_width" class="form-control" value="<?php echo html_output(THUMBS_MAX_WIDTH); ?>" />
											</div>
										</div>
	
										<div class="form-group">
											<label for="max_thumbnail_height" class="col-sm-6 control-label"><?php _e('Max height','cftp_admin'); ?></label>
											<div class="col-sm-6">
												<input type="text" name="max_thumbnail_height" id="max_thumbnail_height" class="form-control" value="<?php echo html_output(THUMBS_MAX_HEIGHT); ?>" />
											</div>
										</div>
									</div>
									<div class="options_col_right">
										<div class="form-group">
											<label for="thumbnail_default_quality" class="col-sm-6 control-label"><?php _e('JPG Quality','cftp_admin'); ?></label>
											<div class="col-sm-6">
												<input type="text" name="thumbnail_default_quality" id="thumbnail_default_quality" class="form-control" value="<?php echo html_output(THUMBS_QUALITY); ?>" />
											</div>
										</div>
									</div>
								</div>
	
								<div class="options_divide"></div>
	
								<h3><?php _e("File's path", 'cftp_admin'); ?></h3>
								<p><?php _e("If thumbnails are not showing (your company logo and file's preview on the branding page and client's files lists) try setting this option ON. It they still don't work, a folders permission issue might be the cause.",'cftp_admin'); ?></p>
	
								<div class="form-group">
									<div class="col-sm-8 col-sm-offset-4">
										<label for="thumbnails_use_absolute">
											<input type="checkbox" value="1" name="thumbnails_use_absolute" id="thumbnails_use_absolute" <?php echo (THUMBS_USE_ABSOLUTE == 1) ? 'checked="checked"' : ''; ?> /> <?php _e("Use file's absolute path",'cftp_admin'); ?>
										</label>
									</div>
								</div>
					<?php
							break;
							case 'branding':
					?>
								<h3><?php _e('Current logo','cftp_admin'); ?></h3>
								<p><?php _e('Use this page to upload your company logo, or update the currently assigned one. This image will be shown to your clients when they access their file list.','cftp_admin'); ?></p>
		
								<div id="current_logo">
									<div id="current_logo_img">
										<?php
											if ($logo_file_info['exists'] === true) {
										?>
												<img src="<?php echo $logo_file_info['url']; ?>" alt="<?php _e('Logo Placeholder','cftp_admin'); ?>" />
										<?php
											}
										?>
									</div>
									<p class="preview_logo_note">
										<?php _e('Tihs preview uses a maximum width of 300px','cftp_admin'); ?>
									</p>
								</div>
						
								<div id="form_upload_logo">
									<input type="hidden" name="MAX_FILE_SIZE" value="1000000000">
									<div class="form-group">
										<label class="col-sm-4 control-label"><?php _e('Select image to upload','cftp_admin'); ?></label>
										<div class="col-sm-8">
											<input type="file" name="select_logo" class="empty" />
										</div>
									</div>
								</div>
		
								<div class="options_divide"></div>
		
								<h3><?php _e('Size settings','cftp_admin'); ?></h3>
								<p><?php _e("The file viewer template may use this setting when showing the image.",'cftp_admin'); ?></p>
		
								<div class="form-group">
									<label for="max_logo_width" class="col-sm-4 control-label"><?php _e('Max width','cftp_admin'); ?></label>
									<div class="col-sm-3">
										<div class="input-group">
											<input type="text" name="max_logo_width" id="max_logo_width" class="form-control" value="<?php echo html_output(LOGO_MAX_WIDTH); ?>" />
											<span class="input-group-addon">px</span>
										</div>
									</div>
								</div>
		
								<div class="form-group">
									<label for="max_logo_height" class="col-sm-4 control-label"><?php _e('Max height','cftp_admin'); ?></label>
									<div class="col-sm-3">
										<div class="input-group">
											<input type="text" name="max_logo_height" id="max_logo_height" class="form-control" value="<?php echo html_output(LOGO_MAX_HEIGHT); ?>" />
											<span class="input-group-addon">px</span>
										</div>
									</div>
								</div>
					<?php
							break;
							case 'social_login':
					?>
								<h3><?php _e('Google','cftp_admin'); ?></h3>
	
								<div class="options_column">
									<div class="form-group">
										<label for="google_signin_enabled" class="col-sm-4 control-label"><?php _e('Enabled','cftp_admin'); ?></label>
										<div class="col-sm-8">
											<select name="google_signin_enabled" id="google_signin_enabled" class="form-control">
												<option value="1" <?php echo (GOOGLE_SIGNIN_ENABLED == '1') ? 'selected="selected"' : ''; ?>><?php _e('Yes','cftp_admin'); ?></option>
												<option value="0" <?php echo (GOOGLE_SIGNIN_ENABLED == '0') ? 'selected="selected"' : ''; ?>><?php _e('No','cftp_admin'); ?></option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="google_client_id" class="col-sm-4 control-label"><?php _e('Client ID','cftp_admin'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="google_client_id" id="google_client_id" class="form-control empty" value="<?php echo html_output(GOOGLE_CLIENT_ID); ?>" />
										</div>
									</div>
									<div class="form-group">
										<label for="google_client_secret" class="col-sm-4 control-label"><?php _e('Client Secret','cftp_admin'); ?></label>
										<div class="col-sm-8">
											<input type="text" name="google_client_secret" id="google_client_secret" class="form-control empty" value="<?php echo html_output(GOOGLE_CLIENT_SECRET); ?>" />
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-8 col-sm-offset-4">
											<a href="<?php echo LINK_DOC_GOOGLE_SIGN_IN; ?>" class="external_link" target="_blank"><?php _e('How do I obtain this credentials?','cftp_admin'); ?></a>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-4">
											<?php _e('Callback URI','cftp_admin'); ?>
										</div>
										<div class="col-sm-8">
											<span class="format_url"><?php echo BASE_URI . 'sociallogin/google/callback.php'; ?></span>
										</div>
									</div>
								</div>
					<?php
							break;
						}
					?>

				<div class="options_divide"></div>

				<div class="after_form_buttons">
					<button type="submit" class="btn btn-wide btn-primary empty"><?php _e('Сақлаш','cftp_admin'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
	include('footer.php');
