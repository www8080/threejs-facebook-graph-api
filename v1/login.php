<?php
require_once  '../vendor/php-graph-sdk/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '{783263342388998}',
  'app_secret' => '{2a475f11d357f7752f34c10f7107ccb0}',
  'default_graph_version' => 'v10.0',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://127.0.0.1/fb-callback.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
?>