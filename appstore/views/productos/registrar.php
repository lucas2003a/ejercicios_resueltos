<!doctype html>
<html lang="es">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>
  
  <div class="container mt-3">
    
    <form action="" autocomplete="off" id="form-producto">
      <div class="card">
        <div class="card-header bg-primary text-light">
          Registro de productos
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label for="categorias" class="form-label">Categoría</label>
            <select id="categorias" class="form-select" required>
              <option value="">Seleccione:</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="descripcion" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="precio" class="form-label">Precio</label>
              <input type="number" class="form-control text-end" id="precio" min="1" max="5000" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="garantia" class="form-label">Garantía</label>
              <input type="number" class="form-control text-end" id="garantia" min="0" max="36" placeholder="Indicar en meses" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="fotografia" class="form-label">Fotografía</label>
            <input type="file" class="form-control" id="fotografia" accept=".jpg">
          </div>
        </div>
        <div class="card-footer text-end">
          <button class="btn btn-primary btn-sm" type="submit" id="guardar">Guardar</button>
        </div>
      </div>
    </form>

  </div>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      function $(id){
        return document.querySelector(id)
      }

      //Método para traer y mostrar categorías
      function getCategorias(){
        //Datos que enviaremos al controlador
        const parametros = new FormData();
        parametros.append("operacion", "listar");

        //Conexión, el valor obtenido, el proceso, el error
        fetch(`../../controllers/categoria.controller.php`, {
          method: "POST",
          body: parametros
        })
          .then(respuesta => respuesta.json())
          .then(datos => {
            //Operaciones, proceso... (render option en <select>)
            datos.forEach(element => {
              const tagOption = document.createElement("option")
              tagOption.value = element.idcategoria
              tagOption.innerText = element.categoria 
              $("#categorias").appendChild(tagOption)
            });
          })
          .catch(e => {
            console.error(e);
          });
      }


      function productRegister(){
        const parametros = new FormData();
        parametros.append("operacion", "registrar");
        parametros.append("idcategoria", $("#categorias").value);
        parametros.append("descripcion", $("#descripcion").value);
        parametros.append("precio", $("#precio").value);
        parametros.append("garantia", $("#garantia").value);
        parametros.append("fotografia", $("#fotografia").files[0]);

        fetch(`../../controllers/producto.controller.php`, {
          method: "POST",
          body: parametros
        })
          .then(respuesta => respuesta.json())
          .then(datos => {

            if (datos.idproducto > 0){
              alert(`Producto registrado con ID: ${datos.idproducto}`)
              $("#form-producto").reset();
            }

          })
          .catch(e => {
            console.error(e);
          });
      }


      //Detener SUBMIT => evento del FORMULARIO
      //Clase   : Plantilla
      //Método  : Acción
      //Atributo: Propiedad
      //Evento  : respuesta
      $("#form-producto").addEventListener("submit", (event) => {
        event.preventDefault(); //Stop al evento

        if (confirm("¿Está seguro de guardar?")){
          productRegister();
        }
      });

      //Funciones de carga automática
      getCategorias();

    })
  </script>

</body>

</html>