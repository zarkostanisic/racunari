<?php session_start();
    include("logovanje.inc");
    $uloga=$_SESSION['uloga'];
    if($uloga!="administrator"){
    header("Location:index.php");
    }

    $id = "";
    if(isset($_GET['id'])){
      $id = $_GET['id'];
    }

    if(isset($_POST['id'])){
      $id = $_POST['id'];
    }

    $title = "Pregled narudžbine | Administracija | " . $site_name;

    if(isset($_POST['potvrdi'])){
      if(mysql_query("UPDATE narudzbine SET status='1', datum_realizacije='" . time() . "' WHERE id_narudzbine='$id'", $konekcija)){
        $ispis = "<div class='komentar' style='margin-left:70px;text-align:center;'>Narudžbina je ralizovana.</div>";
      }else{
        $ispis = "<div class='komentar' style='margin-left:70px;text-align:center;'>Greška</div>";
      }
    }

    if(isset($_POST['ponisti'])){
      if(mysql_query("UPDATE narudzbine SET status='2', datum_realizacije='0' WHERE id_narudzbine='$id'", $konekcija)){
        $ispis = "<div class='komentar' style='margin-left:70px;text-align:center;'>Narudžbina je poništena.</div>";
      }else{
        $ispis = "<div class='komentar' style='margin-left:70px;text-align:center;'>Greška</div>";
      }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<?php
  include_once('head.inc');
 ?>
</head>
<body>
<div id="main_container">
  <div class="top_bar">
      <?php
      include("logovanje_forma.inc");//logovanje korisnika
    ?>
    </div>
  <div id="header">
    <?php
      include("slajder.inc");//slajder
    ?>
    </div>
    <div id="main_content">
    <div id="menu_tab">
      <?php
        include("meni.inc");//gornji meni
      ?>
    </div><!-- end of menu tab -->
   <div class="left_content">
    <?php
      include("levi_meni.inc");//levi meni
    ?>
  </div><!-- end of left content -->
   <div class="center_content">
    <div class="center_title_bar">Pregled narudžbine</div>
    <?php
      $upit_narudzbine="SELECT n.id_narudzbine, n.status, n.datum_realizacije, n.datum, k.korisnicko_ime, k.ime, k.prezime, k.telefon, k.email, k.grad, n.total  FROM narudzbine n JOIN korisnici k ON n.id_korisnika=k.id_korisnika WHERE n.id_narudzbine='$id'";
      $rezultat_narudzbine=mysql_query($upit_narudzbine,$konekcija);
      $broj_narudzbine=mysql_num_rows($rezultat_narudzbine);
      if($rezultat_narudzbine){
          $narudzbina=mysql_fetch_array($rezultat_narudzbine);
          $status = "";

          if($narudzbina['status'] == 0){
            $status = "Nerealizovana";
          }else if($narudzbina['status'] == 1){
            $status = "Realizovana";
          }else if($narudzbina['status'] == 2){
            $status = "Poništena";
          }
          $datum_realizacija = "";
          if($narudzbina['datum_realizacije'] == 0){
            $datum_realizacija = "/";
          }else{
            $datum_realizacija = date('d.m.Y H:i:s', $narudzbina['datum_realizacije']);
          }

          echo '<table style="margin-left:100px;">
            <tr>
              <td class="naslov_tabele" colspan="2">Podaci o narudžbini</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">ID narudžbine</td>
              <td class="druga_kolona short">' . $narudzbina['id_narudzbine'] . '</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">Status</td>
              <td class="druga_kolona short">' . $status . '</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">Datum naručivanja</td>
              <td class="druga_kolona short">' . date('d.m.Y H:i:s', $narudzbina['datum']) . '</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">Datum realizacije</td>
              <td class="druga_kolona short">' . $datum_realizacija . '</td>
            </tr>
            <tr>
              <td class="naslov_tabele" colspan="2">Podaci o korisniku</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">Korisničko ime</td>
              <td class="druga_kolona short">' . ucfirst($narudzbina['korisnicko_ime']) . '</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">Ime</td>
              <td class="druga_kolona short">' . ucfirst($narudzbina['ime']) . '</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">Prezime</td>
              <td class="druga_kolona short">' . ucfirst($narudzbina['prezime']) . '</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">Telefon</td>
              <td class="druga_kolona short">' . $narudzbina['telefon'] . '</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">Email</td>
              <td class="druga_kolona short">' . strtolower($narudzbina['email']) . '</td>
            </tr>
            <tr>
              <td class="prva_kolona short left">Grad</td>
              <td class="druga_kolona short">' . ucfirst($narudzbina['grad']) . '</td>
            </tr>
            ';
            if($narudzbina['status'] == 1){
              $status = "<img src='css/images/confirm.png'/>";
            }else if($narudzbina['status'] == 0){
              $status = "<img src='css/images/cancel.png'/>";
            }
          echo "</table>";

          $upit_artikli = "SELECT * FROM artikli a JOIN proizvodi p ON a.id_proizvoda=p.id_proizvoda WHERE a.id_narudzbine='$id'";
          $rez_artikli = mysql_query($upit_artikli, $konekcija);
          echo "<table style='margin-left:100px;margin-bottom:50px;'>";
          echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
          echo '<tr>
              <td class="naslov_tabele" colspan="4">Podaci o izabranim artiklima</td>
            </tr>';
          echo '<tr><td class="prva_kolona short">Id proizvoda</td>
            <td class="druga_kolona short">Naziv proizvoda</td>
            <td class="prva_kolona short">Količina</td>
            <td class="druga_kolona short">Cena</td>
            ';
          while($artikal = mysql_fetch_array($rez_artikli)){
            echo '<tr><td class="prva_kolona short left">' . $artikal['id_proizvoda'] . '</td>
            <td class="druga_kolona short">' . $artikal['naziv_proizvoda'] . '</td>
            <td class="prva_kolona short">' . $artikal['kolicina'] . '</td>
            <td class="druga_kolona short">' . $artikal['nova_cena'] . ' RSD</td>
            </tr>
            ';
          }
          echo '<tr>
                <td class="prva_kolona short" colspan="4">Ukupna cena: ' . $narudzbina['total'] . ' RSD</td>
              </tr>';
          echo '<tr>
                <td class="prva_kolona short" colspan="4">
                <input type="hidden" name="id" value="' . $narudzbina['id_narudzbine'] . '" class="add_bt"/>
                <input type="submit" name="potvrdi" value="Potvrdi" class="add_bt"/>
                <input type="submit" name="ponisti" value="Poništi" class="add_bt"/>
                </td>
              </tr>';
          echo "</form>";
          echo "</table>";
      }
    ?>
    <?php if(isset($ispis)){ echo $ispis; } ?>
  </div><!-- end of center content -->
  </div><!-- end of main content -->
  <div class="right_content">
    <?php
      include("desni_meni.inc");
    ?>
  </div>
  <div class="footer">
    <?php
      include("futer.inc");//futer
    ?>
  </div>
</div>
<!-- end of main_container -->
</body>
</html>
