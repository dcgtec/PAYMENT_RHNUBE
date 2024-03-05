@include('layouts.header')

<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
<section class="section py-5 formPage">
    <div class="container">
        <div class="row">
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
                            <div class="col-md-12 my-3 px-3 selecPais">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-12 ">
                                        <h5 class="my-3 mx-3 pr-3 text-center">Elige tu país </h5>
                                    </div>
                                    <div class="col-sm-12 mx-0 px-0">
                                        <select name="idCountry" required id="idCountry"
                                            class="form-control text-center w-100">
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
                                <div class="row align-items-center place-content-center">
                                    <div class="selectCountry col-sm-12 ">
                                        <h5 class="my-3 mx-3 pr-3  text-center">N° de empleados
                                    </div>
                                    <div class="col-sm-12 px-0">
                                        <div class="rowAdd m-auto d-flex rounded-pill justify-content-center">
                                            <div class="dismin">-</div>
                                            <input type="number" name="quanty" min="1" required value="1" id="valor">
                                            <div class="addmin">+</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="periodo col-md-12 my-3">
                                <div class="row align-items-center">
                                    <div class="selectCountry col-sm-12 ">
                                        <h5 class="my-3 mx-3 pr-3 text-center">Periodo de pago </h5>
                                        <input type="hidden" name="idPla" id="idPla" min="1" required id="valor"
                                            value="{{ $plans[0]->id }}">

                                    </div>

                                    @foreach ($periodoPago as $periodoPagos)
                                    <div class="col-md-6 my-2">

                                        <button type="button" name="idPla"
                                            class="w-75 btn btn-primary {{ $periodoPagos->n_periodo == $periodo ? 'seleccionado' : '' }}"
                                            value="{{ $periodoPagos->id }}">
                                            {{ $periodoPagos->name }}
                                        </button>
                                    </div>
                                    @endforeach
                                    <!-- 
                                    <div class="col-sm-12 mx-0 px-0">
                                        <select name="idPla" id="idPla" class="w-100 form-control text-center">
                                            <option value=" {{ $periodoPago[0]->id }}"
                                                {{ $periodo == 1 ? 'selected' : '' }}>Mensual
                                            </option>
                                            <option value="{{ $periodoPago[1]->id }}"
                                                {{ $periodo == 2 ? 'selected' : '' }}>Trimestral
                                            </option>
                                            <option value="{{ $periodoPago[2]->id }}"
                                                {{ $periodo == 3 ? 'selected' : '' }}>Semestral
                                            </option>
                                            <option value="{{ $periodoPago[3]->id }}"
                                                {{ $periodo == 4 ? 'selected' : '' }}>Anual</option>
                                        </select>

                                    </div> -->
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
                                            <td class="priceUn">$ {{ $plans->first()->price }}</td>
                                            <td class="subtotal">$
                                                {{ number_format($plans->first()->price * $plans->first()->totNumMonth, 2, '.', '') }}
                                            </td>
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
                                            <td class="total py-4 titleTotal"><b>$
                                                    {{ number_format($plans->first()->price * $plans->first()->totNumMonth, 2, '.', '') }}</b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>



                        </div>

                    </div>

                </div>
                <div class="buttonBuy text-center mt-0 mb-3 row ">
                    {{-- <div class="col-md-6  my-2">
                        <button type="button" class="btn btn-secondary solicitarDemo w-100 rounded-pill"
                            id="solicitarDemo" data-toggle="modal" data-target="#modalDemo">Solicitar demo</button>
                    </div> --}}
                    <div class="col-md-12 my-2 ">
                        <button type="submit" class="btn btn-primary showPayment w-100 rounded-pill"
                            id="payWithStripe">Pagar</button>
                    </div>
                </div>
        </form>
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


<script src="{{ asset('js/payment.js') }}"></script>
@include('layouts.footer')