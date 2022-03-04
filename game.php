<?php
    include_once("connection.php");

    if(!isset($_POST["player1"]) || !isset($_POST["player2"])){
        header("Location:index.php");
    }else{
        if (isset($_POST['play']) || isset($_POST['new'])){
            $player1 = $_POST['player1'];
            $player2 = $_POST['player2'];

            if (isset($_POST['new'])){
                $id = $_POST['id'];
                $query = "update games set Status = 'stopped' WHERE ID_game = '$id'";
                mysqli_query($con, $query);
            }

            $query_c = "select * from games where Status = 'unfinished' and Player_1 = '$player1' and Player_2 = '$player2' and ID_game = (select Max(ID_game) from games)";
            $result_c = mysqli_query($con,$query_c);
            $row_c = mysqli_fetch_array($result_c, MYSQLI_ASSOC);

            if ($row_c != null){

                $id_game = $row_c['ID_game'];

                if(isset($id_game)){
                    session_start();
                    $_SESSION["id_game"] = $id_game;
                    $_SESSION["player_1"] = $row_c['Player_1'];
                    $_SESSION["player_2"] = $row_c['Player_2'];
                }

                header("Location: option.php");
            }else{
                $query = "insert into games (Player_1,Player_2,Status,Winner) values ('$player1','$player2','-','-')";
                mysqli_query($con, $query);

                $query_id = "select * from games WHERE ID_game = (select Max(ID_game) FROM games)";
                $result = mysqli_query($con,$query_id);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $id_game = $row['ID_game'];

                if(isset($id_game)){
                    session_start();
                    $_SESSION["id_game"] = $id_game;
                    $_SESSION["player_1"] = $row['Player_1'];
                    $_SESSION["player_2"] = $row['Player_2'];
                }
            }
        }

        if (isset($_POST['resume'])){
            $id = $_POST['id'];
            $player1 = $_POST['player1'];
            $player2 = $_POST['player2'];

            session_start();
            $_SESSION["id_game"] = $id;
            $_SESSION["player_1"] = $player1;
            $_SESSION["player_2"] = $player2;

            $query = "select * from game_structure where ID_game = '$id'";
            $result = mysqli_query($con,$query);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $c00=$row['c00']; $c01=$row['c01']; $c02=$row['c02'];
            $c10=$row['c10']; $c11=$row['c11']; $c12=$row['c12'];
            $c20=$row['c20']; $c21=$row['c21']; $c22=$row['c22'];
            $playerTurn=$row['playerTurn'];
        }

    }


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>X and 0</title>

    <link href="css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <style>
        tr td div{
            width: 95%;
            height: 95%;
            align-content: center;
        }

        input[type='submit']{
            width: 60%;
            height: 60%;
            margin: 1%;
        }

        td{
            width: 100px;
            height: 100px;
        }

        table{
            margin: 0 auto;
        }
    </style>

</head>
<body>

    <div class="container">
        <div class="row m-3 justify-content-center align-items-center">
            <div class="col-auto p-4 shadow-lg rounded">

                <div class="row">
                    <div class="col">
                        <label for="player1">Player 1</label>
                        <input type="text" class="form-control bg-success text-white" id="player1" value=<?php echo $player1?> disabled>
                    </div>
                    <div class="col">
                        <label for="player2">Player 2</label>
                        <input type="text" class="form-control bg-danger text-white" id="player2" value=<?php echo $player2?> disabled>
                    </div>
                </div>
                <br>

                <div id="div1" class="text-center">
                    <div id="div2" class="badge text-wrap text-center" style="width: 40%; background-color: #90EE90;">
                        Player 1 moves
                    </div>
                </div>
                <br>

                <form action="result.php" method="post">
                    <table>
                        <tr>
                            <td><div class="btn btn-secondary btn-sq-responsive b" id="c00" onclick="colorButton(this)"></div></td>
                            <td><div class="btn btn-secondary btn-sq-responsive b" id="c01" onclick="colorButton(this)"></div></td>
                            <td><div class="btn btn-secondary btn-sq-responsive b" id="c02" onclick="colorButton(this)"></div></td>
                        </tr>
                        <tr>
                            <td><div class="btn btn-secondary btn-sq-responsive b" id="c10" onclick="colorButton(this)"></div></td>
                            <td><div class="btn btn-secondary btn-sq-responsive b" id="c11" onclick="colorButton(this)"></div></td>
                            <td><div class="btn btn-secondary btn-sq-responsive b" id="c12" onclick="colorButton(this)"></div></td>
                        </tr>
                        <tr>
                            <td><div class="btn btn-secondary btn-sq-responsive b" id="c20" onclick="colorButton(this)"></div></td>
                            <td><div class="btn btn-secondary btn-sq-responsive b" id="c21" onclick="colorButton(this)"></div></td>
                            <td><div class="btn btn-secondary btn-sq-responsive b" id="c22" onclick="colorButton(this)"></div></td>
                        </tr>
                    </table>
                    <br>

                    <div class="text-center">
                        <input type="hidden" name="playerTurn" id="playerTurn">
                        <input type="submit" class="btn btn-primary btn-sq-responsive" name="submit" value="SUBMIT">
                        <input type="submit" class="btn btn-primary btn-sq-responsive" name="leave" value="LEAVE">
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        var playerTurn = 1;
        var count = 0;
        var cells = document.getElementsByClassName("b");
        var div1 = document.getElementById("div1");
        var div2 = document.getElementById("div2");
        var pT = document.getElementById("playerTurn");

        var game = [
            [3, 4, 5],
            [6, 7, 8],
            [9, 10, 11]
        ];

        <?php if (isset($_POST['resume'])){ ?>

            var c00 = '<?=$c00?>'; var c01 = '<?=$c01?>'; var c02 = '<?=$c02?>';
            var c10 = '<?=$c10?>'; var c11 = '<?=$c11?>'; var c12 = '<?=$c12?>';
            var c20 = '<?=$c20?>'; var c21 = '<?=$c21?>'; var c22 = '<?=$c22?>';
            pT = '<?=$playerTurn?>';

            game = [
                [c00, c01, c02],
                [c10, c11, c12],
                [c20, c21, c22]
            ];


            if (pT == 1){
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%; background-color: #90EE90;");
                div2.setAttribute("class", "badge text-wrap text-center");
                div2.textContent = "Player 1 moves";
            }else if (pT == 2){
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%; background-color: #ff5a36;");
                div2.setAttribute("class", "badge text-wrap text-center");
                div2.textContent = "Player 2 moves";
            }

            var t=0;
            count=0;

            for (var i = 0; i < 3; i++){
                for (var j = 0; j < 3; j++){
                    if (game[i][j] == 1){
                        cells[t].setAttribute("class", "btn btn-success btn-sq-responsive b");
                        count++;
                    }
                    if(game[i][j] == 2){
                        cells[t].setAttribute("class", "btn btn-danger btn-sq-responsive b");
                        count++;
                    }
                    t++;
                }
            }

        <?php } ?>

        function colorButton(b){
            var id = b.id;

            if (playerTurn == 1){

                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%; background-color: #ff5a36;");
                div2.setAttribute("class", "badge text-wrap text-center");
                div2.textContent = "Player 2 moves";

                b.setAttribute("class", "btn btn-success btn-sq-responsive b");

                if(id == "c00"){
                    b.innerHTML = '<input type="hidden" name="c00" value="1">';
                    game [0][0] = 1;
                }else if(id == "c01"){
                    b.innerHTML = '<input type="hidden" name="c01" value="1">';
                    game [0][1] = 1;
                }else if(id == "c02"){
                    b.innerHTML = '<input type="hidden" name="c02" value="1">';
                    game [0][2] = 1;
                }else if(id == "c10"){
                    b.innerHTML = '<input type="hidden" name="c10" value="1">';
                    game [1][0] = 1;
                }else if(id == "c11"){
                    b.innerHTML = '<input type="hidden" name="c11" value="1">';
                    game [1][1] = 1;
                }else if(id == "c12"){
                    b.innerHTML = '<input type="hidden" name="c12" value="1">';
                    game [1][2] = 1;
                }else if(id == "c20"){
                    b.innerHTML = '<input type="hidden" name="c20" value="1">';
                    game [2][0] = 1;
                }else if(id == "c21"){
                    b.innerHTML = '<input type="hidden" name="c21" value="1">';
                    game [2][1] = 1;
                }else if(id == "c22"){
                    b.innerHTML = '<input type="hidden" name="c22" value="1">';
                    game [2][2] = 1;
                }

                playerTurn = 2;
                pT.value = playerTurn;
            }else if (playerTurn == 2){

                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%; background-color: #90EE90;");
                div2.setAttribute("class", "badge text-wrap text-center");
                div2.textContent = "Player 1 moves";

                b.setAttribute("class", "btn btn-danger btn-sq-responsive b");

                if(id == "c00"){
                    b.innerHTML = '<input type="hidden" name="c00" value="2">';
                    game [0][0] = 2;
                }else if(id == "c01"){
                    b.innerHTML = '<input type="hidden" name="c01" value="2">';
                    game [0][1] = 2;
                }else if(id == "c02"){
                    b.innerHTML = '<input type="hidden" name="c02" value="2">';
                    game [0][2] = 2;
                }else if(id == "c10"){
                    b.innerHTML = '<input type="hidden" name="c10" value="2">';
                    game [1][0] = 2;
                }else if(id == "c11"){
                    b.innerHTML = '<input type="hidden" name="c11" value="2">';
                    game [1][1] = 2;
                }else if(id == "c12"){
                    b.innerHTML = '<input type="hidden" name="c12" value="2">';
                    game [1][2] = 2;
                }else if(id == "c20"){
                    b.innerHTML = '<input type="hidden" name="c20" value="2">';
                    game [2][0] = 2;
                }else if(id == "c21"){
                    b.innerHTML = '<input type="hidden" name="c21" value="2">';
                    game [2][1] = 2;
                }else if(id == "c22"){
                    b.innerHTML = '<input type="hidden" name="c22" value="2">';
                    game [2][2] = 2;
                }

                playerTurn = 1;
                pT.value = playerTurn;
            }


            if(game[0][0]==game[0][1] && game[0][0]==game[0][2]){
                for (var i = 0; i < cells.length; i++){
                    cells[i].removeAttribute("onclick");
                }
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%;");
                div2.setAttribute("class", "badge text-wrap text-center bg-primary");
                div2.textContent = "Please, submit the game";
            }else if(game[1][0]==game[1][1] && game[1][0]==game[1][2]){
                for (var i = 0; i < cells.length; i++){
                    cells[i].removeAttribute("onclick");
                }
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%;");
                div2.setAttribute("class", "badge text-wrap text-center bg-primary");
                div2.textContent = "Please, submit the game";
            }else if(game[2][0]==game[2][1] && game[2][0]==game[2][2]){
                for (var i = 0; i < cells.length; i++){
                    cells[i].removeAttribute("onclick");
                }
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%;");
                div2.setAttribute("class", "badge text-wrap text-center bg-primary");
                div2.textContent = "Please, submit the game";
            }else if(game[0][0]==game[1][0] && game[0][0]==game[2][0]){
                for (var i = 0; i < cells.length; i++){
                    cells[i].removeAttribute("onclick");
                }
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%;");
                div2.setAttribute("class", "badge text-wrap text-center bg-primary");
                div2.textContent = "Please, submit the game";
            }else if(game[0][1]==game[1][1] && game[0][1]==game[2][1]){
                for (var i = 0; i < cells.length; i++){
                    cells[i].removeAttribute("onclick");
                }
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%;");
                div2.setAttribute("class", "badge text-wrap text-center bg-primary");
                div2.textContent = "Please, submit the game";
            }else if(game[0][2]==game[1][2] && game[0][2]==game[2][2]){
                for (var i = 0; i < cells.length; i++){
                    cells[i].removeAttribute("onclick");
                }
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%;");
                div2.setAttribute("class", "badge text-wrap text-center bg-primary");
                div2.textContent = "Please, submit the game";
            }else if(game[0][0]==game[1][1] && game[0][0]==game[2][2]){
                for (var i = 0; i < cells.length; i++){
                    cells[i].removeAttribute("onclick");
                }
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%;");
                div2.setAttribute("class", "badge text-wrap text-center bg-primary");
                div2.textContent = "Please, submit the game";
            }else if(game[2][0]==game[1][1] && game[2][0]==game[0][2]){
                for (var i = 0; i < cells.length; i++){
                    cells[i].removeAttribute("onclick");
                }
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%;");
                div2.setAttribute("class", "badge text-wrap text-center bg-primary");
                div2.textContent = "Please, submit the game";
            }else if(count == 8){
                for (var i = 0; i < cells.length; i++){
                    cells[i].removeAttribute("onclick");
                }
                div1.setAttribute("class", "text-center");
                div2.setAttribute("style", "width: 40%;");
                div2.setAttribute("class", "badge text-wrap text-center bg-primary");
                div2.textContent = "Please, submit the game";
            }

            count++;
        }

    </script>
</body>
</html>