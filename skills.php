<?php
	session_start();
	error_reporting(0);
	$varsesion = $_SESSION['usuario'];

	if (isset($varsesion)){
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Skills</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="css/template-admin.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/unid-ico.ico">
</head>

<body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Skills</a>
        <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="includes/log_out.php">Sign out</a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php
                include("includes/navbar.php");
            ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" id="main">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Skills</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-sm btn-outline-danger cancelar">Cancelar</button>
                            <button type="button" class="btn btn-sm btn-outline-success"
                                id="nuevo_registro">Nuevo</button>
                        </div>
                    </div>
                </div>
                <h2 id="h2-title">Consultar Skills</h2>
                <div class="table-responsive view" id="show_data">
                    <table class="table table-striped table-sm" id="list-usuarios">
                        <thead>
                            <tr>
                                <th>Titulo</th>
                                <th>Subtitulo</th>
                                <th>Skill</th>
                                <th>Porcentaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="insert_data" class="view">
                    <form action="#" id="form_data" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="titulo">Titulo</label>
                                    <input type="text" id="inputTitulo" name="titulo" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="subtitle">Subtitulo</label>
                                    <input type="text" id="inputSubtitle" name="subtitle" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="skill">Skill</label>
                                    <input type="text" id="inputSkill" name="skill" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="parcentaje">Porcentaje %</label>
                                    <input type="range" id = "inputPorcentaje" name="porcentaje" min="0" max="100" value="50" step="1" onchange="rangevalue.value=value" class="form-control-range"/>
                                    <output id="rangevalue"></output>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-success" id="guardar_datos">Guardar</button>
                            </div>
                        </div>
                        <div class="mensaje">
                            <span class="alert alert-danger" id="error" style='display:none;'></span>
                            <span class="alert alert-success" id="success" style='display:none;'></span>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        //FUNCION PARA CAMBIAR VISTA
        function change_view(vista = 'show_data') {
            $("#main").find(".view").each(function () {
                $(this).slideUp('fast');
                let id = $(this).attr("id");
                if (vista == id) {
                    $(this).slideDown(300);
                }
            });
        }
        //FUNCION PARA CONSULTAR A LA BD
        function consultar() {
            let obj = {
                "accion": "consultar_skills"
            };
            $.post("includes/_funciones.php", obj, function (respuesta) {
                let template = ``;
                $.each(respuesta, function (i, e) {
                    template +=
                        `
          <tr>
          <td>${e.titulo}</td>
          <td>${e.subtitulo}</td>
          <td>${e.skill}</td>
          <td>${e.skill_percentage}%</td>
          <td>
          <a href="#" data-id="${e.id_skill}" class="editar_skill">Editar</a>
          <a href="#" data-id="${e.id_skill}" class="eliminar_skill">Eliminar</a>
          </td>
          </tr>
          `;
                });
                $("#list-usuarios tbody").html(template);
            }, "JSON");
        }
        //FUNCION PARA CAMBIAR VISTA -> FORMULARIO
        $("#nuevo_registro").click(function () {
            change_view('insert_data');
            $("#h2-title").text("Insertar Skill");
            $("#guardar_datos").text("Guardar").data("editar", 0);
            $("#preview").html("");
            $('#ruta').attr('value', '');
            $("#form_data")[0].reset();
        });
        //FUNCION PARA INSERTAR DATOS A LA BD
        $("#guardar_datos").click(function () {
            let titulo = $("#inputTitulo").val();
            let subtitulo = $("#inputSubtitle").val();
            let skill = $("#inputSkill").val();
            let porcentaje = $("#inputPorcentaje").val();
            let obj = {
                "accion": "insertar_skills",
                "titulo": titulo,
                "subtitulo": subtitulo,
                "skill" : skill,
                "porcentaje": porcentaje
            }
            $("#form_data").find("input").each(function () {
                $(this).removeClass("has-error");
                if ($(this).val() != "") {
                    obj[$(this).prop("name")] = $(this).val();
                } else {
                    $(this).addClass("has-error");
                    return false;
                }
            });
            if ($(this).data("editar") == 1) {
                obj["accion"] = "editar_skills";
                obj["id"] = $(this).data('id');
            }
            $.post("includes/_funciones.php", obj, function (v) {
                if (v == 0) {
                    $("#error").html("Campos vacios").fadeIn();
                }
                if (v == 1) {
                    alert("Skill insertado");
                    location.reload();
                }
                if (v == 2) {
                    $("#error").html("Favor de ingresar un titulo").fadeIn();
                }
                if (v == 3) {
                    $("#error").html("Favor de ingresar un subtitulo").fadeIn();
                }
                if (v == 4) {
                    $("#error").html("Favor de ingresar un skill").fadeIn();
                }
                if (v == 5) {
                    $("#error").html("Favor de ingresar un porcentaje").fadeIn();
                }
                if (v == 6) {
                    alert("Skill editado");
                    location.reload();
                }
                if (v == 7) {
                    alert("Se produjo en error, intente nuevamente");
                    location.reload();                    
                }
            });
        });
        //FUNCION PARA ELIMINAR 1 REGISTRO EN LA BD
        $("#main").on("click", ".eliminar_skill", function (e) {
            e.preventDefault();
            let confirmacion = confirm('¿Desea eliminar este skill?');
            if (confirmacion) {
                let id = $(this).data('id'),
                    obj = {
                        "accion": "eliminar_skills",
                        "id": id
                    };
                $.post("includes/_funciones.php", obj, function (respuesta) {
                    alert(respuesta);
                    consultar();
                });
            } else {
                alert('El registro no se ha eliminado');
            }
        });
        //FUNCION PARA CONSULTAR REGISTRO A EDITAR
        $("#list-usuarios").on("click", ".editar_skill", function (e) {
            e.preventDefault();
            let id = $(this).data('id'),
                obj = {
                    "accion": "consultar_registro_skills",
                    "id": id
                };
            $("#form_data")[0].reset();
            change_view('insert_data');
            $("#h2-title").text("Editar Skill");
            $("#guardar_datos").text("Editar").data("editar", 1).data("id", id);
            $.post("includes/_funciones.php", obj, function (r) {
                $("#inputTitulo").val(r.titulo);
                $("#inputSubtitle").val(r.subtitulo);
                $("#inputSkill").val(r.skill);
                $("#inputPorcentaje").val(r.skill_percentage);
            }, "JSON");
        });
        //FUNCION DESHABILITAR ATRAS EN EL NAVEGADOR
        function deshabilitaRetroceso() {
            window.location.hash = "no-back-button";
            window.location.hash = "Again-No-back-button" //chrome
            window.onhashchange = function () {
                window.location.hash = "no-back-button";
            }
        }
        //CARGAR FUNCIONES CUANDO EL DOCUMENTO ESTE LISTO
        $(document).ready(function () {
            consultar();
            change_view();
            deshabilitaRetroceso();
        });
        //FUNCION PARA GUARDAR IMAGENES
        $("#foto").on("change", function (e) {
            let formDatos = new FormData($("#form_data")[0]);
            formDatos.append("accion", "carga_foto");
            $.ajax({
                url: "includes/_funciones.php",
                type: "POST",
                data: formDatos,
                contentType: false,
                processData: false,
                success: function (datos) {
                    let respuesta = JSON.parse(datos);
                    if (respuesta.status == 0) {
                        alert("No se cargó la foto");
                    }
                    let template =
                        `
          <img src="${respuesta.archivo}" class="img-thumbnail" width="200" height="200"/>
          `;
                    $("#ruta").val(respuesta.archivo);
                    $("#preview").html(template);
                }
            });
        });
        //BOTON CANCELAR
        $("#main").find(".cancelar").click(function () {
            change_view();
            $("#form_data")[0].reset();
            $("#form_data").find("input").each(function () {
                $(this).removeClass("has-error");
            });
            $("#error").hide();
            $("#success").hide();
            $("#h2-title").text("Consultar Skills");
            $("#preview").html("");
            if ($("#guardar_datos").data("editar") == 1) {
                $("#guardar_datos").text("Guardar").data("editar", 0);
                consultar();
            }
        });
    </script>
</body>

</html>
<?php
	}else{
		header("Location:index.php");
	}
?>