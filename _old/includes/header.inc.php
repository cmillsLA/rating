<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
<link href="/css/styles.css" rel="stylesheet" media="screen">


<style type="text/css">
	body { padding:20px; }
	.dNone { display:none; }
</style>

<script>
	var userId = <?php if ($user) { echo $user_profile['id']; } else { echo 0; }?>;
</script>