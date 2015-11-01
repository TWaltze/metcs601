<!--
Answer:

The form is making a post request, and the php code was checking the $_GET array (incorrectly at that).
Not only was it checking the wrong variable ($_get vs $_GET), but it was looking for "number" as opposed
to the value's actual key, phone. The name attribute is what determines a value's key in requests.
It also didn't wrap the key in quotations.
-->

<form method="post" action="q8.php">
    <input type="number" value="test" name="phone" />
</form>

<?php
if (array_key_exists('phone', $_POST)) {
    $num = $_POST['phone'];
    var_dump($num);
}
?>
