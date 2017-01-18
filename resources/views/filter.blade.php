<!DOCTYPE html>
<html lang="en">
    <head>
        @include('elements.header')
    </head>
    <body>
        <p></p>
        <div class="col-xs-12">
            <div class="col-xs-1 npdlr">
                <button type="button" class="btn btn-primary" id="btnFilterFb"></button>
            </div>
            <div class="col-xs-1 npdlr">
                <button type="button" class="btn btn-primary" id="btnFilterGgQuery"></button>
            </div>
        </div>

        <hr class="col-xs-12">

        <div class="col-xs-12">
            <div class="col-xs-2 npdlr">
                <input type="button" class="btn btn-danger" value="Do it again!" id="btnResetOffset">
            </div>
        </div>

        <hr class="col-xs-12">

        <div class="col-xs-12">
            <label class="text-primary">Processing row... <span class="text-warning" id="processingRow"></span></label>
            <br>
            <label class="text-primary">Inserting: <span class="text-warning" id="inserting"></span></label>
            <br>
            <label class="text-primary">Total Inserted: <span class="text-warning" id="inserted"></span></label>
        </div>

        <script>
            var apiPath = '{{ url('/filter') }}';
            var _token = '{{ csrf_token() }}';
        </script>
    </body>
</html>
