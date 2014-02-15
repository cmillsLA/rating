<?php $page = 'profile'; ?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/facebook.inc.php'); ?>
<?php
if(!$user) {
	header('Location: /');
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Rating Site</title>
    
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/header.inc.php'); ?>
		
  </head>
  <body>
		<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/topbar.inc.php'); ?>

		<div class="container">
			<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/nav.inc.php'); ?>
		</div>
		
		<div class="well">
    	<h2><?php echo $user_profile['name']; ?></h2>
			<h2>Your Reviews</h2>
			<div id="userInfo"><p>Loading User Information...</p></div>
			
		</div>

    <?php include($_SERVER['DOCUMENT_ROOT'].'/includes/scripts.inc.php'); ?>

		<script type="text/javascript">
			$('#rateTabs a:first').tab('show'); // Select first tab
		</script>

  </body>
</html>