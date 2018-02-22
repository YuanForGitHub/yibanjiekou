<!-- nav -->
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <a href="#" class="navbar-brand">欢迎使用，{{ $user['info']['yb_username'] }}</a>
    <a href="{{ url('revoke') }}" class="btn btn-lg btn-primary pull-right">取消授权</a>
</nav>