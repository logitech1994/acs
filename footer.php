<a href="http://190.44.127.51/chat/mobile/index.php" target="_top" class="float">
<i class="fa fa-envelope my-float" ></i>
</a>
<div class="label-container">
<div class="label-text">Чат</div>
<i class="fa fa-play label-arrow"></i>
</div>



					</div> <!-- row -->
				</div> <!-- container-fluid -->

				<?php
				/**
				 * Footer for the backend. Outputs the default mark up and
				 * information generated on functions.php.
				 *
				 * @package ProjectSend
				 */
					default_footer_info();
					
					load_js_files();
				?>
			</div> <!-- main_content -->
		</div> <!-- container-custom -->
<style>
			
			.label-container{
	position:fixed;
	bottom:48px;
	right:105px;
	display:table;
	visibility: hidden;
}

.label-text{
	color:#FFF;
	background:rgba(51,51,51,0.5);
	display:table-cell;
	vertical-align:middle;
	padding:10px;
	border-radius:3px;
}

.label-arrow{
	display:table-cell;
	vertical-align:middle;
	color:#333;
	opacity:0.5;
}

.float{
	position:fixed;
	width:60px;
	height:60px;
	bottom:40px;
	right:40px;
	background-color:#06C;
	color:#FFF;
	border-radius:50px;
	text-align:center;
	box-shadow: 2px 2px 3px #999;
}

.my-float{
	font-size:24px;
	margin-top:18px;
}

a.float + div.label-container {
  visibility: hidden;
  opacity: 0;
  transition: visibility 0s, opacity 0.5s ease;
}

a.float:hover + div.label-container{
  visibility: visible;
  opacity: 1;
}
		</style>
	</body>
</html>
<?php
	if ( DEBUG === true ) {
		echo "\n" . '<!-- DEBUG INFORMATION' . "\n";
		// Print the total count of queries made by PDO
		_e('Бажарилган сўровлар','cftp_admin'); echo ': ' . $dbh->GetCount();
		echo "\n" . '-->' . "\n" ;
	}

	ob_end_flush();