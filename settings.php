<?php
	include_once('functions.php');
?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <div style="float:right">
    	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_donations" />
            <input type="hidden" name="business" value="samhoamt@gmail.com" />
            <input type="hidden" name="item_name" value="SH Contextual Help" />
            <input type="hidden" name="currency_code" value="USD" />
            <input type="image" src="<?php echo WP_PLUGIN_URL; ?>/sh-contextual-help/donate_btn.gif" name="submit" alt="Make payments with payPal - it's fast, free and secure!" />
        </form>
    </div>
    <h2 class="nav-tab-wrapper"><?php sh_contextual_tabs(); ?></h2>
    <?php
		switch($_GET['tab']):
			case 'menu':
				sh_contextual_help_menu();
			break;
			case 'dashboard':
				sh_contextual_help_dashboard_widget();
			break;
			default:
				sh_contextual_help();
			break;
		endswitch;
	?>
</div>