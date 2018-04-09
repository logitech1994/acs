<?php

$load_scripts	= array(
						'flot',
					); 

$allowed_levels = array(9,8,7);
require_once('sys.includes.php');
$page_title = __('Бош саҳифа', 'cftp_admin');

$active_nav = 'dashboard';

$body_class = array('dashboard', 'home', 'hide_title');

include('header.php');

define('CAN_INCLUDE_FILES', true);

$log_allowed = array(9);

$show_log = false;
$sys_info = false;

if (in_session_or_cookies($log_allowed)) {
	$show_log = true;
	$sys_info = true;
}
?>
	<div class="col-sm-8">
		<div class="row">
			<div class="col-sm-12 container_widget_statistics">
				<?php include(WIDGETS_FOLDER.'statistics.php'); ?>
			</div>
		</div>
		
	</div>
		
	<?php
		if ( $show_log == true ) {
	?>
			<div class="col-sm-4 container_widget_actions_log">
				<?php include(WIDGETS_FOLDER.'actions-log.php'); ?>
			</div>
	<?php
		}
	?>

	<script type="text/javascript">
		$(document).ready(function(){
			
			function ajax_widget_statistics( days ) {
				var target = $('.statistics_graph');
				target.html('<div class="loading-graph">'+
								'<img src="<?php echo BASE_URI; ?>/img/ajax-loader.gif" alt="Loading" />'+
							'</div>'
						);
				$.ajax({
					url: '<?php echo WIDGETS_URL; ?>statistics.php',
					data: { days:days, ajax_call:true },
					success: function(response){
								target.html(response);
							},
					cache:false
				});					
			}

			$('.stats_days').click(function(e) {
				e.preventDefault();

				if ($(this).hasClass('btn-inverse')) {
					return false;
				}
				else {
					var days = $(this).data('days');
					$('.stats_days').removeClass('btn-inverse');
					$(this).addClass('btn-inverse');
					ajax_widget_statistics(days);
				}
			});
	
			ajax_widget_statistics(15);

			
			function ajax_widget_log( action ) {
				var target = $('.activities_log');
				target.html('<div class="loading-graph">'+
								'<img src="<?php echo BASE_URI; ?>/img/ajax-loader.gif" alt="Loading" />'+
							'</div>'
						);
				$.ajax({
					url: '<?php echo WIDGETS_URL; ?>actions-log.php',
					data: { action:action, ajax_call:true },
					success: function(response){
								target.html(response);
							},
					cache:false
				});					
			}

			
			$('.log_action').click(function(e) {
				e.preventDefault();

				if ($(this).hasClass('btn-inverse')) {
					return false;
				}
				else {
					var action = $(this).data('action');
					$('.log_action').removeClass('btn-inverse');
					$(this).addClass('btn-inverse');
					ajax_widget_log(action);
				}
			});					

			ajax_widget_log();
		});
	</script>

<?php
	include('footer.php');