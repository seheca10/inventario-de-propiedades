<!doctype html>
<html lang="es-CO">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <title>Inventario del arrendatario: {{ $inventario->arrendatario }}</title>
  </head>
  <body>

    <div>

      <div class="py-4">
        <center>
        <img src="https://cartagenanorte.com/wp-content/uploads/2021/07/LOGO-3D-PAGINA-CARTAGENA-NORTE.png" width="25%">

        </center>
      </div>

      <div class="container-title">
        <h4 class="text-center font-weight-bold">INVENTARIO DE INMUEBLE ARRENDADO</h4>
      </div>

    </div>

    <hr>

    <div class="container-fluid">
        <table class="table table-bordered table-striped">
            <tbody>
              <tr>
                <td class="font-weight-bold" colspan="2">FECHA</td>
                <td colspan="2">{{ $inventario->created_at->format('Y-m-d') }}</td>
                <td class="font-weight-bold" colspan="3">CONDOMINIO</td>
                <td colspan="5">{{ $inventario->condominio }}</td>
              </tr>
              <tr>
                <td class="font-weight-bold" colspan="2">BLOQUE</td>
                <td colspan="1">{{ $inventario->bloque }}</td>
                <td class="font-weight-bold" colspan="2">APARTAMENTO</td>
                <td colspan="1">{{ $inventario->apartamento }}</td>
                <td class="font-weight-bold" colspan="2">GARAJE</td>
                <td colspan="1">{{ $inventario->garaje }}</td>
                <td class="font-weight-bold" colspan="2">DEPÓSITO</td>
                <td colspan="1">{{ $inventario->deposito }}</td>
              </tr>
              <tr>
                <td class="font-weight-bold" colspan="3">ARRENDATARIO</td>
                <td colspan="3">{{ $inventario->arrendatario  }}</td>
                <td class="font-weight-bold" colspan="3">ARRENDADOR</td>
                <td colspan="3">{{ $inventario->arrendador }}</td>
              </tr>
              <tr>
                <td class="font-weight-bold" colspan="2">ALCOBAS</td>
                <td colspan="1">{{ $inventario->alcobas }}</td>
                <td class="font-weight-bold" colspan="1">BAÑOS</td>
                <td colspan="1">{{ $inventario->banos }}</td>
                <td class="font-weight-bold" colspan="1">PATIO</td>
                <td colspan="1">{{ $inventario->patio }}</td>
                <td class="font-weight-bold" colspan="2">JARDIN</td>
                <td colspan="1">{{ $inventario->jardin }}</td>
                <td class="font-weight-bold" colspan="1">MTS</td>
                <td colspan="1">{{ $inventario->metros }}</td>
              </tr>
            </tbody>
          </table>

          <!-- ENTRADA PRINCIPAL -->

          @include('partials.view-pdf.entrada_principal')

          <!-- SALA COMEDOR HALL BALCON -->

          @include('partials.view-pdf.sala_comedor_hall')

          <!-- ALCOBA PRINCIPAL -->

          @include('partials.view-pdf.alcoba_principal')

          <!-- BAÑO ALCOBA PRINCIPAL -->

          @include('partials.view-pdf.bano_principal')

          <!-- COCINA -->

          @include('partials.view-pdf.cocina')

          <!-- OBSERVACIONES -->
          @if ($inventario->propiedad->observaciones->count() > 0)
            @include('partials.view-pdf.observaciones_propiedad')
          @endif

          <!-- OTRAS HABITACIONES -->

          @include('partials.view-pdf.habitaciones')

          <!-- FIRMAS -->

          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th colspan="12" class="text-center">FIRMAS</th>
              </tr>
            </thead>
            <tbody>

              <tr>
                <td colspan="12">
                  <p>Las partes expresamente declaran, que el inmueble objeto del contrato de arrendamiento ha sido entregado por el arrendador conforme al
                    presente inventario, a la persona delegada por el arrendatario para recibirlo.<br>
                    Los arrendatarios se comprometen a conservar y mantener el inmueble y su correspondiente dotación, en el mismo estado en el que lo reciben,
                    salvo los deterioros naturales originados por el adecuado uso del mismo. El arreglo de los daños resultantes del inadecuado uso o del descuido
                    durante el tiempo de la tenencia serán asumidos por el arrendatario. Si estos arreglos no se realizaran, queda el arrendador autorizado para
                    hacerlos por su cuenta y para cobrar ejecutivamente las sumas correspondientes a los arrendatarios; para este efecto convienen las partes en
                    que tanto el contrato firmado como las facturas de reparación de daños o de reposición de faltantes, prestaran merito ejecutivo.
                    Para constancia de nuestra conformidad firmamos el presente documento el día <b><u>{{ $inventario->created_at->toDateString() }}</u></b>.
                </td>
              </tr>

              <tr>
                <td colspan="6">
                  <h6><b>Firma arrendatario:</b></h6>
                  <img src="data:image/png;base64,{{ $inventario->firma->firma_arrendatario }}" width="350px" height="150px">
                </td>
                <td colspan="6">
                  <h6><b>Firma arrendador:</b></h6>
                    <img src="data:image/png;base64,{{ $inventario->firma->firma_arrendador }}" width="350px" height="150px">
                </td>
              </tr>
            </tbody>
          </table>

    </div>

  </body>

  <footer class="text-center py-4">
    <span>2024 &copy; Todos los derechos reservados <b>Cartagena Norte Inmobiliaria</b></span><br>
    <small style="font-size: 13px;">Versión 1.0 | Desarrollador por <u>Sebastian Henao Cardona</u></small>
  </footer>

  <style>
    body{
      font-family: "Montserrat", sans-serif;
    }
    .container-title{
      background: #4f4f4f3b;
      display: block;
      margin: auto;
      padding: 10px 20px;
      border-radius: 50px;
    }
    table td{
        font-size: 15px;
    }
  </style>

</html>