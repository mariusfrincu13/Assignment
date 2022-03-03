<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>X and 0</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

</head>
<body>

    <div class="container">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-auto p-5 shadow-lg mb-5 rounded">
                <form action="game.php" method="post">
                    <div class="input-group p-2">
                        <input type="text" class="form-control" name="player1" placeholder="Player 1" required autocomplete="off">
                    </div>
                    <div class="input-group p-2">
                        <input type="text" class="form-control" name="player2" placeholder="Player 2" required autocomplete="off">
                    </div>
                    <br>
                    <input type="submit" class="btn btn-primary w-100" name="play" value="PLAY">
                </form>
            </div>
        </div>
    </div>


</body>
</html>
