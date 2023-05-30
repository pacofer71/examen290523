<?php
session_start();
if (!isset($_SESSION['perfil'])) {
    header("Location:index.php");
    die();
}
if (!isset($_GET['id'])) {
    header("Location:gestion.php");
    die();
}
$id = $_GET['id'];
require_once __DIR__ . "/../vendor/autoload.php";

use App\Articulos;

function pintarErrores(string $nombre): void
{
    if (isset($_SESSION[$nombre])) {
        echo "<p class='text-sm text-red-700 italic mt-2'>*** {$_SESSION[$nombre]}</p>";
        unset($_SESSION[$nombre]);
    }
}

$articulo = Articulos::read($id);


if (isset($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = (float) trim($_POST['precio']);
    $stock = (int) trim($_POST['stock']);
    $errores = false;
    if (strlen($nombre) < 3) {
        $_SESSION['err_nombre'] = "El nombre debe conterner al menos 3 caracteres!";
        $errores = true;
    } else {
        if (Articulos::existeNombre($nombre, $id)) {
            $_SESSION['err_nombre'] = "Ese nombre de artículo ya está registrado!";
            $errores = true;
        }
    }
    if (strlen($descripcion) < 10) {
        $_SESSION['err_descripcion'] = "La descripción debe conterner al menos 10 caracteres!";
        $errores = true;
    }
    if (!is_numeric($precio) || ($precio <= 0 || $precio >= 10000)) {
        $_SESSION['err_precio'] = "El precio debe estar entre 0 y 9999.99 !";
        $errores = true;
    }
    if (!is_int($stock) || $stock <= 0) {
        $_SESSION['err_stock'] = "El stock debe ser mayor que 0 !";
        $errores = true;
    }
    if ($errores) {
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
        die();
    }
    //Si no hay errores crearemos el articulo
    (new Articulos)
        ->setNombre($nombre)
        ->setDescripcion($descripcion)
        ->setPrecio($precio)
        ->setStock($stock)
        ->update($id);
    $_SESSION['mensaje'] = "Artículo edirado con éxito";
    header("Location:gestion.php");
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
        <title>editar</title>
    </head>

    <body style="background-color:darksalmon">
        <?php include __DIR__ . "/../layouts/nav.htm" ?>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="px-2 py-4 rounded-xl bg-gray-200 shadow-xl w-1/2 mx-auto mt-8">
                    <form action="edit.php?id=<?php echo $id ?>" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                                Nombre
                            </label>
                            <input value="<?php echo $articulo->nombre; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" type="text" placeholder="Nombre" name="nombre">
                            <?php
                            pintarErrores('err_nombre');
                            ?>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="desc">Descripción</label>
                            <textarea rows='5' name="descripcion" id="desc" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $articulo->descripcion; ?></textarea>
                            <?php
                            pintarErrores('err_descripcion');
                            ?>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="precio">
                                Precio (€)
                            </label>
                            <input value="<?php echo $articulo->precio; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="precio" type="number" placeholder="Precio" name="precio" step="0.01" min='0.00' max='9999.99'>
                            <?php
                            pintarErrores('err_precio');
                            ?>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="stock">
                                Stock
                            </label>
                            <input value="<?php echo $articulo->stock; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="stock" type="number" placeholder="Stock" name="stock" min='0'>

                            <?php
                            pintarErrores('err_stock');
                            ?>
                        </div>
                        <div class="flex flex-row-reverse">
                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit">
                                <i class="fas fa-edit mr-2"></i>EDITAR
                            </button>
                            <a href="gestion.php" class="mr-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-xmark mr-2"></i>CANCELAR
                            </a>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php } ?>