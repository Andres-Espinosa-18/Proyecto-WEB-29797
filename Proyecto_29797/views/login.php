<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Proyecto 29797</title>
    <link href="style/style.css" rel="stylesheet" type="text/css">
</head>
<body class="log_in">

    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-header">
                <h2>Proyecto 29797</h2>
                <p>Introduce tus credenciales para acceder</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger">
					<?php 
						if($_GET['error'] == 'credenciales') {
							echo "Usuario o clave incorrectos.";
						} elseif($_GET['error'] == 'sesion_expirada') {
							echo "Tu sesión ha expirado.";
						} elseif($_GET['error'] == 'inactivo') {
							echo "Tu cuenta ha sido desactivada. Contacta al administrador.";
						} elseif($_GET['error'] == 'bloqueado') {
							$tiempo = isset($_GET['tiempo']) ? intval($_GET['tiempo']) : 10;
							echo "Has superado el límite de intentos. Por favor, espera <b>$tiempo segundos</b> antes de volver a intentar.";
						}
					?>
                </div>
            <?php endif; ?>

            <form action="server/login_process.php" method="POST">
                <div class="form-group">
                    <label for="user">Nombre de Usuario</label>
                    <input type="text" name="user" id="user" placeholder="Ingrese su usuario" required autofocus>
                </div>

                <div class="form-group">
                    <label for="pass">Contraseña</label>
                    <input type="password" name="pass" id="pass" placeholder="Ingrese su contraseña" required>
                </div>

                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>
        </div>
    </div>

</body>
</html>