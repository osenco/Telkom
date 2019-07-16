<?php

namespace Osen\Telkom;

class RSAEncryptPassword {
	public function __construct($cert_path, $app_password) {
		// dumb constructor...
		self::$cert = $cert_path;
		self::$app_password = $app_password;
	}

	public function EncryptPassword() {
		if (!file_exists(self::$cert)){
			return("<p>Certificate does not exist in <em>".self::$cert."</em>");
		}
		$file_size = filesize(self::$cert);
		$cert_path_handle = fopen(self::$cert, "r");
		$cert_data = fread($cert_path_handle, $file_size);
		fclose($cert_path_handle);
		$cert_data = openssl_x509_read($cert_data);
		$public_key = openssl_get_publickey($cert_data);
		openssl_public_encrypt(self::$app_password, $encrypted_text, $public_key);
		return(base64_encode($encrypted_text));
	}
}
/*
* sample output of an encrypted password, in one line
*
* BDl6WkGmpuGZeHBzLcXzcE6Eup8RH6ABOIhUag9EWXI2kffcTd46L3jHfPjL5Lys
* cfQouTCqxglmJg7qKnoz0w9vich32gBvz4BZE7ppn1pGFuladiuqZMQ5XWfKtalr
* K06qKjF/GRC4kHw7Zv4D8yrIa75y+vGGfhZFqnELux79RRmPv+BN/193Bffph1Ic
* jACEaIrqMLRBfEPFgquwfQ4ji1Rm1gtB4gurH6905YcR9TN+rI531VqH8rVvfi/Z
* D+YkSlP4HTixL6d3kHLgff/P8PyEGmSnDh/zkhv31k/fhRWq2sAgNwYqRtMX9OIF
* GVEwHyt0zxI9fxXcQUpTOw==
*/
// assumes certificate is in the same directory as this file
$cert_path = dirname(__FILE__) . "\\php_telepinuatrsa.cer";
$app_password = 'siriyangu!';
$pass_enc = new RSAEncryptPassword($cert_path, $app_password);
$encrypted_password = $pass_enc->EncryptPassword();
echo $encrypted_password;
