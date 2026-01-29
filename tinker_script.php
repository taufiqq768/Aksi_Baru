$user = App\Models\User::find(12);
$user->user_password = bcrypt('123456');
$user->save();
echo "Password updated for: " . $user->user_nik;
