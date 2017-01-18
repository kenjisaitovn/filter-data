<br>
<div class="col-xs-12">
    <ul class="nav nav-tabs">
        <li role="presentation" class="{{ !empty($activeMenu) && $activeMenu == 'filter' ? 'active':'' }}"><a href="{{ url('/') }}">Filter Data</a></li>
        <li role="presentation" class="{{ !empty($activeMenu) && $activeMenu == 'fb' ? 'active':'' }}"><a href="{{ url('list-fb') }}">FB Account</a></li>
    </ul>
</div>
<br>
<br>
<br>