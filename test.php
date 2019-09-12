<?php
    require_once('requestApi.php');

    $request = new GetResponseFromUrl("https://ya.ru","get");
    echo $request->getRequest();
