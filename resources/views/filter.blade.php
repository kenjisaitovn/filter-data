<!DOCTYPE html>
<html lang="en">
    <head>
        @include('elements.header')
    </head>
    <body>
        @include('elements.nav')

        <div class="col-xs-12 container">
            <div class="col-xs-12">
                <div class="col-xs-2 ">
                    <button type="button" class="btn btn-primary" id="btnFilterFb" ></button>
                </div>
                <div class="col-xs-2 ">
                    <button type="button" class="btn btn-primary" id="btnFilterGgQuery" ></button>
                </div>
                <div class="col-xs-2 ">
                    <button type="button" class="btn btn-primary" id="btnFilterQuery" ></button>
                </div>
            </div>

            <hr class="col-xs-12">

            <div class="col-xs-12">
                <div class="col-xs-2 ">
                    <button type="button" class="btn btn-danger" id="btnResetOffset" >Do it again!</button>
                </div>
                <div class="col-xs-10">
                    <label class="text-primary">Processing row... <span class="text-warning" id="processingRow"></span></label>
                    <br>
                    <label class="text-primary">Inserting: <span class="text-warning" id="inserting"></span></label>
                    <br>
                    <label class="text-primary">Total Inserted: <span class="text-warning" id="inserted"></span></label>
                </div>
            </div>

            <hr class="col-xs-12">
            <div class="col-xs-12 result"></div>

        </div>

        <script>
            var apiPath = '{{ url('/filter') }}';
            var _token = '{{ csrf_token() }}';
        </script>
    </body>
</html>
