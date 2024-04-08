@include('layouts.header')



<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
<section class="section py-5 formPage">
    <div class="container">
        <div class="row d-none">
            <div class="col-md-12">
                <h1 class="titleGeneral">Enhorabuena! Un paso más a la eficiencia en RH</h1>
            </div>
        </div>
        <form id="miFormulario" action="/checkout" method="POST">
            <div class="card px-4 pt-2 pb-0">
                <div class="row">
                    <div class="col-md-12">
                        @csrf
                        <div class="row text-center mx-3">
                            <div class="col-md-12  mt-1 titleGeneral">
                                <div class="row align-items-center place-content-center">
                                    <div class="selectCountry col-sm-12 ">
                                        <h5 class=" mx-3 pr-3  text-center">CONFIGUREMOS TU PLAN
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12 my-1 px-3 selecPais">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-4 ">
                                        <h5 class="my-3  pr-3 text-left">País:</h5>
                                    </div>
                                    <div class="col-sm-4 mx-0 px-0">
                                        <select name="idCountry" required id="idCountry"
                                            class="form-control text-center w-100">
                                            <option value="">Seleccionar</option>
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



                            <div class="col-md-12  my-1 cantEmp">
                                <div class="row align-items-center place-content-center">
                                    <div class="selectCountry col-sm-4 ">
                                        <h5 class="my-3  pr-3 text-left">N° de empleados:
                                    </div>
                                    <div class="col-sm-4 mx-0 px-0">
                                        <div class="rowAdd m-auto d-flex rounded-pill justify-content-center">
                                            <div class="dismin">-</div>
                                            <input type="number" name="quanty" min="1" required
                                                value="1" id="valor">
                                            <div class="addmin">+</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="paquete col-md-12 my-1">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-4 ">
                                        <h5 class="my-3  pr-3 text-left">Plan: </h5>
                                    </div>

                                    <div class="col-sm-4 mx-0 px-0">
                                        <select class="form-control text-center w-100" required id="idPaquete"
                                            name="idPaquete">
                                            @if (is_array($datos) && !empty($datos['paquetes']))
                                                @php $primerElemento = true; @endphp
                                                @foreach ($datos['paquetes'] as $key => $paquete)
                                                    <option value="{{ $paquete['id_paquete'] }}"
                                                        @if ($primerElemento) selected @php $primerElemento = false; @endphp @endif>
                                                        {{ $paquete['paquete'] }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="1" selected>Plus</option>
                                                <option value="2">Remote</option>
                                                <option value="3">Route</option>
                                            @endif
                                        </select>

                                    </div>

                                </div>
                            </div>

                            <div class="periodo col-md-12 my-1">
                                <div class="row">
                                    <div class="selectCountry col-sm-4 ">
                                        <h5 class="my-3  pr-3 text-left">Periodo: </h5>
                                        <input type="hidden" name="idPla" id="idPla">
                                    </div>

                                    <div class="col-sm-8 mx-0 px-0">
                                        <div class="row lisPer">
                                            @if (is_array($datos))
                                                @foreach ($datos['periodos'] as $key => $periodo)
                                                    <div class="col-md-6 my-2">
                                                        <button type="button"
                                                            value="{{ $periodo['id_tipo_periodo'] }}" name="idPla"
                                                            class="w-100 btn btn-primary {{ $key === 0 ? 'seleccionado' : '' }}">{{ $periodo['periodo'] }}</button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-md-6 my-2 ">
                                                    <button type="button" value="1" name="idPla"
                                                        class="w-100 btn btn-primary seleccionado">Mensual</button>
                                                </div>
                                                <div class="col-md-6 my-2 ">
                                                    <button type="button" value="2" name="idPla"
                                                        class="w-100 btn btn-primary">Trimestral</button>
                                                </div>
                                                <div class="col-md-6 my-2 ">
                                                    <button type="button" value="3" name="idPla"
                                                        class="w-100 btn btn-primary">Semestral</button>
                                                </div>
                                                <div class="col-md-6 my-2 ">
                                                    <button type="button" value="4" name="idPla"
                                                        class="w-100 btn btn-primary">Anual</button>
                                                </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12  my-1 cantEmp">
                            <div class="row align-items-center place-content-center">
                                <div class="selectCountry col-sm-4 ">
                                    <h5 class="my-3  pr-3 text-left">Cupón:
                                </div>
                                <div class="col-sm-4 mx-0 px-0">
                                    <div class="rowAdd m-auto d-flex rounded-pill justify-content-center">
                                        @if (is_array($datos))
                                            <input type="text" value="{{ request('cupon') }}"
                                                descuento="{{ $datos['descuento'] }}"
                                                codcupon="{{ $datos['codigo_cupon'] }}" name="cupon" min="1"
                                                placeholder="------" id="cupon">
                                        @else
                                            <input type="text" value="{{ request('cupon') }}" descuento="0.00"
                                                codcupon="" name="cupon" min="1" placeholder="------"
                                                id="cupon">
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="resPago table-responsive w-100 text-center mt-2 mb-0">
                            {{-- <img src="{{ asset('images/group4.png') }}" alt="Paso 4" /> --}}


                            <div class="col-md-12  mt-3 titleGeneral">
                                <div class="row align-items-center place-content-center">
                                    <div class="selectCountry col-sm-12 ">
                                        <h5 class=" mx-3 pr-3  text-center">RESUMEN DE PAGO
                                    </div>

                                </div>
                            </div>

                            <table class="table w-100 pb-0 mb-0">
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
                                        <td><b class="plan"></b></td>
                                        <td class="quanty">1</td>
                                        <td class="priceUn">$ </td>
                                        <td class="subtotal">$

                                        </td>
                                    </tr>
                                    {{-- <tr>
                                            <td></td>

                                            <td></td>
                                            <td style="text-align: right;">Subtotal</td>
                                            <td class="subtotal">$ </td>
                                        </tr> --}}
                                    <tr>
                                        <td></td>

                                        <td></td>
                                        <td style="text-align: right;">Impuesto</td>
                                        <td class="tax">$ 0.00</td>
                                    </tr>


                                    <tr>
                                        <td></td>

                                        <td></td>
                                        <td style="text-align: right;">Descuento</td>
                                        <td class="desc">$ 0.00</td>
                                    </tr>


                                    <tr>
                                        <td class="py-4"></td>
                                        <td class="py-4"></td>
                                        <td style="text-align: right;" class="py-4 titleTotal"><b>TOTAL</b></td>
                                        <td class="total py-4 titleTotal"><b>$
                                            </b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="buttonBuy text-center mt-0 mb-0 row ">
                {{-- <div class="col-md-6  my-2">
                        <button type="button" class="btn btn-secondary solicitarDemo w-100 rounded-pill"
                            id="solicitarDemo" data-toggle="modal" data-target="#modalDemo">Solicitar demo</button>
                    </div> --}}
                <div class="col-md-12 mt-2 ">
                    <button type="submit" class="btn btn-primary showPayment w-100 rounded-pill"
                        id="payWithStripe">Pagar</button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="modalCarga" tabindex="-1" role="dialog" aria-labelledby="modalCargaLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Preloader -->
                    <div class="text-center">
                        <div class="loader">
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                            <div class="circle"></div>
                        </div>

                        <div class="text cargarInf">
                            Obteniendo información ...
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    {{-- <div class="modal fade" id="modalDemo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" class="font-weight-bold">Agendar una DEMO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Nombres <span id="requi">*</span> </label>
                            <input type="text" class="form-control" id="namePer"
                                placeholder="Ingrese sus nombres">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Ruc <span id="requi">*</span> </label>
                            <input type="number" class="form-control" id="rucPer" placeholder="Ingrese sus ruc">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Email <span id="requi">*</span> </label>
                            <input type="email" class="form-control" id="emailPer"
                                placeholder="Ingrese su correo electrónico">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">N° Celular <span id="requi">*</span> </label>
                            <input type="tel" class="form-control" id="celPer"
                                placeholder="Ingrese su número móvil">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Por favor detalla tus necesidades
                                (opcional)</label>
                            <textarea class="form-control" id="detallePer" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Agendar Reunión</button>
                </div>
            </div>
        </div>
    </div> --}}
</section>

<style>
    .loader {
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    .circle {
        position: absolute;
        width: 0px;
        height: 0px;
        border-radius: 100%;
        background: rgba(31, 113, 240, 1);
        animation: radar 3s ease-out infinite;
        box-shadow: 0px 0px 10px rgba(31, 113, 240, .5);
        /*   box-shadow:0px 0px 10px rgba(0,0,0,.5); */
        /*   border:1px solid rgba(255,255,255,.2); */
    }

    .circle:nth-of-type(1) {
        animation-delay: 0.2s;
    }

    .circle:nth-of-type(2) {
        animation-delay: 0.6s;
    }

    .circle:nth-of-type(3) {
        animation-delay: 1s;
    }

    .circle:nth-of-type(4) {
        animation-delay: 1.4s;
    }

    .circle:nth-of-type(5) {
        animation-delay: 1.8s;
    }

    @keyframes radar {
        0% {}

        30% {
            width: 50px;
            height: 50px;
        }

        100% {
            width: 50px;
            height: 50px;
            opacity: 0;
        }
    }
</style>
@if (!is_array($datos))
    <script>
        Swal.fire({
            title: "¡Cupón no válido!",
            text: '',
            icon: "error",
        });
    </script>
@endif
<script src="{{ asset('js/paymentCupon.js') }}"></script>

@include('layouts.footer')
