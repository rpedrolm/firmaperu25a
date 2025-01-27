<!DOCTYPE html>
<html lang="es">
<head>
    <title>Firma Digital</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        #pdfViewer {
            height: 600px;
            border: 1px solid #ccc;
            width: 100%;
        }
    </style>
</head>
<!-- ---------------------------------------------------------------------------------------------------------- -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="../firmaPe/script/firmaperu-loc.js"></script>  <!-- versión modificada  -->
    <div id="addComponent"> <small>FirmaPe Component</small> </div>

<!-- ---------------------------------------------------------------------------------------------------------- -->
<body>
<div class="container mt-5">
    <h1 class="text-center"> WebApp de integracion con FirmaPerú </h1>
    <hr>

    <div class="row pt-3">
        <!-- Columna para el visor de PDF -->
        <div class="col-md-8">
            <h4>Visor de PDF</h4>
            <iframe id="pdfViewer" src="files/tituloR.pdf" frameborder="0"></iframe>
        </div>

        <!-- Columna para los controles -->



        <div class="col-md-4">
            <h4>Controles</h4>

            <div class="mb-3">
                <label for="pdfInput" class="form-label"> <b>Cargar PDF:</b> </label>
                <input type="file" class="form-control" id="pdfInput" name="loax" accept=".pdf">
            </div>

            <form id="formFirma" method="POST">
                <div class="mb-3">
                    <label for="arch" class="form-label"> <b>Ruta de archivo:</b> </label>
                    <input type="text" class="form-control" id="arch" name="arch">
                </div>

                <div class="mb-3">
                    <label for="cargo" class="form-label"> <b>Texto de Cargo en Firma:</b> </label>
                    <input type="text" class="form-control" id="cargo" name="role" value="Director(e) Adex Perú">
                </div>

                <div class="mb-3">
                    <label for="tipo" class="form-label"> <b>Tipo de Firma:</b> </label>
                    <select class="form-select" id="typeSignature" name="tipo">
                        <option value="1">Horizontal</option>
                        <option value="2">Vertical</option>
                        <option value="3">Solo estampilla</option>
                        <option value="4">Solo descripción</option>
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="batchSigning">
                    <label class="form-check-label" for="batchSigning">Firmar en Lote (.zip,.7z)</label>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="oneSigning" checked>
                    <label class="form-check-label" for="oneSigning">Firmar un solo PDF</label>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="visible" id="visibleSignature" checked>
                    <label class="form-check-label" for="visibleSignature">Firma Visible</label>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="mover" id="moveSignature">
                    <label class="form-check-label" for="moveSignature">Elegir Posicion de Firma</label>
                </div>

            </form>

            <hr>

            <button class="btn btn-primary w-100" onclick="signDocument()">Firmar Documento</button>


        </div>
    </div>
</div>


    <script>

        function signatureInit()
        {
            // alert("FirmaPeru Iniciado");
        }

        function signatureOk()
        {
            // alert("Documento(s) firmado(s)");
            pdfViewer.src = "public/tetolo[F].pdf";
        }

        function signatureCancel()
        {
            alert("Cancelado");
        }

        function sendParam()
        {
            //
            // var param = "ewogInBhcmFtX3VybCI6ICJodHRwczovL2N1cmFwLm0ZW5zaW9uIjogInBkZiIKfQ==";
            var port = "48596";

            jqFirmaPeru.post( "../firmaPe/prepro/", jqFirmaPeru(formFirma).serialize(), function( res ) {

                // console.log( port, res );
                //
                if( res.success )
                    startSignature( port, res.param );

            }, "json");

        }
    </script>


    <script>

        arch.value = pdfViewer.src;

        pdfInput.addEventListener('change', function(event){
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    pdfViewer.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        arch.addEventListener('change', function(event){

            pdfViewer.src = arch.value;

        } );

        function signDocument() {

            // una vista previa
            //
            alert( `Firmar Documento
                    Mostrar Cargo  : ${cargo.value}
                    Firmar en Lote : ${batchSigning.checked}
                    Firmar un PDF  : ${oneSigning.checked}
                    Firma Visible  : ${visibleSignature.checked}
                    Tipo de Firma  : ${typeSignature.value}
                    Mover Firma    : ${moveSignature.checked}` );

            sendParam();
        }
    </script>

</body>
</html>



