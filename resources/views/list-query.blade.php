<!DOCTYPE html>
<html lang="en">
    <head>
        @include('elements.header')
    </head>
    <body>
    @include('elements.nav')
        <div class="col-xs-12 container">
            <div class="col-xs-2">
                @if( !empty($filterBy) )
                <label>{{ 'Filter by: '.$filterBy }} <a href="{{ url('list-query') }}" title="Clear filter"><i class="fa fa-times"></i></a> </label>
                @endif
            </div>
            <div class="auto-scroll-bar col-xs-12">
            <div class="col-xs-12" style="text-align: center">{{ $other->render() }}</div>
            <table class="table table-responsive table-hover col-xs-12">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Domain</th>
                    <th>Class</th>
                    <th>Value</th>
                    <th>ip address</th>
                    <th>Created at</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($other))
                    @foreach($other as $k=>$v)
                    <tr>
                        <th scope="row">{{ $k+1 }}</th>
                        <td><a href="{{ url('list-query?filter-domain='.$v['dm']) }}" title="Click to filter by this domain">{{ $v['dm'] }}</a> <a target="_blank" href="http://{{ $v['dm'] }}" title="Visit website"><i class="fa fa-external-link"></i></a> </td>
                        <td>{{ $v['cn'] }}</td>
                        <td>{{ $v['vl'] }}</td>
                        <td><a href="{{ url('list-query?filter-ip='.$v['ip']) }}" title="Click to filter by this Ip">{{ $v['ip'] }}</a>
                            <a target="_blank" href="http://whatismyipaddress.com/ip/{{ $v['ip'] }}"><i class="fa fa-info-circle"></i></a>  </td>
                        <td>{{ $v['created_at'] }}</td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            <div class="col-xs-12" style="text-align: center">{{ $other->render() }}</div>
            </div>

        </div>

    </body>
</html>
