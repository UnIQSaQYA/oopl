<?php
require_once 'core/init.php';

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true,
				'min' => 2,
				'max' => 20,
				'unique' => 'users'
			),
			'password' => array(
				'required' => true,
				'min' => 6,
			),
			'password_again' => array(
				'required' => true,
				'matches' => 'password',
			),
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
			)
		));

		if($validation->passed()) {
			$user = new User();

			$salt = Hash::salt(32);
			try {
				$user->create(array(
					'username' => Input::get('username'),
					'passwords' => Hash::make(Input::get('password'), $salt),
					'salt' => $salt,
					'name' => Input::get('name'),
					'joined' => date('Y-m-d H:i:s'),
					'user_group' => 1,
				));

				Session::flash('home', 'You have been registered and can now login!');
				Redirect::to('index.php');
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}else {
			foreach($validation->errors() as $error) {
				echo $error, '<br/>';
			}
		}
	}
}
?>
<form action="" method="POST">
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
	</div>

	<div class="password">
		<label for="password">Choose a password:</label>
		<input type="password" name="password" id="password">
	</div>

	<div class="password">
		<label for="password_again">Enter a password again:</label>
		<input type="password" name="password_again" id="password_again">
	</div>

	<div class="name">
		<label for="name">Name:</label>
		<input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>">
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" name="Register">
</form>