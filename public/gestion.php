<?php
session_start();
if (!isset($_SESSION['perfil'])) {
    header("Location:index.php");
    die();
}
require_once __DIR__ . "/../vendor/autoload.php";

use App\Articulos;

$email = $_SESSION['email'];
$perfil = $_SESSION['perfil'];
$datos = Articulos::readAll();
if (isset($_POST['delete'])) {
    if ($_SESSION['perfil'] != 'admin') {
        http_response_code(403);
        die("Forbidden!!");
    }
    Articulos::borrar($_POST['id']);
    $_SESSION['mensaje'] = "Artículo borrado con éxito";
    header("Location:gestion.php");
    die();
}

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
    <title>Gestion</title>
</head>

<body>
    <?php include __DIR__ . "/../layouts/nav.htm" ?>

    <div class="py-12 mt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-row-reverse my-4">
                <a href="nuevo.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-add"></i> Nuevo
                </a>
            </div>
            <?php
            if ($datos) {
            ?>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Descripción
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Precio
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Stock
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($datos as $item) {
                                echo <<< TXT
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {$item->nombre}
                                </th>
                                <td class="px-6 py-4">
                                {$item->descripcion}
                                </td>
                                <td class="px-6 py-4">
                                {$item->precio} (€)
                                </td>
                                <td class="px-6 py-4">
                                {$item->stock} unds.
                                </td>
                                <td class="px-6 py-4">
                                    <form action='gestion.php' method="POST">
                                    <input type="hidden" name='id' value="{$item->id}" />
                                    <a href="edit.php?id={$item->id}"><i class="fas fa-edit text-yellow-400"></i></a>
                                    <button type='submit' name='delete'><i class="ml-2 fas fa-trash text-red-600"></i></button>
                                    </form>
                                </td>
                            </tr>
                            TXT;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else {
                echo "<p class='my-2 px-2 py-2 mx-2 rounded-xl text-gray-200 bg-gray-800'>
                    Actualmente no hay ningún artículo en nuestra base de datos.
                    </p>";
            }
            ?>

        </div>
    </div>
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo <<<TXT
                <script>
                Swal.fire({
                    icon: 'success',
                    title: '{$_SESSION['mensaje']}',
                    showConfirmButton: false,
                    timer: 1500
                  })
                  </script>
                TXT;
        unset($_SESSION['mensaje']);
    }
    ?>
</body>

</html>