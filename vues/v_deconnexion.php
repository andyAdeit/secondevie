<?php
	session_start();
	unset($_SESSION['id']);
	unset($_SESSION['nom']);
	unset($_SESSION['prenom']);
	unset($_SESSION['mail']);
	unset($_SESSION['rang']);
	unset($_SESSION['tel']);
	unset($_SESSION['adresse']);
	unset($_SESSION['cp']);
	unset($_SESSION['ville']);
	unset($_SESSION['livraison']);
	header("Location:index.php");
?>


<SCRIPT LANGUAGE="JavaScript">
document.location.href="index.html"
</SCRIPT>


