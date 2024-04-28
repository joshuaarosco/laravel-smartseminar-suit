<script src="{{asset('assets/plugins/jquery/jquery-1.11.1.min.js')}}"></script>
<script>
    $('.alert-close').on('click', function(){
        $(this).closest('.alert-div').remove();
    })
</script>