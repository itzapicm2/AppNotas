<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<link rel="stylesheet" href="/media/node_modules/bootstrap/dist/css/bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="<?= base_url('media/fontawesome/css/all.min.css') ?>">
<style>
    .btn--total-radius{
        border-radius: 50%;
        min-width: 3.5rem;
        height: 3.5rem;
        width: 3.5rem;
        font-size: 1.5rem;
    }
    .btn--total-radius:hover{
        background-color: #00a56f;
    }
    .btn--fixed{
        position: fixed;
        bottom:2rem;
        right:2rem;
        z-index: 5;
    }
    .bg--purple{
        background-color: #713BA7;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h4>Notas</h4>
            <div id="contenedor-notas" class="row">

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editar" tabindex="-1" role="dialog" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModal">Editar Nota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formNotas">
                    <div class="form-group">
                        <input type="hidden" id="idNota" name="id">
                        <label for="recipient-name" class="col-form-label">Titulo:</label>
                        <input type="text" class="form-control" id="tituloNota" name="titulo">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Contenido:</label>
                        <textarea class="form-control" id="contenidoNota" name="contenido"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
            </div>
        </div>
    </div>
</div>

<button type="button" class="btn bg--purple text-white btn--total-radius btn--fixed" id="agregarNota">
    <i class="fas fa-plus"></i>
</button>
<script>
    'use strict';
    const contenedorDeNotas = document.getElementById('contenedor-notas');
    const notaBase = document.createElement('div');
    const tituloNota = document.getElementById('tituloNota');
    const contenidoNota = document.getElementById('contenidoNota');
    const idNota = document.getElementById('idNota');
    const tituloModal = document.getElementById('tituloModal');
    notaBase.classList.add('card', 'col-4', "m-2");
    notaBase.innerHTML = `
        <div class="mt-3">
            <div class="row">
                <div class="col-9">
                    <h4 class="card-title" data-titulo></h5>
                </div>
                <div class="col-3 text-right">
                    <button type="button" class="btn btn-info" data-id>
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <p class="card-text" data-contenido></p>
            <button class="btn btn-danger">Borrar</button>
        </div>`;


    let notas = [];

    fetch('/read')
    .then(function(response){
        return response.json();
    })
    .then(function(respuesta){
        console.log(respuesta);
        notas = respuesta;
        pintor(notas);

    });

    function pintor(notas){
        contenedorDeNotas.innerHTML = "";

        notas.forEach(nota => {
           const x = notaBase.cloneNode(true);
           //console.log(x);
            const idContainer = x.querySelector('[data-id]');
            const titulo = x.querySelector('[data-titulo]');
            const contenido = x.querySelector('[data-contenido]');
            idContainer.dataset['id'] = nota.id;
            idContainer.addEventListener("click", function(){
                idNota.value = nota.id;
                tituloNota.value = nota.titulo;
                contenidoNota.value = nota.contenido;
                tituloModal.innerText = 'Editar Nota';
                $('#editar').modal('show');
            });
            titulo.innerText = nota.titulo;
            contenido.innerText = nota.contenido;
            x.querySelector('button.btn-danger').addEventListener("click", function () {
                if(confirm("seguro?")){
                    fetch('destroy?id=' + nota.id)
                    .then(function (response){
                        return response.json();
                    })
                    .then(function (response){
                        pintor(response);

                    })
                }
            })

            contenedorDeNotas.append(x);
        })

    }

    document.getElementById('btnGuardar').addEventListener("click", function () {
        const datos = new FormData(document.getElementById('formNotas'));
        fetch('write', {
            method: 'POST',
            body: datos
        }).then(function(response){
            return response.json();
        }).then(function(response){
            pintor(response);
            $('#editar').modal('hide');
        });
    });

    document.getElementById('agregarNota').addEventListener("click", function () {
        idNota.value = 0;
        tituloNota.value = "";
        contenidoNota.value = "";
        tituloModal.innerText = 'Nueva Nota';
        $('#editar').modal('show');
    })

</script>
