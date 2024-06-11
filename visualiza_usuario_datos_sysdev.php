<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visualizamos el título y la descripción</title>
</head>
<body>
    <h1>Visualizamos el título y la descripción</h1>
    <?php
    require 'vendor/autoload.php';
    use Laminas\Ldap\Ldap;
    ini_set('display_errors', 0);

    $config = include('ldap_config.php');

    $ldap_host = $config['ldap_host'];
    $ldap_port = $config['ldap_port'];
    $ldap_dn = $config['ldap_dn'];
    $ldap_user = $config['ldap_user'];
    $ldap_password = $config['ldap_password'];

    $dn = "uid=sysdev,ou=desenvolupadors,dc=fjeclot,dc=net";

    $ldap_conn = ldap_connect($ldap_host, $ldap_port) or die("No se pudo conectar al servidor LDAP.");

    if ($ldap_conn) {
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);

        $bind = ldap_bind($ldap_conn, $ldap_user, $ldap_password);

        if ($bind) {
            $result = ldap_search($ldap_conn, $dn, "(uid=sysdev)", ["title", "description"]);
            $entries = ldap_get_entries($ldap_conn, $result);

            if ($entries["count"] > 0) {
                $current_title = $entries[0]["title"][0];
                $current_description = $entries[0]["description"][0];

                echo "<p><strong>Título:</strong> $current_title</p>";
                echo "<p><strong>Descripción:</strong> $current_description</p>";
            } else {
                echo "<p>No se encontró al usuario sysdev en ou=desenvolupadors.</p>";
            }
        } else {
            echo "Fallo en la autenticación LDAP.";
        }

        ldap_close($ldap_conn);
    } else {
        echo "No se pudo conectar al servidor LDAP.";
    }
    ?>
    <a href="http://zend-dacomo.fjeclot.net/autent/index.php">Torna al menú</a>
</body>
</html>
