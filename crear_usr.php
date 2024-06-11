<?php
require 'vendor/autoload.php';
use Laminas\Ldap\Attribute;
use Laminas\Ldap\Ldap;

ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # Obtener datos del formulario
    $uid = $_POST['uid'];
    $unorg = $_POST['ou'];
    $num_id = $_POST['uidNumber'];
    $grup = $_POST['gidNumber'];
    $dir_pers = $_POST['homeDirectory'];
    $sh = $_POST['loginShell'];
    $cn = $_POST['cn'];
    $sn = $_POST['sn'];
    $nom = $_POST['givenName'];
    $mobil = $_POST['mobile'];
    $adressa = $_POST['postalAddress'];
    $telefon = $_POST['telephoneNumber'];
    $titol = $_POST['title'];
    $descripcio = $_POST['description'];
    $objcl = ['inetOrgPerson', 'organizationalPerson', 'person', 'posixAccount', 'shadowAccount', 'top'];
    
    # Configuración LDAP
    $domini = 'dc=fjeclot,dc=net';
    $opcions = [
        'host' => 'zend-dacomo.fjeclot.net',
        'username' => "cn=admin,$domini",
        'password' => 'fjeclot',
        'bindRequiresDn' => true,
        'accountDomainName' => 'fjeclot.net',
        'baseDn' => 'dc=fjeclot,dc=net',
    ];
    
    try {
        $ldap = new Ldap($opcions);
        $ldap->bind();
        
        $nova_entrada = [];
        Attribute::setAttribute($nova_entrada, 'objectClass', $objcl);
        Attribute::setAttribute($nova_entrada, 'uid', $uid);
        Attribute::setAttribute($nova_entrada, 'uidNumber', $num_id);
        Attribute::setAttribute($nova_entrada, 'gidNumber', $grup);
        Attribute::setAttribute($nova_entrada, 'homeDirectory', $dir_pers);
        Attribute::setAttribute($nova_entrada, 'loginShell', $sh);
        Attribute::setAttribute($nova_entrada, 'cn', $cn);
        Attribute::setAttribute($nova_entrada, 'sn', $sn);
        Attribute::setAttribute($nova_entrada, 'givenName', $nom);
        Attribute::setAttribute($nova_entrada, 'mobile', $mobil);
        Attribute::setAttribute($nova_entrada, 'postalAddress', $adressa);
        Attribute::setAttribute($nova_entrada, 'telephoneNumber', $telefon);
        Attribute::setAttribute($nova_entrada, 'title', $titol);
        Attribute::setAttribute($nova_entrada, 'description', $descripcio);
        
        $dn = 'uid=' . $uid . ',ou=' . $unorg . ',dc=fjeclot,dc=net';
        
        if ($ldap->add($dn, $nova_entrada)) {
            echo "Usuari creat";
        } else {
            echo "Error al crear l'usuari";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario LDAP</title>
</head>
<body>
    <h2>Formulario de Creación de Usuario LDAP</h2>
    <form action="create_user.php" method="post">
        <label for="uid">UID:</label>
        <input type="text" id="uid" name="uid" required><br><br>

        <label for="ou">Unitat Organitzativa:</label>
        <input type="text" id="ou" name="ou" required><br><br>

        <label for="uidNumber">UID Number:</label>
        <input type="number" id="uidNumber" name="uidNumber" required><br><br>

        <label for="gidNumber">GID Number:</label>
        <input type="number" id="gidNumber" name="gidNumber" required><br><br>

        <label for="homeDirectory">Directori Personal:</label>
        <input type="text" id="homeDirectory" name="homeDirectory" required><br><br>

        <label for="loginShell">Shell:</label>
        <input type="text" id="loginShell" name="loginShell" required><br><br>

        <label for="cn">CN:</label>
        <input type="text" id="cn" name="cn" required><br><br>

        <label for="sn">SN:</label>
        <input type="text" id="sn" name="sn" required><br><br>

        <label for="givenName">Given Name:</label>
        <input type="text" id="givenName" name="givenName" required><br><br>

        <label for="postalAddress">Postal Address:</label>
        <input type="text" id="postalAddress" name="postalAddress" required><br><br>

        <label for="mobile">Mobile:</label>
        <input type="text" id="mobile" name="mobile" required><br><br>

        <label for="telephoneNumber">Telephone Number:</label>
        <input type="text" id="telephoneNumber" name="telephoneNumber" required><br><br>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required><br><br>

        <input type="submit" value="Crear Usuario">
        <input type="reset" value="Reiniciar">
    </form>
</body>
</html>
