<?php

$allowed_levels = array(9,8,7,0);
require_once('sys.includes.php');

$page_title = __('Қайта тиклаш','cftp_admin');

include('header-unlogged.php');
	$show_form = 'enter_email';

	if (!empty($_GET['token']) && !empty($_GET['user'])) {
		$got_token	= $_GET['token'];
		$got_user	= $_GET['user'];

		
		$query_user_id	= $dbh->prepare("SELECT id, user FROM " . TABLE_USERS . " WHERE user = :user");
		$query_user_id->bindParam(':user', $got_user);
		$query_user_id->execute();
		$result_user_id = $query_user_id->fetch();
		$got_user_id	= $result_user_id['id'];

		$sql_request = $dbh->prepare("SELECT * FROM " . TABLE_PASSWORD_RESET . " WHERE BINARY token = :token AND user_id = :id");
		$sql_request->bindParam(':token', $got_token);
		$sql_request->bindParam(':id', $got_user_id, PDO::PARAM_INT);
		$sql_request->execute();
		$count_request = $sql_request->rowCount();

		if ( $count_request > 0 ) {
			$sql_request->setFetchMode(PDO::FETCH_ASSOC);
			$token_info = $sql_request->fetch();
			$request_id = $token_info['id'];

			
			if ($token_info['used'] == '1') {
				
				$got_user_id = '';
				$errorstate = 'token_used';
			}

			
			elseif (time() - strtotime($token_info['timestamp']) > 60*60*24) {
			$got_user_id = '';
				$errorstate = 'token_expired';
			}

			else {
				$show_form = 'enter_new_password';
			}
		}
		else {
			$got_user_id = '';
			$errorstate = 'token_invalid';
			$show_form = 'none';
		}
	}

	
	if ($_POST) {
		
		$form_type = encode_html($_POST['form_type']);
		
		switch ($form_type) {

			
			case 'new_request':
				$sql_user = $dbh->prepare("SELECT id, user, email FROM " . TABLE_USERS . " WHERE email = :email");
				$sql_user->bindParam(':email', $_POST['reset_password_email']);
				$sql_user->execute();
				$count_user = $sql_user->rowCount();
		
				if ( $count_user > 0 ) {
					
					$sql_user->setFetchMode(PDO::FETCH_ASSOC);
					$row = $sql_user->fetch();
					$id			= $row['id'];
					$username	= $row['user'];
					$email		= $row['email'];
					$token		= generateRandomString(32);
					
					
					$sql_amount = $dbh->prepare("SELECT * FROM " . TABLE_PASSWORD_RESET . " WHERE user_id = :id AND used = '0' AND timestamp > NOW() - INTERVAL 1 DAY");
					$sql_amount->bindParam(':id', $id, PDO::PARAM_INT);
					$sql_amount->execute();
					$count_requests = $sql_amount->rowCount();
					if ($count_requests >= 3){
						$errorstate = 'too_many_today';
					}
					else {
						$sql_pass = $dbh->prepare("INSERT INTO " . TABLE_PASSWORD_RESET . " (user_id, token)"
														."VALUES (:id, :token)");
						$sql_pass->bindParam(':token', $token);
						$sql_pass->bindParam(':id', $id, PDO::PARAM_INT);
						$sql_pass->execute();
			
						
						$notify_user = new PSend_Email();
						$email_arguments = array(
														'type' => 'password_reset',
														'address' => $email,
														'username' => $username,
														'token' => $token
													);
						$notify_send = $notify_user->psend_send_email($email_arguments);
			
						if ($notify_send == 1){
							$state['email'] = 1;
						}
						else {
							$state['email'] = 0;
						}
					}
					
					$show_form = 'none';
				}
				else {
					$errorstate = 'email_not_found';
				}
			break;

			
			case 'new_password':
				if (!empty($got_user_id)) {
					$reset_password_new = $_POST['reset_password_new'];
	
					
					$valid_me->validate('completed',$reset_password_new,$validation_no_pass);
					$valid_me->validate('password',$reset_password_new,$validation_valid_pass.' '.$validation_valid_chars);
					$valid_me->validate('pass_rules',$reset_password_new,$validation_rules_pass);
					$valid_me->validate('length',$reset_password_new,$validation_length_pass,MIN_PASS_CHARS,MAX_PASS_CHARS);
			
					if ($valid_me->return_val) {
	
						$enc_password = $hasher->HashPassword($reset_password_new);
				
						if (strlen($enc_password) >= 20) {
				
							$state['hash'] = 1;
				
							

							$sql_query = $dbh->prepare("UPDATE " . TABLE_USERS . " SET 
														password = :password
														WHERE id = :id"
												);
							$sql_query->bindParam(':password', $enc_password);
							$sql_query->bindParam(':id', $got_user_id, PDO::PARAM_INT);
							$sql_query->execute();							
					
							if ($sql_query) {
								$state['reset'] = 1;

								$sql_query = $dbh->prepare("UPDATE " . TABLE_PASSWORD_RESET . " SET 
															used = '1' 
															WHERE id = :id"
													);
								$sql_query->bindParam(':id', $request_id, PDO::PARAM_INT);
								$sql_query->execute();							

								$show_form = 'none';
							}
							else {
								$state['reset'] = 0;
							}
						}
						else {
							$state['hash'] = 0;
						}
					}
				}
				
			break;
		}
	}
	?>

<div class="col-xs-12 col-sm-12 col-lg-4 col-lg-offset-4">

	<?php echo generate_branding_layout(); ?>

	<div class="white-box">
		<div class="white-box-interior">
			<?php
				
				$valid_me->list_errors();
			?>
	
			<?php
			
				if (isset($errorstate)) {
					switch ($errorstate) {
						case 'email_not_found':
							$login_err_message = __("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin');
							break;
						case 'token_invalid':
							$login_err_message = __("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin');
							break;
						case 'token_expired':
							$login_err_message = __("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin');
							break;
						case 'token_used':
							$login_err_message = __("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin');
							break;
						case 'too_many_today':
							$login_err_message = __("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin');
							break;
					}
	
					echo system_message('error',$login_err_message,'login_error');
				}

				
				if (isset($state['email'])) {
					switch ($state['email']) {
						case 1:
							$msg = __('Илтимос, E-mail ни текшириб кўринг','cftp_admin');
							echo system_message('ok',$msg);
						break;
						case 0:
							$msg = __("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin');
							$msg .= ' ' . __("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin');
							echo system_message('error',$msg);
						break;
					}
				}

				
				if (isset($state['reset'])) {
					switch ($state['reset']) {
						case 1:
							$msg = __('Илтимос, E-mail ни текшириб кўринг','cftp_admin');
							echo system_message('ok',$msg);
						break;
						case 0:
							$msg = __("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin');
							$msg .= ' ' . __("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin');
							echo system_message('error',$msg);
						break;
					}
				}
				 
				switch ($show_form) {
					case 'enter_email':
					default:
			?>
						<script type="text/javascript">
							$(document).ready(function() {
								$("form").submit(function() {
									clean_form(this);
						
									is_complete(this.reset_password_email,'<?php echo $validation_no_email; ?>');
									is_email(this.reset_password_email,'<?php echo $validation_invalid_mail; ?>');
						
									
									if (show_form_errors() == false) { return false; }
								});
							});
						</script>
						
						<form action="reset-password.php" name="resetpassword" method="post" role="form">
							<fieldset>
								<input type="hidden" name="form_type" id="form_type" value="new_request" />

								<div class="form-group">
									<label for="reset_password_email"><?php _e('E-mail','cftp_admin'); ?></label>
									<input type="text" name="reset_password_email" id="reset_password_email" class="form-control" />
								</div>

								<p><?php _e("Илтимос, E-mail ни киритинг ва ўзингизни почтангизни текшириб кўринг",'cftp_admin'); ?></p>

								<div class="inside_form_buttons">
									<button type="submit" name="submit" class="btn btn-wide btn-primary"><?php _e('Кайта олиш','cftp_admin'); ?></button>
								</div>
							</fieldset>
						</form>
			<?php
					break;
					case 'enter_new_password':
			?>
						<script type="text/javascript">
							
							var ignore_columns = true;

							$(document).ready(function() {
								$("form").submit(function() {

									clean_form(this);
						
									is_complete(this.reset_password_new,'<?php echo $validation_no_pass; ?>');
									is_length(this.reset_password_new,<?php echo MIN_PASS_CHARS; ?>,<?php echo MAX_PASS_CHARS; ?>,'<?php echo $validation_length_pass; ?>');
									is_password(this.reset_password_new,'<?php $chars = addslashes($validation_valid_chars); echo $validation_valid_pass." ".$chars; ?>');
						
									
									if (show_form_errors() == false) { return false; }
								});
							});
						</script>
						
						<form action="reset-password.php?token=<?php echo html_output($got_token); ?>&user=<?php echo html_output($got_user); ?>" name="newpassword" method="post" role="form">
							<fieldset>
								<input type="hidden" name="form_type" id="form_type" value="new_password" />

								<div class="form-group">
									<label for="reset_password_new"><?php _e('Янги парол','cftp_admin'); ?></label>
									<div class="input-group">
										<input type="password" name="reset_password_new" id="reset_password_new" class="form-control password_toggle" />
										<div class="input-group-btn password_toggler">
											<button type="button" class="btn pass_toggler_show"><i class="glyphicon glyphicon-eye-open"></i></button>
										</div>
									</div>
									<button type="button" name="generate_password" id="generate_password" class="btn btn-default btn-sm btn_generate_password" data-ref="reset_password_new" data-min="<?php echo MAX_GENERATE_PASS_CHARS; ?>" data-max="<?php echo MAX_GENERATE_PASS_CHARS; ?>"><?php _e('Генерация','cftp_admin'); ?></button>
								</div>
								<?php echo password_notes(); ?>
								
								<p><?php _e("Генерация қилинган паролни киритинг",'cftp_admin'); ?></p>

								<div class="inside_form_buttons">
									<button type="submit" name="submit" class="btn btn-wide btn-primary"><?php _e('Янги парол','cftp_admin'); ?></button>
								</div>
							</fieldset>
						</form>
			<?php
					break;
					case 'none':
			?>
			<?php
					break;
				 }
			?>

			<div class="login_form_links">
				<p><a href="<?php echo BASE_URI; ?>" target="_self"><?php _e('Бош саҳифага қайтиш','cftp_admin'); ?></a></p>
			</div>
		</div>
	</div>
</div>

<?php
	include('footer.php');