<?php

if (! empty($redis_server )){

    echo "redis server:";
    echo "<br/>";
    echo $redis_server;
    echo "<br/>";
    echo "<br/>";

}

if (! empty($info) ){

    foreach($info as $k => $v){
        echo $k . " " . $v;
        echo "<br/>";
    }

}

?>

</div>

</div>

</div>

</body>
</html>