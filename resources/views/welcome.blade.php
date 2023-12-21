@include('layouts.header')

<section class="section py-5">
    <div class="container">
        <div class="content text-center">
            <h4 class="titlePlan">NUESTROS PLANES</h4>
        </div>
        <div class="content text-center">
            <h1 class="principal-title-discovery">Gestiona el talento de tu empresa <br class="d-sm-block d-none"> hoy!!
            </h1>
        </div>
        <p class="paragraph text-beneficios">Hemos diseñado los mejores planes disponibles para brindarte una solución
            <br class="d-sm-block d-none">que te permita administrar a tus empleados sean estos: Presenciales,<br
                class="d-sm-block d-none"> remotos, de campo o de calle.
        </p>
    </div>

    <div class="container my-5">
        <div class="row">
            @foreach ($categoryPlans as $categoryPlan)
                @if ($categoryPlan->action == '1')
                    <div class="col-md-4 my-4">
                        <div class="card p-0">
                            <div class="card-body p-4">
                                <div class="text-center titlePre mb-3">
                                    <h3 class="titlePre">{{ $categoryPlan->name }}</h3>
                                </div>

                                <div class="text-center costPre mb-3">
                                    <h1 class="titlePre">${{ $categoryPlan->price }}
                                    </h1>
                                </div>

                                <div class="text-center quaEmp mb-3">
                                    <h3 class="titleQua">Empleado / mes
                                    </h3>
                                </div>

                                <div class="text-center buyPre mb-3">
                                    <a
                                        href="{{ route('category_plans.show', ['slug' => $categoryPlan->slug, 'id' => $categoryPlan->id]) }}">
                                        <button class="btn btn-primary">
                                            Suscribirse
                                        </button>
                                    </a>
                                </div>

                                <hr class="hrPrice">

                                <div class="detallePri">
                                    <div class="d-flex mb-2 align-items-center">
                                        <img alt="" src="{{ asset('images/iconoCheck.svg') }}"
                                            class="brix---icon-list-2">
                                        <div class="contDetalle pl-3">
                                            Recorrido por geolocalización en tiempo real
                                        </div>
                                    </div>

                                    <div class="d-flex mb-2 align-items-center">
                                        <img alt="" src="{{ asset('images/iconoCheck.svg') }}"
                                            class="brix---icon-list-2">
                                        <div class="contDetalle pl-3">
                                            Recorrido por geolocalización en tiempo real
                                        </div>
                                    </div>

                                    <div class="d-flex mb-2 align-items-center">
                                        <img alt="" src="{{ asset('images/iconoCheck.svg') }}"
                                            class="brix---icon-list-2">
                                        <div class="contDetalle pl-3">
                                            Recorrido por geolocalización en tiempo real
                                        </div>
                                    </div>

                                    <div class="d-flex mb-2 align-items-center">
                                        <img alt="" src="{{ asset('images/iconoCheck.svg') }}"
                                            class="brix---icon-list-2">
                                        <div class="contDetalle pl-3">
                                            Recorrido por geolocalización en tiempo real
                                        </div>
                                    </div>

                                    <div class="d-flex mb-2 align-items-center">
                                        <img alt="" src="{{ asset('images/iconoCheck.svg') }}"
                                            class="brix---icon-list-2">
                                        <div class="contDetalle pl-3">
                                            Recorrido por geolocalización en tiempo real
                                        </div>
                                    </div>

                                    <div class="d-flex mb-2 align-items-center">
                                        <img alt="" src="{{ asset('images/iconoCheck.svg') }}"
                                            class="brix---icon-list-2">
                                        <div class="contDetalle pl-3">
                                            Recorrido por geolocalización en tiempo real
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<style>
    h4.titlePlan {
        color: #5654e6;
        letter-spacing: .15em;
        text-transform: uppercase;
        margin-bottom: 8px;
        font-family: Poppins, sans-serif;
        font-size: 18px;
        font-weight: 700;
        line-height: 20px;
    }

    h1.principal-title-discovery {
        font-size: 40px;
        font-weight: 700;
        line-height: 55px;
        color: #464b50;
        text-align: center;
    }

    .paragraph.text-beneficios {
        text-align: center;
        margin-top: 25px;
        ;
        color: #9ea2ab;
        font-size: 18px;
        font-weight: 300;
        line-height: 25px;
    }

    .card {
        border: 1px solid #eff0f6;
        box-shadow: 0 2px 7px 0 rgba(20, 20, 43, .06);
        border-radius: 24px;
    }

    h3.titlePre {
        color: #464b50;
        text-align: center;
        margin-top: 0;
        margin-bottom: 12px;
        font-family: Poppins, sans-serif;
        font-size: 24px;
        font-weight: 700;
        line-height: 34px;
    }

    h1.titlePre {
        font-size: 54px;
        color: #464b50;
        font-weight: 700;
    }

    h3.titleQua {
        font-size: 20px;
        color: #464b50;
        font-weight: 500;
        margin-bottom: 16px;
    }

    .buyPre button {
        width: 100%;
        background-color: rgb(0, 142, 250);
        color: rgb(255, 255, 255);
        font-weight: 500;
        height: 44px;
        border-radius: 5px;
    }

    .hrPrice {
        margin-top: 40px;
        margin-bottom: 40px;
    }

    .detallePri img {
        width: 25px;
        height: 25px;
    }

    .contDetalle {
        color: #464b50;
        font-size: 15px;
        font-weight: 500;
        line-height: 24px;
    }


    @media screen and (max-width: 479px) {
        h4.titlePlan {
            font-size: 14px;
            line-height: 18px;
        }

        h1.principal-title-discovery {
            font-size: 35px;
            line-height: 40px;
        }
    }
</style>

@include('layouts.footer')
