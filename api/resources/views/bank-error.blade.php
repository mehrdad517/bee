<style>
    a{
        text-decoration: none;
        font-family: Tahoma, Helvetica, Arial;
    }
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50%;
        margin: 0 auto;
        flex-direction: column;
    }
</style>
<div class="container">
    <h1>{{ $msg }}</h1>
    <img src="{{ asset('/error.jpg') }}">
    <a href="{{ env('WEB_URL') . '/card' }}">بازگشت به سایت</a>
</div>
<script type="text/javascript">
    setTimeout(() => {
        window.location = {{ env('WEB_URL') }} + '/card';
    }, 500)
</script>
