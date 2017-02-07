<?php
$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

header('Location: intent://'.$actual_link.'/#Intent;scheme=http;package=com.sega.vimarket;S.browser_fallback_url=https%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.sega.vimarket&%26referrer%3Dkinlan;end');
die();
?>

