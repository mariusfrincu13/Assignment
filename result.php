<?php
    session_start();

    $player1 = $_SESSION['player_1'];
    $player2 = $_SESSION['player_2'];
    $id = $_SESSION["id_game"];
    $c00=0;$c01=0;$c02=0;$c10=0;$c11=0;$c12=0;$c20=0;$c21=0;$c22=0;

    if(isset($_POST['c00'])){
        $c00 = $_POST['c00'];
    }
    if(isset($_POST['c01'])){
        $c01 = $_POST['c01'];
    }
    if(isset($_POST['c02'])){
        $c02 = $_POST['c02'];
    }
    if(isset($_POST['c10'])){
        $c10 = $_POST['c10'];
    }
    if(isset($_POST['c11'])){
        $c11 = $_POST['c11'];
    }
    if(isset($_POST['c12'])){
        $c12 = $_POST['c12'];
    }
    if(isset($_POST['c20'])){
        $c20 = $_POST['c20'];
    }
    if(isset($_POST['c21'])){
        $c21 = $_POST['c21'];
    }
    if(isset($_POST['c22'])){
        $c22 = $_POST['c22'];
    }

    $winner = 0;

    if ($c00==$c01 && $c00==$c02){
        if($c00 == 1){
            $winner = 1;
        }else if($c00 == 2){
            $winner = 2;
        }
    }else if ($c10==$c11 && $c10==$c12){
        if($c10 == 1){
            $winner = 1;
        }else if($c10 == 2){
            $winner = 2;
        }
    }else if ($c20==$c21 && $c20==$c22){
        if($c20 == 1){
            $winner = 1;
        }else if($c20 == 2){
            $winner = 2;
        }
    }else if ($c00==$c10 && $c00==$c20){
        if($c00 == 1){
            $winner = 1;
        }else if($c00 == 2){
            $winner = 2;
        }
    }else if ($c01==$c11 && $c01==$c21){
        if($c01 == 1){
            $winner = 1;
        }else if($c01 == 2){
            $winner = 2;
        }
    }else if ($c02==$c12 && $c02==$c22){
        if($c02 == 1){
            $winner = 1;
        }else if($c02 == 2){
            $winner = 2;
        }
    }else if ($c00==$c11 && $c00==$c22){
        if($c00 == 1){
            $winner = 1;
        }else if($c00 == 2){
            $winner = 2;
        }
    }else if ($c20==$c11 && $c20==$c02){
        if($c20 == 1){
            $winner = 1;
        }else if($c20 == 2){
            $winner = 2;
        }
    }

    include_once("connection.php");

    if (isset($_POST['leave'])){
        $query = "update games set Status = 'unfinished' WHERE ID_game = '$id'";
        mysqli_query($con, $query);

        $query = "select * from game_structure where ID_game = '$id'";
        $result = mysqli_query($con,$query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if(sizeof($row) == null){
            $query_g = "insert into game_structure (ID_game,c00,c01,c02,c10,c11,c12,c20,c21,c22) values ('$id','$c00','$c01','$c02','$c10','$c11','$c12','$c20','$c21','$c22')";
            mysqli_query($con, $query_g);
        }

        header("Location: index.php");
    }

    if (isset($_POST['submit'])){
        if($winner == 1){
            $query = "update games SET Winner = '$player1', Status = 'finished' WHERE ID_game = '$id'";
        }else if($winner == 2){
            $query = "update games SET Winner = '$player2', Status = 'finished' WHERE ID_game = '$id'";
        }else{
            $query = "update games SET Winner = 'Tie', Status = 'finished' WHERE ID_game = '$id'";
        }

        mysqli_query($con, $query);

        $query_g = "insert into game_structure (ID_game,c00,c01,c02,c10,c11,c12,c20,c21,c22) values ('$id','$c00','$c01','$c02','$c10','$c11','$c12','$c20','$c21','$c22')";
        mysqli_query($con, $query_g);
    }

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
        .container{
            width: 40%;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-auto p-5 shadow-lg mb-5 rounded">
                <div class="jumbotron">
                    <h1 class="display-4 text-center"><?php if($winner==1){echo "Winner: <span class='text-success'> $player1 <span>";}else if($winner==2){echo "Winner: <span class='text-danger'> $player2 </span>";}else{echo "<span class='text-warning'> No winner! Tie! </span>";} ?></h1>
                    <hr class="my-4">
                    <p class="lead text-center">
                        <a class="btn btn-primary" href="index.php" role="button">PLAY AGAIN</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<?php
    session_destroy();
?>