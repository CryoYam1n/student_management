<?php
echo password_hash('admin321', PASSWORD_DEFAULT);
?>


INSERT INTO users (username, password, role, full_name, email)
VALUES ('newuser', 'admin321', 'admin', 'New Admin', 'newadmin@example.com');