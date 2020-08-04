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
                    <i class="fa fa-align-justify"></i> Artículos
                    <button type="button" @click="abrirModal('articulo','registrar')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="cargarPdf()" class="btn btn-info">
                        <i class="icon-dic"></i>&nbsp;Reporte
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
                                <input type="text" v-model="query" @keyup.enter="listarArticulo(1)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio venta</th>
                                <th>Stock</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                <td>
                                    <button type="button" @click="abrirModal('articulo','actualizar', articulo)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="articulo.condicion">
                                        <button type="button" @click="desactivarCategoria(articulo.id)" class="btn btn-danger btn-sm">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="activarCategoria(articulo.id)" class="btn btn-info btn-sm">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="articulo.codigo"></td>
                                <td v-text="articulo.nombre"></td>
                                <td v-text="articulo.nombre_categoria"></td>
                                <td v-text="articulo.precio_venta"></td>
                                <td v-text="articulo.stock"></td>
                                <td v-text="articulo.descripcion"></td>
                                <td>
                                    <span v-if="articulo.condicion" class="badge badge-success">Activo</span>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idCategoria">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="codigo" class="form-control" placeholder="Código de barras">                                        
                                        <barcode :value="codigo" :options="{ format: 'EAN-13'}"></barcode>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de artículo">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio Venta</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="precio_venta" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="stock" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="errorArticulo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errores" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal()" class="btn btn-secondary">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" @click="registrarArticulo()" class="btn btn-primary">Guardar</button>
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
import VueBarcode from 'vue-barcode';

    export default {
        data(){
            return {
                articulo_id:0,
                idCategoria:0,
                nombre_categoria:'',
                codigo:'',
                nombre: '',
                precio_venta:0,
                stock:0,
                descripcion:'',
                arrayArticulo:[],
                showModal:0,
                tituloModal:'',
                tipoAccion:0,
                errorArticulo:0,
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
                query:'',
                arrayCategoria :[]
            }
        },
        components: {
            'barcode': VueBarcode
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
            listarArticulo(page){
                let me = this;
                var url = '/articulo?page='+page+'&buscar='+this.query+'&criterio='+this.criterio;
                axios.get(url).then(function(response){
                    console.log(response);
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            cargarPdf(){
                window.open('http://vues.ec/articulo/listarPdf','_blank');
            },
            cambiarPagina(page){
                let me = this;
                me.pagination.current_page = page;
                me.listarArticulo(page);
            },
            selectCategoria(){
                let me=this;
                var url= '/categoria/select_categoria';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayCategoria = respuesta.categorias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarArticulo(){
                if(this.validarArticulo()){
                    return;
                }
                let me = this;
                axios.post('/articulo/registrar',{
                    nombre:me.nombre,
                    descripcion:me.descripcion,
                    codigo:me.codigo,
                    precio_venta:me.precio_venta,
                    stock:me.stock,
                    categoria_id:me.idCategoria
                }).then(function(response){
                    me.cerrarModal();
                    me.listarArticulo(1);
                }).catch(function(error){
                    alert(error);
                });
            },
            actualizarCategoria(){
                if(this.validarArticulo()){
                    return;
                }
                let me = this;
                axios.put('/articulo/actualizar',{
                    id:me.articulo_id,
                    nombre:me.nombre,
                    descripcion:me.descripcion,
                    codigo:me.codigo,
                    precio_venta:me.precio_venta,
                    stock:me.stock,
                    categoria_id:me.idCategoria
                }).then(function(response){
                    me.cerrarModal();
                    me.listarArticulo(1);
                }).catch(function(error){
                    alert(error);
                });
            },
            desactivarCategoria(articulo_id){
                var me = this;
                Swal.fire({
                    title: 'Estas seguro?',
                    text: 'Vas a desactivar este articulo!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                    if (result.value) {
                        axios.put('/articulo/desactivar',{
                            id:articulo_id
                        }).then(function(response){
                            me.listarArticulo(1);
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
            activarCategoria(articulo_id){
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
                        axios.put('/articulo/activar',{
                            id:articulo_id
                        }).then(function(response){
                            me.listarArticulo(1);
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
            validarArticulo(){
                this.errorArticulo=0;
                this.errores=[];
                
                if (this.idcategoria==0) this.errores.push("Seleccione una categoría.");
                if (!this.nombre) this.errores.push("El nombre del artículo no puede estar vacío.");
                if (!this.stock) this.errores.push("El stock del artículo debe ser un número y no puede estar vacío.");
                if (!this.precio_venta) this.errores.push("El precio venta del artículo debe ser un número y no puede estar vacío.");

                if(this.errores.length) this.errorArticulo=1;
                return this.errorArticulo;
            },
            cerrarModal(){
                this.showModal = 0;
                this.tituloModal = '';
                this.nombre = '';
                this.descripcion='';
                this.codigo=0;
                this.precio_venta=0;
                this.stock=0;
                this.errorArticulo=0;
            },
            abrirModal(modelo, accion, data=[]){
                switch (modelo) {
                    case 'articulo':{
                        switch(accion){
                            case 'registrar':{
                                this.showModal=1;
                                this.tituloModal = 'Registrar Artículo';
                                this.nombre='';
                                this.descripcion='';
                                this.codigo=0;
                                this.precio_venta=0;
                                this.stock=0;
                                this.tipoAccion=1;
                                break;     
                            }
                            case 'actualizar':{
                                this.showModal=1;
                                this.tituloModal = 'Actualizar Artículo';
                                this.articulo_id = data.id;
                                this.nombre = data.nombre;
                                this.descripcion = data.descripcion;
                                this.stock = data.stock;
                                this.codigo = data.codigo;
                                this.precio_venta = data.precio_venta;
                                this.idCategoria = data.categoria_id;
                                this.tipoAccion=2;
                            }
                        }
                        break;
                    }
                     
                    default:
                        break;
                }
                this.selectCategoria();
            }
        },
        mounted() {
            this.listarArticulo(1);
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