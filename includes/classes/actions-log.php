<?php
/**
 * Class that handles all the actions that are logged on the database.
 *
 * @package		ProjectSend
 * @subpackage	Classes
 *
 */
global $activities_references;
$activities_references = array(
							//0	=> __('Дастур ишга туширилган','cftp_admin'),
							1	=> __('Тизимга кирганлар рўйҳати','cftp_admin'),
							24	=> __('Сookies орқали сақланиб қолганлар','cftp_admin'),
							31	=> __('Тизимдан чиққанлар','cftp_admin'),
							2	=> __('Янги фойдаланувчи яратилинди','cftp_admin'),
							3	=> __('Янги аудит яратилинди','cftp_admin'),
							/*4	=> __('A client registers an account for himself','cftp_admin'),*/
							5	=> __('Фойдаланувчи тамонидан ҳужжат юкланди','cftp_admin'),
							6	=> __('Аудит тамонидан ҳужжат юкланди','cftp_admin'),
							7	=> __('Фойдаланувчи тамонидан ҳужжат юкланди','cftp_admin'),
							8	=> __('Аудит тамонидан ҳужжат юкланди','cftp_admin'),
							9	=> __('Фойдаланувчи тамонидан ҳужжатлар архивланган','cftp_admin'),
							10	=> __('Аудит тамонидан йўналтирилмаган ҳужжатлар','cftp_admin'),
							11	=> __('Гуруҳ тамонидан йўналтирилмаган ҳужжатлар','cftp_admin'),
							12	=> __('Ўчирилган ҳужжатлар','cftp_admin'),
							13	=> __('Фойдаланувчи тахрир килгани','cftp_admin'),
							14	=> __('Аудит тахрирланди','cftp_admin'),
							15	=> __('Гуруҳ тахрирланди','cftp_admin'),
							16	=> __('Фойдаланувчи ўчирилди','cftp_admin'),
							17	=> __('Аудит тизимдан ўчирилди','cftp_admin'),
							18	=> __('Гуруҳ тизимдан ўчирилди','cftp_admin'),
							19	=> __('Аудит холати актив холатда','cftp_admin'),
							20	=> __('Аудит холати деактив холатда','cftp_admin'),
							27	=> __('Фойдаланувчи холати актив холатда','cftp_admin'),
							28	=> __('Фойдаланувчи холати деактив холатда','cftp_admin'),
							21	=> __('Ҳужжат яширин ҳолатда','cftp_admin'),
							22	=> __('Ҳужжат ошкора ҳолатда','cftp_admin'),
							23	=> __('Фойдаланувчи янги гуруҳ яратди','cftp_admin'),
							25	=> __('Ҳужжат аудитга йўналтирилди','cftp_admin'),
							26	=> __('Ҳужжат гуруҳга йўналтирилди','cftp_admin'),
							27	=> __('Фойдаланувчи актив қилиб белгиланди','cftp_admin'), // TODO: check repetition
							28	=> __('Фойдаланувчи деактив қилиб белгиланди','cftp_admin'),
							29	=> __('Логотип ўзгартирилди','cftp_admin'),
							30	=> __('Дастур янгиланди','cftp_admin'),
							32	=> __('Тизим фойдаланувчиси ҳужжатни тахрирлади','cftp_admin'),
							33	=> __('Аудит ҳужжатни тахрирлади','cftp_admin'),
							34	=> __('Аудит категория яратди','cftp_admin'),
							35	=> __('Аудит категорияни тахрирлади','cftp_admin'),
							36	=> __('Аудит категорияни ўчирди','cftp_admin'),
							37	=> __('Бошқа фойдаланувчи ошкора ҳужжатни юклади','cftp_admin'),
							/*38	=> __('A client account request was processed.','cftp_admin'),
							39	=> __("A client's groups membership requests were processed.",'cftp_admin'),*/
						);
 /**
 * More to be added soon.
 */

class LogActions
{

	var $action = '';

	/**
	 * Create a new client.
	 */
	function log_action_save($arguments)
	{
		global $dbh;
		global $global_name;
		$this->state = array();

		/** Define the account information */
		$this->action = $arguments['action'];
		$this->owner_id = $arguments['owner_id'];
		$this->owner_user = (!empty($arguments['owner_user'])) ? $arguments['owner_user'] : $global_name;
		$this->affected_file = (!empty($arguments['affected_file'])) ? $arguments['affected_file'] : '';
		$this->affected_account = (!empty($arguments['affected_account'])) ? $arguments['affected_account'] : '';
		$this->affected_file_name = (!empty($arguments['affected_file_name'])) ? $arguments['affected_file_name'] : '';
		$this->affected_account_name = (!empty($arguments['affected_account_name'])) ? $arguments['affected_account_name'] : '';
		
		/** Get the real name of the client or user */
		if (!empty($arguments['get_user_real_name'])) {
			$this->short_query = $dbh->prepare( "SELECT name FROM " . TABLE_USERS . " WHERE user =:user" );
			$params = array(
							':user'		=> $this->affected_account_name,
						);
			$this->short_query->execute( $params );
			$this->short_query->setFetchMode(PDO::FETCH_ASSOC);
			while ( $srow = $this->short_query->fetch() ) {
				$this->affected_account_name = $srow['name'];
			}
		}

		/** Get the title of the file on downloads */
		if (!empty($arguments['get_file_real_name'])) {
			$this->short_query = $dbh->prepare( "SELECT filename FROM " . TABLE_FILES . " WHERE url =:file" );
			$params = array(
							':file'		=> $this->affected_file_name,
						);
			$this->short_query->execute( $params );
			$this->short_query->setFetchMode(PDO::FETCH_ASSOC);
			while ( $srow = $this->short_query->fetch() ) {
				$this->affected_file_name = $srow['filename'];
			}
		}

		/** Insert the client information into the database */
		$lq = "INSERT INTO " . TABLE_LOG . " (action,owner_id,owner_user";
		
			if (!empty($this->affected_file)) { $lq .= ",affected_file"; }
			if (!empty($this->affected_account)) { $lq .= ",affected_account"; }
			if (!empty($this->affected_file_name)) { $lq .= ",affected_file_name"; }
			if (!empty($this->affected_account_name)) { $lq .= ",affected_account_name"; }
		
		$lq .= ") VALUES (:action, :owner_id, :owner_user";

			$params = array(
							':action'		=> $this->action,
							':owner_id'		=> $this->owner_id,
							':owner_user'	=> $this->owner_user,
						);
		
			if (!empty($this->affected_file)) {			$lq .= ", :file";		$params['file'] = $this->affected_file; }
			if (!empty($this->affected_account)) {		$lq .= ", :account";	$params['account'] = $this->affected_account; }
			if (!empty($this->affected_file_name)) {	$lq .= ", :title";		$params['title'] = $this->affected_file_name; }
			if (!empty($this->affected_account_name)) {	$lq .= ", :name";		$params['name'] = $this->affected_account_name; }

		$lq .= ")";

		$this->sql_query = $dbh->prepare( $lq );
		$this->sql_query->execute( $params );
	}
}