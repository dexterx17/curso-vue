<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Categorías
                    <button type="button" @click="abrirModal('categoria','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="query" @keyup.enter="listarCategoria(1)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="categoria in arrayCategoria" :key="categoria.id">
                                <td>
                                    <button type="button" @click="abrirModal('categoria','actualizar', categoria)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="categoria.condicion">
                                        <button type="button" @click="desactivarCategoria(categoria.id)" class="btn btn-danger btn-sm">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="activarCategoria(categoria.id)" class="btn btn-info btn-sm">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="categoria.nombre"></td>
                                <td v-text="categoria.descripcion"></td>
                                <td>
                                    <span v-if="categoria.condicion" class="badge badge-success">Activo</span>
                                    <span v-else class="badge badge-danger">Inactivo</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page >1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page-1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page==isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page+1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" :class="{'mostrar':showModal}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                    <span class="help-block" v-show="errorCategoria">(*) Ingrese el nombre de la categoría</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                                </div>
                            </div>
                            <div v-show="errorCategoria" class="form group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errores" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" @click="registrarCategoria()" class="btn btn-primary">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" @click="actualizarCategoria()" class="btn btn-primary">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    export default {
        data(){
            return {
                categoria_id:'',
                nombre: '',
                descripcion:'',
                arrayCategoria:[],
                showModal:0,
                tituloModal:'',
                tipoAccion:0,
                errorCategoria:0,
                errores:[],
                pagination:{
                    'total':0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0
                },
                offset:3,
                criterio:'nomber',
                query:''
            }
        },
        computed: {
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from =1;
                }

                var to = from + (this.offset*2);
                if(to >=this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray= [];
                while(from <=to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods: {
            listarCategoria(page){
                let me = this;
                var url = '/categoria?page='+page+'&buscar='+this.query+'&criterio='+this.criterio;
                axios.get(url).then(function(response){
                    console.log(response);
                    var respuesta = response.data;
                    me.arrayCategoria = respuesta.categorias.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me = this;
                me.pagination.current_page = page;
                me.listarCategoria(page);
            },
            registrarCategoria(){
                if(this.validarCategoria()){
                    return;
                }
                let me = this;
                axios.post('/categoria/registrar',{
                    nombre:me.nombre,
                    descripcion:me.descripcion
                }).then(function(response){
                    me.cerrarModal();
                    me.listarCategoria();
                }).catch(function(error){
                    alert(error);
                });
            },
            actualizarCategoria(){
                if(this.validarCategoria()){
                    return;
                }
                let me = this;
                axios.put('/categoria/actualizar',{
                    id:me.categoria_id,
                    nombre:me.nombre,
                    descripcion:me.descripcion
                }).then(function(response){
                    me.cerrarModal();
                    me.listarCategoria(1);
                }).catch(function(error){
                    alert(error);
                });
            },
            desactivarCategoria(categoria_id){
                var me = this;
                Swal.fire({
                    title: 'Estas seguro?',
                    text: 'Vas a desactivar esta categoria!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('/categoria/desactivar',{
                            id:categoria_id
                        }).then(function(response){
                            me.listarCategoria(1);
                            Swal.fire(
                            'Desactivado!',
                            'Categoria desactivada correctamente',
                            'success'
                            );
                        }).catch(function(error){
                            alert(error);
                        });
                    // For more information about handling dismissals please visit
                    // https://sweetalert2.github.io/#handling-dismissals
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        
                    }
                    });
            },
            activarCategoria(categoria_id){
                var me = this;
                Swal.fire({
                    title: 'Estas seguro?',
                    text: 'Vas a activar esta categoria!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('/categoria/activar',{
                            id:categoria_id
                        }).then(function(response){
                            me.listarCategoria(1);
                            Swal.fire(
                            'Activado!',
                            'Categoria activada correctamente',
                            'success'
                            );
                        }).catch(function(error){
                            alert(error);
                        });
                    // For more information about handling dismissals please visit
                    // https://sweetalert2.github.io/#handling-dismissals
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        
                    }
                    });
            },
            validarCategoria(){
                this.errorCategoria=0;
                this.errores=[];
                if(!this.nombre) this.errores.push('El nombre de la categoria es obligatorio.');
                if(this.errores.length) this.errorCategoria=1;
                return this.errorCategoria;
            },
            cerrarModal(){
                this.showModal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.descripcion='';
            },
            abrirModal(modelo, accion, data=[]){
                switch (modelo) {
                    case 'categoria':{
                        switch(accion){
                            case 'registrar':{
                                this.showModal=1;
                                this.tituloModal = 'Registrar Categoria';
                                this.nombre='';
                                this.descripcion='';
                                this.tipoAccion=1;
                                break;     
                            }
                            case 'actualizar':{
                                this.showModal=1;
                                this.tituloModal = 'Actualizar Categoria';
                                this.categoria_id = data.id;
                                this.nombre = data.nombre;
                                this.descripcion = data.descripcion;
                                this.tipoAccion=2;
                            }
                        }
                        break;
                    }
                    default:
                        break;
                }
            }
        },
        mounted() {
            this.listarCategoria(1);
        }
    }
</script>

<style>
    .modal-content{
        width:100% !important;
        position: absolute;
    }
    .mostrar{
        display:list-item !important;
        opacity:1 !important;
        position:absolute !important;
        background-color:#2c29297a !important;
    }
    .div-error{
        display:flex;
        justify-content:center;
    }
    .text-error{
        color:red;
        font-weight:bold;
    }
</style>