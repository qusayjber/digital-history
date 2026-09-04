<?php
// logout.php
// Digital History - Logout

require_once 'includes/config.php';

logoutUser();
redirect('index.php');
?>