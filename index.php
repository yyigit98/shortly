<?php 
    include './connection/db.php';
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        .vertical-center {
            min-height: 100%;
            /* Fallback for browsers do NOT support vh unit */
            min-height: 100vh;
            /* These two lines are counted as one :-)       */

            display: flex;
            align-items: center;
        }
    </style>
    <title>Short.ly</title>
</head>

<body>
    <div class="container-fluid vertical-center justify-content-center">
        <div class="col-md-6">
            <img src="./img/logo.png" alt="" class="mx-auto d-block" width="520"
                style="margin-bottom: -40px; margin-top: -100px;">
            <div class="col-md-12">
                <form method="post" action="./service.php">
                    <input class="form-control" name="original_link" type="text" id="" style="border-radius: 24px;">

                    <div class="justify-content-center" style="text-align: center;">

                        <button type="submit" style="border:1px solid #ddd; margin:10px" type="button" class="btn btn-light btn-lg ">
                            Link Kısalt
                        </button>
                        <div style="margin-top:5%">
                    <?php 
                        // url üzerinde status değeri varsa link kısaltım işlemi gerçekleştirilmişstir.
                        if(isset($_GET['status'])){
                            if($_GET['status'] == 1 && isset($_GET['link'])){
                                // status 1 ise işlem başarılıdır
                                echo '
                                <div class="alert alert-success" role="alert">
                                    Link kısaltma işlemi başarılı <br>
                                    <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="generate_link" placeholder="" value="http://localhost/shortly/service.php?url='.$_GET['link'].'">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" onClick="copyFunction()" type="button" id="button-addon2">Kopyala</button>
                                        </div>
                                    </div>
                                </div>';
                            }else if($_GET['status'] == 0){
                                 // status 0 ise işlem gerçekleştirilememiştir.
                                echo '
                                <div class="alert alert-danger" role="alert">
                                    Link kısaltma yapılamadı
                                </div>';
                            }
                        }

                        if(isset($_GET['deleted'])){
                            if($_GET['deleted'] == 'ok'){
                                echo '
                                    <div class="alert alert-success">
                                        Silme işlemi başarılı 
                                    </div>
                                ';
                            }else{
                                echo '
                                    <div class="alert alert-danger">
                                        Silme işlemi gerçekleştirilemedi. 
                                    </div>
                                ';
                            }
                        }
                    ?> 

                    </div> 
                </div>
                </form>
            </div>

            <div style="border:1px solid #aaa; max-height: 300px; height: 300px; margin-top: 5% ; overflow: auto;"
                class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Önceki Link</th>
                            <th scope="col">Kısaltılmış Link</th>
                            <th scope="col">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = $db->query("SELECT * FROM links", PDO::FETCH_ASSOC);
                            if ( $query->rowCount() > 0 ){
                                $startTag = '<tr>';
                                $endTag = '</tr>';
                                $render = '';
                                 foreach( $query as $row ){
                                    $render .= $startTag;
                                    $render.='
                                    <th scope="row">'. $row["id"] .'</th><td><a target="_blank" href="'.$row["original_link"].'">'. substr($row["original_link"],0,20) .' </a></td>
                                    <td>
                                        <a target="_blank" href="http://localhost/shortly/service.php?url='. $row["converted_link"] .'">http://localhost/shortly/service.php?url='. $row["converted_link"] .'</a>
                                    </td>';
                                    $render .= '<td>
                                    <form method="post" action="./service.php">
                                        <button type="submit" value="'.$row['id'].'" name="delete" class="btn btn-danger btn-sm">
                                            Linki sil
                                        </button>';
                                    if($row['status'] == 1){
                                        $render .= '
                                        <button name="pasif" value="'.$row['id'].'" class="btn btn-secondary ml-2 btn-sm">
                                            Pasifleştir
                                        </button>';
                                    }else{
                                        $render .= '
                                        <button name="aktif" value="'.$row['id'].'" class="btn btn-success ml-2 btn-sm">
                                            Aktifleştir
                                        </button>';
                                    }

                                    $render .= ' </form> </td>';

                                    $render.=$endTag;
                                 }
                                 echo $render;
                            }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>

        <script>
            function copyFunction() {
            var copyText = document.getElementById("generate_link");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
            }
        </script>
</body>

</html>