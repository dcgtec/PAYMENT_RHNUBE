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
                            <div class="col-md-12 my-2 px-3">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-6 ">
                                        <h5 class="my-3 mx-3 pr-3 text-sm-right text-center">Elige tu país: </h5>
                                    </div>
                                    <div class="col-sm-6 mx-0 px-0">
                                        <select name="idCountry" required id="idCountry"
                                            class="form-control text-center">
                                            <option value="">Seleccione su país</option>
                                            <option value="PE">Perú</option>
                                            <option value="Other">Otro País</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12  my-2 cantEmp">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-6 ">
                                        <h5 class="my-3 mx-3 pr-3 text-sm-right text-center">Cant. empleados:
                                    </div>
                                    <div class="col-sm-6  px-0">
                                        <div class="rowAdd m-auto d-flex rounded-pill justify-content-center">
                                            <div class="dismin">-</div>
                                            <input type="number" name="quanty" min="1" required value="1"
                                                id="valor">
                                            <div class="addmin">+</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="periodo col-md-12 my-2 ">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-6 ">
                                        <h5 class="my-3 mx-3 pr-3 text-sm-right text-center">Periodo de pago: </h5>
                                    </div>
                                    <div class="col-sm-6 mx-0 px-0">
                                        <select name="idPla" id="idPla" class="form-control text-center"
                                            id="">
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="resPago table-responsive w-100 text-center mb-0">
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
                                        <tr>
                                            <td></td>

                                            <td></td>
                                            <td style="text-align: right;"><b>SUBTOTAL</b></td>
                                            <td class="subtotal">$ {{ $categoryPlan->price }}</td>
                                        </tr>
                                        <tr>
                                            <td></td>

                                            <td></td>
                                            <td style="text-align: right;"><b>IMPUESTO</b></td>
                                            <td class="tax">$ 0.00</td>
                                        </tr>

                                        <tr>
                                            <td class="py-4"></td>
                                            <td class="py-4"></td>
                                            <td style="text-align: right;" class="py-4"><b>TOTAL</b></td>
                                            <td class="total py-4">$ {{ $categoryPlan->price }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>



                        </div>

                    </div>

                </div>
                <div class="buttonBuy text-center my-4 ">
                    <button class="btn btn-primary showPayment w-100 rounded-pill" id="payWithStripe">Pagar</button>
                </div>
        </form>
    </div>


</section>



<style>
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
        width: 50%;
    }

    .showPayment {
        background: #08d7d4;
        border: 1px solid #08d7d4;
        border: none;
    }

    .card {
        border: 1px solid #eff0f6;
        box-shadow: 0 2px 7px 0 rgba(20, 20, 43, 0.06);
        border-radius: 24px;
    }

    .selectMeth h5,
    .cantEmp h5,
    .periodo h5,
    .selectCountry h5,
    .resPago h5 {
        color: #464b50;
        font-size: 18px;
    }

    .rowAdd,
    select {
        background-color: #fff;
        width: 100%;
        height: 41px;
        align-items: center;
        border-radius: 50rem !important;
    }

    .rowAdd {
        border: 1px solid #ced4da;
    }

    select {
        font-size: 18px !important;
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
    }

    .periodo button,
    .selectMeth button {
        border: 2px solid #EFF0F6;
        font-size: 18px;
    }

    .periodo button.btnSelec,
    button.selectPayment {
        background: #5654e6;
        color: #fff !important;
        font-weight: 600;
        border-color: #5654e6;
    }

    @media screen and (max-width:1000px) {
        .formPage .container {
            width: 100%;
        }
    }
</style>

<script>
    // $("button#mercadoPa_pay.selectPayment img").attr("src", "{{ asset('images/mercado-blanco.png') }}")

    // $('.btnPay').click(function() {
    //     // Remove the class from all buttons
    //     $('.btnPay').removeClass('selectPayment');

    //     // Add the class to the clicked button
    //     $(this).addClass('selectPayment');
    //     console.log($(this).attr("id"))

    //     if ($(this).attr("id") == "mercadoPa_pay") {
    //         $("#paymentMercado").prop("checked", true);
    //         $("#paymentStripe").prop("checked", false);

    //         $("button#mercadoPa_pay.selectPayment img").attr("src", "{{ asset('images/mercado-blanco.png') }}");
    //         $("button#stripe_pay img").attr("src", "{{ asset('images/stripe-negro.png') }}");
    //     } else {
    //         $("button#stripe_pay.selectPayment img").attr("src", "{{ asset('images/stripe-blanco.png') }}");
    //         $("button#mercadoPa_pay img").attr("src", "{{ asset('images/mercado-negro.png') }}");
    //         $("#paymentStripe").prop("checked", true);
    //         $("#paymentMercado").prop("checked", false);
    //     }
    // });

    // seleccionarPeriodo($(".btnSelec"));
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
            $("td.subtotal").text("$ " + response.subtotal);
            $("td.priceUn").text("$ " + response.price);
            $("td.quanty").text($("#valor").val());
            $("td.total").text("$ " + response.total);
        }

        $(".dismin, .addmin").on("click", function() {
            var increment = $(this).hasClass("addmin") ? 1 : -1;
            $valor.val(Math.max(1, parseInt($valor.val()) + increment));
            changePer();
        });


        // function seleccionarPeriodo(boton) {
        //     // Convert boton to a jQuery object
        //     var boton = $(boton);

        //     // Obtener todos los botones dentro del contenedor
        //     var botones = $('.btn');

        //     // Remover la clase btnSelec de todos los botones
        //     botones.removeClass('btnSelec');

        //     // Agregar la clase btnSelec solo al botón clicado
        //     boton.addClass('btnSelec');

        //     var planId = boton.data('plan-id');
        //     $("input#idPla").val(planId);
        //     var quantity = $("#valor").val();
        //     var csrfToken = "{{ csrf_token() }}";

        //     // Hacer la solicitud AJAX al servidor
        //     $.ajax({
        //         url: "{{ route('update_prices') }}",
        //         method: "POST",
        //         data: {
        //             _token: csrfToken,
        //             quantity: quantity,
        //             plan_id: planId
        //         },
        //         success: function(response) {
        //             // Actualizar la información en la página
        //             $("td.tax").text("$ " + response.tax);
        //             $("td.subtotal").text("$ " + response.subtotal);
        //             $("td.priceUn").text("$ " + response.price);
        //             $("td.quanty").text(quantity);
        //             $("td.total").text("$ " + response.total);
        //         },
        //         error: function(xhr, status, error) {
        //             console.log(xhr);
        //         },
        //         complete: function() {
        //             console.log("Actualización de precios completada");
        //         }
        //     });
        // }


    });
</script>

@include('layouts.footer')
