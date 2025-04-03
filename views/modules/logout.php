<?php
session_unset();
// session_regenerate_id();
session_destroy();

echo '<script>

	window.location = "login";

</script>';