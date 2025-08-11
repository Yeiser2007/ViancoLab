<script>
    @auth
        window.Laravel = {!! json_encode([
            'user'          => Auth::user(), 
            'permissions'   => Auth::user()->getCanPermissions(),
            'roles'         => Auth::user()->getRoleNames()
        ], true) 
        !!};
    @else
        window.Laravel = null;
    @endauth
</script>