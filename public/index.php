<?php
session_start();
if(isset($_SESSION['perfil'])){
    header("Location:gestion.php");
    die();
}
require_once __DIR__ . "./../vendor/autoload.php";

use App\Users;

function pintarErrores(string $nombre): void
{
    if (isset($_SESSION[$nombre])) {
        echo "<p class='text-sm text-red-700 italic'>{$_SESSION[$nombre]}</p>";
        unset($_SESSION[$nombre]);
    }
}

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);
    $errores = false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores = true;
        $_SESSION['err_email'] = "*** Debe introducir un email válido";
    }
    if (strlen($pass) < 6) {
        $errores = true;
        $_SESSION['err_pass'] = "*** La contraseña debe tener al menos 6 caracteres";
    }
    if ($errores) {
        header("Location:index.php");
        die();
    }
    $valor = Users::comprobarLogin($email, $pass);

    switch ($valor) {
        case 0:
            $_SESSION['err_val'] = "*** Error de validación";
            header("Location:index.php");
            die();
        case 1:
            $_SESSION['perfil'] = 'normal';
            $_SESSION['email'] = $email;
            break;
        case 2:
            $_SESSION['perfil'] = 'admin';
            $_SESSION['email'] = $email;
    }
    header("Location:gestion.php");
    die();
} else {

?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Tailwind css -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!--Sweet Alert 2-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- FontAwsome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>Document</title>
    </head>

    <body>
        <section class="bg-gray-50 dark:bg-gray-900 px-6 py-8 mx-auto rounded-lg shadow-lg w-3/4 mt-8">
            <?php
            pintarErrores('err_val');
            ?>
            <div class="flex flex-col items-center justify-center">
                <div" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                    <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
                    DAW (DWES)
            </div>
            <div class="w-3/4 bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700 mx-auto">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Sign in to your account
                    </h1>
                    <form class="space-y-4 md:space-y-6" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                            <input type="text" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required="">
                            <?php
                            pintarErrores('err_email');
                            ?>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="pass" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                            <?php
                            pintarErrores('err_pass');
                            ?>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="w-full text-white bg-blue-300 hover:bg-blue-200 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Sign in</button>

                    </form>
                </div>
            </div>
            </div>
        </section>
    </body>

    </html>
<?php } ?>