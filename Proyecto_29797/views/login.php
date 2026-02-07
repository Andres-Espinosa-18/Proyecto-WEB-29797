<div class="login-wrapper">
    <div class="login-box">
        <div class="login-header">
            <h2>Proyecto 29797</h2>
            <p>Introduce tus credenciales para acceder</p>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    if($_GET['error'] == 'credenciales') echo "Usuario o clave incorrectos.";
                    if($_GET['error'] == 'sesion_expirada') echo "Tu sesión ha expirado.";
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

<style>
/* Estilos específicos para la pantalla de login que no afectan al resto del sistema */
.login-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
}

.login-box {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.login-header h2 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    font-size: 0.9rem;
    border: 1px solid #f5c6cb;
}

.btn-login {
    width: 100%;
    background-color: #3498db;
    color: white;
    padding: 12px;
    font-size: 1rem;
    margin-top: 10px;
}

.btn-login:hover {
    background-color: #2980b9;
}
</style>