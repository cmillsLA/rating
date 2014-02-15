<?php if ($user): ?>
  <a href="<?php echo $logoutUrl; ?>">Logout</a>
<?php else: ?>
  <div>
    <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
  </div>
<?php endif ?>

<!--<h3>PHP Session</h3>
<pre><?php print_r($_SESSION); ?></pre>-->

<?php if ($user): ?>
  <h3>Welcome, <?php $user_profile['name']; ?></h3>
  <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

  <!--<h3>Your User Object (/me)</h3>
  <pre><?php print_r($user_profile); ?></pre>-->
<?php else: ?>
  <strong><em>You are not Connected.</em></strong>
<?php endif ?>