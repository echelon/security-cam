<div id="navbar">
	<a href="/">Main</a> |
	<a href="/?gallery">Movement Detection Gallery</a> |
	<a href="/?configure">Panorama View</a> |
	<a href="/?configure">Configure</a> |
	<?php echo $auth->getUser()->getUsername(); ?>
		(<a href="/?logout=1">logout</a>)
	<input type="checkbox" name="local" 
		   onclick="checkboxLocal();" id="checkbox_local"
		<?php echo $user->isLocal()? "checked=\"checked\"" : ""; ?> /> local 
	<input type="checkbox" name="firewall" 
		   onclick="checkboxFirewall();" id="checkbox_firewall"
		<?php echo $user->isFirewall()? "checked=\"checked\"" : ""; ?> /> firewall 
	<hr />
</div>
