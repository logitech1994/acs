<?php
/**
 * Define the language strings that are used on several parts of
 * the system, to avoid repetition.
 *
 * @package		ProjectSend
 * @subpackage	Core
 */

/**
 * System User Roles names
 */
$user_role_9_name = __('Тизим администратори','cftp_admin');
$user_role_8_name = __('Назоратчи аудитор','cftp_admin');
$user_role_7_name = __('Аноним аудитор','cftp_admin');
$user_role_0_name = __('Аудитор','cftp_admin');
if ( !defined( 'USER_ROLE_LVL_9' ) ) { define('USER_ROLE_LVL_9', $user_role_9_name); }
if ( !defined( 'USER_ROLE_LVL_8' ) ) { define('USER_ROLE_LVL_8', $user_role_8_name); }
if ( !defined( 'USER_ROLE_LVL_7' ) ) { define('USER_ROLE_LVL_7', $user_role_7_name); }
if ( !defined( 'USER_ROLE_LVL_0' ) ) { define('USER_ROLE_LVL_0', $user_role_0_name); }

/**
 * Validation class strings
 */
$validation_recaptcha		= __('reCAPTCHA verification failed','cftp_admin');
$validation_no_name			= __('Майдон тўлдирилмади','cftp_admin');
$validation_no_client		= __('Аудит танланмади','cftp_admin');
$validation_no_user			= __('Логин тўлдирилмади','cftp_admin');
$validation_no_pass			= __('Парол тўлдирилмади','cftp_admin');
$validation_no_pass2		= __('Паролни киритинг','cftp_admin');
$validation_no_email		= __('E-mail тўлдирилмади','cftp_admin');
$validation_invalid_mail	= __('E-mail ни киритинг','cftp_admin');
$validation_alpha_user		= __('Логинга ушбу символлар киритилмаслики лозим(a-z,A-Z,0-9,. ҳам)','cftp_admin');
$validation_alpha_pass		= __('Парол ушбу символлардан ташкил топиши керак(a-z,A-Z,0-9)','cftp_admin');
$validation_match_pass		= __('Майдон тўлдирилмади','cftp_admin');
$validation_rules_pass		= __('Қайта уриниб кўринг, парол майдонида ҳатолик!','cftp_admin');
$validation_file_size		= __('Ҳужжат хажми сонларда ташкил топиши лозим','cftp_admin');
$validation_no_level		= __('Майдонни тўлдиринг','cftp_admin');
$add_user_exists			= __('Тизим фойдаланувчиси ёки аудитор аллақачон рўйҳатга олинган','cftp_admin');
$add_user_mail_exists		= __('Тизим фойдаланувчиси ёки аудитор E-mail и аллақачон рўйҳатга олинган','cftp_admin');
$validation_valid_pass		= __('Паролни киритишда ҳатолик, шундай кўринишда бўлиши лозим:','cftp_admin');
$validation_valid_chars		= ('` ! " ? $ ? % ^ & * ( ) _ - + = { [ } ] : ; @ ~ # | < , > . ? \' / \ ');
$validation_no_title		= __('Майдон тўлдирилмади','cftp_admin');

/**
 * Validation strings for the length of usernames and passwords.
 * Uses the MIN and MAX values defined on sys.vars.php
 */
$validation_length_usr_1 = __('Логин','cftp_admin');
$validation_length_pass_1 = __('Парол','cftp_admin');
$validation_length_1 = __('узунлиги орасида','cftp_admin');
$validation_length_2 = __('ва','cftp_admin');
$validation_length_3 = __('характеристика','cftp_admin');
$validation_length_user = $validation_length_usr_1.' '.$validation_length_1.' '.MIN_USER_CHARS.' '.$validation_length_2.' '.MAX_USER_CHARS.' '.$validation_length_3;
$validation_length_pass = $validation_length_pass_1.' '.$validation_length_1.' '.MIN_PASS_CHARS.' '.$validation_length_2.' '.MAX_PASS_CHARS.' '.$validation_length_3;

$validation_req_upper	= __('1 катта ҳарфлар билан бошланиши','cftp_admin');
$validation_req_lower	= __('1 кичик ҳарфлар билан бошланиши','cftp_admin');
$validation_req_number	= __('1 камида сон бўлиши','cftp_admin');
$validation_req_special	= __('1 махсус белгилар бўлиши','cftp_admin');