<template>
    <div id="quotes">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-uppercase font-weight-bold">Cotizaciones</div>

                    <div class="card-body">
                        <div class="float-right">
                            <button type="button" class="btn btn-outline-primary" @click="create">
                                <i class="fas fa-plus"></i>&nbsp;Nueva cotización
                            </button>
                            <br><br>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center" id="quotes_table">
                                <thead>
                                    <tr>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Información Cliente</th>
                                        <th scope="col">Auto</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Fecha</th>
                                        <th>Depositos</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(quote, index) in quotes" :key="quote.id">
                                        <td>{{quote.id}}</td>
                                        <td>
                                            <p class="text-center">{{quote.customer_name}}</p>
                                            <p class="text-center">{{quote.customer_email}}</p>
                                            <p class="text-center">{{quote.customer_address}}</p>
                                        </td>
                                        <td>
                                            <p class="text-center">{{quote.car_brand}}</p>
                                            <p class="text-center">{{quote.car_model}}</p>
                                            <p class="text-center">{{quote.car_year}}</p>
                                        </td>
                                        <td>${{quote.car_price}}</td>
                                        <td>{{quote.date}}</td>
                                        <td>
                                            <ul>
                                                <li v-for="(deposit, ind) in quote.deposits">
                                                    <p class="text-center">{{deposit.description}}</p>
                                                    <p class="text-center">Monto: ${{deposit.total}}</p>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary" title="Agregar depositos" v-show="!quote.isPaid"
                                                    @click="addDeposits(quote)">
                                                <i class="fas fa-plus"></i>
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

        <!-- modal to create or edit -->
        <div class="modal fade" id="addQuote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nueva cotización</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="store" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <h6 class="font-weight-bold">Datos del cliente</h6>
                            <hr>

                            <div class="form-group row">
                                <label for="customer_name" class="col-sm-2 col-form-label">Nombre:</label>
                                <div class="col-sm-10">
                                    <input v-model="form.customer_name" type="text" id="customer_name" name="customer_name"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('customer_name') }"
                                           placeholder="Introduce el nombre completo">
                                    <has-error :form="form" field="customer_name"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="customer_email" class="col-sm-2 col-form-label">Correo electrónico:</label>
                                <div class="col-sm-10">
                                    <input v-model="form.customer_email" type="email" id="customer_email" name="customer_email"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('customer_email') }"
                                           placeholder="Introduce el correo electrónico">
                                    <has-error :form="form" field="customer_email"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="customer_address" class="col-sm-2 col-form-label">Dirección:</label>
                                <div class="col-sm-10">
                                    <input v-model="form.customer_address" type="text" id="customer_address" name="customer_address"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('customer_address') }"
                                           placeholder="Introduce el domicilio">
                                    <has-error :form="form" field="customer_address"></has-error>
                                </div>
                            </div>

                            <h6 class="font-weight-bold">Datos del auto</h6>
                            <hr>

                            <div class="form-group row">
                                <label for="car_brand" class="col-sm-2 col-form-label">Marca:</label>
                                <div class="col-sm-10">
                                    <input v-model="form.car_brand" type="text" id="car_brand" name="car_brand"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('car_brand') }"
                                           placeholder="Introduce la marca">
                                    <has-error :form="form" field="car_brand"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="car_model" class="col-sm-2 col-form-label">Modelo:</label>
                                <div class="col-sm-10">
                                    <input v-model="form.car_model" type="text" id="car_model" name="car_model"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('car_model') }"
                                           placeholder="Introduce el modelo">
                                    <has-error :form="form" field="car_model"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="car_year" class="col-sm-2 col-form-label">Año:</label>
                                <div class="col-sm-10">
                                    <input v-model="form.car_year" type="text" id="car_year" name="car_year"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('car_year') }"
                                    placeholder="Introduce el año del Auto">
                                    <has-error :form="form" field="car_year"></has-error>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="car_price" class="col-sm-2 col-form-label">Precio:</label>
                                <div class="col-sm-10">
                                    <input v-model="form.car_price" type="text"
                                           id="car_price" name="car_price"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('car_price') }"
                                           placeholder="Introduce la cantidad">
                                    <has-error :form="form" field="car_price"></has-error>
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

        <!--modal to add deposits in quotes-->
        <div class="modal fade" id="addPayments" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Nueva cotización</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="storePayments" @keydown="form2.onKeydown($event)">
                        <input type="hidden" name="quote_id" id="quote_id" v-model="form2.id">
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center" id="payments_table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Fecha</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(deposit, index) in deposits" :key="deposit.id">
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" :value="deposit.id" v-model="depositsSelected" id="defaultCheck1">
                                                    <label class="form-check-label" for="defaultCheck1"></label>
                                                </div>
                                            </td>
                                            <td>{{deposit.description}}</td>
                                            <td>${{deposit.total}}</td>
                                            <td>{{deposit.date}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button :disabled="form2.busy" type="submit" class="btn btn-outline-success" id="btnAdd">
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
                quotes: [],
                form: new Form({
                    id: "",
                    customer_name: "",
                    customer_email: "",
                    customer_address: "",
                    car_brand: "",
                    car_model: "",
                    car_year: "",
                    car_price: "",
                    date: "",
                }),
                deposits: [],
                form2: new Form({
                    id: "",
                    depositsSelected: [],
                }),
                depositsSelected: [],
            }
        },
        mounted() {
            this.getQuotes();
        },
        methods: {
            // Inicialización del datatable de cotizaciones
            quotesTable(){
                $(function() {
                    $('#quotes_table').DataTable();
                    $('#car_price').number( true, 2 );
                });
            },
            // Inicialización del datatable de depositos
            paymentsTable(){
                $(function() {
                    $('#payments_table').DataTable({
                        bFilter: false,
                    });
                });
            },
            // Función para obtener el listado de todas las cotizaciones con sus respectivos depositos
            getQuotes() {
                this.$Progress.start();
                axios.get("/api/quotes")
                    .then(response => {
                        console.log(response.data.data);
                        this.quotes = response.data.data;
                        this.quotesTable();
                        this.$Progress.finish();
                    }).catch(error => {
                        console.log(error);
                    this.$Progress.fail();
                });
            },

            create(){
                this.form.reset();
                this.form.clear();
                $("#addQuote").modal("show");
            },
            // Función para enviar y guardar en base de datos una cotización
            store(){
                this.$Progress.start();
                this.form.busy = true;
                this.form
                    .post("/api/quotes")
                    .then(response => {
                        this.getQuotes();

                        $("#addQuote").modal("hide");

                        if (this.form.successful) {
                            this.$Progress.finish();
                            this.$snotify.success("Cotización creada correctamente!", "Success");
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
            // Función para llamar al modal para seleccionar los depositos a vincular en la cotización previamente seleccionada
            addDeposits(quote){
                this.form2.reset();
                this.form2.clear();
                this.form2.fill(quote);
                console.log(quote.id);
                axios.get('/api/get-deposits/' + quote.id).then(response => {
                    this.deposits = response.data.data;
                    this.paymentsTable();
                    this.$Progress.finish();
                }).catch(e => {
                    this.$Progress.fail();
                    console.log(e);
                });

                $("#addPayments").modal("show");
            },
            // Función para vincular los depositos a las cotizaciones
            storePayments(){
                if (!this.depositsSelected.length){
                    this.$snotify.warning(
                        "Por favor seleccione un deposito del listado!",
                        "Deposito es requerido",
                        {
                            titleMaxLength: 22,
                        }
                    );
                }else{
                    this.form2.depositsSelected = this.depositsSelected;
                    console.log(this.form2.depositsSelected);
                    this.$Progress.start();
                    this.form2.busy = true;
                    this.form2
                        .put("/api/add-payments/" + this.form2.id)
                        .then(response => {
                            this.getQuotes();
                            console.log(response.data);
                            $("#addPayments").modal("hide");

                            if (this.form2.successful) {
                                this.$Progress.finish();
                                this.$snotify.success("Deposito(s) agregado(s) correctamente!", "Éxito");
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
                }
            }

        }
    }
</script>

<style scoped>

</style>
