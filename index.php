<?php

$allowed_levels = array(9,8,7,0);
require_once('sys.includes.php');

$page_title = __('Кириш','cftp_admin');

$body_class = array('login');

include('header-unlogged.php');

$login_button_text = __('Кириш','cftp_admin');
	
	
	if ( GOOGLE_SIGNIN_ENABLED == '1' ) {
		$googleClient = new Google_Client();
		$googleClient->setApplicationName(THIS_INSTALL_SET_TITLE);
		$googleClient->setClientSecret(GOOGLE_CLIENT_SECRET);
		$googleClient->setClientId(GOOGLE_CLIENT_ID);
		$googleClient->setAccessType('online');
		$googleClient->setApprovalPrompt('auto');
		$googleClient->setRedirectUri(BASE_URI . 'sociallogin/google/callback.php');
		$googleClient->setScopes(array('profile','email'));
		$auth_url = $googleClient->createAuthUrl();
	}
	

	if ( isset($_SESSION['errorstate'] ) ) {
		$errorstate = $_SESSION['errorstate'];
		unset($_SESSION['errorstate']);
	}
?>
<div class="col-xs-12 col-sm-12 col-lg-4 col-lg-offset-4">

	<?php echo generate_branding_layout(); ?>

	<div class="white-box">
		<div class="white-box-interior">
			<div class="ajax_response">
				<?php
					
					if ( isset( $_GET['error'] ) ) {
						switch ( $_GET['error'] ) {
							case 1:
								echo system_message('error',__("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin'),'login_error');
								break;
							case 'timeout':
								echo system_message('error',__("Ҳатолик, кайтадан уриниб кўринг",'cftp_admin'),'login_error');
								break;
						}
					}
				?>
			</div>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#login_form").submit(function(e) {
						e.preventDefault();
						e.stopImmediatePropagation();
						$('.ajax_response').html();
						clean_form(this);
		
						is_complete(this.username,'<?php _e('Ҳатолик, кайтадан уриниб кўринг','cftp_admin'); ?>');
						is_complete(this.password,'<?php _e('Ҳатолик, кайтадан уриниб кўринг','cftp_admin'); ?>');
		
						
						if (show_form_errors() == false) {
							return false;
						}
						else {
							var url = $(this).attr('action');
							$('.ajax_response').html('');
							$('#submit').html('<i class="fa fa-cog fa-spin fa-fw"></i><span class="sr-only"></span> <?php _e('Тизимга кириш','cftp_admin'); ?>...');
							$.ajax({
									cache: false,
									type: "get",
									url: url,
									data: $(this).serialize(), 
									success: function(response)
									{
										var json = jQuery.parseJSON(response);
										if ( json.status == 'success' ) {
											
											$('#submit').html('<i class="fa fa-check"></i><span class="sr-only"></span> <?php _e('Илтимос, кутиб туринг','cftp_admin'); ?>...');
											$('#submit').removeClass('btn-primary').addClass('btn-success');
											setTimeout('window.location.href = "'+json.location+'"', 1000);
										}
										else {
											$('.ajax_response').html(json.message);
											$('#submit').html('<?php echo $login_button_text; ?>');
										}
									}
							});
							return false;
						}
					});
				});
			</script>
		
			<form action="process.php" name="login_admin" role="form" id="login_form">
				<input type="hidden" name="do" value="login">
				<fieldset>
					<div class="form-group">
						<label for="username"><?php _e('Логин','cftp_admin'); ?> / <?php _e('E-mail','cftp_admin'); ?></label>
						<input type="text" name="username" id="username" value="<?php if (isset($sysuser_username)) { echo htmlspecialchars($sysuser_username); } ?>" class="form-control" autofocus />
					</div>

					<div class="form-group">
						<label for="password"><?php _e('Парол','cftp_admin'); ?></label>
						<input type="password" name="password" id="password" class="form-control" />
					</div>

					<!-- <div class="form-group">
						<label for="language"><?php _e('Тилни танланг','cftp_admin'); ?></label>
						<select name="language" id="language" class="form-control">
							<?php
								
								$available_langs = get_available_languages();
								foreach ($available_langs as $filename => $lang_name) {
							?>
									<option value="<?php echo $filename;?>" <?php echo ( LOADED_LANG == $filename ) ? 'selected' : ''; ?>>
										<?php
											echo $lang_name;
											if ( $filename == SITE_LANG ) {
												echo ' [' . __('default','cftp_admin') . ']';
											}
										?>
									</option>
							<?php
								}
							?>
						</select>
					</div> -->
<?php
/*
					<label for="login_form_remember">
						<input type="checkbox" name="login_form_remember" id="login_form_remember" value="on" />
						<?php _e('Сақлаб қол','cftp_admin'); ?>
					</label>
*/?>
					<div class="inside_form_buttons">
						<button type="submit" id="submit" class="btn btn-wide btn-primary"><?php echo $login_button_text; ?></button>
					</div>

					<div class="social-login">
						<?php if(GOOGLE_SIGNIN_ENABLED == '1'): ?>
							<a href="<?php echo $auth_url; ?>" name="Sign in with Google" class="google-login"><img src="<?php echo BASE_URI; ?>img/google/btn_google_signin_light_normal_web.png" alt="Google Signin" /></a>
						<?php endif; ?>
					</div>
				</fieldset>
			</form>

			<div class="login_form_links">
				<p id="reset_pass_link"><?php _e("Паролни эсдан чиқардингизми?",'cftp_admin'); ?> <a href="<?php echo BASE_URI; ?>reset-password.php"><?php _e('Қайта олиш.','cftp_admin'); ?></a></p>
				<?php
					if (CLIENTS_CAN_REGISTER == '1') {
				?>
						<p id="register_link"><?php _e("Don't have an account yet?",'cftp_admin'); ?> <a href="<?php echo BASE_URI; ?>register.php"><?php _e('Register as a new client.','cftp_admin'); ?></a></p>
				<?php
					} else {
				?>
						
				<?php
					}
				?>
			</div>

		</div>
	</div>
</div>

<?php
	include('footer.php');