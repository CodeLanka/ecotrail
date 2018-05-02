<?php
namespace lk\gdgsrilanka\io18;

include_once 'Question.php';
include_once 'Answer.php';
require 'vendor/autoload.php';

class Question_CSR implements Question
{
	public function getClueContent($email, $key)
	{
		// echo $key;

		$privkey = openssl_pkey_new(array(
		    "private_key_bits" => 2048,
		    "private_key_type" => OPENSSL_KEYTYPE_RSA,
		));

		// var_dump($privkey);
		$dn = array(
		    "countryName" => "LK",
		    "stateOrProvinceName" => "Sabaragamuwa",
		    "localityName" => "Sinharaja Forest Reserve",
		    "organizationName" => "GDG Sri Lanka",
		    "organizationalUnitName" => $key,
		    "commonName" => "Eco Trailer",
		    "emailAddress" => $email
		);

		$csr = openssl_csr_new($dn, $privkey, array('digest_alg' => 'sha256'));
		openssl_csr_export($csr, $csrString);
		
		$ans = new Answer();
		$ans->answerHeader = 'text/html';
		$ans->answerContent = $csrString;

		return $ans;


	}
}
