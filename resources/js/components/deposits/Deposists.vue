<template>
    <div id="deposits">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold">Depositos</div>

                    <div class="card-body">
                        <div class="float-right">
                            <button type="button" class="btn btn-outline-primary" @click="create">
                                <i class="fas fa-plus"></i>&nbsp;Nuevo deposito
                            </button>
                            <br><br>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center" id="deposits_table">
                                <thead>
                                <tr>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col" class="text-center">Acciones</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="(deposit, index) in deposits" :key="deposit.id">
                                    <td>{{deposit.description}}</td>
                                    <td>${{deposit.total}}</td>
                                    <td>{{deposit.date}}</td>
                                    <td>
                                        <button class="btn btn-outline-info" title="Editar" @click="edit(deposit)">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-outline-danger" title="Eliminar"  @click="destroy(deposit)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--modal to create or edit-->
        <div class="modal fade" id="addDeposit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ isUpdate ? "Editar" : "Nuevo" }} deposito</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="isUpdate ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">Descripción:</label>
                                <div class="col-sm-10">
                                <textarea v-model="form.description" id="description" name="description" rows="3"
                                          class="form-control"
                                          :class="{ 'is-invalid': form.errors.has('description') }"
                                          placeholder="Introduce una descripción"></textarea>
                                    <has-error :form="form" field="description"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="total" class="col-sm-2 col-form-label">Total:</label>
                                <div class="col-sm-10">
                                    <input v-model="form.total" type="text" id="total" name="total"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('total') }"
                                           placeholder="Introduce el monto">
                                    <has-error :form="form" field="total"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date" class="col-sm-2 col-form-label">Fecha:</label>
                                <div class="col-sm-10">
                                    <input v-model="form.date" type="date" id="date" name="date"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('date') }"
                                           placeholder="Introduce la fecha actual">
                                    <has-error :form="form" field="date"></has-error>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button :disabled="form.busy" type="submit" class="btn btn-outline-success" id="btnSave">
                                <i class="fas fa-check"></i>&nbsp;Guardar
                            </button>

                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">
                                <i class="fas fa-times"></i>&nbsp;Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <vue-progress-bar></vue-progress-bar>
        <vue-snotify></vue-snotify>
    </div>
</template>

<script>
    import datables from 'datatables';
    export default {
        data() {
            return {
                deposits: [],
                form: new Form({
                    id: "",
                    description: "",
                    total: "",
                    date: "",
                }),
                isUpdate: false,
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            //Inicializamos el datatable
            depositsTable(){
                $(function() {
                    $('#deposits_table').DataTable();
                    $('#total').number( true, 2 );
                });
            },
            // Obtenemos todos los depositos
            getData() {
                this.$Progress.start();
                axios.get("/api/deposits")
                    .then(response => {
                        this.deposits = response.data.data;
                        this.depositsTable();
                        this.$Progress.finish();
                    }).catch(error => {
                        console.log(error);
                        this.$Progress.fail();
                    });
            },
            // Función para llamar al modal para crear un deposito
            create(){
                this.isUpdate = false;
                this.form.reset();
                this.form.clear();
                $("#addDeposit").modal("show");
            },
            // Función para enviar los datos y crear el registro en la base de datos
            store(){
                this.$Progress.start();
                this.form.busy = true;
                this.form
                    .post("/api/deposits")
                    .then(response => {
                        this.getData();

                        $("#addDeposit").modal("hide");

                        if (this.form.successful) {
                            this.$Progress.finish();
                            this.$snotify.success("Deposito creado correctamente!", "Éxito");
                        } else {
                            this.$Progress.fail();
                            this.$snotify.error(
                                "Algo salió mal, intente nuevamente más tarde.",
                                "Error"
                            );
                        }
                    })
                    .catch(e => {
                        this.$Progress.fail();
                        console.log(e);
                    });
            },
            // Función para realizar la edición de un registro previamente seleccionado
            edit(deposit) {
                this.isUpdate = true;
                this.form.reset();
                this.form.clear();
                this.form.fill(deposit);

                $("#addDeposit").modal("show");
            },
            // Función para enviar los datos que se hayan actualizado y se almacenen
            update() {
                this.$Progress.start();
                this.form.busy = true;
                this.form
                    .put("/api/deposits/" + this.form.id)
                    .then(response => {
                        this.getData();

                        $("#addDeposit").modal("hide");

                        if (this.form.successful) {
                            this.$Progress.finish();
                            this.$snotify.success("Deposito actualizado correctamente!", "Éxito");
                        } else {
                            this.$Progress.fail();
                            this.$snotify.error(
                                "Algo salió mal, intente nuevamente más tarde.",
                                "Error"
                            );
                        }
                    })
                    .catch(e => {
                        this.$Progress.fail();
                        console.log(e);
                    });
            },
            // Función para eliminar un registro que haya sido seleccionado
            destroy(deposit) {
                this.$snotify.clear();
                this.$snotify.confirm(
                    "\n" +
                    "¿Estás seguro de que deseas eliminar el deposito?",
                    "Eliminar deposito",
                    {
                        titleMaxLength: 22,
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true,
                        buttons: [
                            {
                                text: "Aceptar",
                                action: toast => {
                                    this.$snotify.remove(toast.id);
                                    this.$Progress.start();
                                    axios
                                        .delete("/api/deposits/" + deposit.id)
                                        .then(response => {
                                            this.getData();
                                            this.$Progress.finish();
                                            this.$snotify.success(
                                                "Deposito eliminado correctamente!",
                                                "Éxito"
                                            );
                                        })
                                        .catch(e => {
                                            this.$Progress.fail();
                                            console.log(e);
                                        });
                                },
                                bold: true
                            },
                            {
                                text: "Cancelar",
                                action: toast => {
                                    this.$snotify.remove(toast.id);
                                },
                                bold: true
                            }
                        ]
                    }
                );
            }
        }
    }
</script>

<style scoped>

</style>
