<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script src="{{ asset('js/haiht.js') }}"></script>

        <title>Index</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('css/haiht.css') }}">
    </head>
    <body>
        <hr class="col-xs-12">
        <div class="col-xs-12">
            <div class="col-xs-1 npdlr">
                <input type="button" class="btn btn-primary" value="Filter Fb" id="btnFilterFb">
            </div>
            <div class="col-xs-2 npdlr">
                <input type="button" class="btn btn-danger" value="Do it again!" id="btnResetOffset">
            </div>
        </div>

        <hr class="col-xs-12">
        <div class="col-xs-12">
            <div class="col-xs-1 npdlr">
                <input type="button" class="btn btn-primary" value="Filter Google query" id="btnFilterGgQuery">
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
        <hr class="col-xs-12">

        <script>
            var apiPath = '{{ url('/filter') }}';
            var _token = '{{ csrf_token() }}';
        </script>
    </body>
</html>
