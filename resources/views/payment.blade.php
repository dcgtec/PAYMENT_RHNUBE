@include('layouts.header')


<section class="section py-5 formPage">
    <div class="container">
        <form id="miFormulario" action="/checkout" method="POST">
            <div class="card px-4 pt-2 pb-0">
                <div class="row">
                    <div class="col-md-12">

                        @csrf
                        <div class="selectMeth text-center d-none">
                            {{-- <img src="{{ asset('images/group1.png') }}" alt="Paso 1" /> --}}
                            {{-- <br /> --}}
                            {{-- <h5 class="mt-3">Seleccione tu medio de pago</h5> --}}
                            <div class="row  w-75 m-auto">
                                {{-- <div class="col-md-6 px-2 my-2">
                                    <button type="button" id="mercadoPa_pay"
                                        class="btnPay selectPayment w-100 btn rounded-pill" onclick="">Perú
                                        <img class="ml-3" src="{{ asset('images/mercado-negro.png') }}"> </button>
                            </div>

                            <div class="col-md-6 px-2 my-2">
                                <button type="button" id="stripe_pay" class="btnPay w-100 btn rounded-pill"
                                    onclick="">Otros <img class="ml-3" src="{{ asset('images/stripe-negro.png') }}">
                                </button>
                            </div> --}}

                                {{-- <input class="d-none" type="radio" name="paymetnPage" id="paymentMercado" checked="true"
                                    value="Mercado_Pago"> --}}
                                <!-- <input class="d-none" type="radio" name="paymetnPage" id="paymentStripe" checked="true"
                                value="Stripe"> -->
                            </div>
                        </div>

                        <div class="row text-center mx-3">
                            {{-- <img src="{{ asset('images/group2.png') }}" alt="Paso 2" /> --}}
                            <div class="col-md-12 my-3 px-3">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-6 ">
                                        <h5 class="my-3 mx-3 pr-3 text-sm-left text-center">Elige tu país </h5>
                                    </div>
                                    <div class="selectCountry col-sm-1 ">
                                    </div>
                                    <div class="col-sm-5 mx-0 px-0">
                                        <select name="idCountry" required id="idCountry"
                                            class="form-control text-center">
                                            <option value="">...</option>
                                            <option value="otro_pais_valido">Alemania</option>
                                            <option value="otro_pais_valido">Antigua y Barbuda</option>
                                            <option value="otro_pais_valido">Aruba</option>
                                            <option value="otro_pais_valido">Austria</option>
                                            <option value="otro_pais_valido">Bahamas</option>
                                            <option value="otro_pais_valido">Barbados</option>
                                            <option value="otro_pais_valido">Bélgica</option>
                                            <option value="otro_pais_valido">Belice</option>
                                            <option value="otro_pais_valido">Bielorrusia</option>
                                            <option value="otro_pais_valido">Bolivia</option>
                                            <option value="otro_pais_valido">Brasil</option>
                                            <option value="otro_pais_valido">Canada</option>
                                            <option value="otro_pais_valido">Chile</option>
                                            <option value="otro_pais_valido">Colombia</option>
                                            <option value="otro_pais_valido">Costa Rica</option>
                                            <option value="otro_pais_valido">Cuba</option>
                                            <option value="otro_pais_valido">Dominica</option>
                                            <option value="otro_pais_valido">Ecuador</option>
                                            <option value="otro_pais_valido">El Salvador</option>
                                            <option value="otro_pais_valido">España</option>
                                            <option value="otro_pais_valido">Francia</option>
                                            <option value="otro_pais_valido">Grecia</option>
                                            <option value="otro_pais_valido">Grenada</option>
                                            <option value="otro_pais_valido">Guadalupe</option>
                                            <option value="otro_pais_valido">Guatemala</option>
                                            <option value="otro_pais_valido">Guyana</option>
                                            <option value="otro_pais_valido">Guyana Francesa</option>
                                            <option value="otro_pais_valido">Haití</option>
                                            <option value="otro_pais_valido">Honduras</option>
                                            <option value="otro_pais_valido">Hungría</option>
                                            <option value="otro_pais_valido">India</option>
                                            <option value="otro_pais_valido">Islas Caimán</option>
                                            <option value="otro_pais_valido">Islas Turcas y Caicos</option>
                                            <option value="otro_pais_valido">Islas Vírgenes</option>
                                            <option value="otro_pais_valido">Italia</option>
                                            <option value="otro_pais_valido">Jamaica</option>
                                            <option value="otro_pais_valido">Martinica</option>
                                            <option value="otro_pais_valido">México</option>
                                            <option value="otro_pais_valido">Nicaragua</option>
                                            <option value="otro_pais_valido">Paises bajos</option>
                                            <option value="otro_pais_valido">Panamá</option>
                                            <option value="otro_pais_valido">Paraguay</option>
                                            <option value="PE">Perú</option>
                                            <option value="otro_pais_valido">Polonia</option>
                                            <option value="otro_pais_valido">Portugal</option>
                                            <option value="otro_pais_valido">Puerto Rico</option>
                                            <option value="otro_pais_valido">Reino Unido</option>
                                            <option value="otro_pais_valido">República checa</option>
                                            <option value="otro_pais_valido">República Dominicana</option>
                                            <option value="otro_pais_valido">Rumania</option>
                                            <option value="otro_pais_valido">Rusia</option>
                                            <option value="otro_pais_valido">San Bartolomé</option>
                                            <option value="otro_pais_valido">San Cristóbal y Nieves</option>
                                            <option value="otro_pais_valido">San Vicente y las Granadinas</option>
                                            <option value="otro_pais_valido">Santa Lucía</option>
                                            <option value="otro_pais_valido">Suecia</option>
                                            <option value="otro_pais_valido">Suiza</option>
                                            <option value="otro_pais_valido">Surinam</option>
                                            <option value="otro_pais_valido">Trinidad y Tobago</option>
                                            <option value="otro_pais_valido">Ucrania</option>
                                            <option value="otro_pais_valido">Uruguay</option>
                                            <option value="otro_pais_valido">USA</option>
                                            <option value="otro_pais_valido">Venezuela</option>
                                            <option value="otro_pais_valido">Otros países</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12  my-3 cantEmp">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-6 ">
                                        <h5 class="my-3 mx-3 pr-3 text-sm-left text-center">N° de empleados
                                    </div>
                                    <div class="selectCountry col-sm-1 ">
                                    </div>
                                    <div class="col-sm-5  px-0">
                                        <div class="rowAdd m-auto d-flex rounded-pill justify-content-center">
                                            <div class="dismin">-</div>
                                            <input type="number" name="quanty" min="1" required
                                                value="1" id="valor">
                                            <div class="addmin">+</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="periodo col-md-12 my-3">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-6 ">
                                        <h5 class="my-3 mx-3 pr-3 text-sm-left text-center">Periodo de pago </h5>
                                    </div>
                                    <div class="selectCountry col-sm-1 ">
                                    </div>
                                    <div class="col-sm-5 mx-0 px-0">
                                        <select name="idPla" id="idPla" class="form-control text-center"
                                            id="">
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="resPago table-responsive w-100 text-center my-3 mb-0">
                                {{-- <img src="{{ asset('images/group4.png') }}" alt="Paso 4" /> --}}

                                <table class="table w-100 mt-3 pb-0 mb-0">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Cantidad</th>
                                            <th>P.U</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>{{ $categoryPlan->name }}</b></td>
                                            <td class="quanty">1</td>
                                            <td class="priceUn">$ {{ $categoryPlan->price }}</td>
                                            <td class="subtotal">$ {{ $categoryPlan->price }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td></td>

                                            <td></td>
                                            <td style="text-align: right;">Subtotal</td>
                                            <td class="subtotal">$ {{ $categoryPlan->price }}</td>
                                        </tr> --}}
                                        <tr>
                                            <td></td>

                                            <td></td>
                                            <td style="text-align: right;">Impuesto</td>
                                            <td class="tax">$ 0.00</td>
                                        </tr>

                                        <tr>
                                            <td class="py-4"></td>
                                            <td class="py-4"></td>
                                            <td style="text-align: right;" class="py-4 titleTotal"><b>TOTAL</b></td>
                                            <td class="total py-4 titleTotal"><b>$ {{ $categoryPlan->price }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>



                        </div>

                    </div>

                </div>
                <div class="buttonBuy text-center mt-0 mb-3 ">
                    <button class="btn btn-primary showPayment w-100 rounded-pill" id="payWithStripe">Pagar</button>
                </div>
        </form>
    </div>


</section>



<style>
    header,
    footer {
        display: none;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        display: none !important;
    }

    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        text-indent: 1px;
        text-overflow: '';
    }

    input:focus {
        outline: none
    }

    .formPage .container {
        width: 60%;
    }

    .showPayment {
        background: #08d7d4;
        border-radius: 10px !important;
        border: none;
        font-size: 25px;
        text-transform: uppercase;
        font-weight: bold;
        height: 50px;
    }

    .card {
        border: 1px solid #b0b4b9;
        border-radius: 0px;
        padding-top: 40px !important;
        padding-bottom: 40px !important;
    }

    .selectMeth h5,
    .cantEmp h5,
    .periodo h5,
    .selectCountry h5,
    .resPago h5 {
        color: #b0b4b9;
        font-size: 25px;
        font-weight: 400;
        border-bottom: 2px solid #dee2e6;
    }

    .rowAdd,
    select {
        background-color: #fff;
        width: 100%;
        height: 52px !important;
        align-items: center;
        border-radius: 50rem !important;
        border: 1px solid #08d7d4 !important;
    }



    select {
        font-size: 25px !important;
    }

    .addmin,
    .dismin {
        color: #5654e6;
        font-size: 20px;
        font-weight: 500;
    }

    .addmin:hover,
    .dismin:hover {
        cursor: pointer;
    }

    .addmin {
        margin-left: 10px;
    }

    .dismin {
        margin-right: 10px;
    }

    .cantEmp input {
        width: 100px;
        text-align: center;
        border: none;
        font-size: 22px;
    }

    .periodo button,
    .selectMeth button {
        border: 2px solid #EFF0F6;
        font-size: 25px;
    }

    .periodo button.btnSelec,
    button.selectPayment {
        background: #5654e6;
        color: #fff !important;
        font-weight: 600;
        border-color: #5654e6;
    }

    table {
        font-size: 25px;
    }

    .titleTotal {
        color: #08d7d4;
    }

    .table td,
    .table th {
        padding-top: 1.45rem;
        padding-bottom: 1.45rem;
    }

    .table thead th {
        border-top: none;
    }

    @media screen and (max-width:1000px) {
        .formPage .container {
            width: 100%;
        }
    }
</style>

<script>
    $(document).ready(function() {


        var $valor = $("#valor");
        var $idPla = $("select#idPla");
        var $idCountry = $("select#idCountry");

        $valor.on("change", function() {
            var valor = parseInt($valor.val()) || 1;
            $valor.val(valor < 5 ? 1 : valor);
            changePer();
        });

        $idPla.change(function() {
            changePer();
        });

        $idCountry.change(function() {
            changePer();
        });

        function changePer() {
            var valorPer = $idPla.val();
            var quantity = $valor.val();
            var country = $idCountry.val();

            $.ajax({
                url: "{{ route('update_prices') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    quantity: quantity,
                    plan_id: valorPer,
                    country: country
                },
                success: function(response) {
                    updatePrices(response);
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                },
                complete: function() {
                    console.log("Actualización de precios completada");
                }
            });
        }

        function updatePrices(response) {

            $("td.tax").text("$ " + response.tax);
            //$("td.subtotal").text("$ " + response.subtotal);
            $("td.priceUn").text("$ " + response.price);
            $("td.quanty").text($("#valor").val());
            $("td.total b").text("$ " + response.total);
        }

        $(".dismin, .addmin").on("click", function() {
            var increment = $(this).hasClass("addmin") ? 1 : -1;
            $valor.val(Math.max(1, parseInt($valor.val()) + increment));
            changePer();
        });

    });
</script>

@include('layouts.footer')
