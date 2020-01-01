<?php

require_once('pinterest-api-settings.php');

?>
<html>
<head></head>

<body>
	<a href="<?= 'https://api.pinterest.com/oauth/?client_id=' . PINTEREST_APPLICATION_ID . '&redirect_uri=' . urlencode(PINTEREST_REDIRECT_URI) . '&response_type=code&scope=read_public' ?>">Login with Pinterest</a>
</body>

</html>