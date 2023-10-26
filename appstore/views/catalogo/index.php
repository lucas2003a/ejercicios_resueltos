<!doctype html>
<html lang="es">

<head>
  <title>Catálogo de productos</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>

  <div class="container mt-3">

    <!-- Filtro -->
    <div class="row mb-3">
      <div class="col-12">
        <label for="" class="form-label">Seleccione una categoría</label>
        <select name="categorias" id="categorias" class="form-select">
          <option value="-1">Mostrar todas</option>
        </select>
      </div>
    </div>

    <!-- Catálogo -->
    <div class="row" id="lista-productos">

      <!-- Este datos debe ser creado de manera asíncrona -->
      <!-- <div class="col-3 mb-3">
        <div class="card" style="width: 18rem;">
          <img src="../../images/7c82367aaa47b2145abc130ac5c98b4cca13eed1.jpg" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">Audífonos</h5>
            <p class="card-text">S/ 120</p>
            <div class="d-grid">
              <a href="#" class="btn btn-sm btn-primary">Comprar</a>
            </div>
          </div>
        </div>
      </div> -->

    </div>
  </div>


  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>


  <script>
    function $(id){
      return document.querySelector(id);
    }

    function getCategorias(){

      const parametros = new FormData();
      parametros.append("operacion", "listar");

      fetch(`../../controllers/categoria.controller.php`, {
        method: "POST",
        body: parametros
      })
        .then(respuesta => respuesta.json())
        .then(datos => {
          datos.forEach(element => {
            const tagOption = document.createElement("option");
            tagOption.innerText = element.categoria;
            tagOption.value = element.idcategoria;
            $("#categorias").appendChild(tagOption)
          });
        })
        .catch(e => {
          console.error(e);
        });
    }

    function actualizarCatalogo(){
      const parametros = new FormData();
      parametros.append("operacion", "filtrarCategoria");
      parametros.append("idcategoria", $("#categorias").value);

      fetch(`../../controllers/producto.controller.php`, {
        method: "POST",
        body: parametros
      })
        .then(respuesta => respuesta.json())
        .then(datos => {
          
          if (datos.length == 0){
            $("#lista-productos").innerHTML = `<p>Pronto tendremos más novedades</p>`;  
          }else{
            $("#lista-productos").innerHTML = ``;
            datos.forEach(element => {
              //Evaluar si tiene una fotografía
              const rutaImagen = (element.fotografia == null) ? "noimagen.jpg" : element.fotografia;
  
              //Renderizado
              const nuevoItem = `
              <div class="col-3 mb-3">
                <div class="card" style="width: 100%;">
                  <img src="../../images/${rutaImagen}" class="card-img-top" alt="...">
                  <div class="card-body">
                    <h5 class="card-title">${element.descripcion}</h5>
                    <p class="card-text">S/ ${element.precio}</p>
                    <div class="d-grid">
                      <a href="#" data-idproducto="${element.idproducto}" class="btn btn-sm btn-primary">Comprar</a>
                    </div>
                  </div>
                </div>
              </div>
              `;
              $("#lista-productos").innerHTML += nuevoItem;
            });
          }

        })
        .catch(e => {
          console.error(e)
        });
    }

    $("#categorias").addEventListener("change", actualizarCatalogo);

    getCategorias();
    actualizarCatalogo();

  </script>

</body>

</html>