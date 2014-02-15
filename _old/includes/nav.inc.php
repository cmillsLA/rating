<ul class="nav nav-pills">
	<li<?php if($page == 'index') { echo ' class="active"'; } ?>><a href="/">Home</a></li>
 	<li<?php if($page == 'add') { echo ' class="active"'; } ?>><a href="/add.php">Add a Property</a></li>
	<?php if ($user) { ?>
	<li id="navProfile" <?php if($page == 'profile') { echo ' class="active"'; } ?>><a href="/profile.php">Profile</a></li>
	<?php } ?>
</ul>