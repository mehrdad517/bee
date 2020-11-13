<html lang="fa">
<body>
<script>
    let form = document.createElement("form");
    let element1 = document.createElement("input");

    form.method = "POST";
    form.action = "https://pep.shaparak.ir/payment.aspx";

    element1.value= {!! $token !!};
    element1.name="Token";
    form.appendChild(element1);


    document.body.appendChild(form);

    form.submit();
</script>
</body>
</html>
