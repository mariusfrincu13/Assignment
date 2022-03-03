<?php
    session_start();

    $id = $_SESSION['id_game'];
    $player1 = $_SESSION['player_1'];
    $player2 = $_SESSION['player_2'];

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>X and 0</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        input[type="submit"]{
            margin: 2%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row vh-100 justify-content-center align-items-center">
        <div class="col-auto p-5 shadow-lg mb-5 rounded">

            <div class="row">
                <div class="col">
                    <label for="player1">Player 1</label>
                    <input type="text" class="form-control bg-success text-white" value=<?php echo $player1?> disabled>
                </div>
                <div class="col">
                    <label for="player2">Player 2</label>
                    <input type="text" class="form-control bg-danger text-white" value=<?php echo $player2?> disabled>
                </div>
            </div>
            <br>

            <form action="game.php" method="post" class="text-center">
                <input type="hidden" name="player1" value=<?php echo $player1 ?> >
                <input type="hidden" name="player2" value=<?php echo $player2 ?> >
                <input type="hidden" name="id" value=<?php echo $id ?> >
                <input type="submit" class="btn btn-primary w-50" name="resume" value="RESUME GAME">
                <input type="submit" class="btn btn-primary w-50" name="new" value="NEW GAME">
            </form>
        </div>
    </div>
</div>

</body>
</html>
