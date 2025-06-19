<?php
require_once('vendor/autoload.php');

$stripe = [
  "secret_key"      => "sk_test_51RYYoTD0zBi1autjTJbhS3292c7mzKKT791mPcle4H4Filh8ZI3v3rfoVh3LdhQFkdKnquR7uPH3n7LTmyiye9Hf00eDWYtDqg",
  "publishable_key" => "pk_test_51RYYoTD0zBi1autjomMiZbNoqGQWyYbNTinew7qeChBt733LegOIe971T543i3ckVULQGLMhsSVfq4Sp2TvbW47K00IOAmWaLk",
];

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>