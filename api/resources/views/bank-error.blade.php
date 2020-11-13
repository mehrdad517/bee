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
    <a  href="{{ env('WEB_URL') . '/card' }}"><b>بازگشت به سایت</b></a>
</div>
<script type="text/javascript">
    setTimeout(() => {
        window.location = 'http://beeapp.ir/card';
    }, 500)
</script>
