<?php
class Login {
	//Autentica al usuario comparando su username y password.
    public function autenticar($username, $password, $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        //Verifica si la contraseña ingresada es correcta.
        if ($user && password_verify($password, $user['password'])) {
            return $user['id'];
        } else {
            return false;
        }
    }
}
?>
