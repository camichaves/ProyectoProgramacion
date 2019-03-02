
<?php
    session_start();
    //Conecto la base de datos
    $usuario="root";
    $clave="";
    $bd="programacioni";
    $servidor="localhost";
    $conexion = new PDO("mysql:host=$servidor;dbname=$bd",$usuario,$clave);

    $hora = date('j-G');
    $session_id = session_id();
    $token = hash('sha256', $hora.$session_id);


    if( $_SESSION['rol']!="ad" || $_SESSION['token']!=$token ){ 

    session_destroy();
    header("location: http://localhost/proyProg1/login.php");

    die();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="ops.css">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

</head>
<body>

<?php


if (empty($_POST['opcion']) || !empty($_POST['volver'])){

    ?>
    <a href="mainadmin.php" class="btn btn-hot text-capitalize btn-xs">Menu Principal</a>
     <div class="well text-center">
          
        <form method="POST">                  
            <input type="hidden" name="opcion" value="list">
            <input class="btn btn-fresh text-uppercase btn-lg" value="Listar Usuarios" type="submit">
        </form>
        <form method="POST">
            <input type="hidden" name="opcion" value="add">
            <input class="btn btn-sky text-uppercase btn-lg" value="Agregar Usuario" type="submit">
        </form>
        <form method="POST">
            <input type="hidden" name="opcion" value="edit">
            <input class="btn btn-sunny text-uppercase btn-lg" value="Editar Usuarios" type="submit">
        </form>
        <form method="POST">
            <input type="hidden" name="opcion" value="del">
            <input class="btn btn-hot text-uppercase btn-lg" value="Borrar Usuario" type="submit">
        </form>
        
     </div>
    <?php

}else if($_POST["opcion"]=="list"){
                    $sql = "SELECT * from usuario";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                    $res = $ejecucionSQL->fetchAll();
                    $count = $ejecucionSQL->rowCount();
                    if ($count > 0) {
                        ?>
                        <section class="title-banner py-4 bg-success text-white" id="title-banner">
                            <div class="container">
                                <div class="row">
                                    <h4 class="text-center">Lista de usuarios</h4>
                                </div>
                            </div>
                        </section>
                         <table class="table">
                        <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Clave</th>
                        <th>Rol</th>
                    
                        </tr>
                        <?php
                        foreach ($res as $rs) {
                            echo "<tr>";
                            echo "<td>".$rs["id"]."</td>";
                            echo "<td>".$rs["usuario"]."</td>";
                            echo "<td>".$rs["clave"]."</td>";
                            echo "<td>".$rs["rol"]."</td>";
                            echo "</tr>";
                        } 
                        ?>
                        </table>
                        <form method="POST">
                        <input type="hidden" name="volver" value="1">
                        <input value="Volver" type="submit">
                        </form>
                        <?php 
                    }
    }else if ($_POST["opcion"]=="add"){

                    ?>
                     <form method="POST" action="opUsu.php">
                    <fieldset>
                        <legend>Agregar Usuario</legend>
                        <div class="form-group">
                            <label for="InputUser">Usuario</label>
                            <input type="text"name="usuario" class="form-control" id="InputUser" aria-describedby="emailHelp" placeholder="Ingrese Usuario">
                        </div>
                        <div class="form-group">
                            <label for="InputClave">Clave</label>
                            <input type="password" name="pass" class="form-control" id="InputClave" placeholder="Ingrese Clave">
                            <input type="hidden" name="opcion" value="add">
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect1">Rol</label>
                            <select name="rol" class="form-control" id="exampleSelect1">
                                <option>user</option>
                                <option>admin</option>
                            </select>
                            </div>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </fieldset>
                    </form>
                    
                    <?php
                    $sql ="";
                    $ejecucionSQL="";

                    ?>
                    
                        <form method="POST">
                        <input type="hidden" name="volver" value="1">
                        <input value="Volver" type="submit">
                        </form>
                     <?php   

                    if(!empty($_POST['usuario'])) {
                        
                        $sql = "INSERT INTO `usuario` (`usuario`, `clave`, `rol`) VALUES ('" . $_POST['usuario'] . "', '" . hash('sha256', strtoupper($_POST['pass'] . "adminxd")) . "', '" . $_POST['rol'] . "');";
                        $ejecucionSQL = $conexion->prepare($sql);
                        $ejecucionSQL->execute();
                        echo "<h4>Se ha creado el usuario " . $_POST['usuario'] . "</h4>";
                       
                    }
                     

                }else if ($_POST['opcion'] == "del"){?>
                    <form method="POST" action="opUsu.php">
                        <p>Ingrese el ID del usuario a eliminar: </p>
                        <input type="text" name="idborrar">
                        <input type="hidden" name="opcion" value="del">
                        <br><br>
                        <input type="submit">
                    </form>
                <?php
                    $sql ="";
                    $ejecucionSQL="";


                    ?>
                    </table>
                        <form method="POST">
                        <input type="hidden" name="volver" value="1">
                        <input value="Volver" type="submit">
                        </form>
                     <?php 


                if (!empty($_POST['idborrar'])){
                    $sql = "DELETE FROM `programacioni`.`usuario` WHERE `id` = '".$_POST['idborrar']."';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                    echo "<h4>Se ha borrado el usuario con id " . $_POST['idborrar'] . "</h4>";
                    
                    

                }




            }else if($_POST['opcion'] == "edit") {
                if(!empty($_POST['editarUsuarioOK'])) {
                    $sql = "UPDATE `usuario` SET `usuario` = '".$_POST['usuario']."' , `clave` = '".hash('sha256', strtoupper($_POST['pass'] . "adminxd"))."' WHERE `id` = '".$_POST['lista']."';";
                    $ejecucionSQL = $conexion->prepare($sql);
                    $ejecucionSQL->execute();
                    echo "<h4>Se ha editado el usuario " . $_POST['usuario'] . "</h4>";

                }
                $sql = "SELECT * FROM `usuario`";
                $ejecucionSQL = $conexion->prepare($sql);
                $ejecucionSQL->execute();
                $res = $ejecucionSQL->fetchAll();
                ?>
                <form method="POST" action="opUsu.php">
                <select name="lista">
                    <?php
                    foreach ($res as $rs) {
                        ?>
                        <option value="<?php echo $rs["id"]; ?>"><?php echo $rs["id"] . " " . $rs["usuario"]; ?></option>
                        <?php
                    } ?>
                </select>
                <input type="hidden" name="opcion" value="edit">
                <input type="submit" value="editar">
                </form><?php
                foreach ($res as $rs) {
                    if (!empty($_POST['lista'])) {
                        if ($_POST['lista'] == $rs['id']) {
                            echo "<form method='POST' action='opUsu.php'>";
                            echo "<input type='text' name='usuario' placeholder='" . $rs['usuario'] . "'>";
                            echo "<input type='password' name='pass' placeholder='clave'>";
                            echo "<input type='hidden' name='opcion' value='edit'>";
                            echo "<input type='hidden' name='editarUsuarioOK' value='editarUsuarioOK'>";
                            echo "<input type='hidden' name='lista' value='".$_POST['lista']."'>";
                            echo "<input type='submit' value='OK'>";
                            echo "</form>";
                        }
                    }
                }
                ?>
                </table>
                <form method="POST">
                <input type="hidden" name="volver" value="1">
                <input value="Volver" type="submit">
                </form>
             <?php 
            
            }
            ?>
            
      
</body>
</html>