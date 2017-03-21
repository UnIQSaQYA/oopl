<?php
require_once "core/init.php";

$user = new User();
if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();

		$validation = $validate->check($_POST, array(
			'password_current' => array(
				'required' => true,
				'min' => 6
			),
			'password' => array(
				'required' => true,
				'min' => 6,
			),
			'password_again' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'password',
			),
		));

		if($validation->passed()) {
			if(Hash::make(Input::get('password_current'), $user->data()->salt) != $user->data()->passwords) {
				echo 'Password Incorrect';
			} else {
				$salt = Hash::salt(32);
				$user->update(array(
					'password' => Input::get('password', $salt),
					'salt' => $salt,
				));
				Session::flash('home', 'Password has been successfully');
				Redirect::to('index.php');
			}
		}else {
			foreach($validation->errors() as $error) {
				echo $error, '<br>';
			}
		}
	}
}
?>

<form action="" method="POST">
	<div class="field">
		<label for="password_current">Current password:</label>
		<input type="password" name="password_current" id="password_current" autocomplete="off">
	</div>

	<div class="password">
		<label for="password">New password:</label>
		<input type="password" name="password" id="password">
	</div>

	<div class="password">
		<label for="password_again">Enter New password again:</label>
		<input type="password" name="password_again" id="password_again">
	</div>

	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="Change Password">
</form>