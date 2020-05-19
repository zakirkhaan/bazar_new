<?php
$encrypted_password = password_hash("your-password", PASSWORD_DEFAULT);
echo "<script> alert('$encrypted_password'); </script>";
?>