<?php
$hashedPassword = password_hash("admin@321$", PASSWORD_BCRYPT);
echo $hashedPassword;
?>
