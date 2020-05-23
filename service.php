<?php
    // veri tabanı bağlantısını içeri aktarıyorum
    include './connection/db.php';

    

    if(isset($_POST['original_link'])){
        // post içerisinden değer alınır.
        $originalLink = $_POST['original_link'];
        
        if(!empty($originalLink)){
            // kullanıcının gönderdiği değer var ise link kısaltma işlemi başlar
    
            // link unique random bir değer ile eşleştirilir.
            $random = randomUrl(8);
    
            // gelen url datası ile random üretilen değer veri tabanına kayıt edilir.
           $query = $db->prepare("INSERT INTO links SET
            original_link = ?,
            converted_link = ?,
            status = ?");
            $insert = $query->execute(array(
                $originalLink, $random, 1
            ));
            if ( $insert ){
                $last_id = $db->lastInsertId();
               header('Location: ./index.php?status=1&link='.$random);
            } 
        } else {
            // kullanıcı input boşken kısaltma servisini çağırırsa hata döndürülür status = 0
            header('Location: ./index.php?status=0');
        }
    }

    // random url oluşturma fonksiyonu
    function randomUrl($uzunluk=5) {
        // url içerisinde bulunmasını istediğim karakterler
        $karakterler = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // fonksiyon tamamlandığında oluşacak string ifadenin uzunluğu 
        $karakterlerUzunluk = strlen($karakterler);

        // oluşacak url'i tutan değişken 
        $randomUrl = '';
        for ($i = 0; $i < $uzunluk; $i++) {
            // döngü içerisinde her seferinde belirttiğim karakterlerden rastgele bir tanesini seçerek değişkene atıyorum.
            $randomUrl .= $karakterler[rand(0, $karakterlerUzunluk - 1)];
        }

        // oluşan değişkeni kullanmak için return ediyorum.
        return $randomUrl;
    }


    // silme methodu
    if(isset($_POST['delete'])){
        $query = $db->prepare("DELETE FROM links WHERE id = :id");
        $delete = $query->execute(array(
            'id' => $_POST['delete']
        ));

        if($delete){
            header('Location: ./index.php?deleted=ok');
        }else {
            header('Location: ./index.php?deleted=fail');
        }
    }

    // status güncelleme methodu - Aktifleştirme
    if(isset($_POST['aktif'])){
        $query = $db->prepare("UPDATE links SET
        status = :status_deger
        WHERE id = :update_id");
        $update = $query->execute(array(
            "status_deger" => 1,
            "update_id" => $_POST['aktif']
        ));
        if ( $update ){
            header('Location: ./index.php?update=ok');
        }else{
            header('Location: ./index.php?update=fail');
        }
    }

    // status güncelleme methodu - Pasifleştirme
    if(isset($_POST['pasif'])){
        $query = $db->prepare("UPDATE links SET
        status = :status_deger
        WHERE id = :update_id");
        $update = $query->execute(array(
            "status_deger" => 0,
            "update_id" => $_POST['pasif']
        ));
        if ( $update ){
            header('Location: ./index.php?update=ok');
        }else{
            header('Location: ./index.php?update=fail');
        }
    }

    // redirection
    if(isset($_GET['url'])){
        $id = $_GET['url'];
        $query = $db->query("SELECT * FROM links WHERE converted_link = '{$id}'")->fetch(PDO::FETCH_ASSOC);
        
        
        if($query){
            if($query['status'] == 1){
                header('Location:'.$query['original_link']);
            }else if($query['status'] == 0){
                header('Location:404.php?status=pasive');
            }
        }else{
            header('Location:404.php');
        }

    }

?>