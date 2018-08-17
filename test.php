<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="//necolas.github.io/normalize.css/8.0.0/normalize.css">
    <style>
        .main__header{
            display: flex;
            justify-content: space-between;
            padding: 30px;
        }
        .left-block,
        .right-block{
            color: #ddd;
            display: flex;
            justify-content: space-between;
        }
        .left-block svg,
        .right-block svg{
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <aside class="aside"></aside>
    <main class="main">
        <div class="main__header">
            <div class="left-block">
                <i data-feather="sidebar"></i>
                <i data-feather="moon"></i>
                <i data-feather="sun"></i>
            </div>
            <div class="right-block">
                <i data-feather="edit-3"></i>
                <i data-feather="more-horizontal"></i>
            </div>
        </div>

    </main>


    <script>
        feather.replace()
    </script>
</body>
</html>