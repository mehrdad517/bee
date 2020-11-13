<style>
    a{
        text-decoration: none;
        font-family: "Segoe UI";
        font-size: 16px;
    }
    h1 {
        font-family: "Segoe UI";
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
        window.location = "{{ env('WEB_URL') }}" + '/card';
    }, 2000)
</script>
