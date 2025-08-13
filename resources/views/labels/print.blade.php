<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Print Label</title>
</head>

<body style="margin:0; padding:0; display:grid; place-items:center; background:white;">
    @include('labels.partials.renderer', ['label' => $label])
    <script>
        window.onload = () => window.print();
    </script>
</body>

</html>
