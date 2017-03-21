<?php

require_once "core/init.php";

//$user = Database::getInstance()->query("SELECT * FROM users");

//$user = Database::getInstance()->get('users', array('username', '=', 'uniqsaqya'));

// $userInsert = Database::getInstance()->update('users', 6 ,array(
// 	'passwords' => 'passwords',
// 	'name' => 'niklesh shakya'
// ));

// $userInsert = Database::getInstance()->insert('users' ,array(
// 	'passwords' => 'passwords',
// 	'name' => 'niklesh shakya'
// ));


if(Session::exists('home')) {
	echo '<p>' .Session::flash('home'). '</p>';
}

$user = new User();

if($user->isLoggedIn()) {
?>

	<p> Hello <a href="profile.php?user=<?php echo escape($user->data()->username);?>"><?php echo escape($user->data()->username);?></a> </p>
	<ul>
		<li><a href="update.php">Update</a></li>
		<li><a href="changepassword.php">Change Password</a></li>
		<li><a href="logout.php">Log out</a></li>
	</ul>
<?php
	if($user->hasPermission('admin')) {
		echo '<p>You are an admin.</p>';
	}
} else{
	echo '<p>You need to <a href="login.php">Login</a> or register <a href="register.php">Register</a></p>';
}
?>